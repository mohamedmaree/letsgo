<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Ads;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\userCars;
use App\carTypes;
use App\Notifications;
use App\Offers;
use App\Rewards;
use App\Guarantees;
use App\userMeta;
use App\Page;
use DB;
use App\Stores;
use App\menuCategories;
use App\Order;
use Auth;
use App\CashBack;

class HomeController extends Controller{

    public function nearResturants(Request $request){
      $validator    = Validator::make($request->all(),[
          'lat'             => 'required',
          'long'            => 'required',
          'next_page_token' => 'nullable',
          'page'            => 'nullable'
      ]);
      if($validator->passes()){   
            $lang      = ($request->header('lang'))?? 'ar'; 
            $data = [] ; $places = []; $specialPlaces = []; $places_ended = false; $distance = 0;
            $next_page_token=''; $opening_hours = []; $icon='';
            $googlekey = setting('google_places_key'); 
            $page      = ($request->page)?? 1;
            $sepcial_page   = 1;
            $db_google_page = 1;
            $offset    = ( $page - 1 ) * 20 ;//$this->limit
            $max_distance = setting('distance');
            $cats      = 'restaurant';//$request->cats;
            $msg = '';

          if( ($request->lat == '') || ($request->long == '') ){
              $msg = trans('user.allow_location');
              if($request->header('Authorization')){
                $user = JWTAuth::parseToken()->authenticate();
                $lat       = doubleval($user->lat);
                $long      = doubleval($user->long);  
              }else{
                $lat       = doubleval( 23.8859 );
                $long      = doubleval( 45.0792 );
              }
          }else{
              $lat       = doubleval($request->lat);
              $long      = doubleval($request->long); 
          }         
         
           // get nearst  special stores with menus
            if($stores = DB::select("SELECT * FROM ( SELECT *, ( 6371 * acos( cos( radians('".$lat."') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('".$long."') ) + sin( radians('".$lat."') ) * sin( radians( lat ) ) ) ) AS distance FROM stores where category ='".$cats."' HAVING distance <= ".$max_distance." ORDER BY distance ASC limit 40 offset 0) as nearstBranchs GROUP BY name_en order By distance ASC limit 20 offset $offset")){           
              foreach($stores as $store){
                  // $distance = directDistance($lat,$long,$store->lat,$store->lng);
                  // $distance = ($lang == 'ar')? $distance." كم" : $distance." KM";  
                  $distance = round($store->distance,2);//directDistance($lat,$long,$store->lat,$store->lng);
                  $distance = ($lang == 'ar')? $distance." كم" : $distance." KM"; 
                  // $storemenus = storeMenus::where('store_id','=',$store->id)->get();
                  // $menus = []; 
                  // foreach($storemenus as $menu){
                  //     $menus[] =  url('img/store/menus/'.$menu->image);
                  // }
                  $now       = strtotime('now');
                  $open_from = strtotime($store->open_from);
                  $open_to   = strtotime($store->open_to);
                  if($open_to <= $open_from){
                    $open_to += (24*60*60);
                  }
                  $place_open = false;
                  if( ($now >= $open_from) && ($now <= $open_to) ){
                    $place_open = true;
                  }
                  $rate = ( $store->num_rating > 0 )? floatval($store->rating / $store->num_rating) : 0.0;
                  $specialPlaces[] = ['id'                 => $store->id,
                                      'name'               => ($store->{"name_$lang"})??'',
                                      'icon'               => url('img/store/icons/'.$store->icon),
                                      'cover'              => url('img/store/cover/'.$store->cover),
                                      'phone'              => ($store->phone)??'',
                                      'email'              => ($store->email)??'',
                                      'address'            => ($store->address)??'' ,
                                      'lat'                => doubleval( $store->lat ),               
                                      'long'               => doubleval( $store->lng ),               
                                      'rate'               => doubleval( $rate ).'',               
                                      'num_rating'         => ($store->num_rating)??0,               
                                      'num_comments'       => ($store->num_comments)??0,   
                                      'website'            => ($store->website)??'' ,               
                                      'distance'           => $distance,
                                      'open_from'          => date('H:i a',strtotime($store->open_from)),
                                      'open_to'            => date('H:i a',strtotime($store->open_to)),
                                      'place_open'         => $place_open,
                                      // 'menus'              => $menus,
                  ];
              }
            }else{
              $sepcial_page = '';
            }


          $results = DB::connection('mysql2')->select("SELECT *, ( 6371 * acos( cos( radians('".$lat."') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('".$long."') ) + sin( radians('".$lat."') ) * sin( radians( lat ) ) ) ) AS distance FROM places where name_$lang != '' and types like '".$cats.",%' HAVING distance <= 50 ORDER BY distance ASC limit 20 offset $offset ");
          if(count($results) >= 15 ){
                   $nearst_opening_hours = [] ;
                   foreach($results as $result){
                      $icon = url('img/icons/restaurant.png');               
                      $distance = round($result->distance,2);// directDistance($lat,$long,$result->lat,$result->lng);
                      $distance = ($lang == 'ar')? $distance." كم" : $distance." KM ";
                      $nearst_opening_hours = ($result->{"opening_hours_$lang"} == null)? [] : explode(',', $result->{"opening_hours_$lang"});
                      $places[]     = ['name'            => $result->{"name_$lang"},
                                       'lat'             => doubleval( $result->lat ),
                                       'lng'             => doubleval( $result->lng ),
                                       'icon'            => $icon,
                                       'place_id'        => $result->place_id,
                                       'reference'       => $result->place_ref,
                                       'vicinity'        => $result->{"address_$lang"},
                                       'opening_hours'   => $nearst_opening_hours,
                                       'distance'        => $distance
                              ];
                    // $i++;
                   }  
          }else{
              $db_google_page = '';
              $next_page_token = ($request->next_page_token == '' )?'':'&pagetoken='.$request->next_page_token;
              $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$lat.",".$long."&name=".$cats."&opennow=true&rankby=distance&key=".$googlekey."&language=".$lang.$next_page_token;
              $jsonresult = file_get_contents($url);
              $results    = json_decode($jsonresult);
              if($results->results){
                 foreach($results->results as $result){
                    $icon = url('img/icons/restaurant.png');
                    $distance = directDistance($lat,$long,$result->geometry->location->lat,$result->geometry->location->lng);
                    $distance = ($lang == 'ar')? $distance." كم" : $distance." KM";
                    $places[] = [  'name'            => $result->name,
                                   'lat'             => $result->geometry->location->lat ,
                                   'lng'             => $result->geometry->location->lng ,
                                   'icon'            => $icon,
                                   'place_id'        => $result->place_id,
                                   'reference'       => $result->reference,
                                   'vicinity'        => $result->vicinity,
                                   'opening_hours'   => $opening_hours,
                                   'distance'        => $distance
                              ];
                          if( count( DB::connection('mysql2')->select('select *  from places where place_id = ?  and place_ref = ?',[$result->place_id,$result->reference])) >= 1 ){
                              $address = str_replace(",","", $result->vicinity);
                              $address = str_replace("'","", $address);
                              $types   = implode(',', $result->types);
                              DB::connection("mysql2")->update('update places set name_'.$lang.'="'.$result->name.'" ,lat="'.$result->geometry->location->lat.'" ,lng="'.$result->geometry->location->lng.'" ,address_'.$lang.'="'.$address.'" , icon="'.$result->icon.'" ,types="'.$types.'" where place_id="'.$result->place_id.'" AND place_ref="'.$result->reference.'"');                        
                          }else{
                              $address = str_replace(",","", $result->vicinity);
                              $address = str_replace("'","", $address);
                              $types   = implode(',', $result->types);
                              DB::connection('mysql2')->insert("insert into places (name_$lang, place_id ,place_ref ,lat ,lng ,address_$lang, icon,types) values (?,?,?,?,?,?,?,?)",
                                [ $result->name,$result->place_id,$result->reference,$result->geometry->location->lat,$result->geometry->location->lng,
                                  $address,$result->icon,$types]
                              ); 
                          }  
                 }
                 $next_page_token = (isset($results->next_page_token))?$results->next_page_token:'';
                 $places_ended =  ($next_page_token == '')? true:false;
              }else{
                  $lang = ($lang == 'ar')?'en':'ar';
                  $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$lat.",".$long."&name=".$cats."&opennow=true&rankby=distance&key=".$googlekey."&language=".$lang.$next_page_token;
                  $jsonresult = file_get_contents($url);
                  $results    = json_decode($jsonresult);
                  if($results->results){
                     foreach($results->results as $result){
                        $icon = url('img/icons/restaurant.png');                 
                        $distance = directDistance($lat,$long,$result->geometry->location->lat,$result->geometry->location->lng);
                        $distance = ($lang == 'en')? $distance." كم" : $distance." KM";
                        $places[] = [  'name'            => $result->name,
                                       'lat'             => doubleval( $result->geometry->location->lat ),
                                       'lng'             => doubleval( $result->geometry->location->lng ),
                                       'icon'            => $icon,
                                       'place_id'        => $result->place_id,
                                       'reference'       => $result->reference,
                                       'vicinity'        => $result->vicinity,
                                       'opening_hours'   => $opening_hours,
                                       'distance'        => $distance
                                  ];
                          if( count( DB::connection('mysql2')->select('select *  from places where place_id = ?  and place_ref = ?',[$result->place_id,$result->reference])) >= 1 ){
                              $address = str_replace(",","", $result->vicinity);
                              $address = str_replace("'","", $address);
                              $types   = implode(',', $result->types);
                              DB::connection("mysql2")->update('update places set name_'.$lang.'="'.$result->name.'" ,lat="'.$result->geometry->location->lat.'" ,lng="'.$result->geometry->location->lng.'" ,address_'.$lang.'="'.$address.'" , icon="'.$result->icon.'" ,types="'.$types.'" where place_id="'.$result->place_id.'" AND place_ref="'.$result->reference.'"');                        
                          }else{
                              $address = str_replace(",","", $result->vicinity);
                              $address = str_replace("'","", $address);
                              $types   = implode(',', $result->types);
                              DB::connection('mysql2')->insert("insert into places (name_$lang, place_id ,place_ref ,lat ,lng ,address_$lang, icon,types) values (?,?,?,?,?,?,?,?)",
                                [ $result->name,$result->place_id,$result->reference,$result->geometry->location->lat,$result->geometry->location->lng,
                                  $address,$result->icon,$types]
                              ); 
                          }                             

                     }
                     $next_page_token = (isset($results->next_page_token))?$results->next_page_token:'';
                     $places_ended =  ($next_page_token == '')? true:false;
                  }                        
              } 

          }

          $data = ['places'=>$places ,'specialPlaces' => $specialPlaces ,'next_page_token' => $next_page_token,'page' => intval($page) ,'special_pages_ended' => ($sepcial_page == '' && $db_google_page == '')? true : false];
          return response()->json(successReturn($data,$msg));  
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }               
    }

