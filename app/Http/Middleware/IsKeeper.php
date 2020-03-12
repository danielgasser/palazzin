<?php

namespace App\Http\Middleware;

use Closure;

class IsKeeper
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
        if (\User::isKeeper()) {
            return $next($request);
        }
        return redirect()->back()->withErrors(['error' => 'Nur für Administratoren']);
    }
}
