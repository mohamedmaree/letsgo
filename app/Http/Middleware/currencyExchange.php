<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
class currencyExchange{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){ 
        $exchangeRate = 1;
        if($user = JWTAuth::parseToken()->authenticate()){
           if($user->country_id != $user->current_country_id ){
              $f_currency = ($user->country)? $user->country->currency_en:'SAR';
              $s_currency = ($user->currentCountry)? $user->currentCountry->currency_en:'SAR';
              $exchangeRate = convertCurrency(1,$f_currency,$s_currency); 
           }           
        }
        $request->merge(['exchangeRate' => $exchangeRate]);
        return $next($request);
    }
}
