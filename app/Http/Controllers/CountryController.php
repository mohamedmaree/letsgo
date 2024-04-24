<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Country;
use Auth;
use App\City;

class CountryController extends Controller{

    public function countries(){
      $countries = Country::orderBy('name_ar','ASC')->get(); 
      return view('dashboard.setting.countries',compact('countries'));
    }

    public function createCountry(Request $request){
        $this->validate($request,[
            'iso2'         => 'required|min:2|max:2',
            'name_ar'      => 'required|min:3',
            'name_en'      => 'required|min:3',
            'phonekey'     => 'required|min:3',
            'currency_ar'  => 'required|min:2',
            'currency_en'  => 'required|min:2',
        ]);
        $country = new Country();        
        $country->iso2        = $request->iso2;
        $country->name_ar     = $request->name_ar;
        $country->name_en     = $request->name_en;
        $country->phonekey    = convert2english( $request->phonekey );
        $country->currency_ar = $request->currency_ar;
        $country->currency_en = $request->currency_en;
        $country->save();
        History(Auth::user()->id,'بأضافة الدولة '.$country->name_ar);
        return back()->with('success','تم اضافة دولة جديدة بنجاح');
    }

    public function updateCountry(Request $request){
        $this->validate($request,[
            'id'                => 'required',
            'edit_iso2'         => 'required|min:2|max:2',
            'edit_name_ar'      => 'required|min:3',
            'edit_name_en'      => 'required|min:3',
            'edit_phonekey'     => 'required|min:3',
            'edit_currency_ar'  => 'required|min:2',
            'edit_currency_en'  => 'required|min:2',
        ]);
        $country = Country::findOrFail($request->id);
        $country->iso2        = $request->edit_iso2;
        $country->name_ar     = $request->edit_name_ar;
        $country->name_en     = $request->edit_name_en;
        $country->phonekey    = convert2english( $request->edit_phonekey );
        $country->currency_ar = $request->edit_currency_ar;
        $country->currency_en = $request->edit_currency_en;
        $country->save();
        History(Auth::user()->id,'بتحديث الدولة '.$country->name_ar);
        return redirect('admin/countries')->with('success','تم حفظ التعديلات');
    }

    public function DeleteCountry(Request $request){
            $country = Country::findOrFail($request->id);
            $country->delete();
            History(Auth::user()->id,'بحذف الدولة '.$country->name_ar);
            return back()->with('success','تم الحذف');
    }   

    public function cities($country_id = 1){
      $cities  = City::where('country_id',$country_id)->orderBy('name_ar','ASC')->get(); 
      $country = Country::findOrFail($country_id);
      return view('dashboard.setting.cities',compact('cities','country'));
    }

    public function createCity(Request $request){
        $this->validate($request,[
            'country_id'   => 'required',
            'name_ar'      => 'required|min:3',
            'name_en'      => 'required|min:3'
        ]);

        $city = new City();        
        $city->country_id     = $request->country_id;
        $city->name_ar        = $request->name_ar;
        $city->name_en     = $request->name_en;
        $city->save();
        History(Auth::user()->id,'بأضافة مدينة '.$city->name_ar);
        return back()->with('success','تم اضافة مدينة جديدة بنجاح');
    }

    public function updateCity(Request $request){
        $this->validate($request,[
            'id'                => 'required',
            'edit_country_id'   => 'required',
            'edit_name_ar'      => 'required|min:3',
            'edit_name_en'      => 'required|min:3'
        ]);

        $city = City::findOrFail($request->id);
        $city->country_id  = $request->edit_country_id;
        $city->name_ar     = $request->edit_name_ar;
        $city->name_en     = $request->edit_name_en;
        $city->save();
        History(Auth::user()->id,'بتحديث المدينة '.$city->name_ar);
        return redirect('admin/cities/'.$city->country_id)->with('success','تم حفظ التعديلات');
    }

    #delete workstage
    public function DeleteCity(Request $request){
            $city = City::findOrFail($request->id);
            $city->delete();
            History(Auth::user()->id,'بحذف المدينة '.$city->name_ar);
            return back()->with('success','تم الحذف');
    }   


}
