<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class MobileUserMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if ($request->input('user_id')) {
            $user_id = $request->input('user_id');
            if (User::find($user_id) == null) return response()->json(['value' => '0', 'key' => 'fail', 'msg' => 'user not found']);
            if (User::where('active', 'true')->find($user_id)) {
                return $next($request);
            } else {
                $data = ['user_id' => $user_id, 'msg' => 'Account Not Activated'];
                return response()->json(['value' => '0', 'key' => 'fail', 'msg' => $data['msg']]);
            }
        } else {
            $data = ['msg' => 'unautherized user_id'];
            return response()->json(['value' => '0', 'key' => 'fail', 'msg' => $data['msg']]);
        }
    }

}
