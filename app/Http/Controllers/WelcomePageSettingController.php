<?php

namespace App\Http\Controllers;

use App\DataTables\AdvantageDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdvantageRequest;
use App\Models\Category;
use App\WelcomePageSetting;
use Ramsey\Uuid\Uuid;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\WelcomePageSettingRequest;



class WelcomePageSettingController extends Controller
{

    /** public function index **/
    public function index()
    {
        $welcomePageSettings = WelcomePageSetting::pluck('value', 'key');
        return view('dashboard.welcomePageSettings.index', compact('welcomePageSettings'));
    }


    /** public function siteSetting **/
    public function siteSetting(WelcomePageSettingRequest $request)
    {
        $data = $request->validated();
        $settings = $data['settings'];
        if ($request->hasFile('settings.logo')) {
            $file = $request->file('settings.logo');
            $path = Uuid::uuid4()->toString() . '.' . $file->getClientOriginalExtension();
            $settings['logo']          = $path;
            $file->move('assets/uploads/welcomePageSettings', $path);
        }

        if ($request->hasFile('settings.img_welcome_msg')) {
            $file = $request->file('settings.img_welcome_msg');
            $path = Uuid::uuid4()->toString() . '.' . $file->getClientOriginalExtension();
            $settings['img_welcome_msg']          = $path;
            $file->move('assets/uploads/welcomePageSettings', $path);
        }

        if ($request->hasFile('settings.img_about_msg')) {
            $file = $request->file('settings.img_about_msg');
            $path = Uuid::uuid4()->toString() . '.' . $file->getClientOriginalExtension();
            $settings['img_about_msg']          = $path;
            $file->move('assets/uploads/welcomePageSettings', $path);
        }

        foreach ($settings as $key => $value) {
            $setting = WelcomePageSetting::where('key', $key)->first();
            ($setting) ? $setting->update(['value' => $value]) : WelcomePageSetting::create(['key' => $key, 'value' => $value]);

        }

        session()->flash('success', 'تم الحفظ بنجاح');


        return back();
    }


}
