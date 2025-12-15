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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Ensure all methods in this controller require a client to be authenticated.
        $this->middleware('auth:clients');
    }

    /**
     * Display the client's dashboard.
     */
    public function dashboard()
    {
        $client = Auth::guard('clients')->user();
        return view('Clients.dashboard', compact('client'));
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
}
