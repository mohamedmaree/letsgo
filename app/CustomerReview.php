<?php

namespace App;

use App\Traits\Uploadable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Spatie\Translatable\HasTranslations;

class CustomerReview extends Model
{
    use  Uploadable;

    protected $guarded = [];


    public function getImagePathAttribute()
    {
        return asset('assets/uploads/customer_reviews/' . $this->image);
    }


    public static function boot()
    {
        parent::boot();
        static::deleted(function ($instance) {
            File::delete(public_path('assets/uploads/customer_reviews/' . $instance->image));
        });
    }
}
