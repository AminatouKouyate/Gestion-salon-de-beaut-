<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Services\MobilePaymentService;
use Barryvdh\DomPDF\Facade\Pdf;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

/**
 * Contrôleur pour la gestion des paiements côté client.
 *
 * Ce contrôleur gère l'ensemble du processus de paiement pour les clients :
 * - Consultation de l'historique des paiements
 * - Création de nouveaux paiements (Stripe, PayPal, Orange Money, Wave, espèces)
 * - Suivi du statut des paiements en cours
 * - Génération et téléchargement des factures au format PDF
 * - Gestion des callbacks pour les paiements mobiles et webhooks Stripe
 * 
 * @package App\Http\Controllers\Client
 */
class PaymentController extends Controller
{
    /**
     * Affiche la liste de tous les paiements du client connecté.
     *
     * Récupère les paiements associés aux rendez-vous du client,
     * avec les informations du service correspondant.
     *
     * @return \Illuminate\View\View La vue contenant la liste paginée des paiements
     */
    public function index()
    {
        // Récupérer le client connecté via le guard 'clients'
        $client = Auth::guard('clients')->user();
        
        // Récupérer les paiements via la relation avec les rendez-vous
        // On utilise whereHas pour filtrer les paiements liés aux RDV du client
        $payments = Payment::whereHas('appointment', function ($q) use ($client) {
            $q->where('client_id', $client->id);
        })
            ->with('appointment.service') // Eager loading pour les performances
            ->orderBy('created_at', 'desc') // Les plus récents en premier
            ->paginate(15);

        return view('clients.payments.index', compact('payments'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau paiement.
     *
     * Cette méthode prépare les données nécessaires pour le formulaire :
     * - Si un rendez-vous est passé en paramètre, il sera pré-sélectionné
     * - Récupère la liste des rendez-vous terminés mais non payés
     *
     * @param Request $request Requête contenant éventuellement l'ID du rendez-vous à payer
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse La vue du formulaire ou redirection si erreur
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException Erreur 403 si le RDV n'appartient pas au client
     */
    public function create(Request $request)
    {
        // Récupérer le client connecté
        $client = Auth::guard('clients')->user();

        // Vérifier si un rendez-vous spécifique est demandé via l'URL (?appointment=ID)
        $appointment = null;
        $appointmentId = $request->query('appointment');
        if ($appointmentId) {
            $appointment = Appointment::find($appointmentId);
            
            // Vérifications de sécurité
            if (! $appointment) {
                return back()->with('error', 'Rendez-vous introuvable.');
            }
            if ($appointment->client_id !== $client->id) {
                abort(403); // Le RDV n'appartient pas au client
            }
            if ($appointment->payment) {
                return back()->with('error', 'Ce rendez-vous a déjà été payé');
            }
        }

        // Récupérer tous les rendez-vous non encore payés (confirmés ou terminés)
        $unpaidAppointments = Appointment::where('client_id', $client->id)
            ->whereIn('status', [
                \App\Enums\AppointmentStatus::Pending,
                \App\Enums\AppointmentStatus::Confirmed,
                \App\Enums\AppointmentStatus::Completed,
            ])
            ->doesntHave('payment')
            ->with('service')
            ->get();

        return view('clients.payments.create', compact('unpaidAppointments', 'appointment', 'client'));
    }

    /**
     * Enregistre un nouveau paiement et redirige vers le processus approprié.
     *
     * Cette méthode centrale gère les différentes méthodes de paiement :
     * - "salon" : Paiement différé à régler au salon (statut pending)
     * - "orange_money" / "wave" : Paiements mobiles via API
     * - "stripe" / "paypal" : Paiements en ligne (redirection vers le prestataire)
     *
     * @param Request $request Les données du formulaire de paiement
     * @return \Illuminate\Http\RedirectResponse Redirection selon la méthode choisie
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException Erreur 403 si le RDV n'appartient pas au client
     * @throws \Illuminate\Validation\ValidationException Si les données sont invalides
     */
    public function store(Request $request)
    {
        // Validation des champs du formulaire
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'method' => 'required|in:stripe,paypal,salon,orange_money,wave',
            'phone_number' => 'required_if:method,orange_money,wave|nullable|string|regex:/^[0-9+\s-]+$/',
        ], [
            'appointment_id.required' => 'Le rendez-vous est requis.',
            'method.required' => 'La méthode de paiement est requise.',
            'phone_number.required_if' => 'Le numéro de téléphone est requis pour les paiements mobiles.',
            'phone_number.regex' => 'Le format du numéro de téléphone est invalide.',
        ]);

        // Récupérer le client et le rendez-vous
        $client = Auth::guard('clients')->user();
        $appointment = Appointment::findOrFail($request->appointment_id);

        // Vérification de propriété : le RDV doit appartenir au client
        if ($appointment->client_id !== $client->id) {
            abort(403);
        }

        // Vérifier que le RDV n'est pas déjà payé
        if ($appointment->payment) {
            return back()->with('error', 'Ce rendez-vous a déjà été payé');
        }

        // Vérifier que le service existe
        if (!$appointment->service) {
            return back()->with('error', 'Service introuvable pour ce rendez-vous.');
        }

        // ============================================================
        // PAIEMENT AU SALON (espèces ou CB au comptoir)
        // ============================================================
        if ($request->method === 'salon') {
            // Créer un paiement avec statut "pending" (à régler sur place)
            Payment::create([
                'client_id' => $client->id,
                'appointment_id' => $appointment->id,
                'amount' => $appointment->service->getCurrentPrice(),
                'method' => $request->method,
                'status' => 'pending',
            ]);

            return redirect()->route('client.payments.index')->with('success', 'Paiement enregistré - a regler au salon');
        }

        // ============================================================
        // PAIEMENTS MOBILES (Orange Money et Wave)
        // ============================================================
        if ($request->method === 'orange_money' || $request->method === 'wave') {
            // Créer le paiement avec statut "pending"
            $payment = Payment::create([
                'client_id' => $client->id,
                'appointment_id' => $appointment->id,
                'amount' => $appointment->service->price,
                'method' => $request->method,
                'status' => 'pending',
            ]);

            // Initialiser le service de paiement mobile
            $mobilePaymentService = new MobilePaymentService();

            // Initier la transaction selon la méthode choisie
            if ($request->method === 'orange_money') {
                $result = $mobilePaymentService->initiateOrangeMoney($payment, $request->phone_number);
            } else {
                $result = $mobilePaymentService->initiateWave($payment, $request->phone_number);
            }

            // Vérifier le résultat de l'initiation
            if ($result['success']) {
                // Mettre à jour le statut vers "processing" (en cours de traitement)
                $payment->update(['status' => 'processing']);

                // Rediriger vers la page d'attente de confirmation
                return redirect()->route('client.payments.mobile', [
                    'payment' => $payment->id,
                    'method' => $request->method
                ])->with('payment_info', $result);
            }

            // Échec de l'initiation
            return back()->with('error', 'Erreur lors de l\'initiation du paiement. Veuillez réessayer.');
        }

        // ============================================================
        // PAIEMENTS EN LIGNE (Stripe, PayPal)
        // ============================================================
        // Rediriger vers la méthode process() qui gère la création de session
        return redirect()->route('client.payments.process', [
            'appointment' => $appointment->id,
            'method' => $request->method
        ]);
    }

    /**
     * Initie le processus de paiement en ligne (Stripe / PayPal).
     *
     * Pour Stripe, cette méthode crée une session Checkout et redirige
     * le client vers la page de paiement Stripe. L'intégration Stripe
     * nécessite la configuration de STRIPE_SECRET dans le fichier .env.
     *
     * @param Request $request Requête contenant l'ID du rendez-vous et la méthode
     * @return \Illuminate\Http\RedirectResponse Redirection vers Stripe ou page de confirmation
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException Erreur 403 si le RDV n'appartient pas au client
     * @throws \Illuminate\Validation\ValidationException Si les paramètres sont invalides
     */
    public function process(Request $request)
    {
        // Validation des paramètres
        $request->validate([
            'appointment' => 'required|exists:appointments,id',
            'method' => 'required|in:stripe,paypal',
        ]);

        // Récupérer le client et le rendez-vous
        $client = Auth::guard('clients')->user();
        $appointment = Appointment::findOrFail($request->query('appointment'));

        // Vérification de propriété
        if ($appointment->client_id !== $client->id) {
            abort(403);
        }

        // Vérifier que le RDV n'est pas déjà payé
        if ($appointment->payment) {
            return back()->with('error', 'Ce rendez-vous a déjà été payé');
        }

        // Vérifier que le service existe
        if (!$appointment->service) {
            return back()->with('error', 'Service introuvable pour ce rendez-vous.');
        }

        // Créer le paiement avec statut "processing"
        $payment = Payment::create([
            'client_id' => $client->id,
            'appointment_id' => $appointment->id,
            'amount' => $appointment->service->price,
            'method' => $request->query('method'),
            'status' => 'processing',
        ]);

        // ============================================================
        // INTÉGRATION STRIPE
        // ============================================================
        $method = $request->query('method');
        $stripeSecret = env('STRIPE_SECRET');
        
        if ($method === 'stripe' && $stripeSecret) {
            // Configurer l'API Stripe avec la clé secrète
            Stripe::setApiKey($stripeSecret);

            // Convertir le montant en centimes (Stripe utilise les plus petites unités)
            // Note : Pour FCFA/XOF, adapter selon les besoins (pas de décimales)
            $amountCents = intval(round($appointment->service->price * 100));

            // Construire les URLs de retour après paiement
            $successUrl = url('/client/payments/' . $payment->id) . '?session_id={CHECKOUT_SESSION_ID}';
            $cancelUrl = url('/client/payments/' . $payment->id);

            // Créer la session Stripe Checkout
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd', // Adapter selon la devise utilisée
                        'product_data' => [
                            'name' => $appointment->service->name,
                        ],
                        'unit_amount' => $amountCents,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'metadata' => [
                    'payment_id' => $payment->id, // Pour retrouver le paiement dans le webhook
                ],
            ]);

            // Rediriger vers la page de paiement Stripe
            return redirect($session->url);
        }

