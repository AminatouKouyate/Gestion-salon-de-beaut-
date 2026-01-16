<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display the client's dashboard.
     */
    public function dashboard()
    {
        $client = Auth::guard('clients')->user();
        
        $upcomingAppointments = $client->getUpcomingAppointments();
        $unreadNotifications = $client->unreadNotifications()->take(5)->get();
        $recentPayments = $client->payments()
            ->with('appointment.service')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('Clients.dashboard', compact(
            'client',
            'upcomingAppointments',
            'unreadNotifications',
            'recentPayments'
        ));
    }

    /**
     * Display the client's profile form.
     */
    public function profile()
    {
        $client = Auth::guard('clients')->user();
        return view('Clients.profile', compact('client'));
    }

    /**
     * Update the client's profile.
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $client = Auth::guard('clients')->user();
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $client->update($data);

        return back()->with('success', 'Profil mis à jour');
    }

    /**
     * Deactivate the client's account.
     */
    public function deactivate(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password:clients',
        ]);

        $client = Auth::guard('clients')->user();
        $client->update(['active' => false]);

        Auth::guard('clients')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('client.login')
            ->with('success', 'Votre compte a été désactivé avec succès.');
    }
}
