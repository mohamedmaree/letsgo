<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Stores;
use App\menuCategories;
use File;
use Auth;

class ProductController extends Controller{
    
    //Admin part 
    public function products($store_id = ''){
        $store  = Stores::findOrFail($store_id);            
        $products = Product::with('store')->where('store_id','=',$store_id)->orderBy('created_at','DESC')->get();
        $menuCategories = menuCategories::where('store_id','=',$store_id)->orderBy('name_ar','ASC')->get(); 
      return view('dashboard.products.index',compact('products','store_id','store','menuCategories'));
    }

    #add product
    public function createProduct(Request $request){
        $this->validate($request,[
            'store_id'         => 'required',
            'menu_category_id' => 'required', 
            'name_ar'          => 'required|min:2',
            'name_en'          => 'required|min:2',
            'price'            => 'required',
            // 'description_ar'   => 'required',
            // 'description_en'   => 'required',
            'image'      => 'required',
        ]);

        $product = new Product();        
        $product->store_id         = $request->store_id;
        $product->menu_category_id = $request->menu_category_id;
        $product->name_ar          = $request->name_ar;
        $product->name_en          = $request->name_en;
        $product->price            = convert2english( $request->price );
        $product->description_ar   = $request->description_ar;
        $product->description_en   = $request->description_en;
        if($request->hasFile('image')) {
            $image           = $request->file('image');
            $name            = md5($request->file('image')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/img/store/products');
            $imagePath       = $destinationPath. "/".  $name;
            $image->move($destinationPath, $name);
            $product->image = $name;
        }
        $product->save();
        History(Auth::user()->id,'بأضافة المنتج '.$product->name_ar);
        return back()->with('success','تم اضافة منتج جديد بنجاح');
    }

    #update workstage
    public function updateProduct(Request $request){
        $this->validate($request,[
            'id'                     => 'required',
            'edit_store_id'          => 'required',
            'edit_menu_category_id'  => 'required',
            'edit_name_ar'           => 'required|min:2',
            'edit_name_en'           => 'required|min:2',
            'edit_price'             => 'required',
            // 'edit_description_ar'   => 'required',
            // 'edit_description_en'   => 'required',
            // 'edit_image'      => 'required',
        ]);

        $product = Product::findOrFail($request->id);
        $oldstore   = Stores::findOrFail($product->store_id);
        $newstore   = Stores::findOrFail($request->edit_store_id);
        $oldmenu   = menuCategories::findOrFail($product->menu_category_id);
        $newmenu   = menuCategories::findOrFail($request->edit_menu_category_id);        
        $firstmsg = 'بتحديث المنتج "'.$product->name_ar.'"<br/>';
        $msg = '';
        if( ($request->has('edit_store_id')) && ($request->edit_store_id != $product->store_id) ) {
            $msg .= 'المتجر من '.$oldstore->name_ar .' الي '.$newstore->name_ar.'<br/>';
            $product->store_id  = $request->edit_store_id;
        }   
        if( ($request->has('edit_menu_category_id')) && ($request->edit_menu_category_id != $product->menu_category_id) ) {
            $msg .= 'قسم المنيو من '.$oldmenu->name_ar .' الي '.$newmenu->name_ar.'<br/>';
            $product->menu_category_id  = $request->edit_menu_category_id;
        }                
        if( ($request->has('edit_name_ar')) && ($request->edit_name_ar != $product->name_ar) ) {
            $msg .= 'الاسم بالعربية من '.$product->name_ar .' الي '.$request->edit_name_ar.'<br/>';
            $product->name_ar          = $request->edit_name_ar;
        }
        if( ($request->has('edit_name_en')) && ($request->edit_name_en != $product->name_en) ) {
            $msg .= 'الاسم بالانجليزية من '.$product->name_en .' الي '.$request->edit_name_en.'<br/>';
            $product->name_en          = $request->edit_name_en;
        } 
        if( ($request->has('edit_price')) && ($request->edit_price != $product->price) ) {
            $msg .= 'ألسعر من '.$product->price .' الي '.$request->edit_price.'<br/>';
            $product->price          =  convert2english( $request->edit_price );
        }
        if( ($request->has('edit_description_ar')) && ($request->edit_description_ar != $product->description_ar) ) {
            $msg .= 'الوصف بالعربية من '.$product->description_ar .' الي '.$request->edit_description_ar.'<br/>';
            $product->description_ar          = $request->edit_description_ar;
        }  
        if( ($request->has('edit_description_en')) && ($request->edit_description_en != $product->description_en) ) {
            $msg .= 'الوصف بالانجليزية من '.$product->description_en .' الي '.$request->edit_description_en.'<br/>';
            $product->description_en          = $request->edit_description_en;
        }                                 
        if($request->hasFile('edit_image')) {
            $image           = $request->file('edit_image');
            $name            = md5($request->file('edit_image')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/img/store/products');
            $imagePath       = $destinationPath. "/".  $name;
            $image->move($destinationPath, $name);
            $product->image = $name;
        }
        $product->save();
        if($msg){
           History(Auth::user()->id,$firstmsg.$msg);
        }
        return back()->with('success','تم حفظ التعديلات');
    }

    #delete workstage
    public function DeleteProduct(Request $request){
            $product = Product::findOrFail($request->id);
            File::delete('img/store/products/'.$product->image);
            $product->delete();
            History(Auth::user()->id,'بحذف المنتج '.$product->name_ar);
            return back()->with('success','تم الحذف');
    }   
}
