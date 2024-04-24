<?php

namespace App\Http\Middleware;
use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

    class JwtMiddleware extends BaseMiddleware
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
                $user = JWTAuth::parseToken()->authenticate();
            } catch (Exception $e) {
                if($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                    return response()->json(['value' => '0' , 'key' => 'fail' ,'msg' => 'Token is Invalid','code'=>419]);
                }elseif($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                    return response()->json(['value' => '0' , 'key' => 'fail' ,'msg' => 'Token is Expired','code'=>419]);
                }else{
                    return response()->json(['value' => '0' , 'key' => 'fail' ,'msg' => 'Authorization Token not found','code'=>419]);
                }
            }
            // if($user->captain == 'true'){
            // if ((!$user->package_id) || (strtotime($user->package_end_date) < strtotime('now')))  {
            //     $msg = trans('user.must_subscribe_package');
            //     return response()->json(['value' => '0' , 'key' => 'need_package' ,'msg' => $msg,'code'=>401,'user_status' => 'active']);
            // }
            // }
            return $next($request);
        }
    }