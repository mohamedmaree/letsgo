<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stores;
use App\menuCategories;
use File;
use Auth;

class menuCategoryController extends Controller{
    
    //Admin part 
    public function menuCategories($store_id = ''){
        $store  = Stores::findOrFail($store_id);            
        $menuCategories = menuCategories::where('store_id','=',$store_id)->orderBy('name_ar','ASC')->get(); 
      return view('dashboard.MenuCategory.index',compact('store_id','store','menuCategories'));
    }

    #add createMenuCategory
    public function createMenuCategory(Request $request){
        $this->validate($request,[
            'store_id'         => 'required',
            'name_ar'          => 'required|min:2',
            'name_en'          => 'required|min:2'
        ]);

        $menuCategory = new menuCategories();        
        $menuCategory->store_id         = $request->store_id;
        $menuCategory->name_ar          = $request->name_ar;
        $menuCategory->name_en          = $request->name_en;
        $menuCategory->save();
        History(Auth::user()->id,'بأضافة القسم '.$menuCategory->name_ar);
        return back()->with('success','تم اضافة قسم جديد بنجاح');
    }

    #update workstage
    public function updateMenuCategory(Request $request){
        $this->validate($request,[
            'id'                     => 'required',
            'edit_store_id'          => 'required',
            'edit_name_ar'           => 'required|min:2',
            'edit_name_en'           => 'required|min:2'
        ]);

        $menuCategory = menuCategories::findOrFail($request->id);
        $oldstore   = Stores::findOrFail($menuCategory->store_id);
        $newstore   = Stores::findOrFail($request->edit_store_id);      
        $firstmsg = 'بتحديث القسم "'.$menuCategory->name_ar.'"<br/>';
        $msg = '';
        if( ($request->has('edit_store_id')) && ($request->edit_store_id != $menuCategory->store_id) ) {
            $msg .= 'المتجر من '.$oldstore->name_ar .' الي '.$newstore->name_ar.'<br/>';
            $menuCategory->store_id  = $request->edit_store_id;
        }                  
        if( ($request->has('edit_name_ar')) && ($request->edit_name_ar != $menuCategory->name_ar) ) {
            $msg .= 'الاسم بالعربية من '.$menuCategory->name_ar .' الي '.$request->edit_name_ar.'<br/>';
            $menuCategory->name_ar          = $request->edit_name_ar;
        }
        if( ($request->has('edit_name_en')) && ($request->edit_name_en != $menuCategory->name_en) ) {
            $msg .= 'الاسم بالانجليزية من '.$menuCategory->name_en .' الي '.$request->edit_name_en.'<br/>';
            $menuCategory->name_en          = $request->edit_name_en;
        } 
        $menuCategory->save();
        if($msg){
           History(Auth::user()->id,$firstmsg.$msg);
        }
        return back()->with('success','تم حفظ التعديلات');
    }

    #delete workstage
    public function DeleteMenuCategory(Request $request){
            $menuCategory = menuCategories::findOrFail($request->id);
            $menuCategory->delete();
            History(Auth::user()->id,'بحذف القسم '.$menuCategory->name_ar);
            return back()->with('success','تم الحذف');
    }   
}
