<?php

namespace App\Http\Controllers;

use App\DataTables\AdvantageDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdvantageRequest;
use App\ImageApp;
use App\Models\Category;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Ramsey\Uuid\Uuid;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;


class ImageAppController extends Controller
{

    /** public function index **/
    public function index()
    {
        $ImageApps = ImageApp::latest()->get();
        return view('dashboard.welcomePageSettings.imageApps.index', compact('ImageApps'));
    }



    /** public function store **/
    public function store(Request $request)
    {
        $this->validate($request, [
            'image'             =>  'required|image',
        ]);



        if ($request->hasFile('image')){
            $file = $request->file('image');
            $path = Uuid::uuid4()->toString() . '.' . $file->getClientOriginalExtension();
            $data['image']          = $path;
            $file->move('assets/uploads/imageApp', $path);

        }

        ImageApp::create($data);

        session()->flash('success', 'تم الاضافه بنجاح');

        return back();
    }



    /** public function destroy Id **/
    public function destroy($id)
    {
        ImageApp::findOrFail($id)->delete();

        session()->flash('success', 'تم الحذف بنجاح');

        return back();
    }

}
