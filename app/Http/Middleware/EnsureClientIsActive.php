<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureClientIsActive
{
    public function handle(Request $request, Closure $next)
    {
        $client = Auth::guard('clients')->user();

        // Treat null as active (for backward compatibility)
        if ($client && $client->active === false) {
            Auth::guard('clients')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('client.login')
                ->with('error', 'Votre compte a été désactivé. Contactez le salon pour plus d\'informations.');
        }

        return $next($request);
    }
}
