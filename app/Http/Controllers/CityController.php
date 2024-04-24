<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;

class CityController extends Controller{

    public function getCitiesByCountry($country_id = false){
      if($country_id != false){
         $cities = City::where('country_id','=',$country_id)->orderBy('name_ar','ASC')->get(); 
         return json_encode($cities);       
      }
       return '';
    }

}
