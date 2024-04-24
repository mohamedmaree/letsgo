<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CashBack;
use Auth;

class CashBackController extends Controller{
    
    //Admin part 
    public function cashBack(){
      $cashBacks = CashBack::orderBy('created_at','DESC')->get();
      return view('dashboard.cashBack.index',compact('cashBacks'));
    }

    #add plan
    public function createCashBack(Request $request){
        $this->validate($request,[
            'name_ar' => 'required',
            'name_en' => 'required',
            'description_ar' => 'nullable',
            'description_en' => 'nullable',
            'from_date'   => 'required',
            'from_time'   => 'required',
            'to_date'     => 'required',
            'to_time'     => 'required',
            'percentage'    => 'required',
            'max_discount'  => 'required',
            'budget'        => 'required',
            // 'total_cost'    => 'nullable',
            'num_orders_one_user'  => 'nullable',
        ]);

        $cash = new CashBack();    
        $cash->name_ar   = $request->name_ar;
        $cash->name_en   = $request->name_en;   
        $cash->description_ar   = $request->description_ar;
        $cash->description_en   = $request->description_en; 
        $cash->from_date   = date('Y-m-d',strtotime($request->from_date));
        $cash->from_time   = date('H:i',strtotime($request->from_time));
        $cash->to_date     = date('Y-m-d',strtotime($request->to_date));
        $cash->to_time     = date('H:i',strtotime($request->to_time));   
        
        $cash->percentage  = convert2english( $request->percentage );
        $cash->max_discount  = convert2english( $request->max_discount );
        $cash->budget   = convert2english( $request->budget );
        $cash->num_orders_one_user      = $request->num_orders_one_user ;

        $cash->save();
        History(Auth::user()->id,'بأضافة كاش باك جديد ');
        return back()->with('success','تم اضافة كاش باك جديد بنجاح');
    }

    #update workstage
    public function updateCashBack(Request $request){
        $this->validate($request,[
            'id'               => 'required',
            'edit_name_ar' => 'required',
            'edit_name_en' => 'required',
            'edit_description_ar' => 'nullable',
            'edit_description_en' => 'nullable',
            'edit_from_date'   => 'required',
            'edit_from_time'   => 'required',
            'edit_to_date'     => 'required',
            'edit_to_time'     => 'required',
            'edit_percentage'    => 'required',
            'edit_max_discount'  => 'required',
            'edit_budget'        => 'required',
            // 'edit_total_cost'    => 'nullable',
            'edit_num_orders_one_user'  => 'nullable',
        ]);

        $cash = CashBack::findOrFail($request->id);
        $cash->name_ar   = $request->edit_name_ar;
        $cash->name_en   = $request->edit_name_en;
        $cash->description_ar   = $request->edit_description_ar;
        $cash->description_en   = $request->edit_description_en;
        $cash->from_date   = date('Y-m-d',strtotime($request->edit_from_date));
        $cash->from_time   = date('H:i',strtotime($request->edit_from_time));
        $cash->to_date     = date('Y-m-d',strtotime($request->edit_to_date));
        $cash->to_time     = date('H:i',strtotime($request->edit_to_time));        
       
        $cash->percentage  = convert2english( $request->edit_percentage );
        $cash->max_discount  = convert2english( $request->edit_max_discount );
        $cash->budget   = convert2english( $request->edit_budget );
        $cash->num_orders_one_user      = $request->edit_num_orders_one_user ;

        $cash->save();
        History(Auth::user()->id,'بتعديل الكاش باك ');
        return back()->with('success','تم حفظ التعديلات');
    }

    #delete workstage
    public function DeleteCashBack(Request $request){
        $cash = CashBack::findOrFail($request->id);
        $cash->delete();
        History(Auth::user()->id,'بحذف الكاش باك.');
        return back()->with('success','تم الحذف.');
    }   
}