        // Fallback : si Stripe n'est pas configuré, afficher la page de paiement simulé
        return redirect()->route('client.payments.show', $payment)->with('success', 'Paiement en ligne initié (simulation).');
    }

    /**
     * Simule un paiement pour un rendez-vous donné (mode développement/test).
     *
     * Cette méthode est utile pour tester le flux de paiement sans
     * utiliser de vrais moyens de paiement. Elle crée un paiement
     * avec statut "paid" et met à jour le rendez-vous en "completed".
     *
     * @param Appointment $appointment Le rendez-vous à payer (injecté via Route Model Binding)
     * @return \Illuminate\Http\RedirectResponse Redirection vers la page de détails du paiement
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException Erreur 403 si le RDV n'appartient pas au client
     */
    public function simulate(Appointment $appointment)
    {
        // Récupérer le client connecté
        $client = Auth::guard('clients')->user();

        // Vérification de propriété
        if ($appointment->client_id !== $client->id) {
            abort(403);
        }

        // Vérifier que le RDV n'a pas déjà un paiement
        if ($appointment->payment) {
            return back()->with('error', 'Ce rendez-vous a déjà un paiement.');
        }

        // Utiliser une transaction DB pour garantir la cohérence des données
        $payment = null;
        \DB::transaction(function () use ($client, $appointment, & $payment) {
            // Créer le paiement avec statut "paid"
            $payment = Payment::create([
                'client_id' => $client->id,
                'appointment_id' => $appointment->id,
                'amount' => $appointment->service->price,
                'method' => 'simulation',
                'transaction_id' => uniqid('sim_'), // ID de transaction simulé
                'status' => 'paid',
            ]);

            // Marquer le rendez-vous comme terminé (décision métier : paiement = service effectué)
            $appointment->status = \App\Enums\AppointmentStatus::Completed;
            $appointment->save();
        });

        // Logger l'événement pour traçabilité
        \Illuminate\Support\Facades\Log::info('Payment simulated', [
            'payment_id' => $payment->id,
            'appointment_id' => $appointment->id,
            'client_id' => $client->id
        ]);
        
        // Déclencher l'événement PaymentSimulated (notifications gérées par listener)
        event(new \App\Events\PaymentSimulated($payment));

        return redirect()->route('client.payments.show', $payment)
            ->with('success', 'Paiement simulé et enregistré. Le rendez-vous a été mis à jour.');
    }

    /**
     * Affiche les détails d'un paiement spécifique.
     *
     * @param Payment $payment Le paiement à afficher (injecté via Route Model Binding)
     * @return \Illuminate\View\View La vue des détails du paiement
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException Erreur 403 si le paiement n'appartient pas au client
     */
    public function show(Payment $payment, Request $request)
    {
        // Récupérer le client connecté
        $client = Auth::guard('clients')->user();
        
        // Vérification de propriété (via le rendez-vous associé)
        if ($payment->appointment->client_id !== $client->id) {
            abort(403);
        }

        // Vérifier le paiement Stripe si session_id est présent dans l'URL
        if ($request->has('session_id') && $payment->method === 'stripe' && $payment->status !== 'paid') {
            $stripeSecret = env('STRIPE_SECRET');
            if ($stripeSecret) {
                try {
                    Stripe::setApiKey($stripeSecret);
                    $session = StripeSession::retrieve($request->query('session_id'));

                    if ($session->payment_status === 'paid') {
                        $payment->update([
                            'status' => 'paid',
                            'transaction_id' => $session->payment_intent,
                        ]);
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Stripe session verification failed', [
                        'payment_id' => $payment->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        // Charger les relations pour afficher toutes les informations
        $payment->load('appointment.service', 'appointment.employee');

        return view('clients.payments.show', compact('payment'));
    }

    /**
     * Affiche la facture d'un paiement dans le navigateur (format HTML).
     *
     * @param Payment $payment Le paiement dont afficher la facture
     * @return \Illuminate\View\View La vue de la facture
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException Erreur 403 si le paiement n'appartient pas au client
     */
    public function showInvoice(Payment $payment)
    {
        // Récupérer le client connecté
        $client = Auth::guard('clients')->user();
        
        // Vérification de propriété
        if (!$payment->appointment || $payment->appointment->client_id !== $client->id) {
            abort(403);
        }

        // Charger toutes les relations nécessaires pour la facture
        $payment->load('appointment.service', 'appointment.client', 'appointment.employee');

        return view('clients.payments.invoice', compact('payment'));
    }

    /**
     * Génère et télécharge la facture au format PDF.
     *
     * Utilise la librairie DomPDF (via barryvdh/laravel-dompdf) pour
     * générer le PDF à partir d'une vue Blade dédiée.
     *
     * @param Payment $payment Le paiement dont télécharger la facture
     * @return \Illuminate\Http\Response Fichier PDF à télécharger
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException Erreur 403 si le paiement n'appartient pas au client
     */
    public function downloadInvoice(Payment $payment)
    {
        // Récupérer le client connecté
        $client = Auth::guard('clients')->user();
        
        // Vérification de propriété
        if (!$payment->appointment || $payment->appointment->client_id !== $client->id) {
            abort(403);
        }

        // Charger les relations nécessaires pour la facture
        $payment->load('appointment.service', 'appointment.client', 'appointment.employee');

        // Générer le PDF à partir de la vue Blade
        $pdf = Pdf::loadView('clients.payments.invoice-pdf', compact('payment'));

        // Retourner le PDF avec un nom de fichier formaté (ex: facture-000001.pdf)
        return $pdf->download('facture-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }

    /**
     * Affiche la page d'attente de confirmation pour paiement mobile.
     *
     * Cette page informe le client des instructions de paiement et
     * vérifie périodiquement le statut du paiement via AJAX.
     *
     * @param Payment $payment Le paiement mobile en cours
     * @param string $method La méthode de paiement ("orange_money" ou "wave")
     * @return \Illuminate\View\View La vue d'attente de confirmation
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException Erreur 403 si le paiement n'appartient pas au client
     */
    public function showMobilePayment(Payment $payment, string $method)
    {
        // Récupérer le client connecté
        $client = Auth::guard('clients')->user();

        // Vérification de propriété
        if (!$payment->appointment || $payment->appointment->client_id !== $client->id) {
            abort(403);
        }

        // Charger le service pour afficher le montant
        $payment->load('appointment.service');
        
        // Récupérer les informations de paiement depuis la session
        $paymentInfo = session('payment_info');

        return view('clients.payments.mobile', compact('payment', 'method', 'paymentInfo'));
    }

    /**
     * Vérifie le statut d'un paiement mobile en cours (API AJAX).
     *
     * Cette méthode est appelée périodiquement par le frontend pour
     * vérifier si le paiement a été confirmé par l'opérateur mobile.
     *
     * @param Payment $payment Le paiement à vérifier
     * @return \Illuminate\Http\JsonResponse Statut du paiement ("paid" ou "pending")
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException Erreur 403 si le paiement n'appartient pas au client
     */
    public function checkMobilePaymentStatus(Payment $payment)
    {
        // Récupérer le client connecté
        $client = Auth::guard('clients')->user();

        // Vérification de propriété
        if (!$payment->appointment || $payment->appointment->client_id !== $client->id) {
            abort(403);
        }

        // Vérifier le statut auprès de l'opérateur mobile
        $mobilePaymentService = new MobilePaymentService();
        $isPaid = $mobilePaymentService->checkPaymentStatus($payment->transaction_id, $payment->method);

        if ($isPaid) {
            // Paiement confirmé : mettre à jour le statut
            $payment->update(['status' => 'paid']);
            return response()->json([
                'status' => 'paid',
                'status_label' => 'Payé',
                'message' => 'Paiement confirmé avec succès !'
            ]);
        }

        // Paiement toujours en attente
        return response()->json([
            'status' => 'pending',
            'status_label' => 'En attente',
            'message' => 'Paiement en attente de confirmation'
        ]);
    }

    /**
     * Traite le callback de paiement Orange Money.
     *
     * Point d'entrée webhook pour les notifications de paiement
     * envoyées par l'API Orange Money. Cette URL doit être configurée
     * dans l'espace développeur Orange Money.
     *
     * @param Request $request Les données du callback Orange Money
     * @return \Illuminate\Http\JsonResponse Réponse de confirmation pour Orange Money
     */
    public function orangeMoneyCallback(Request $request)
    {
        // Traiter le callback via le service dédié
        $mobilePaymentService = new MobilePaymentService();
        $success = $mobilePaymentService->handleOrangeMoneyCallback($request->all());

        // Retourner le statut approprié à Orange Money
        if ($success) {
            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'error'], 400);
    }

    /**
     * Traite le callback de paiement Wave.
     *
     * Point d'entrée webhook pour les notifications de paiement
     * envoyées par l'API Wave. Cette URL doit être configurée
     * dans le dashboard Wave Business.
     *
     * @param Request $request Les données du callback Wave
     * @return \Illuminate\Http\JsonResponse Réponse de confirmation pour Wave
     */
    public function waveCallback(Request $request)
    {
        // Traiter le callback via le service dédié
        $mobilePaymentService = new MobilePaymentService();
        $success = $mobilePaymentService->handleWaveCallback($request->all());

        // Retourner le statut approprié à Wave
        if ($success) {
            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'error'], 400);
    }

    /**
     * Traite le webhook Stripe pour les événements de paiement.
     *
     * Ce webhook reçoit les notifications de Stripe concernant les
     * événements de paiement (checkout.session.completed, payment_failed, etc.).
     * L'URL doit être configurée dans le dashboard Stripe.
     *
     * Note : Pour la production, il faut vérifier la signature du webhook
     * avec STRIPE_WEBHOOK_SECRET pour sécuriser le endpoint.
     *
     * @param Request $request Les données du webhook Stripe
     * @return \Illuminate\Http\JsonResponse Confirmation de réception
     */
    public function stripeWebhook(Request $request)
    {
        $payload = json_decode($request->getContent(), true);

        if (!$payload || !isset($payload['type'])) {
            return response()->json(['status' => 'invalid'], 400);
        }

        if ($payload['type'] === 'checkout.session.completed') {
            $session = $payload['data']['object'] ?? null;
            $paymentId = $session['metadata']['payment_id'] ?? null;

            if ($paymentId && $session) {
                $payment = Payment::find($paymentId);
                if ($payment && $payment->status !== 'paid') {
                    $payment->update([
                        'status' => 'paid',
                        'transaction_id' => $session['payment_intent'] ?? null,
                    ]);

                    // Marquer le rendez-vous comme terminé
                    if ($payment->appointment) {
                        $payment->appointment->update([
                            'status' => \App\Enums\AppointmentStatus::Completed,
                        ]);
                    }
                }
            }
        }

        return response()->json(['status' => 'received']);
    }
}
