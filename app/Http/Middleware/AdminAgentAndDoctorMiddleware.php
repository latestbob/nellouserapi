<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAgentAndDoctorMiddleware
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
        if (Auth::check() && (($userType = $request->user()->user_type) == 'admin'
                || $userType == 'agent' || $userType == 'doctor')) return $next($request);

        return response()->json([
            'status' => false,
            'message' => "You don't have access to this route"
        ]);
    }
}
