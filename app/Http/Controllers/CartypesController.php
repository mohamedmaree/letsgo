<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\carTypes;
use Auth;

class CartypesController extends Controller{

    //Admin part 
    public function cartypes(){
      $cartypes = carTypes::orderBy('name_ar','ASC')->get(); 
      return view('dashboard.setting.cartypes',compact('cartypes'));
    }

    #add product
    public function createCartype(Request $request){
        $this->validate($request,[
            'name_ar'      => 'required',
            'name_en'      => 'required',
            'type'         => 'required',
            'order_type'   => 'required',
            'num_persons'  => 'nullable',
            'max_weight'   => 'nullable'
        ]);

        $cartype = new carTypes();        
        $cartype->name_ar     = $request->name_ar;
        $cartype->name_en     = $request->name_en;
        $cartype->type        = $request->type;
        $cartype->order_type  = $request->order_type;
        $cartype->num_persons = $request->num_persons;
        $cartype->max_weight  = $request->max_weight;
        if($request->hasFile('image')) {
              $file = $request->file('image');
              $extension = $file->getClientOriginalExtension();
              $img_extensions = array("jpg","jpeg","gif","png","svg");
              if(in_array($extension,$img_extensions)){
                $filename = md5($file->getClientOriginalName()).time().rand(99999,1000000).'.'.$extension;
                $file->move(public_path('/img/car/'),$filename);
                $cartype->image    = $filename;
              }else{
                  return back()->with('danger','نوع الصورة التي ادخلتها غير صحيح, الأنواع المسموح بها [gif|jpg|jpeg|png|svg]');
              }
        }        
        $cartype->save();
        History(Auth::user()->id,'بأضافة تصنيف السيارات '.$cartype->name_ar);
        return back()->with('success','تم اضافة تصنيف جديد بنجاح');
    }

    #update workstage
    public function updateCartype(Request $request){
        $this->validate($request,[
            'id'                => 'required',
            'edit_name_ar'      => 'required',
            'edit_name_en'      => 'required',
            'edit_type'         => 'required',
            'edit_order_type'   => 'required',
            'edit_num_persons'  => 'nullable',
            'edit_max_weight'   => 'nullable'
        ]);

        $cartype = carTypes::findOrFail($request->id);
        $cartype->name_ar     = $request->edit_name_ar;
        $cartype->name_en     = $request->edit_name_en;
        $cartype->type        = $request->edit_type;
        $cartype->order_type  = $request->edit_order_type;
        $cartype->num_persons = $request->edit_num_persons;
        $cartype->max_weight  = $request->edit_max_weight;
        if($request->hasFile('edit_image')) {
              $file = $request->file('edit_image');
              $extension = $file->getClientOriginalExtension();
              $img_extensions = array("jpg","jpeg","gif","png","svg");
              if(in_array($extension,$img_extensions)){
                $filename = md5($file->getClientOriginalName()).time().rand(99999,1000000).'.'.$extension;
                $file->move(public_path('/img/car/'),$filename);
                $cartype->image    = $filename;
              }else{
                  return back()->with('danger','نوع الصورة التي ادخلتها غير صحيح, الأنواع المسموح بها [gif|jpg|jpeg|png|svg]');
              }
        }        
        $cartype->save();
        History(Auth::user()->id,'بتحديث تصنيف السيارات '.$cartype->name_ar);
        return redirect('admin/cartypes')->with('success','تم حفظ التعديلات');
    }

    #delete workstage
    public function DeleteCartype(Request $request){
            $cartype = carTypes::findOrFail($request->id);
            $cartype->delete();
            History(Auth::user()->id,'بحذف تصنيف السيارات '.$cartype->name_ar);
            return back()->with('success','تم الحذف');
    }   

}
