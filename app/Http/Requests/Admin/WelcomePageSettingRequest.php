<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class WelcomePageSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'settings.logo'              =>  'nullable|image|mimes:jpeg,png,jpg,svg',
            'settings.name_en'           =>  'nullable|max:255',
            'settings.about'          =>  'required',
            'settings.about_en'          =>  'nullable',
            'settings.welcome_msg'    =>  'required',
            'settings.img_welcome_msg'   =>  'nullable|image|mimes:jpeg,png,jpg,svg',
            'settings.img_about_msg'     =>  'nullable|image|mimes:jpeg,png,jpg,svg',
            'settings.google_play'       =>  'required|max:255',
            'settings.apple_store'       =>  'required|max:255',
            'settings.color_header'      =>  'required',
            'settings.color_navbar'      =>  'required',
            'settings.color_footer'      =>  'required',
            'settings.color_about'       =>  'required',
            'settings.color_advantage'   =>  'required',
            'settings.color_background'  =>  'required',
            'settings.color_footer_end'  =>  'required',
        ];
    }

    public function attributes()
    {
        return [
            'settings.logo'              =>  'شعار الموقع',
            'settings.name_ar'           =>  'اسم الموقع باللغة العربية',
            'settings.name_en'           =>  'اسم الموقع باللغة الانجليزية',
            'settings.about'          =>  'حول التطبيق',
            'settings.about_en'          =>  'حول التطبيق باللغة العربية',
            'settings.welcome_msg'    =>  'الرسالة الترحبية',
            'settings.welcome_msg_en'    =>  'الرسالة الترحبية باللغة الانجليزية',
            'settings.img_welcome_msg'   =>  'صورة الرسالة الترحبيه',
            'settings.img_about_msg'     =>  'صورة حوال التطبيق',
            'settings.google_play'       =>  'لينك جوجل بلاى',
            'settings.apple_store'       =>  'لينك ابل استور',

        ];
    }

}
