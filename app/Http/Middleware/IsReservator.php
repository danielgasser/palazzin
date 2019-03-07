<?php

namespace App\Http\Middleware;

use Closure;

class IsReservator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\User::isReservator() || \User::isLoggedAdmin() || \User::isManager()) {
            return $next($request);
        }
        return redirect()->intended('/');
    }
}
