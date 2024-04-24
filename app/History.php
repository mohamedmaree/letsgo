<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
	protected $table = 'report';
	
	public function User()
	{
		return $this->belongsTo('App\User','user_id','id');
	}


}
