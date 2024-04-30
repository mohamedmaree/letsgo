<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Offers;
use Auth;
use Illuminate\Support\Facades\DB; 

class OffersController extends Controller{

    public function offers(){
        $offers = Offers::where('type','=','user')->orderBy('created_at','DESC')->get();
        return view('dashboard.offers.offers',compact('offers'));
    }

    #add Store
    public function createOffer(Request $request){
        $this->validate($request,[
            'title'     => 'required',
            'end_at'    => 'required|date|after:'.date('Y-m-d'),
            'image'     => 'required'
        ]);

        $offer = new Offers();        
        $offer->end_at       = $request->end_at;
        $offer->title        = $request->title;
        $offer->notes        = $request->notes;
        $offer->type         = 'user';
        if($request->hasFile('image')) {
            $image           = $request->file('image');
            $name            = md5($request->file('image')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/img/offers/');
            $imagePath       = $destinationPath. "/".  $name;
            $image->move($destinationPath, $name);
            $offer->image = $name;
        }
        $offer->save();
        History(Auth::user()->id,'بأضافة عرض جديد');
        Session::flash('success','تم اضافة عرض جديد بنجاح');
        return back();
    }

    #update coupon
    public function updateOffer(Request $request){
        $this->validate($request,[
            'id'             => 'required',
            'edit_title'     => 'required',
            'edit_end_at'    => 'required|date|after:'.date('Y-m-d')
        ]);

        $offer = Offers::findOrFail($request->id);
        $offer->end_at  = $request->edit_end_at;
        $offer->title   = $request->edit_title;
        $offer->notes   = $request->edit_notes;
        if($request->hasFile('edit_image')) {
            $image           = $request->file('edit_image');
            $name            = md5($request->file('edit_image')->getClientOriginalName()).rand(99999,1000000).'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/img/offers/');
            $imagePath       = $destinationPath. "/".  $name;
            $image->move($destinationPath, $name);
            $offer->image = $name;
        }
        $offer->save();
            Session::flash('success','تم تعديل العرض بنجاح');
            return back();
    }

    public function DeleteOffer(Request $request){
            $offer = Offers::findOrFail($request->id);
            $offer->delete();
            History(Auth::user()->id,'بحذف العرض');
            return back()->with('success','تم الحذف');
    }

    public function offersCaptain(){
        $offers = Offers::where('type','=','captain')->orderBy('created_at','DESC')->get();
        return view('dashboard.offers.offersCaptain',compact('offers'));
    }

    #add Store
    public function createCaptainOffer(Request $request){
        $this->validate($request,[
            'title'     => 'required',
            'end_at'    => 'required|date|after:'.date('Y-m-d'),
            'image'     => 'required'
        ]);

        $offer = new Offers();        
        $offer->end_at       = $request->end_at;
        $offer->title        = $request->title;
        $offer->notes        = $request->notes;
        $offer->type         = 'captain';
        if($request->hasFile('image')) {
            $image           = $request->file('image');
            $name            = md5($request->file('image')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/img/offers/');
            $imagePath       = $destinationPath. "/".  $name;
            $image->move($destinationPath, $name);
            $offer->image = $name;
        }
        $offer->save();
        History(Auth::user()->id,'بأضافة عرض جديد');
        Session::flash('success','تم اضافة عرض جديد بنجاح');
        return back();
    }

    #update coupon
    public function updateCaptainOffer(Request $request){
        $this->validate($request,[
            'id'             => 'required',
            'edit_title'     => 'required',
            'edit_end_at'    => 'required|date|after:'.date('Y-m-d')
        ]);

        $offer = Offers::findOrFail($request->id);
        $offer->end_at  = $request->edit_end_at;
        $offer->title   = $request->edit_title;
        $offer->notes   = $request->edit_notes;
        if($request->hasFile('edit_image')) {
            $image           = $request->file('edit_image');
            $name            = md5($request->file('edit_image')->getClientOriginalName()).rand(99999,1000000).'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/img/offers/');
            $imagePath       = $destinationPath. "/".  $name;
            $image->move($destinationPath, $name);
            $offer->image = $name;
        }
        $offer->save();
            Session::flash('success','تم تعديل العرض بنجاح');
            return back();
    }

    public function DeleteCaptainOffer(Request $request){
            $offer = Offers::findOrFail($request->id);
            $offer->delete();
            History(Auth::user()->id,'بحذف العرض');
            return back()->with('success','تم الحذف');
    }


}
