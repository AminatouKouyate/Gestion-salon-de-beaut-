<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                if ($user) {
                    switch ($user->role) {
                        case User::ROLE_ADMIN:
                            return redirect()->route('admin.dashboard');
                        case User::ROLE_EMPLOYEE:
                            return redirect()->route('employee.dashboard');
                        case User::ROLE_CLIENT:
                            return redirect()->route('client.dashboard');
                        default:
                            return redirect(RouteServiceProvider::HOME);
                    }
                }
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
