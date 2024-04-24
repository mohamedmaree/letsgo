<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Uuid;


trait Uploadable
{

    public function uploadOne($file, $domain, $aspect = false, $width = null, $height = null,$quality = 60)
    {
        $directory = public_path($domain);
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0777, true, true);
        }

        $image = Image::make($file);

        // resize image to fixed size with no aspect
        if ($aspect != false and $width != null and $height != null) {
            $image->resize($width, $height);
            // resize only the width of the image with no aspect
        } elseif($aspect == false and $width != null) {
            $image->resize($width, null);
            // resize only the height of the image with no aspect
        } elseif($aspect == false and $width != null) {
            $image->resize(null, $height);

            // resize the image to a *fixed width* and constrain aspect ratio (auto height)
        } elseif($aspect == true and $width != null) {
            $image->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            // resize the image to a *fixed height* and constrain aspect ratio (auto height)
        } elseif($aspect == true and $height != null) {
            $image->resize(null, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

//        $path = Uuid::uuid4()->toString() . '.' . $file->getClientOriginalExtension();
        $path = Uuid::uuid4()->toString() . '.' . 'JPG';
        $image->save($directory . '/' . $path, $quality);

        return $path;
    }

    public function uploadFile($file, $domain)
    {
        $directory = public_path('assets/uploads/' . $domain);

        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0777, true, true);
        }

        $path = Uuid::uuid4()->toString() . '.' . $file->getClientOriginalExtension();

        $file->move($directory, $path);

        return $path;
    }

}
