<?php

namespace App\Http\Controllers;

use App\DataTables\AdvantageDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdvantageRequest;
use App\Advantage;
use App\Models\Category;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Ramsey\Uuid\Uuid;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;


class AdvantageController extends Controller
{

    /** public function index **/
    public function index()
    {
        $advantages = Advantage::latest()->get();
        return view('dashboard.welcomePageSettings.advantages.index', compact('advantages'));
    }



    /** public function store **/
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'             =>  'required|min:3',
            'content'           =>  'required|min:3',
            'image'             =>  'required',
        ],[],[
            'title'             =>  'العنوان'
        ]);
        $data['title']          = $request['title'];
        $data['content']        = $request['content'];


        if ($request->hasFile('image')){
            $file = $request->file('image');
            $path = Uuid::uuid4()->toString() . '.' . $file->getClientOriginalExtension();
            $data['image']          = $path;
            $file->move('assets/uploads/advantages', $path);

        }

        Advantage::create($data);

        session()->flash('success', 'تم الاضافه بنجاح');

        return back();
    }



    /** public function update Request Id **/
    public function update(Request $request)
    {
        $this->validate($request, [
            'title'             =>  'required|min:3',
            'content'           =>  'required|min:3',
            'image'             =>  'nullable',
            'advantage_id'      =>  'required'
        ],[],[
            'title'             =>  'العنوان'
        ]);
        $data['title']          = $request['title'];
        $data['content']        = $request['content'];


        if ($request->hasFile('image')){
            $file = $request->file('image');
            $path = Uuid::uuid4()->toString() . '.' . $file->getClientOriginalExtension();
            $data['image']          = $path;
            $file->move('assets/uploads/advantages', $path);

        }


        $Advantage = Advantage::findOrFail($request['advantage_id']);

        $Advantage->update($data);

        session()->flash('success', 'تم التعديل بنجاح');

        return back();
    }

    /** public function destroy Id **/
    public function destroy($id)
    {
        Advantage::findOrFail($id)->delete();

        session()->flash('success', 'تم الحذف بنجاح');

        return back();
    }

}
