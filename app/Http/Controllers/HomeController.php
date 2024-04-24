<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversation;
use App\Order;
// use Auth;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($lat = 24.774265,$long = 46.738586){
        $distance   = 50;//setting('distance'); in km
        $distance   = floatval(( $distance* 0.1 ) / 15 );
        $min_lat    = $lat  - $distance;
        $min_long   = $long - $distance;
        $max_lat    = $lat  + $distance;
        $max_long   = $long + $distance; 
        $orders = Order::where('start_lat','>=',$min_lat)->where('start_lat','<=',$max_lat)->where('start_long','>=',$min_long)->where('start_long','<=',$max_long)->where('status','=','open')->get();
        // $orders = Order::all();
        $google_key = setting('google_places_key');//'AIzaSyDYjCVA8YFhqN2pGiW4I8BCwhlxThs1Lc0';
        return view('home',compact('orders','google_key','lat','long'));
    }


}
