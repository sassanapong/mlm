<?php

namespace App\Http\Middleware;

use Closure;
use Member;

class Admin
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
        if (Member::check() && Member::user()->isAdmin()) {
            return $next($request);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
