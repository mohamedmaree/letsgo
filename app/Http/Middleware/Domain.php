<?php

namespace App\Http\Middleware;
use URL;
use Closure;

class Domain
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
        if(URL::to('/') !== 'http://localhost/aait3')
        {
            abort(400);
        }
        return $next($request);
    }
}
