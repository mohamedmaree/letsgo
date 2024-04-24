<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Country;
use App\City;
use Validator;

class SettingController extends Controller{

    public function checkPublish()
    {
        $data = ['publish' => true];
       return response()->json(successReturn($data));
    }
    
    public function countries(Request $request){
        $c = [];
        $lang = $request->header('lang');
        $countries = Country::orderBy("iso2",'ASC')->get();
        foreach($countries as $country){
                $c[] = ['id'   => $country->id,
                        'iso'  => $country->iso2,
                        'key'  => ($country->phonekey == null)? '': $country->phonekey,
                        'name' => ($country->{"name_$lang"} == null)? '': $country->{"name_$lang"},
                        'currency'  => $country->{"currency_$lang"}
                       ];
        }
        $curCountry = currentCountry();
        $iso = ($curCountry['iso'] == null)? '': $curCountry['iso'];
        $data = ['countries'=>$c ,'currentCountry' => $iso];
       return response()->json(successReturn($data));
    }

    public function getCountryCities(Request $request){
        $validator = Validator::make($request->all(),[
            'country_id'  => 'required',
        ]);
        if($validator->passes()){
            $lang = $request->header('lang');
            $data = [];
          if($cities = City::where('country_id',$request->country_id)->orderBy("name_$lang",'ASC')->get()){
            foreach($cities as $city){
               $data[] = ['id'   => $city->id,
                          'name' => $city->{"name_$lang"}
                         ];
            }
          }
          return response()->json(successReturn($data));
        }else{
            $msg  = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }
}
