<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientRegisterController extends Controller
{
    /**
     * Where to redirect clients after registration.
     */
    protected string $redirectTo = '/client/dashboard';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest:clients');
    }

    /**
     * Show the application registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.client-register');
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $client = $this->create($request->all());

        $this->guard()->login($client);

        return redirect($this->redirectTo);
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:clients'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new client instance after a valid registration.
     */
    protected function create(array $data): Client
    {
        return Client::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'active' => true,
            'loyalty_points' => 0,
            'total_appointments' => 0,
        ]);
    }

    /**
     * Get the guard to be used during registration.
     */
    protected function guard()
    {
        return Auth::guard('clients');
    }
}