    public function searchResturants(Request $request){
      $validator    = Validator::make($request->all(),[
          'lat'             => 'required',
          'long'            => 'required',
          'next_page_token' => 'nullable',
          'name'            => 'required',
          'page'            => 'nullable'
      ]);
      if($validator->passes()){  
        $lang = ($request->header('lang'))?? 'ar';
        $data = [] ; $places = []; $specialPlaces = []; $distance = 0;
        $next_page_token =''; $opening_hours = [];
        $icon='';$places_ended = false;
        $googlekey = setting('google_places_key'); 
        $max_distance = setting('distance');
        $name         = ($request->name)??'';
        $page         = ($request->page)?? 1;
        $sepcial_page   = 1;
        $db_google_page = 1;
        $offset       = ( $page - 1 ) * 20 ;//$this->limit        
        $msg = '';
        if( ($request->lat == '') || ($request->long == '') ){
            $msg = trans('user.allow_location');
            if($request->header('Authorization')){
              $user = JWTAuth::parseToken()->authenticate();
              $lat       = doubleval($user->lat);
              $long      = doubleval($user->long);  
            }else{
              $lat       = doubleval( 23.8859 );
              $long      = doubleval( 45.0792 );
            }
        }else{
            $lat       = doubleval($request->lat);
            $long      = doubleval($request->long); 
        }  
        
        // get nearst  special stores with menus
        if($stores = DB::select("SELECT * FROM ( SELECT *, ( 6371 * acos( cos( radians('".$lat."') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('".$long."') ) + sin( radians('".$lat."') ) * sin( radians( lat ) ) ) ) AS distance FROM stores where name_ar LIKE '%$name%' OR name_en LIKE '%$name%' HAVING distance <= ".$max_distance." ORDER BY distance ASC limit 40 offset 0) as nearstBranchs GROUP BY name_en order By distance ASC limit 20 offset $offset")){           
            foreach($stores as $store){
                  // $distance = directDistance($lat,$long,$store->lat,$store->lng);
                  // $distance = ($lang == 'ar')? $distance." كم" : $distance." KM";  
                  $distance = round($store->distance,2);//directDistance($lat,$long,$store->lat,$store->lng);
                  $distance = ($lang == 'ar')? $distance." كم" : $distance." KM"; 
                  // $storemenus = storeMenus::where('store_id','=',$store->id)->get();
                  // $menus = []; 
                  // foreach($storemenus as $menu){
                  //     $menus[] =  url('img/store/menus/'.$menu->image);
                  // }
                  $now       = strtotime('now');
                  $open_from = strtotime($store->open_from);
                  $open_to   = strtotime($store->open_to);
                  if($open_to <= $open_from){
                    $open_to += (24*60*60);
                  }
                  $place_open = false;
                  if( ($now >= $open_from) && ($now <= $open_to) ){
                    $place_open = true;
                  }
                  $rate = ( $store->num_rating > 0 )? floatval($store->rating / $store->num_rating) : 0.0;
                  $specialPlaces[] = ['id'                 => $store->id,
                                      'name'               => ($store->{"name_$lang"})??'',
                                      'icon'               => url('img/store/icons/'.$store->icon),
                                      'cover'              => url('img/store/cover/'.$store->cover),
                                      'phone'              => ($store->phone)??'',
                                      'email'              => ($store->email)??'',
                                      'address'            => ($store->address)??'' ,
                                      'lat'                => doubleval( $store->lat ),               
                                      'long'               => doubleval( $store->lng ),               
                                      'rate'               => doubleval( $rate ).'',               
                                      'num_rating'         => ($store->num_rating)??0,               
                                      'num_comments'       => ($store->num_comments)??0,   
                                      'website'            => ($store->website)??'' ,               
                                      'distance'           => $distance,
                                      'open_from'          => date('H:i a',strtotime($store->open_from)),
                                      'open_to'            => date('H:i a',strtotime($store->open_to)),
                                      'place_open'         => $place_open,
                                      // 'menus'              => $menus,
                  ];
            }
        }else{
            $sepcial_page = '';
        }

        $results = DB::connection('mysql2')->select("SELECT *, ( 3959 * acos( cos( radians('".$lat."') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('".$long."') ) + sin( radians('".$lat."') ) * sin( radians( lat ) ) ) ) AS distance FROM places where name_$lang LIKE '%$name%' HAVING distance <= 50 ORDER BY distance ASC limit 20 offset $offset ");
        if(count($results) >= 1 ){
               $nearst_opening_hours = [] ;
               foreach($results as $result){
                  $icon = url('img/icons/restaurant.png');
                  $distance = round($result->distance,2);//directDistance($lat,$long,$result->lat,$result->lng);
                  $distance = ($lang == 'ar')? $distance." كم" : $distance." KM";
                  $nearst_opening_hours = ($result->{"opening_hours_$lang"} == '')? [] : explode(',',$result->{"opening_hours_$lang"});
                      $places[]     = ['name'            => $result->{"name_$lang"},
                                       'lat'             => doubleval( $result->lat ),
                                       'lng'             => doubleval( $result->lng ),
                                       'icon'            => $icon,
                                       'place_id'        => $result->place_id,
                                       'reference'       => $result->place_ref,
                                       'vicinity'        => $result->{"address_$lang"},
                                       'opening_hours'   => $nearst_opening_hours,
                                       'distance'        => $distance
                              ]; 
                // $i++;
               } 
        }else{
            $db_google_page = '';
            $name = urlencode( $request->name );      
            $next_page_token = ($request->next_page_token == '' )?'':'&pagetoken='.$request->next_page_token;
            $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$lat.",".$long."&name=".$name."&opennow=true&rankby=distance&key=".$googlekey."&language=".$lang.$next_page_token;
            $jsonresult = file_get_contents($url);
            $results    = json_decode($jsonresult);
            if($results->results){
               foreach($results->results as $result){
                  $icon = url('img/icons/restaurant.png');
                  $distance = directDistance($lat,$long,$result->geometry->location->lat,$result->geometry->location->lng);
                  $distance = ($lang == 'ar')? $distance." كم" : $distance." KM";
                      $places[] = ['name'            => $result->name,
                                   'lat'             => doubleval( $result->geometry->location->lat ),
                                   'lng'             => doubleval( $result->geometry->location->lng ),
                                   'icon'            => $icon,
                                   'place_id'        => $result->place_id,
                                   'reference'       => $result->reference,
                                   'vicinity'        => $result->vicinity,
                                   'opening_hours'   => $opening_hours,
                                   'distance'        => $distance
                              ];
                      if( count( DB::connection('mysql2')->select('select *  from places where place_id = ?  and place_ref = ?',[$result->place_id,$result->reference])) >= 1 ){
                          $address = str_replace(",","", $result->vicinity);
                          $address = str_replace("'","", $address);
                          $types   = implode(',', $result->types);
                          DB::connection("mysql2")->update('update places set name_'.$lang.'="'.$result->name.'" ,lat="'.$result->geometry->location->lat.'" ,lng="'.$result->geometry->location->lng.'" ,address_'.$lang.'="'.$address.'" , icon="'.$result->icon.'" ,types="'.$types.'" where place_id="'.$result->place_id.'" AND place_ref="'.$result->reference.'"');                        
                      }else{
                          $address = str_replace(",","", $result->vicinity);
                          $address = str_replace("'","", $address);
                          $types   = implode(',', $result->types);
                          DB::connection('mysql2')->insert("insert into places (name_$lang, place_id ,place_ref ,lat ,lng ,address_$lang, icon,types) values (?,?,?,?,?,?,?,?)",
                            [ $result->name,$result->place_id,$result->reference,$result->geometry->location->lat,$result->geometry->location->lng,
                              $address,$result->icon,$types]
                          ); 
                      }                             

               }
               $next_page_token = (isset($results->next_page_token))?$results->next_page_token:'';
               $places_ended =  ($next_page_token == '')? true:false;
            }
        }       
        
        $data = ['places' => $places , 'specialPlaces' => $specialPlaces ,'next_page_token' => $next_page_token,'page' => intval($page) ,'special_pages_ended' => ($sepcial_page == '' && $db_google_page == '')? true : false,'name' => urldecode($name)];  
        return response()->json(successReturn($data,$msg));        
        }else{
              $msg   = implode(' , ',$validator->errors()->all());
              return response()->json(failReturn($msg));
        }               
    }
   
