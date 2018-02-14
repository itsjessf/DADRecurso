<?php

namespace App\Http\Middleware;

use Closure;

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

        try {
            if(\Auth::user()->isAdmin()){
                return $next($request);
            }
            return response()->json(
                ['msg'=>'Unauthorized'], 401);
        }
        catch (\Exception $e) {
            return response()->json(
                ['msg'=>$e->getMessage()], 401);
        }

    }
}
