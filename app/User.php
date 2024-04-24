<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
 use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject {
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'password','phone','address','lat','long'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ]; 

    public function getJWTIdentifier(){
        return $this->getKey();
    }
    
    public function getJWTCustomClaims(){
        return [];
    }

    public function Role(){
        return $this->belongsTo('App\Role','role','id');
    }  

    public function currentOrder(){
        return $this->hasMany('App\Order','captain_id','id')->where('status','=','inprogress')
                                             /*->orwhere('status','=','finished')->where('confirm_payment','=','false')*/->orderBy('start_journey_time','DESC');
    } 

    public function country(){
        return $this->belongsTo('App\Country','country_id','id');
    } 


    public function city(){
        return $this->belongsTo('App\City','city_id','id');
    } 

    public function plan(){
        return $this->belongsTo('App\Plans','plan_id','id');
    } 

    public function currentCountry(){
        return $this->belongsTo('App\Country','current_country_id','id');
    } 
    
    public function devices(){
        return $this->hasMany('App\userDevices');
    } 

    public function comments(){
        return $this->hasMany('App\Comments','profile_id','id');
    } 

    public function Authentications(){
        return $this->hasMany('App\Authentication','user_id','id');
    }

    public function userMeta(){
        return $this->hasOne('App\userMeta','user_id','id');
    } 

    public function conversations()
    {
        return \App\Conversation::where('user1',$this->id)->orWhere('user2',$this->id)->get();
    }
    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    public function currentCar(){
        return $this->belongsTo('App\userCars','captain_current_car_id','id');
    }

    public function currentCarType(){
        return $this->belongsTo('App\carTypes','captain_current_car_type_id','id');
    }

}
