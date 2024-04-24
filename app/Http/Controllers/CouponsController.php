<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Coupons;
use Auth;

class CouponsController extends Controller
{

    public function coupons(){
        $coupons = Coupons::latest()->get();
        return view('dashboard.coupons.coupons',compact('coupons'));
    }

    #add coupon
    public function createCoupon(Request $request){
        $this->validate($request,[
            'code'       => 'required',
            'type'       => 'required',
            'value'      => 'required',
            'num_to_use' => 'required',
            'num_to_use_person' => 'required',
            'max_discount' => 'required',
            'budget'      => 'required',
            // 'total_cost'  => 'nullble',
            // 'num_used'    => 'nullble',
            'end_at'      => 'required|date|after:today'
        ]);

        $coupon = new Coupons();
        if($c = Coupons::where(['code'=>$request->code])->first()){
            Session::flash('warning','هذا الكوبون موجود بالفعل.');
            return back();
        }else{
            $coupon->code   = $request->code;
        }        
        $coupon->type       = $request->type;
        $coupon->value      = $request->value;
        $coupon->num_to_use = $request->num_to_use;
        
        $coupon->num_to_use_person = $request->num_to_use_person;
        $coupon->max_discount      = $request->max_discount;
        $coupon->budget            = $request->budget;
        $coupon->total_cost        = 0;

        $coupon->num_used   = 0;
        $coupon->end_at     = date('Y-m-d',strtotime($request->end_at));
        $coupon->save();
        History(Auth::user()->id,'بأضافة كوبون جديد');
        Session::flash('success','تم اضافة الكوبون بنجاح');
        return back();
    }

    #update coupon
    public function updateCoupon(Request $request){
        $this->validate($request,[
            'id'       => 'required',
            'edit_code'       => 'required',
            'edit_type'       => 'required',
            'edit_value'      => 'required',
            'edit_num_to_use' => 'required',
            'edit_num_to_use_person' => 'required',
            'edit_max_discount' => 'required',
            'edit_budget'       => 'required',
            'edit_end_at'       => 'required|date|after:today'
        ]);

        $coupon = Coupons::find($request->id);
        if($c = Coupons::where(['code'=>$request->edit_code])->first()){
            if($c->id != $coupon->id){
            Session::flash('warning','هذا الكوبون موجود بالفعل.');
            return back();              
            }else{
              $coupon->code       = $request->edit_code;
            }
        }else{
              $coupon->code       = $request->edit_code;
        }        
        $coupon->type       = $request->edit_type;
        $coupon->value      = $request->edit_value;
        $coupon->num_to_use = $request->edit_num_to_use;
        // $coupon->num_used   = $request->edit_num_used;

        $coupon->num_to_use_person = $request->edit_num_to_use_person;
        $coupon->max_discount      = $request->edit_max_discount;
        $coupon->budget            = $request->edit_budget;
        // $coupon->total_cost        = $request->edit_total_cost;

        $coupon->end_at     = date('Y-m-d',strtotime($request->edit_end_at));
        $coupon->save();
        History(Auth::user()->id,'تعديل الكوبون');
        Session::flash('success','تم تعديل الكوبون بنجاح');
        return back();

    }

    #delete user
    public function DeleteCoupon(Request $request){
            $coupon = Coupons::findOrFail($request->id);
            $coupon->delete();
            History(Auth::user()->id,'بحذف الكوبون '.$coupon->code);
            return back()->with('success','تم الحذف');
    }

    #delete user
    public function deleteCoupons(Request $request){
        $this->validate(Request(),['deleteids'=>'required']);
            foreach($request->deleteids as $id){
                if($coupon = Coupons::find($id)){
                $coupon->delete();
            }
        }
            History(Auth::user()->id,'بحذف أكثر من كوبون ');
            return back()->with('success','تم الحذف');   
    }


   public function generateCode(){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 7; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        if($coupon = Coupons::where(['code'=>$randomString])->first()){
            return $this->generateCode();
        }
        return $randomString;
   }

}