    //get place details from google
    public function placeDetails(Request $request){
      $validator    = Validator::make($request->all(),[
          'lat'             => 'required',
          'long'            => 'required',
          'place_id'        => 'required',
          'place_ref'       => 'nullable'
      ]);
      if($validator->passes()){  
          $place_name = ''; $place_icon = ''; $place_address = ''; $opening_hours = []; $place_lat =''; $place_long=''; $place_website= ''; $phone='';$email='';
          $msg = '';
          if( ($request->lat == '') || ($request->long == '') ){
              $msg = trans('user.allow_location');
              if($request->header('Authorization')){
                $user = JWTAuth::parseToken()->authenticate();
                $lat       = doubleval($user->lat);
                $long      = doubleval($user->long);  
              }else{
                $lat       = doubleval( 23.8859 );
                $long      = doubleval( 45.0792 );
              }
          }else{
              $lat       = doubleval($request->lat);
              $long      = doubleval($request->long); 
          } 
          
          $distance = floatval((setting('distance') * 0.1 ) / 15 );
          $min_lat  = $lat  - $distance;
          $min_long = $long - $distance;
          $max_lat  = $lat  + $distance;
          $max_long = $long + $distance;          
          $lang     = ($request->header('lang'))??'ar';
          $placeid  = ($request->place_id)??'';  // id from google places
          $placeref = ($request->place_ref)??$placeid; // reference from google places
          $user = JWTAuth::parseToken()->authenticate();
              //using google places
              $result = DB::connection('mysql2')->select('select * from places where place_id = ? or place_ref = ?  limit ?',[$placeid,$placeref,1]);
              if($result){
                  if($result[0]->{"opening_hours_$lang"}){
                          $opening_hours = ($result[0]->{"opening_hours_$lang"} == null)? [] : explode(',',$result[0]->{"opening_hours_$lang"});
                          $place_name    = ($result[0]->{"name_$lang"})?? '';
                          $place_icon = url('img/icons/restaurant.png');
                          $place_address = ($result[0]->{"address_$lang"})?? '';
                          $place_lat     = ($result[0]->lat)?? '';
                          $place_long    = ($result[0]->lng)?? '';
                          $place_website = ($result[0]->website)?? '';  
                  }else{
                    $googlekey = setting('google_places_key'); 
                    $url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=".$placeid."&key=".$googlekey."&language=".$lang;
                    $jsonresult = file_get_contents($url);
                    $results    = json_decode($jsonresult);
                      if(isset($results->result)){
                          $opening_hours = ($results->result->opening_hours->weekday_text)?? [];
                          $place_name    = ($results->result->name)?? '';
                          $place_icon    = url('img/icons/restaurant.png');
                                                
                          $place_address = ($results->result->formatted_address)?? '';
                          $place_lat     = ($results->result->geometry->location->lat)?? '';
                          $place_long    = ($results->result->geometry->location->lng)?? '';
                          $place_website = ($results->result->website)?? '';
                          
                            $new_opening_hours = implode(',', $opening_hours);
                            $place_address     = str_replace(",","", $place_address);
                            $place_address     = str_replace("'","", $place_address);  
                            $types             = implode(',', $results->result->types);
                            if( count( DB::connection('mysql2')->select('select *  from places where place_id = ? ',[$results->result->place_id])) >= 1 ){
                                DB::connection("mysql2")->update("update places set name_$lang='".$place_name."' ,lat='".$results->result->geometry->location->lat."' ,lng='".$results->result->geometry->location->lng."' ,address_$lang='".$place_address."' , icon='".$results->result->icon."' ,types='".$types."' , opening_hours_$lang='".$new_opening_hours."' where place_id='".$results->result->place_id."'");                        
                            }else{
                                DB::connection('mysql2')->insert('insert into places (name_'.$lang.' , place_id ,place_ref ,lat ,lng ,address_'.$lang.' , icon,types, opening_hours_'.$lang.' ) values (?,?,?,?,?,?,?,?,?)',
                                  [ ''.$place_name.'', ''.$results->result->place_id.'',''.$results->result->reference.'',''.$results->result->geometry->location->lat.'',''.$results->result->geometry->location->lng.'',
                                    ''.$place_address.'',''.$results->result->icon.'',''.$types.'',''.$new_opening_hours]
                                );                           
                            }                        
                      }
                    }                        
              }else{
                $googlekey = setting('google_places_key'); 
                $url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=".$placeid."&key=".$googlekey."&language=".$lang;
                $jsonresult = file_get_contents($url);
                $results    = json_decode($jsonresult);
                  if(isset($results->result)){
                      $opening_hours = ($results->result->opening_hours->weekday_text)?? [];
                      $place_name    = ($results->result->name)?? '';
                      $place_icon    = url('img/icons/restaurant.png');
                      $place_address = ($results->result->formatted_address)?? '';
                      $place_lat     = ($results->result->geometry->location->lat)?? '';
                      $place_long    = ($results->result->geometry->location->lng)?? '';
                      $place_website = ($results->result->website)?? '';
                      
                      $new_opening_hours = implode(',', $opening_hours);
                      $place_address = str_replace(",","", $place_address);
                      $place_address = str_replace("'","", $place_address);  
                      $types         = implode(',', $results->result->types);
                      if( count( DB::connection('mysql2')->select('select *  from places where place_id = ? ',[$results->result->place_id])) >= 1 ){
                          DB::connection("mysql2")->update("update places set name_$lang='".$place_name."' ,lat='".$results->result->geometry->location->lat."' ,lng='".$results->result->geometry->location->lng."' ,address_$lang='".$place_address."' , icon='".$results->result->icon."' ,types='".$types."' , opening_hours_$lang='".$new_opening_hours."' where place_id='".$results->result->place_id."'");                        
                      }else{
                          DB::connection('mysql2')->insert('insert into places (name_'.$lang.' , place_id ,place_ref ,lat ,lng ,address_'.$lang.' , icon,types, opening_hours_'.$lang.' ) values (?,?,?,?,?,?,?,?,?)',
                            [ ''.$place_name.'', ''.$results->result->place_id.'',''.$results->result->reference.'',''.$results->result->geometry->location->lat.'',''.$results->result->geometry->location->lng.'',
                              ''.$place_address.'',''.$results->result->icon.'',''.$types.'',''.$new_opening_hours]
                          );                           
                      }                        
                  }
                }
                $place_lat          = doubleval($place_lat);
                $place_long         = doubleval($place_long);
          $data = ['place_id'       => ($placeid)??'',
                   'place_ref'      => ($placeref)??'',
                   'place_name'     => ($place_name)??'',
                   'place_icon'     => ($place_icon)??'',
                   'place_address'  => ($place_address)??'',
                   'place_lat'      => ($place_lat)??0.0,
                   'place_lng'      => ($place_long)??0.0,
                   'distance'       => directDistance($lat,$long,$place_lat,$place_long),
                   'opening_hours'  => ($opening_hours)??[],
                   'place_website'  => ($place_website)??''
                  ];
          return response()->json(successReturn($data,$msg));
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }           
      }
    
