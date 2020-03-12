<?php

namespace App\Http\Middleware;

use Closure;

class IsAdmin
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
        if (\User::isLoggedAdmin() || \User::isManager()) {
            return $next($request);
        }
        if (\User::isClerk() && ($request->is('admin/bills*') || $request->is('admin/send_bill*'))) {
            return $next($request);
        }
        if (\User::isKeeper()) {
            return $next($request);
        }
        return redirect()->back()->withErrors(['error' => '"' . trans('navigation.' . ltrim($request->getPathInfo(), '/')) . '" ist nur für Administratoren']);
    }
}
