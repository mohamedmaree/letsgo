<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Plans;
use Auth;

class PlanController extends Controller{

    //Admin part 
    public function plans(){
      $plans = Plans::orderBy('id','ASC')->get(); 
      return view('dashboard.plans.plans',compact('plans'));
    }

    #add product
    public function createPlan(Request $request){
        $this->validate($request,[
            'name_ar'         => 'required',
            'name_en'         => 'required',
            'working_hours'   => 'required',
            'acceptance_rate' => 'required',
            'rate'            => 'required',
            'num_orders'      => 'required',
            'reward'          => 'required',
        ]);

        $plan = new Plans();        
        $plan->name_ar         = $request->name_ar;
        $plan->name_en         = $request->name_en;
        $plan->working_hours   = convert2english( $request->working_hours );
        $plan->acceptance_rate = convert2english( $request->acceptance_rate );
        $plan->rate            = convert2english( $request->rate );
        $plan->num_orders      = convert2english( $request->num_orders );
        $plan->reward          = convert2english( $request->reward );
        $plan->save();
        History(Auth::user()->id,'بأضافة المستوي '.$plan->name_ar);
        return back()->with('success','تم اضافة مستوي جديد بنجاح');
    }

    #update workstage
    public function updatePlan(Request $request){
        $this->validate($request,[
            'id'              => 'required',
            'edit_name_ar'         => 'required',
            'edit_name_en'         => 'required',
            'edit_working_hours'   => 'required',
            'edit_acceptance_rate' => 'required',
            'edit_rate'            => 'required',
            'edit_num_orders'      => 'required',
            'edit_reward'          => 'required',
        ]);

        $plan                  = Plans::findOrFail($request->id);
        $plan->name_ar         = $request->edit_name_ar;
        $plan->name_en         = $request->edit_name_en;
        $plan->working_hours   = convert2english( $request->edit_working_hours );
        $plan->acceptance_rate = convert2english( $request->edit_acceptance_rate );
        $plan->rate            = convert2english( $request->edit_rate );
        $plan->num_orders      = convert2english( $request->edit_num_orders );
        $plan->reward          = convert2english( $request->edit_reward );
        $plan->save();
        History(Auth::user()->id,'بتحديث المستوي '.$plan->name_ar);
        return redirect('admin/plans')->with('success','تم حفظ التعديلات');
    }

    #delete workstage
    public function DeletePlan(Request $request){
            $plan = Plans::findOrFail($request->id);
            $plan->delete();
            History(Auth::user()->id,'بحذف الدولة '.$plan->name_ar);
            return back()->with('success','تم الحذف');
    }   

}
