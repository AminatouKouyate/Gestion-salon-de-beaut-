<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Payment;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ChatbotController extends Controller
{
    private array $intents = [
        'greeting' => ['bonjour', 'salut', 'hello', 'bonsoir', 'coucou', 'hey', 'hi'],
        'services' => ['services', 'prestations', 'offres', 'catalogue', 'tarifs', 'prix', 'coupe', 'couleur', 'soin', 'coiffure'],
        'promotions' => ['promotion', 'promo', 'réduction', 'soldes', 'offre spéciale', 'remise', 'discount'],
        'appointment' => ['rendez-vous', 'réserver', 'rdv', 'booking', 'prendre rdv', 'disponibilité', 'créneau'],
        'cancel' => ['annuler', 'annulation', 'supprimer', 'reporter'],
        'hours' => ['horaires', 'heures', 'ouverture', 'fermeture', 'ouvert', 'fermé'],
        'location' => ['adresse', 'où', 'localisation', 'situé', 'emplacement', 'venir'],
        'payment' => ['paiement', 'payer', 'facture', 'carte', 'espèces', 'prix'],
        'my_appointments' => ['mes rendez-vous', 'mes rdv', 'mes réservations', 'prochain rendez-vous'],
        'loyalty' => ['fidélité', 'points', 'avantages', 'réduction', 'niveau', 'statut fidélité'],
        'history' => ['historique', 'passé', 'ancien', 'précédent'],
        'invoice' => ['facture', 'reçu', 'justificatif'],
        'profile' => ['profil', 'compte', 'informations', 'modifier profil'],
        'help' => ['aide', 'help', 'comment', 'quoi', 'info'],
        'thanks' => ['merci', 'thanks', 'super', 'parfait', 'excellent'],
        'bye' => ['au revoir', 'bye', 'à bientôt', 'ciao', 'adieu'],
    ];

    public function index()
    {
        $client = Auth::guard('clients')->user();

        // Charger l'historique des conversations récentes
        $chatHistory = [];
        if ($client) {
            $chatHistory = ChatMessage::forClient($client->id)
                ->orderBy('created_at', 'asc')
                ->recent(20)
                ->get()
                ->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'message' => $message->is_user_message ? $message->message : $message->response,
                        'is_user' => $message->is_user_message,
                        'timestamp' => $message->created_at->format('H:i'),
                        'date' => $message->created_at->format('d/m/Y'),
                    ];
                });
        }

        return view('Clients.chatbot.index', compact('client', 'chatHistory'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500']);

        $message = strtolower(trim($request->input('message')));
        $client = Auth::guard('clients')->user();

        $intent = $this->detectIntent($message);
        $response = $this->generateResponse($intent, $message, $client);

        // Sauvegarder le message utilisateur
        if ($client) {
            ChatMessage::create([
                'client_id' => $client->id,
                'message' => $request->input('message'),
                'intent' => $intent,
                'is_user_message' => true,
            ]);

            // Sauvegarder la réponse du bot
            ChatMessage::create([
                'client_id' => $client->id,
                'message' => $request->input('message'),
                'response' => $response['text'],
                'intent' => $intent,
                'is_user_message' => false,
            ]);
        }

        return response()->json([
            'reply' => $response['text'],
            'suggestions' => $response['suggestions'] ?? [],
            'data' => $response['data'] ?? null,
            'actions' => $response['actions'] ?? [],
        ]);
    }

    private function detectIntent(string $message): string
    {
        foreach ($this->intents as $intent => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($message, $keyword)) {
                    return $intent;
                }
            }
        }
        return 'unknown';
    }

    private function generateResponse(string $intent, string $message, $client): array
    {
        return match ($intent) {
            'greeting' => $this->greetingResponse($client),
            'services' => $this->servicesResponse($message),
            'promotions' => $this->promotionsResponse(),
            'appointment' => $this->appointmentResponse($client),
            'cancel' => $this->cancelResponse($client),
            'hours' => $this->hoursResponse(),
            'location' => $this->locationResponse(),
            'payment' => $this->paymentResponse($client),
            'my_appointments' => $this->myAppointmentsResponse($client),
            'loyalty' => $this->loyaltyResponse($client),
            'history' => $this->historyResponse($client),
            'invoice' => $this->invoiceResponse($client),
            'profile' => $this->profileResponse($client),
            'help' => $this->helpResponse(),
            'thanks' => $this->thanksResponse(),
            'bye' => $this->byeResponse(),
            default => $this->unknownResponse(),
        };
    }

    private function greetingResponse($client): array
    {
        $name = $client ? $client->name : 'cher client';
        $hour = Carbon::now()->hour;

        $greeting = match (true) {
            $hour < 12 => 'Bonjour',
            $hour < 18 => 'Bon après-midi',
            default => 'Bonsoir',
        };

        $loyaltyInfo = '';
        if ($client) {
            $level = $client->getLoyaltyLevel();
            $points = $client->loyalty_points ?? 0;
            $loyaltyInfo = "\n\n🎖️ Votre niveau fidélité : **{$level}** ({$points} points)";
        }

        return [
            'text' => "$greeting $name ! 👋 Bienvenue au salon. Comment puis-je vous aider aujourd'hui ?$loyaltyInfo",
            'suggestions' => ['Voir les services', 'Promotions', 'Prendre rendez-vous', 'Mes points fidélité'],
        ];
    }

    private function servicesResponse(string $message): array
    {
        $services = Service::active()->get();

        if ($services->isEmpty()) {
            return [
                'text' => "Nos services seront bientôt disponibles. Revenez nous voir !",
                'suggestions' => ['Prendre rendez-vous', 'Horaires'],
            ];
        }

        $categories = $services->groupBy('category');
        $text = "✨ **Nos services** :\n\n";

        foreach ($categories as $category => $categoryServices) {
            $catName = $category ?: 'Général';
            $text .= "**$catName**\n";
            foreach ($categoryServices as $service) {
                $priceDisplay = $service->price . '€';
                if ($service->hasActivePromotion()) {
                    $priceDisplay = "~~{$service->price}€~~ **{$service->promotion_price}€** 🔥";
                }
                $text .= "• {$service->name} - $priceDisplay ({$service->duration} min)\n";
            }
            $text .= "\n";
        }

        $text .= "Souhaitez-vous réserver un service ?";

        return [
            'text' => $text,
            'suggestions' => ['Voir les promotions', 'Prendre rendez-vous', 'Horaires'],
            'data' => ['services' => $services->toArray()],
        ];
    }

    private function promotionsResponse(): array
    {
        $promotions = Service::active()->withPromotion()->get();

        if ($promotions->isEmpty()) {
            return [
                'text' => "Pas de promotion en cours actuellement.\n\nMais restez connecté, de nouvelles offres arrivent bientôt ! 🎁",
                'suggestions' => ['Voir les services', 'Mes points fidélité', 'Prendre rendez-vous'],
            ];
        }

        $text = "🔥 **Promotions en cours** :\n\n";

        foreach ($promotions as $service) {
            $discount = $service->getDiscountPercentage();
            $label = $service->promotion_label ?? 'Offre spéciale';
            $endDate = $service->promotion_end ? " (jusqu'au {$service->promotion_end->format('d/m/Y')})" : '';

            $text .= "**{$service->name}**\n";
            $text .= "• {$label} : **-{$discount}%**$endDate\n";
            $text .= "• ~~{$service->price}€~~ → **{$service->promotion_price}€**\n\n";
        }

        $text .= "Profitez-en vite ! 🏃‍♂️";

        return [
            'text' => $text,
            'suggestions' => ['Prendre rendez-vous', 'Tous les services', 'Mes points fidélité'],
            'actions' => [['type' => 'link', 'label' => 'Réserver maintenant', 'url' => '/appointments/create']],
        ];
    }

    private function appointmentResponse($client): array
    {
        $employees = Employee::all();
        $services = Service::active()->take(5)->get();
        $promotions = Service::active()->withPromotion()->take(3)->get();

        $promoText = '';
        if ($promotions->isNotEmpty()) {
            $promoText = "\n\n🔥 **En promotion** :\n";
            foreach ($promotions as $promo) {
                $promoText .= "• {$promo->name} (-{$promo->getDiscountPercentage()}%)\n";
            }
        }

        return [
            'text' => "📅 Je peux vous aider à réserver un rendez-vous !\n\n" .
                "**Employés disponibles** : " . $employees->pluck('name')->join(', ') . "\n\n" .
                "**Services populaires** :\n" .
                $services->map(fn($s) => "• {$s->name} ({$s->getCurrentPrice()}€)")->join("\n") .
                $promoText . "\n\n" .
                "👉 [Cliquez ici pour réserver](/appointments/create)",
            'suggestions' => ['Voir tous les services', 'Promotions', 'Mes rendez-vous'],
            'actions' => [['type' => 'link', 'label' => 'Réserver', 'url' => '/appointments/create']],
        ];
    }

    private function cancelResponse($client): array
    {
        if (!$client) {
            return [
                'text' => "Veuillez vous connecter pour gérer vos rendez-vous.",
                'suggestions' => ['Se connecter'],
            ];
        }

        $upcoming = Appointment::where('client_id', $client->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('date', '>=', now()->toDateString())
            ->with('service')
            ->first();

        if (!$upcoming) {
            return [
                'text' => "Vous n'avez pas de rendez-vous à venir à annuler.",
                'suggestions' => ['Prendre rendez-vous', 'Historique'],
            ];
        }

        return [
            'text' => "Votre prochain rendez-vous :\n" .
                "📌 **{$upcoming->service->name}**\n" .
                "📅 {$upcoming->date->format('d/m/Y')} à {$upcoming->time}\n\n" .
                "👉 [Gérer ce rendez-vous](/appointments/{$upcoming->id})",
            'suggestions' => ['Modifier', 'Annuler', 'Garder'],
            'actions' => [
                ['type' => 'link', 'label' => 'Modifier', 'url' => "/appointments/{$upcoming->id}/edit"],
                ['type' => 'danger', 'label' => 'Annuler', 'url' => "/appointments/{$upcoming->id}"],
            ],
        ];
    }

    private function hoursResponse(): array
    {
        return [
            'text' => "🕐 **Nos horaires d'ouverture** :\n\n" .
                "Lundi - Vendredi : 9h00 - 19h00\n" .
                "Samedi : 9h00 - 18h00\n" .
                "Dimanche : Fermé\n\n" .
                "📞 Pour toute urgence, appelez-nous !",
            'suggestions' => ['Prendre rendez-vous', 'Adresse', 'Services'],
        ];
    }

    private function locationResponse(): array
    {
        return [
            'text' => "📍 **Notre adresse** :\n\n" .
                "123 Rue du Salon\n" .
                "75001 Bamako\n\n" .
                "🚗 Parking disponible à proximité",
            'suggestions' => ['Horaires', 'Prendre rendez-vous', 'Nous appeler'],
        ];
    }

    private function paymentResponse($client): array
    {
        $text = "💳 **Modes de paiement acceptés** :\n\n" .
            "• Carte bancaire (Visa, Mastercard)\n" .
            "• Espèces\n" .
            "• PayPal\n" .
            "• Chèques\n\n" .
            "Le paiement s'effectue après la prestation.";

        if ($client) {
            $unpaidCount = Appointment::where('client_id', $client->id)
                ->where('status', 'completed')
                ->doesntHave('payment')
                ->count();

            if ($unpaidCount > 0) {
                $text .= "\n\n⚠️ Vous avez **{$unpaidCount} rendez-vous** en attente de paiement.";
            }
        }

        return [
            'text' => $text,
            'suggestions' => ['Mes paiements', 'Mes factures', 'Services'],
            'actions' => $client ? [['type' => 'link', 'label' => 'Voir mes paiements', 'url' => '/payments']] : [],
        ];
    }

    private function myAppointmentsResponse($client): array
    {
        if (!$client) {
            return [
                'text' => "Veuillez vous connecter pour voir vos rendez-vous.",
                'suggestions' => ['Se connecter', 'S\'inscrire'],
            ];
        }

        $appointments = $client->getUpcomingAppointments()->take(3);

        if ($appointments->isEmpty()) {
            return [
                'text' => "Vous n'avez pas de rendez-vous à venir.\n\nSouhaitez-vous en réserver un ?",
                'suggestions' => ['Prendre rendez-vous', 'Voir les services', 'Historique'],
            ];
        }

        $text = "📅 **Vos prochains rendez-vous** :\n\n";
        foreach ($appointments as $apt) {
            $employee = $apt->employee ? " avec {$apt->employee->name}" : "";
            $status = $apt->status == 'confirmed' ? '✅' : '⏳';
            $text .= "$status **{$apt->service->name}**$employee\n";
            $text .= "   📆 {$apt->date->format('d/m/Y')} à {$apt->time}\n\n";
        }

        $text .= "👉 [Voir tous mes rendez-vous](/appointments)";

        return [
            'text' => $text,
            'suggestions' => ['Nouveau rendez-vous', 'Annuler un RDV', 'Historique'],
            'data' => ['appointments' => $appointments->toArray()],
            'actions' => [['type' => 'link', 'label' => 'Gérer mes RDV', 'url' => '/appointments']],
        ];
    }

    private function loyaltyResponse($client): array
    {
        if (!$client) {
            return [
                'text' => "Connectez-vous pour voir vos points de fidélité !",
                'suggestions' => ['Se connecter', 'S\'inscrire'],
            ];
        }

        $points = $client->loyalty_points ?? 0;
        $level = $client->getLoyaltyLevel();
        $discount = $client->getLoyaltyDiscount();
        $totalVisits = $client->total_appointments ?? 0;

        $nextLevel = match($level) {
            'Bronze' => ['Argent', 100 - $points],
            'Argent' => ['Or', 200 - $points],
            'Or' => ['Platine', 500 - $points],
            default => [null, 0],
        };

        $progressText = $nextLevel[0]
            ? "\n\n📈 Plus que **{$nextLevel[1]} points** pour atteindre le niveau **{$nextLevel[0]}** !"
            : "\n\n🏆 Vous êtes au niveau maximum !";

        $discountText = $discount > 0
            ? "\n💰 Réduction actuelle : **{$discount}%** sur tous vos services"
            : "";

        return [
            'text' => "🎁 **Votre programme fidélité** :\n\n" .
                "🎖️ Niveau : **$level**\n" .
                "⭐ Points accumulés : **$points points**\n" .
                "📊 Nombre de visites : **$totalVisits**" .
                $discountText .
                $progressText . "\n\n" .
                "**Avantages par niveau** :\n" .
                "• Bronze : Accès au programme\n" .
                "• Argent (100 pts) : -10%\n" .
                "• Or (200 pts) : -15%\n" .
                "• Platine (500 pts) : -20%",
            'suggestions' => ['Prendre rendez-vous', 'Mes rendez-vous', 'Promotions'],
        ];
    }

    private function historyResponse($client): array
    {
        if (!$client) {
            return [
                'text' => "Connectez-vous pour voir votre historique.",
                'suggestions' => ['Se connecter'],
            ];
        }

        $history = $client->getCompletedAppointments()->take(5);

        if ($history->isEmpty()) {
            return [
                'text' => "Vous n'avez pas encore d'historique de services.\n\nPrenez votre premier rendez-vous !",
                'suggestions' => ['Prendre rendez-vous', 'Voir les services'],
            ];
        }

        $text = "📜 **Vos derniers services** :\n\n";
        $totalSpent = 0;

        foreach ($history as $apt) {
            $paid = $apt->payment ? '✅' : '⏳';
            $amount = $apt->payment ? $apt->payment->amount : $apt->service->price;
            $totalSpent += $amount;
            $text .= "$paid **{$apt->service->name}**\n";
            $text .= "   📅 {$apt->date->format('d/m/Y')} - {$amount}€\n\n";
        }

        $text .= "💰 Total dépensé : **{$totalSpent}€**\n\n";
        $text .= "👉 [Voir l'historique complet](/appointments-history)";

        return [
            'text' => $text,
            'suggestions' => ['Mes factures', 'Prendre rendez-vous', 'Mes points'],
            'actions' => [['type' => 'link', 'label' => 'Historique complet', 'url' => '/appointments-history']],
        ];
    }

    private function invoiceResponse($client): array
    {
        if (!$client) {
            return [
                'text' => "Connectez-vous pour accéder à vos factures.",
                'suggestions' => ['Se connecter'],
            ];
        }

        $payments = Payment::whereHas('appointment', fn($q) => $q->where('client_id', $client->id))
            ->where('status', 'completed')
            ->with('appointment.service')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        if ($payments->isEmpty()) {
            return [
                'text' => "Vous n'avez pas encore de factures.",
                'suggestions' => ['Prendre rendez-vous', 'Services'],
            ];
        }

        $text = "🧾 **Vos dernières factures** :\n\n";

        foreach ($payments as $payment) {
            $text .= "• **{$payment->appointment->service->name}** - {$payment->amount}€\n";
            $text .= "   📅 {$payment->created_at->format('d/m/Y')}\n";
            $text .= "   👉 [Télécharger](/payments/{$payment->id}/invoice)\n\n";
        }

        return [
            'text' => $text,
            'suggestions' => ['Mes paiements', 'Historique', 'Services'],
            'actions' => [['type' => 'link', 'label' => 'Tous les paiements', 'url' => '/payments']],
        ];
    }

    private function profileResponse($client): array
    {
        if (!$client) {
            return [
                'text' => "Connectez-vous pour accéder à votre profil.",
                'suggestions' => ['Se connecter', 'S\'inscrire'],
            ];
        }

        return [
            'text' => "👤 **Votre profil** :\n\n" .
                "**Nom** : {$client->name}\n" .
                "**Email** : {$client->email}\n" .
                "**Téléphone** : " . ($client->phone ?? 'Non renseigné') . "\n" .
                "**Membre depuis** : {$client->created_at->format('d/m/Y')}\n\n" .
                "🎖️ Niveau fidélité : **{$client->getLoyaltyLevel()}**\n" .
                "⭐ Points : **{$client->loyalty_points}**\n\n" .
                "👉 [Modifier mon profil](/profile)",
            'suggestions' => ['Mes points', 'Mes rendez-vous', 'Changer mot de passe'],
            'actions' => [['type' => 'link', 'label' => 'Modifier le profil', 'url' => '/profile']],
        ];
    }

    private function helpResponse(): array
    {
        return [
            'text' => "🤖 **Comment puis-je vous aider ?**\n\n" .
                "Voici ce que je peux faire :\n" .
                "• 📋 Afficher les **services** et **tarifs**\n" .
                "• 🔥 Voir les **promotions** en cours\n" .
                "• 📅 Vous aider à **prendre rendez-vous**\n" .
                "• 🕐 Donner les **horaires** d'ouverture**\n" .
                "• 📍 Indiquer l'**adresse** du salon\n" .
                "• 💳 Informer sur les **paiements**\n" .
                "• 🧾 Accéder à vos **factures**\n" .
                "• 🎁 Consulter vos **points fidélité**\n" .
                "• 📜 Voir votre **historique**\n\n" .
                "Posez-moi simplement votre question !",
            'suggestions' => ['Services', 'Promotions', 'Rendez-vous', 'Fidélité'],
        ];
    }

    private function thanksResponse(): array
    {
        $responses = [
            "Avec plaisir ! 😊 N'hésitez pas si vous avez d'autres questions.",
            "Je vous en prie ! À votre service. 💇",
            "C'est un plaisir de vous aider ! ✨",
        ];

        return [
            'text' => $responses[array_rand($responses)],
            'suggestions' => ['Prendre rendez-vous', 'Services', 'Au revoir'],
        ];
    }

    private function byeResponse(): array
    {
        return [
            'text' => "Au revoir et à bientôt ! 👋\n\nNous avons hâte de vous revoir au salon. Passez une excellente journée !",
            'suggestions' => ['Revenir au début'],
        ];
    }

    private function unknownResponse(): array
    {
        return [
            'text' => "Je ne suis pas sûr de comprendre votre demande. 🤔\n\n" .
                "Essayez de me poser une question sur :\n" .
                "• Nos **services** et **tarifs**\n" .
                "• Les **promotions** en cours\n" .
                "• La **prise de rendez-vous**\n" .
                "• Les **horaires** d'ouverture\n" .
                "• Votre **compte fidélité**\n" .
                "• Vos **factures** et **paiements**",
            'suggestions' => ['Aide', 'Services', 'Promotions', 'Rendez-vous'],
        ];
    }
}
