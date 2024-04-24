<?php

namespace App\Http\Middleware;
use Closure;
use Auth;
use App\externalAppTokens;

class tokenAllowed{

        /**
         * Handle an incoming request.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure  $next
         * @return mixed
         */
        public function handle($request, Closure $next){
            $externalAppTokens = externalAppTokens::where(['app_id' => $request->header('appId') ,'server_key' => $request->header('serverKey')])->first();
            if($externalAppTokens){
                $request['external_app_id'] = $externalAppTokens->id; 
            }else{
                return response()->json(['value' => '0' , 'key' => 'fail' ,'msg' => 'Not Allowed','code'=>405]);
            }
            return $next($request);
        }
    }