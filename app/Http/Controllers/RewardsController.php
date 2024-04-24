<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rewards;
use Auth;

class RewardsController extends Controller{
    
    //Admin part 
    public function rewards(){
      $rewards = Rewards::orderBy('created_at','DESC')->get();
      return view('dashboard.rewards.rewards',compact('rewards'));
    }

    #add plan
    public function createRewards(Request $request){
        $this->validate($request,[
            'description_ar' => 'nullable',
            'description_en' => 'nullable',
            'type'        => 'required',
            'from_date'   => 'required',
            'from_time'   => 'required',
            'to_date'     => 'required',
            'to_time'     => 'required',
            'num_orders'  => 'nullable',
            'num_users'   => 'required',
            'points'      => 'required'
        ]);

        $reward = new Rewards();        
        $reward->type        = $request->type;
        $reward->from_date   = date('Y-m-d',strtotime($request->from_date));
        $reward->from_time   = date('H:i',strtotime($request->from_time));
        $reward->to_date     = date('Y-m-d',strtotime($request->to_date));
        $reward->to_time     = date('H:i',strtotime($request->to_time));        
        $reward->num_orders  = convert2english( $request->num_orders );
        $reward->num_users   = convert2english( $request->num_users );
        $reward->points      = convert2english( $request->points );
        $reward->description_ar   = $request->description_ar;
        $reward->description_en   = $request->description_en;
        $reward->save();
        History(Auth::user()->id,'بأضافة مكافأة جديدة ');
        return back()->with('success','تم اضافة مكافأة جديد بنجاح');
    }

    #update workstage
    public function updateRewards(Request $request){
        $this->validate($request,[
            'id'               => 'required',
            'edit_description_ar' => 'nullable',
            'edit_description_en' => 'nullable',
            'edit_type'        => 'required',
            'edit_from_date'   => 'required',
            'edit_from_time'   => 'required',
            'edit_to_date'     => 'required',
            'edit_to_time'     => 'required',
            'edit_num_orders'  => 'required',
            'edit_num_users'   => 'nullable',
            'edit_points'      => 'required'
        ]);

        $reward = Rewards::findOrFail($request->id);
        $reward->type        = $request->edit_type;
        $reward->from_date   = date('Y-m-d',strtotime($request->edit_from_date));
        $reward->from_time   = date('H:i',strtotime($request->edit_from_time));
        $reward->to_date     = date('Y-m-d',strtotime($request->edit_to_date));
        $reward->to_time     = date('H:i',strtotime($request->edit_to_time));        
        $reward->num_orders  = convert2english( $request->edit_num_orders );
        $reward->num_users   = convert2english( $request->edit_num_users );
        $reward->points      = convert2english( $request->edit_points );
        $reward->description_ar   = $request->edit_description_ar;
        $reward->description_en   = $request->edit_description_en;
        $reward->save();
        History(Auth::user()->id,'بتعديل المكافأة ');
        return back()->with('success','تم حفظ التعديلات');
    }

    #delete workstage
    public function DeleteRewards(Request $request){
            $reward = Rewards::findOrFail($request->id);
            $reward->delete();
            History(Auth::user()->id,'بحذف المكافأة.');
            return back()->with('success','تم الحذف.');
    }   
}
