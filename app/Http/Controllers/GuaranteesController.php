<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Guarantees;
use Auth;

class GuaranteesController extends Controller{
    
    //Admin part 
    public function guarantees(){
      $guarantees = Guarantees::orderBy('created_at','DESC')->get();
      return view('dashboard.guarantees.guarantees',compact('guarantees'));
    }

    #add plan
    public function createGuarantees(Request $request){
        $this->validate($request,[
            'description_ar' => 'nullable',
            'description_en' => 'nullable',
            'from_date'   => 'required',
            'from_time'   => 'required',
            'to_date'     => 'required',
            'to_time'     => 'required',
            'num_orders'  => 'required',
            'num_users'   => 'nullable',
            'guarantee'   => 'required'
        ]);

        $guarante = new Guarantees();        
        $guarante->from_date   = date('Y-m-d',strtotime($request->from_date));
        $guarante->from_time   = date('H:i',strtotime($request->from_time));
        $guarante->to_date     = date('Y-m-d',strtotime($request->to_date));
        $guarante->to_time     = date('H:i',strtotime($request->to_time));        
        $guarante->num_orders  = convert2english( $request->num_orders );
        $guarante->num_users   = convert2english( $request->num_users );
        $guarante->guarantee   = convert2english( $request->guarantee );
        $guarante->description_ar   = $request->description_ar;
        $guarante->description_en   = $request->description_en;
        $guarante->save();
        History(Auth::user()->id,'بأضافة ضمان جديدة ');
        return back()->with('success','تم اضافة ضمان جديد بنجاح');
    }

    #update workstage
    public function updateGuarantees(Request $request){
        $this->validate($request,[
            'id'               => 'required',
            'edit_description_ar' => 'nullable',
            'edit_description_en' => 'nullable',
            'edit_from_date'   => 'required',
            'edit_from_time'   => 'required',
            'edit_to_date'     => 'required',
            'edit_to_time'     => 'required',
            'edit_num_orders'  => 'required',
            'edit_num_users'   => 'nullable',
            'edit_guarantee'   => 'required'
        ]);

        $guarante = Guarantees::findOrFail($request->id);
        $guarante->from_date   = date('Y-m-d',strtotime($request->edit_from_date));
        $guarante->from_time   = date('H:i',strtotime($request->edit_from_time));
        $guarante->to_date     = date('Y-m-d',strtotime($request->edit_to_date));
        $guarante->to_time     = date('H:i',strtotime($request->edit_to_time));        
        $guarante->num_orders  = convert2english( $request->edit_num_orders );
        $guarante->num_users  = convert2english( $request->edit_num_users );
        $guarante->guarantee   = convert2english( $request->edit_guarantee );
        $guarante->description_ar   = $request->edit_description_ar;
        $guarante->description_en   = $request->edit_description_en;
        $guarante->save();
        History(Auth::user()->id,'بتعديل الضمان ');
        return back()->with('success','تم حفظ التعديلات');
    }

    #delete workstage
    public function DeleteGuarantees(Request $request){
            $guarante = Guarantees::findOrFail($request->id);
            $guarante->delete();
            History(Auth::user()->id,'بحذف المكافأة.');
            return back()->with('success','تم الحذف.');
    }   
}
  
