<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Prices;
use Auth;
use App\carTypes;
class PricesController extends Controller{

    //Admin part 
    public function prices(){
      $prices   = Prices::orderBy('id','ASC')->get(); 
      $cartypes = carTypes::orderBy('id','ASC')->get();
      return view('dashboard.prices.prices',compact('prices','cartypes'));
    }

    #add product
    public function createPrice(Request $request){
        $this->validate($request,[
            'type'             => 'required',
            'car_type_id'      => 'required',
            'counter'          => 'required',
            'km_price'         => 'required',
            'waiting_minute'   => 'required',
            'min_price'        => 'required',
            'client_cancel'    => 'required',
            'captain_cancel'   => 'required',
        ]);

        $price = new Prices();        
        $price->type                  = $request->type;
        $price->car_type_id           = $request->car_type_id;
        $price->counter               = convert2english( $request->counter );
        $price->km_price              = convert2english( $request->km_price );
        $price->waiting_minute        = convert2english( $request->waiting_minute );
        $price->min_price             = convert2english( $request->min_price );
        $price->client_cancel         = convert2english( $request->client_cancel );
        $price->captain_cancel        = convert2english( $request->captain_cancel );
        $price->save();
        History(Auth::user()->id,'بأضافة خطة أسعار جديدة ');
        return back()->with('success','تم اضافة خطة أسعار جديدة بنجاح');
    }

    #update workstage
    public function updatePrice(Request $request){
        $this->validate($request,[
            'id'                     => 'required',
            'edit_type'              => 'required',
            'edit_car_type_id'       => 'required',
            'edit_counter'           => 'required',
            'edit_km_price'          => 'required',
            'edit_waiting_minute'    => 'required',
            'edit_min_price'         => 'required',
            'edit_client_cancel'     => 'required',
            'edit_captain_cancel'    => 'required',
        ]);

        $price                   = Prices::findOrFail($request->id);
        $price->type             = $request->edit_type;
        $price->car_type_id      = $request->edit_car_type_id;
        $price->counter          = convert2english( $request->edit_counter );
        $price->km_price         = convert2english( $request->edit_km_price );
        $price->waiting_minute   = convert2english( $request->edit_waiting_minute );
        $price->min_price        = convert2english( $request->edit_min_price);
        $price->client_cancel    = convert2english( $request->edit_client_cancel);
        $price->captain_cancel   = convert2english( $request->edit_captain_cancel );
        $price->save();
        History(Auth::user()->id,'بتحديث خطة الأسعار ');
        return redirect('admin/prices')->with('success','تم حفظ التعديلات');
    }

    #delete workstage
    public function DeletePrice(Request $request){
            $price = Prices::findOrFail($request->id);
            $price->delete();
            History(Auth::user()->id,'بحذف خطة أسعار ');
            return back()->with('success','تم الحذف');
    }   

}
