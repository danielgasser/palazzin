<?php

namespace App\Http\Middleware;

use Closure;

class IsClerkOrReservator
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
        if (\User::isKeeper() || \User::isReservator() || \User::isClerk() || \User::isLoggedAdmin() || \User::isManager()) {
            return $next($request);
        }
        return redirect()->intended('/');
    }
}
