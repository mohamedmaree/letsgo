<?php

namespace App;

use App\Traits\Uploadable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Spatie\Translatable\HasTranslations;

class Advantage extends Model
{
    use  Uploadable;

    protected $guarded = [];


    public function getImagePathAttribute()
    {
        return asset('assets/uploads/advantages/' . $this->image);
    }


    public static function boot()
    {
        parent::boot();
        static::deleted(function ($instance) {
            File::delete(public_path('assets/uploads/advantages/' . $instance->image));
        });
    }
}
