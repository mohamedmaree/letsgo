<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use App\Favorites;
use App\CartItems;
use App\Notifications;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(){

            $this->data = array();
            $this->limit = setting('per_page');
            // $this->data['site_logo'] = setting('site_logo');
            $this->middleware(function ($request, $next) {
                if(Auth::check()){
                     $this->data['user'] = Auth::user();           
                     $this->data['user']->last_activity = date('Y-m-d H:i:s');
                     $this->data['user']->update();
                }                         
                return $next($request);
            });

    }
    
}
