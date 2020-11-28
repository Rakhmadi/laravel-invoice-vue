<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
class apitoken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (User::where('token','=',$request->token)->exists()) {
            return $next($request);
        } else {
            return response()->json(['msg'=>'Unauthorized.'],401);
        }
    }
}
