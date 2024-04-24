<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Closure;

class UpdateLang{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        $lang = $request->header('lang');
        app()->setLocale($lang);
        \Carbon\Carbon::setLocale($lang);
        return $next($request);
                 
    }
}
