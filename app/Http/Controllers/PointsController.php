<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Points;
use Auth;

class PointsController extends Controller{
    
    //Admin part 
    public function points(){
      $points = Points::orderBy('points','ASC')->get();
      return view('dashboard.points.points',compact('points'));
    }

    #add plan
    public function createPoint(Request $request){
        $this->validate($request,[
            'points'   => 'required',
            'amount'   => 'required'
        ]);

        $point = new Points();        
        $point->points        = convert2english( $request->points );
        $point->amount        = convert2english( $request->amount );
        $point->save();
        History(Auth::user()->id,'بأضافة نقاط المكافأت جديدة.');
        return back()->with('success','تم اضافة نقاط المكافأت بنجاح.');
    }

    #update workstage
    public function updatePoint(Request $request){
        $this->validate($request,[
            'id'            => 'required',
            'edit_points'   => 'required',
            'edit_amount'   => 'required'
        ]);

        $point = Points::findOrFail($request->id);
        $point->type          = $request->edit_type;
        $point->points        = convert2english( $request->edit_points );
        $point->amount        = convert2english( $request->edit_amount );
        $point->save();
        History(Auth::user()->id,'بتعديل الخطة.');
        return back()->with('success','تم حفظ التعديلات.');
    }

    #delete workstage
    public function DeletePoint(Request $request){
            $point = Points::findOrFail($request->id);
            $point->delete();
            History(Auth::user()->id,'بحذف نقاط المكافأت ');
            return back()->with('success','تم الحذف');
    }   
}
