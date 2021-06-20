<?php

namespace App\Http\Middleware;

use Closure;

class GerenteMiddleware
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
        dd("AQUI MIDDD");
        if(\Auth::guest() || \Auth::user()->tipo != 'admin' ){
             return redirect("home");
        }

            return $next($request);
    }
}
