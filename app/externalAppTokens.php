<?php

namespace App;

use Illuminate\Foundation\Auth\externalAppTokens as Authenticatable;

class externalAppTokens extends Authenticatable{
	protected $table = 'external_app_tokens';
    
    protected $fillable = [
       'email', 'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function orders(){
        return $this->hasMany('App\Order','external_app_id','id');
    } 
}
