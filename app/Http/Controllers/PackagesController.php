<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Package;
use Auth;
use Illuminate\Support\Facades\DB; 

class PackagesController extends Controller{

    public function clientPackages(){
        $packages = Package::where('type','=','user')->orderBy('created_at','DESC')->get();
        $total_packages  = Package::select('*', DB::raw("(num_sells * price) as total"))->where('type','=','user')->get();
        $total = $total_packages->sum('total')??0;
        return view('dashboard.packagesClient.index',compact('packages','total'));
    }

    #add Store
    public function createClientPackage(Request $request){
        $this->validate($request,[
            'name_ar'           => 'required',
            'name_en'           => 'required',
            'description_ar'    => 'nullable',
            'description_en'    => 'nullable',
            'price'             => 'required',
            'offer_price'       => 'required',
            'offer_percent'     => 'required',
            // 'type'              =>  'required|in:user,provider'
            // 'num_days'       => 'required',
        ]);

        $package = new Package();        
        $package->name_ar        = $request->name_ar;
        $package->name_en        = $request->name_en;
        $package->description_ar = $request->description_ar;
        $package->description_en = $request->description_en;
        $package->price          = $request->price;
        $package->offer_price    = $request->offer_price;
        $package->offer_percent  = $request->offer_percent;
        $package->type           = 'user';//$request->type;
        // $package->num_days       = $request->num_days;
        $package->save();
        History(Auth::user()->id,'بأضافة باقة جديدة');
        Session::flash('success','تم اضافة باقة جديدة بنجاح');
        return back();
    }

    #update coupon
    public function updateClientPackage(Request $request){
        $this->validate($request,[
            'id'                  => 'required',
            'edit_name_ar'        => 'required',
            'edit_name_en'        => 'required',
            'edit_description_ar' => 'nullable',
            'edit_description_en' => 'nullable',
            'edit_price'          => 'required',
            'edit_offer_price'    => 'required',
            'edit_offer_percent'  => 'required',
            // 'edit_type'           => 'required|in:user,provider',
            // 'edit_num_days'       => 'required',
        ]);

        $package = Package::findOrFail($request->id);
        $package->name_ar        = $request->edit_name_ar;
        $package->name_en        = $request->edit_name_en;
        $package->description_ar = $request->edit_description_ar;
        $package->description_en = $request->edit_description_en;
        $package->price          = $request->edit_price;
        $package->offer_price    = $request->edit_offer_price;
        $package->offer_percent  = $request->edit_offer_percent;
        // $package->type           = $request->edit_type;
        // $package->num_days       = $request->num_days;
        $package->save();
            Session::flash('success','تم تعديل الباقة بنجاح');
            return back();
    }

    public function DeleteClientPackage(Request $request){
        $package = Package::findOrFail($request->id);
        $package->delete();
        History(Auth::user()->id,'بحذف الباقة');
        return back()->with('success','تم الحذف');
    }


    public function captainPackages(){
        $packages = Package::where('type','=','provider')->orderBy('created_at','DESC')->get();
        $total_packages  = Package::select('*', DB::raw("(num_sells * price) as total"))->where('type','=','provider')->get();
        $total = $total_packages->sum('total')??0;
        return view('dashboard.packagesCaptain.index',compact('packages','total'));
    }

    #add Store
    public function createCaptainPackage(Request $request){
        $this->validate($request,[
            'name_ar'           => 'required',
            'name_en'           => 'required',
            'description_ar'    => 'nullable',
            'description_en'    => 'nullable',
            'price'             => 'required',
            'offer_price'       => 'required',
            'offer_percent'     => 'required',
            // 'type'              =>  'required|in:user,provider'
            // 'num_days'       => 'required',
        ]);

        $package = new Package();        
        $package->name_ar        = $request->name_ar;
        $package->name_en        = $request->name_en;
        $package->description_ar = $request->description_ar;
        $package->description_en = $request->description_en;
        $package->price          = $request->price;
        $package->offer_price    = $request->offer_price;
        $package->offer_percent  = $request->offer_percent;
        $package->type           = 'provider';//$request->type;
        // $package->num_days       = $request->num_days;
        $package->save();
        History(Auth::user()->id,'بأضافة باقة جديدة');
        Session::flash('success','تم اضافة باقة جديدة بنجاح');
        return back();
    }

    #update coupon
    public function updateCaptainPackage(Request $request){
        $this->validate($request,[
            'id'                  => 'required',
            'edit_name_ar'        => 'required',
            'edit_name_en'        => 'required',
            'edit_description_ar' => 'nullable',
            'edit_description_en' => 'nullable',
            'edit_price'          => 'required',
            'edit_offer_price'    => 'required',
            'edit_offer_percent'  => 'required',
            // 'edit_type'           => 'required|in:user,provider',
            // 'edit_num_days'       => 'required',
        ]);

        $package = Package::findOrFail($request->id);
        $package->name_ar        = $request->edit_name_ar;
        $package->name_en        = $request->edit_name_en;
        $package->description_ar = $request->edit_description_ar;
        $package->description_en = $request->edit_description_en;
        $package->price          = $request->edit_price;
        $package->offer_price    = $request->edit_offer_price;
        $package->offer_percent  = $request->edit_offer_percent;
        // $package->type           = $request->edit_type;
        // $package->num_days       = $request->num_days;
        $package->save();
            Session::flash('success','تم تعديل الباقة بنجاح');
            return back();
    }

    public function DeleteCaptainPackage(Request $request){
        $package = Package::findOrFail($request->id);
        $package->delete();
        History(Auth::user()->id,'بحذف الباقة');
        return back()->with('success','تم الحذف');
    }

}