    //get special store details
    public function storeDetails(Request $request){
        $validator        = Validator::make($request->all(),[
            'store_id'    => 'required',
            'lat'         => 'nullable',
            'long'        => 'nullable',
        ]);
        if($validator->passes()){
            $lang     = ($request->header('lang'))?? 'ar';
            $distance = 0 ; 
            $data = [] ; $storeData = [] ; $branches = []; /*$menus = [];*/ $menucategories = [];
            $currency = setting('site_currency_'.$lang);
            $max_distance = setting('distance');
            $msg = '';
            if( ($request->lat == '') || ($request->long == '') ){
                $msg = trans('user.allow_location');
                if($request->header('Authorization')){
                  $user = JWTAuth::parseToken()->authenticate();
                  $lat       = doubleval($user->lat);
                  $long      = doubleval($user->long);  
                }else{
                  $lat       = doubleval( 23.8859 );
                  $long      = doubleval( 45.0792 );
                }
            }else{
                $lat       = doubleval($request->lat);
                $long      = doubleval($request->long); 
            } 

            if($store = Stores::find($request->store_id)){
                if($store->parent_id == 0){
                    $storeId = $store->id;
                }else{
                    $storeId = $store->parent_id;
                }
                //menucategories and products
                if($categories = menuCategories::with('products')->where('store_id','=',$storeId)->get()){
                    // $cat_order = 1;
                    foreach ($categories as $category) {
                        $dataproducts = [];
                        if($products = $category->products){
                            foreach($products as $product){
                                $dataproducts[] = [ 'id'               => $product->id,
                                                    'name'             => $product->{"name_$lang"},
                                                    'price'            => $product->price,
                                                    'menu_category_id' => $product->menu_category_id,
                                                    'currency'         => $currency,
                                                    'description'      => ($product->{"description_$lang"})??'',
                                                    'image'            => url('img/store/products/'.$product->image)
                                ];
                            }
                        }
                        $menucategories[] = [ 'id'        => $category->id,
                                              // 'cat_order' => $cat_order,
                                              'store_id'  => $category->store_id,
                                              'name'      => $category->{"name_$lang"},
                                              'products'  => $dataproducts
                        ];
                        // $cat_order++;
                    }
                }

                // foreach($store->menues as $menu){
                //     $menus[] =  url('img/store/menus/'.$menu->image);
                // }
                // $opening_hours = ($store->{"opening_hours_$lang"} == null)? [] : explode(',', $store->{"opening_hours_$lang"});
                $now       = strtotime('now');
                $open_from = strtotime($store->open_from);
                $open_to   = strtotime($store->open_to);
                if($open_to <= $open_from){
                  $open_to += (24*60*60);
                }
                $place_open = false;
                if( ($now >= $open_from) && ($now <= $open_to) ){
                  $place_open = true;
                }
                $rate = ( $store->num_rating > 0 )? floatval($store->rating / $store->num_rating) : 0.0;
                $distance = directDistance($lat,$long,$store->lat,$store->lng);
                $distance = ($lang == 'ar')? $distance." كم" : $distance." KM";
                $storeData = [  'id'                 => $store->id,
                                'name'               => ($store->{"name_$lang"})??'',
                                'parent_id'          => ($store->parent_id)??0,
                                'phone'              => ($store->phone)??'' ,
                                'email'              => ($store->email)??'' ,
                                'icon'               => url('img/store/icons/'.$store->icon),
                                'cover'              => url('img/store/cover/'.$store->cover),
                                'address'            => ($store->address)??'' ,
                                'lat'                => doubleval( $store->lat ),
                                'lng'                => doubleval( $store->lng ),
                                'rate'               => doubleval( $rate).'',
                                'num_rating'         => ($store->num_rating)??0,
                                'num_comments'       => ($store->num_comments)??0,
                                'website'            => ($store->website)??'' ,
                                // 'opening_hours'      => $opening_hours,
                                'distance'           => $distance,
                                // 'menus'              => $menus,
                                'menucategories'     => $menucategories,
                                'open_from'          => date('H:i a',strtotime($store->open_from)),
                                'open_to'            => date('H:i a',strtotime($store->open_to)),
                                'place_open'         => $place_open,
          ];
                $branches[] =  [  'id'                 => $store->id,
                                  'address'            => ($store->address)??'' ,
                                  'lat'                => doubleval( $store->lat ),
                                  'lng'                => doubleval( $store->lng ),
                                  'distance'           => $distance,
                                  'menucategories'     => $menucategories,
                                  'open_from'          => date('H:i a',strtotime($store->open_from)),
                                  'open_to'            => date('H:i a',strtotime($store->open_to)),
                                  'place_open'         => $place_open,

                ];

                if($store->have_branches == 'true'){
                    $parent_id = ($store->parent_id == 0)? $store->id : $store->parent_id;
                    if($otherBranches = Stores::where('parent_id','=',$parent_id)->where('id','!=',$store->id)->orwhere('id','=',$parent_id)->where('id','!=',$store->id)->get()){
                        foreach($otherBranches as $branch){
                            $distance = directDistance($lat,$long,$branch->lat,$branch->lng);
                            $now       = strtotime('now');
                            $open_from = strtotime($branch->open_from);
                            $open_to   = strtotime($branch->open_to);
                            if($open_to <= $open_from){
                              $open_to += (24*60*60);
                            }
                            $place_open = false;
                            if( ($now >= $open_from) && ($now <= $open_to) ){
                              $place_open = true;
                            }
                            if($distance <= $max_distance){
                                $distance = ($lang == 'ar')? $distance." كم" : $distance." KM";
                                $branches[] =  [  'id'                 => $branch->id,
                                                  'address'            => ($branch->address)??'' ,
                                                  'lat'                => doubleval( $branch->lat ),
                                                  'lng'                => doubleval( $branch->lng ),
                                                  'distance'           => $distance,
                                                  'menucategories'     => $menucategories,
                                                  'open_from'          => date('H:i a',strtotime($branch->open_from)),
                                                  'open_to'            => date('H:i a',strtotime($branch->open_to)),
                                                  'place_open'         => $place_open,
                                ];
                            }

                        }
                    }
                }
                // $dis = array_column($branches, 'distance');
                // array_multisort($dis, SORT_ASC, $branches);

                $data = ['store' => $storeData , 'branches' => $branches ];
                return response()->json(successReturn($data,$msg));
            }
            $msg = trans('order.notfound_store');
            return response()->json(failReturn($msg));
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function offers(Request $request){
        $data = [];
        $offers = Offers::where('type','=','user')->orderBy('end_at','ASC')->get();
        foreach ($offers as $offer) {
           $data[] = ['id'      => $offer->id,
                      'image'   => url('img/offers/'.$offer->image),
                      'title'   => ($offer->title)?? '',
                      'notes'   => ($offer->notes)?? '',
                      'expired' => (strtotime($offer->end_at) < time() )? true : false ,
                      'date'    => ($offer->created_at)? $offer->created_at->diffForHumans(): '' 
                     ]; 
        }
        return response()->json(successReturn($data));        
    }

    public function captainOffers(Request $request){
      $data = [];
      $offers = Offers::where('type','=','captain')->orderBy('end_at','ASC')->get();
      foreach ($offers as $offer) {
         $data[] = ['id'      => $offer->id,
                    'image'   => url('img/offers/'.$offer->image),
                    'title'   => ($offer->title)?? '',
                    'notes'   => ($offer->notes)?? '',
                    'expired' => (strtotime($offer->end_at) < time() )? true : false ,
                    'date'    => ($offer->created_at)? $offer->created_at->diffForHumans(): '' 
                   ]; 
      }
      return response()->json(successReturn($data));        
  }

    public function captainCars(Request $request){
      if($user = JWTAuth::parseToken()->authenticate()){
          $lang     = $request->header('lang'); 
          $usercars = [];
          if($cars = userCars::where('user_id','=',$user->id)->orderBy('last_used','DESC')->get()){
             foreach($cars as $car){
                  $car_type_name = '';
                  $car_type_people = true;
                  $car_type_ids = explode(',', $car->car_type_id);
                    if($car_type_ids){
                      foreach($car_type_ids as $car_type_id){
                        if($cartype = getCarType($car_type_id)){
                          $car_type_name   .= $cartype->{"name_$lang"}.' ';
                          $car_type_people = ($cartype->type == 'people')? true : false;
                        }
                      }
                    }              
              $usercars[] = [ 'id'         => $car->id,
                              'people'     => $car_type_people,
                              'type'       => $car_type_name,
                              'brand'      => $car->brand,
                              'model'      => $car->model,
                              'year'       => $car->year,
                              'car_number' => $car->car_number,
                              'last_used'  => ($car->last_used)??'',
                              'image'      => ($car->image)? url('img/car/'.$car->image): url('img/car/default.png')
                        ];
             }
          }
          $data = ['cars' => $usercars ,'available' =>$user->available,'active' => $user->active];
          return response()->json(successReturn($data)); 
      }
      return response()->json(['value' => '0' , 'key' => 'fail' ,'msg' => 'Token is Expired','code'=>419]);
      // return response()->json(failReturn($msg)); 
    }

    public function captainCarDetails(Request $request){
        if($user = JWTAuth::parseToken()->authenticate()){
            $lang     = $request->header('lang');
            $usercar = [];
            if($car = userCars::where('user_id','=',$user->id)->where('id',$user->captain_current_car_id)->first()){
                $car_type_name = '';
                $car_type_people = true;
                $car_type_ids = explode(',', $car->car_type_id);
                if($car_type_ids){
                    foreach($car_type_ids as $car_type_id){
                        if($cartype = getCarType($car_type_id)){
                            $car_type_name   .= $cartype->{"name_$lang"}.' ';
                            $car_type_people = ($cartype->type == 'people')? true : false;
                        }
                    }
                }
                //driving_license
                $meta = userMeta::where('user_id',$user->id)->orderBy('created_at','desc')->first();
                $usercar = [ 'id'         => $car->id,
                    'people'     => $car_type_people,
                    'type'       => $car_type_name,
                    'brand'      => $car->brand,
                    'model'      => $car->model,
                    'year'       => $car->year,
                    'car_number' => $car->car_number,
                    'last_used'  => ($car->last_used)??'',
                    'image'      => ($car->image)? url('img/car/'.$car->image): url('img/car/default.png'),
                    'driving_license'=> $meta?$meta->driving_license?url('img/car/'.$meta->driving_license):'':''
                ];
            }
            $data = ['car' => $usercar ,'available' =>$user->available,'active' => $user->active];
            return response()->json(successReturn($data));
        }
        return response()->json(['value' => '0' , 'key' => 'fail' ,'msg' => 'Token is Expired','code'=>419]);
    }

    public function chooseCaptainCar(Request $request){
        $validator    = Validator::make($request->all(),[
            'car_id'  => 'required',
        ]);
        if($validator->passes()){        
                $user = JWTAuth::parseToken()->authenticate();
                $lang = $request->header('lang'); 
                $data = [];
                if($car = userCars::where(['user_id'=>$user->id,'id'=>$request->car_id])->first()){
                  $car_type_name = '';
                  $car_type_ids = explode(',', $car->car_type_id);
                    if($car_type_ids){
                      foreach($car_type_ids as $car_type_id){
                        if($cartype = getCarType($car_type_id)){
                          $car_type_name   .= $cartype->{"name_$lang"}.' ';
                        }
                      }
                    } 
                    $data[] = [ 'id'         => $car->id,
                                'type'       => $car_type_name,
                                'brand'      => $car->brand,
                                'model'      => $car->model,
                                'car_number' => $car->car_number,
                                'last_used'  => ($car->last_used == null)? '':$car->last_used,
                                'image'      => ($car->image)? url('img/car/'.$car->image): url('img/car/default.png')
                              ];
                    $car->last_used = date('Y-m-d H:i:s');
                    $car->save(); 
                    $user->captain_current_car_id      = $car->id;
                    $user->captain_current_car_type_id = $car->car_type_id.',';
                    $user->order_type                  = ($car->type)? $car->type->order_type : 'trip';
                    $user->save();
                 return response()->json(successReturn($data)); 
                }
              $msg   = trans('user.car_notfound');
              return response()->json(failReturn($msg));
        }else{
              $msg   = implode(' , ',$validator->errors()->all());
              return response()->json(failReturn($msg));
        }
    }

    public function clientHomeAds(Request $request){      
        $data = []; $dataAds = [];
        if($ads = Ads::where('end_at','>=',date('Y-m-d'))->get()){
           foreach ($ads as $ad) {
               $dataAds[] = [ 'id'          => $ad->id,
                              'link'        => $ad->link,
                              'image'       => url('dashboard/uploads/ads/'.$ad->image)
                           ];
           }
        }
        $num_notifications = 0;
        if($request->header('Authorization')){
          if($user = JWTAuth::parseToken()->authenticate()){
            $num_notifications = Notifications::where(['user_id'=>$user->id,'seen'=>'false'])->count();
          }
        }
        $data = ['ads' => $dataAds , 'num_notifications' => $num_notifications];
        return response()->json(successReturn($data));        
    }
    
    public function carTypesFilter(Request $request){
          $lang      = ($request->header('lang'))?? 'ar';
          $data = []; 
          if($cartypes = carTypes::orderBy('id','ASC')->get()){
            foreach ($cartypes as $cartype) {
              $data[] = [ 'id'             => $cartype->id,
                          'name'           => $cartype->{"name_$lang"},
                          'type'           => $cartype->type,
                          'max_weight'     => ($cartype->max_weight)??'',
                          'num_persons'    => intval( $cartype->num_persons ),
                          'image'          => ($cartype->image)? url('img/car/'.$cartype->image):url('img/car/default.png'),
                        ];
            }
          }
          return response()->json(successReturn($data));
    }

    public function clientRewards(Request $request){
      $data = [];
      $lang      = ($request->header('lang'))?? 'ar';
      $rewards = Rewards::where('type','=','client')->where('to_date','>=',date('Y-m-d'))->orderBy('from_date','ASC')->get();
      foreach ($rewards as $reward) {
         $data[] = ['id'          => $reward->id,
                    'type'        => (string) $reward->type,
                    'description' => $reward->{"description_$lang"},
                    'from_date'   => (string) $reward->from_date,
                    'from_time'   => (string) $reward->from_time,
                    'to_date'     => (string) $reward->to_date,
                    'to_time'     => (string) $reward->to_time,
                    'num_orders'  => (int) $reward->num_orders,
                    'points'      => (int) $reward->points,
                   ]; 
      }
      return response()->json(successReturn($data));        
    }

    public function captainRewards(Request $request){
      $data = [];
      $lang      = ($request->header('lang'))?? 'ar';
      $rewards = Rewards::where('type','=','captain')->where('to_date','>=',date('Y-m-d'))->orderBy('from_date','ASC')->get();
      foreach ($rewards as $reward) {
         $data[] = ['id'          => $reward->id,
                    'type'        => (string) $reward->type,
                    'description' => $reward->{"description_$lang"},
                    'from_date'   => (string) $reward->from_date,
                    'from_time'   => (string) $reward->from_time,
                    'to_date'     => (string) $reward->to_date,
                    'to_time'     => (string) $reward->to_time,
                    'num_orders'  => (int) $reward->num_orders,
                    'points'      => (int) $reward->points,
                   ]; 
      }
      return response()->json(successReturn($data));        
    }

    public function guarantees(Request $request){
      $data = [];
      $lang      = ($request->header('lang'))?? 'ar';
      $guarantees = Guarantees::where('to_date','>=',date('Y-m-d'))->orderBy('from_date','ASC')->get();
      foreach ($guarantees as $guarante) {
         $data[] = ['id'          => $guarante->id,
                    'description' => $guarante->{"description_$lang"},
                    'from_date'   => (string) $guarante->from_date,
                    'from_time'   => (string) $guarante->from_time,
                    'to_date'     => (string) $guarante->to_date,
                    'to_time'     => (string) $guarante->to_time,
                    'num_orders'  => (int) $guarante->num_orders,
                    'guarantee'   => (int) $guarante->guarantee,
                   ]; 
      }
      return response()->json(successReturn($data));        
    }

    public function captainRewardsGuarantees(Request $request){
      $dataRewards=[];$dataGuarantees=[];
      $lang      = ($request->header('lang'))?? 'ar';
      $user = JWTAuth::parseToken()->authenticate();
      $rewards = Rewards::where('type','=','captain')->where('to_date','>=',date('Y-m-d'))->orderBy('from_date','ASC')->get();
      foreach ($rewards as $reward) {
      $num_done_orders = Order::where('captain_id','=',$user->id)->where('status','=','finished')->where('created_at','>=',$reward->from_date.' '.$reward->from_time)->where('created_at','<=',$reward->to_date.' '.$reward->to_time)->count();
      $dataRewards[] = [ 'id'          => $reward->id,
                            'type'        => (string) $reward->type,
                            'description' => $reward->{"description_$lang"},
                            'from_date'   => (string) $reward->from_date,
                            'from_time'   => (string) $reward->from_time,
                            'to_date'     => (string) $reward->to_date,
                            'to_time'     => (string) $reward->to_time,
                            'is_finished' =>  strtotime($reward->to_date.' '.$reward->to_time) > strtotime('now') ? false: true,
                            'num_orders'  => (int) $reward->num_orders,
                            'points'      => (int) $reward->points,
                            'num_done_orders' => (int) $num_done_orders,
                            'num_needed_orders' => ((int) $num_done_orders > (int)$reward->num_orders)? 0 : (int) $reward->num_orders - (int) $num_done_orders,
                          ]; 
      }

      $guarantees = Guarantees::where('to_date','>=',date('Y-m-d'))->orderBy('from_date','ASC')->get();
        foreach ($guarantees as $guarante) {
        $num_done_guarantees = Order::where('captain_id','=',$user->id)->where('status','=','finished')->where('created_at','>=',$guarante->from_date.' '.$guarante->from_time)->where('created_at','<=',$guarante->to_date.' '.$guarante->to_time)->count();
        $dataGuarantees[] = ['id'          => $guarante->id,
                              'description' => $guarante->{"description_$lang"},
                              'from_date'   => (string) $guarante->from_date,
                              'from_time'   => (string) $guarante->from_time,
                              'to_date'     => (string) $guarante->to_date,
                              'to_time'     => (string) $guarante->to_time,
                              'is_finished' =>  strtotime($guarante->to_date.' '.$guarante->to_time) > strtotime('now') ? false: true,
                              'num_orders'  => (int) $guarante->num_orders,
                              'points'   => (int) $guarante->guarantee,
                              'num_done_orders' => (int) $num_done_guarantees,
                              'num_needed_orders' => ((int) $num_done_guarantees > (int)$guarante->num_orders)? 0 : (int) $guarante->num_orders - (int) $num_done_guarantees,
                            ]; 
      }
      $data = ['rewards' => $dataRewards,'guarantees' => $dataGuarantees];
      return response()->json(successReturn($data));        
    }

    public function cashBack(Request $request){
      $data = [];
      $lang      = ($request->header('lang'))?? 'ar';
      $cashbacks = cashBack::where('to_date','>',date('Y-m-d'))
                            ->where('total_cost','<','budget')
                            ->orwhere('to_date','=',date('Y-m-d'))
                            ->where('to_time','>=',date('H:i'))
                            ->where('total_cost','<','budget')
                            ->orderBy('from_date','ASC')->get();
      foreach ($cashbacks as $cashback) {
        if((int)$cashback->total_cost >= (int)$cashback->budget){
          continue;
        }
         $data[] = ['id'          => $cashback->id,
                    'name'        => $cashback->{"name_$lang"},
                    'description' => $cashback->{"description_$lang"},
                    'from_date'   => (string) $cashback->from_date,
                    'from_time'   => (string) date('H:i',strtotime($cashback->from_time)),
                    'to_date'     => (string) $cashback->to_date,
                    'to_time'     => (string) date('H:i',strtotime($cashback->to_time)),
                    'percentage'  => (int) $cashback->percentage,
                    'max_discount'   => (string)$cashback->max_discount,
                    'num_orders_one_user'   => (int) $cashback->num_orders_one_user,
                   ]; 
      }
      return response()->json(successReturn($data));        
    }


  public function supportPhone(Request $request){
    $data['captain_phone']  =  setting('captains_support_phone');
    $data['client_phone']  =  setting('clients_support_phone');
    return response()->json(successReturn($data));
 }

    public function about_app(Request $request){
         $lang = $request->header('lang');
        if($lang == 'en' ){
            $data['about_app']  = strip_tags( setting('about_app_en') );
            return response()->json(successReturn($data));
        }else{
            $data['about_app']  = strip_tags( setting('about_app_ar') );
            return response()->json(successReturn($data));
        }
    }

    public function terms(Request $request){
         $lang = $request->header('lang');
        if($lang == 'en' ){
            $data['terms']  = strip_tags( setting('terms_en') );
            return response()->json(successReturn($data));
        }else{
            $data['terms']  = strip_tags( setting('terms_ar') );
            return response()->json(successReturn($data));
        }
    }    

    public function privacy(Request $request){
         $lang = $request->header('lang');
        if($lang == 'en' ){
            $data['privacy']  = strip_tags( setting('privacy_en') );
            return response()->json(successReturn($data));
        }else{
            $data['privacy']  = strip_tags( setting('privacy_ar') );
            return response()->json(successReturn($data));
        }
    } 
      
    public function helpPages(Request $request){
      $lang = ($request->header('lang') )??'ar';
      $data = [];
      if($pages = Page::orderBy('created_at','ASC')->get()){
          foreach ($pages as $page) {
            $data[] = [ 'id'       => $page->id,
                        'title'    => $page->{"title_$lang"},
                        'content'  => $page->{"content_$lang"},
                      ]; 
          }
      }
      return response()->json(successReturn($data));
    }   
  
}
