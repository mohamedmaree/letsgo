<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Stores;
use App\storeMenus;
use Auth;
use Illuminate\Support\Facades\DB; 
use App\User;

class StoresController extends Controller{

    //***************** Start Admin Part ******************** //
    public function stores(){
        $stores = Stores::where('parent_id','=','0')->orderBy('created_at','DESC')->get();
        return view('dashboard.stores.stores',compact('stores'));
    }

    #add Store
    public function createStore(Request $request){
        $this->validate($request,[
            'name_ar'          => 'required',
            'name_en'          => 'required',
            'phone'            => 'nullable',
            'email'            => 'nullable',
            'icon'             => 'required|image|mimes:jpeg,png,jpg,gif',
            'cover'            => 'required|image|mimes:jpeg,png,jpg,gif',
            'address'          => 'required',
            'lat'              => 'required',
            'long'             => 'required',
            'website'          => 'nullable',
            'open_from'        => 'required',
            'open_to'          => 'required',
        ]);

        $store = new Stores();        
        $store->parent_id        = 0;
        $store->name_ar          = $request->name_ar;
        $store->name_en          = $request->name_en;
        $store->phone            = convert2english($request->phone);
        $store->email            = $request->email;
        $store->address          = $request->address;
        $store->lat              = $request->lat;
        $store->lng              = $request->long;
        $store->website          = $request->website;
        $store->num_branches     = 1;
        $store->open_from       = convert2english($request->open_from);
        $store->open_to         = convert2english($request->open_to);
        if($request->hasFile('icon')) {
            $image           = $request->file('icon');
            $name            = md5($request->file('icon')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/img/store/icons');
            $imagePath       = $destinationPath. "/".  $name;
            $image->move($destinationPath, $name);
            $store->icon = $name;
        }   
        if($request->hasFile('cover')) {
            $image           = $request->file('cover');
            $name            = md5($request->file('cover')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/img/store/cover');
            $imagePath       = $destinationPath. "/".  $name;
            $image->move($destinationPath, $name);
            $store->cover = $name;
        }             
        $store->save();
            if($request->has('menus')){        
                $uploadedimages = [];
                foreach($request->file('menus') as $image){
                          $extension = $image->getClientOriginalExtension();
                          $img_extensions = array("jpg","jpeg","gif","png","svg");
                          if(in_array($extension,$img_extensions)){
                            $imagename = md5($image->getClientOriginalName()).time().rand(99999,1000000).'.'.$extension;
                            $uploadflag = $image->move(public_path('/img/store/menus'),$imagename);
                              if($uploadflag){
                                $uploadedimages[] = $imagename;
                              } 
                          }else{
                              return back()->withErrors(['images'=>'نوع الصورة التي ادخلتها غير صحيح, الأنواع المسموح بها [gif|jpg|jpeg|png|svg]']);
                          }
                }
                foreach($uploadedimages as $upimage){
                    $menu = new storeMenus();
                    $menu->image    = $upimage;
                    $menu->store_id = $store->id;
                    $menu->save();
                }
            }        
        History(Auth::user()->id,'بأضافة المتجر '.$store->name_ar);
        Session::flash('success','تم اضافة متجر جديد بنجاح');
        return back();
    }

    #update coupon
    public function updateStore(Request $request){
        $this->validate($request,[
            'id'                    => 'required',
            'edit_name_ar'          => 'required',
            'edit_name_en'          => 'required',
            'edit_phone'            => 'nullable',
            'edit_email'            => 'nullable',
            'edit_address'          => 'required',
            'edit_lat'              => 'required',
            'edit_long'             => 'required',
            'edit_website'          => 'nullable',
            'edit_open_from'        => 'required',
            'edit_open_to'          => 'required',
        ]);

        $store = Stores::findOrFail($request->id);
            $firstmsg = 'بتحديث المتجر "'.$store->name_ar.'"<br/>';
            $msg = '';
            if( ($request->has('edit_name_ar')) && ($request->edit_name_ar != $store->name_ar) ) {
                $msg .= 'الاسم بالعربية من '.$store->name_ar .' الي '.$request->edit_name_ar.'<br/>';
                $store->name_ar          = $request->edit_name_ar;
            }           
            if( ($request->has('edit_name_en')) && ($request->edit_name_en != $store->name_en) ) {
                $msg .= 'الاسم بالانجليزية من '.$store->name_en .' الي '.$request->edit_name_en.'<br/>';
                $store->name_en          = $request->edit_name_en;
            } 
            if( ($request->has('edit_address')) && ($request->edit_address != $store->address) ) {
                $msg .= 'العنوان من '.$store->address .' الي '.$request->edit_address.'<br/>';
                $store->address          = $request->edit_address;
            } 
            $store->lat             = $request->edit_lat;
            $store->lng             = $request->edit_long;       
            if( ($request->has('edit_phone')) && ($request->edit_phone != $store->phone) ) {
                $msg .= 'الهاتف من '.$store->phone .' الي '.$request->edit_phone.'<br/>';
                $store->phone          = convert2english($request->edit_phone);
            } 
            if( ($request->has('edit_email')) && ($request->edit_email != $store->email) ) {
                $msg .= 'الايميل من '.$store->email .' الي '.$request->edit_email.'<br/>';
                $store->email          = $request->edit_email;
            }  
            if( ($request->has('edit_website')) && ($request->edit_website != $store->website) ) {
                $msg .= 'الموقع الالكتروني من '.$store->website .' الي '.$request->edit_website.'<br/>';
                $store->website          = $request->edit_website;
            }    
            if( ($request->has('edit_open_from')) && ($request->edit_open_from != $store->open_from) ) {
                $msg .= 'موعد بدأ العمل من '.$store->open_from .' الي '.$request->edit_open_from.'<br/>';
                $store->open_from          = convert2english($request->edit_open_from);
            }  

            if( ($request->has('edit_open_to')) && ($request->edit_open_to != $store->open_to) ) {
                $msg .= 'موعد بدأ العمل من '.$store->open_to .' الي '.$request->edit_open_to.'<br/>';
                $store->open_to          = $request->edit_open_to;
            }             
            if($request->hasFile('edit_icon')) {
                $image           = $request->file('edit_icon');
                $name            = md5($request->file('edit_icon')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/img/store/icons');
                $imagePath       = $destinationPath. "/".  $name;
                $image->move($destinationPath, $name);
                $store->icon = $name;
                $msg .= 'ايقونة المتجر <br/>';
            } 
            if($request->hasFile('edit_cover')) {
                $image           = $request->file('edit_cover');
                $name            = md5($request->file('edit_cover')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/img/store/cover');
                $imagePath       = $destinationPath. "/".  $name;
                $image->move($destinationPath, $name);
                $store->cover = $name;
                $msg .= 'غلاف المتجر <br/>';
            }                       
            $store->save();
            if($request->has('edit_menus')){        
                DB::table('store_menus')->where('store_id','=',$store->id)->delete();
                    $uploadedimages = array();
                    foreach($request->file('edit_menus') as $image){
                              $extension = $image->getClientOriginalExtension();
                              $img_extensions = array("jpg","jpeg","gif","png","svg");
                              if(in_array($extension,$img_extensions)){
                                $imagename = md5($image->getClientOriginalName()).time().rand(99999,1000000).'.'.$extension;
                                $uploadflag = $image->move(public_path('/img/store/menus'),$imagename);
                                  if($uploadflag){
                                    $uploadedimages[] = $imagename;
                                  } 
                              }else{
                                  return back()->withErrors(['images'=>'نوع الصورة التي ادخلتها غير صحيح, الأنواع المسموح بها [gif|jpg|jpeg|png|svg]']);
                              }
                    }
                    foreach($uploadedimages as $upimage){
                        $menu = new storeMenus();
                        $menu->image    = $upimage;
                        $menu->store_id = $store->id;
                        $menu->save();
                    }
                $msg .= 'قوائم الطعام <br/>';
            }            
            DB::table('stores')->where(['parent_id' => $store->id])->update(['name_ar' => $store->name_ar,'name_en'=> $store->name_en,'icon' => $store->icon,'cover' => $store->cover]);
            if($msg){
               History(Auth::user()->id,$firstmsg.$msg);
            }
            Session::flash('success','تم تعديل المتجر بنجاح');
            return back();
    }

    public function DeleteStore(Request $request){
            $store = Stores::with('parent')->findOrFail($request->id);
            if($store->parent_id == '0'){
               DB::table('stores')->where(['parent_id' => $store->id])->delete();
            }
            $num_branches  = $store->num_branches - 1;
            $have_branches = ($num_branches > 1)? 'true' : 'false';
            DB::table('stores')->where('parent_id',$store->parent_id)->orwhere('id',$store->parent_id)->update(['num_branches' => $num_branches,'have_branches'=> $have_branches ]);

            DB::table('store_menus')->where('store_id',$store->id)->delete();
            $store->delete();
            History(Auth::user()->id,'بحذف المتجر '.$store->name_ar);
            return back()->with('success','تم الحذف');
    }

    public function branches($store_id = ''){
      $store    = Stores::findOrFail($store_id);
      $branches = Stores::where('parent_id','=',$store_id)->orderBy('created_at','DESC')->get();
      return view('dashboard.stores.branches',compact('branches','store'));
    }

    public function createBranch(Request $request){
        $this->validate($request,[
            'store_id'         => 'required',
            'phone'            => 'nullable',
            'email'            => 'nullable',
            'address'          => 'required',
            'lat'              => 'required',
            'long'             => 'required',
            'website'          => 'nullable',
            'open_from'        => 'required',
            'open_to'          => 'required',
        ]);
        $parentStore = Stores::findOrFail($request->store_id);
        $store = new Stores();        
        $store->parent_id        = $parentStore->id;
        $store->name_ar          = $parentStore->name_ar;
        $store->name_en          = $parentStore->name_en;      
        $store->phone            = convert2english($request->phone);
        $store->email            = $request->email;
        $store->address          = $request->address;
        $store->lat              = $request->lat;
        $store->lng              = $request->long;
        $store->website          = $request->website;
        $store->open_from        = convert2english($request->open_from);
        $store->open_to          = convert2english($request->open_to);
        $store->icon             = $parentStore->icon;        
        $store->cover            = $parentStore->cover;        
        $store->save();
        $parentStore->have_branches = 'true';
        $parentStore->num_branches  += 1;
        $parentStore->save();
        DB::table('stores')->where(['parent_id' => $parentStore->id])->update(['num_branches' => $parentStore->num_branches,'have_branches'=> $parentStore->have_branches ]);
            
            if($request->has('menus')){        
                $uploadedimages = [];
                foreach($request->file('menus') as $image){
                          $extension = $image->getClientOriginalExtension();
                          $img_extensions = array("jpg","jpeg","gif","png","svg");
                          if(in_array($extension,$img_extensions)){
                            $imagename = md5($image->getClientOriginalName()).time().rand(99999,1000000).'.'.$extension;
                            $uploadflag = $image->move(public_path('/img/store/menus'),$imagename);
                              if($uploadflag){
                                $uploadedimages[] = $imagename;
                              } 
                          }else{
                              return back()->withErrors(['images'=>'نوع الصورة التي ادخلتها غير صحيح, الأنواع المسموح بها [gif|jpg|jpeg|png|svg]']);
                          }
                }
                foreach($uploadedimages as $upimage){
                    $menu = new storeMenus();
                    $menu->image    = $upimage;
                    $menu->store_id = $store->id;
                    $menu->save();
                }
            }

        History(Auth::user()->id,'بأضافة فرع جديد الي '.$parentStore->name_ar);
        Session::flash('success','تم اضافة فرع جديد بنجاح');
        return back();
    }
    
    public function updateBranch(Request $request){ 
        $this->validate($request,[
            'id'                    => 'required',
            'edit_store_id'         => 'required',
            'edit_phone'            => 'nullable',
            'edit_email'            => 'nullable',
            'edit_address'          => 'required',
            'edit_lat'              => 'required',
            'edit_long'             => 'required',
            'edit_website'          => 'nullable',
            'edit_open_from'        => 'required',
            'edit_open_to'          => 'required',
        ]);
            $brancheStore = Stores::findOrFail($request->edit_store_id);
            $store = Stores::findOrFail($request->id);
            $firstmsg = 'بتحديث الفرع "'.$store->name_ar.'"<br/>';
            $msg = '';
            $store->name_ar          = $brancheStore->name_ar;
            $store->name_en          = $brancheStore->name_en;      
            if( ($request->has('edit_address')) && ($request->edit_address != $store->address) ) {
                $msg .= 'العنوان من '.$store->address .' الي '.$request->edit_address.'<br/>';
                $store->address          = $request->edit_address;
            } 
            $store->lat             = $request->edit_lat;
            $store->lng             = $request->edit_long;            
            if( ($request->has('edit_phone')) && ($request->edit_phone != $store->phone) ) {
                $msg .= 'الهاتف من '.$store->phone .' الي '.$request->edit_phone.'<br/>';
                $store->phone          = convert2english($request->edit_phone);
            } 
            if( ($request->has('edit_email')) && ($request->edit_email != $store->email) ) {
                $msg .= 'الايميل من '.$store->email .' الي '.$request->edit_email.'<br/>';
                $store->email          = $request->edit_email;
            }  
            if( ($request->has('edit_website')) && ($request->edit_website != $store->website) ) {
                $msg .= 'الموقع الالكتروني من '.$store->website .' الي '.$request->edit_website.'<br/>';
                $store->website          = $request->edit_website;
            }    
            if( ($request->has('edit_open_from')) && ($request->edit_open_from != $store->open_from) ) {
                $msg .= 'موعد بدأ العمل من '.$store->open_from .' الي '.$request->edit_open_from.'<br/>';
                $store->open_from          = convert2english($request->edit_open_from);
            }  

            if( ($request->has('edit_open_to')) && ($request->edit_open_to != $store->open_to) ) {
                $msg .= 'موعد بدأ العمل من '.$store->open_to .' الي '.$request->edit_open_to.'<br/>';
                $store->open_to          = $request->edit_open_to;
            }             
            $store->icon = $brancheStore->icon;
            $store->cover = $brancheStore->cover;
            $store->save();
            if($request->has('edit_menus')){        
                DB::table('store_menus')->where('store_id','=',$store->id)->delete();
                    $uploadedimages = array();
                    foreach($request->file('edit_menus') as $image){
                              $extension = $image->getClientOriginalExtension();
                              $img_extensions = array("jpg","jpeg","gif","png","svg");
                              if(in_array($extension,$img_extensions)){
                                $imagename = md5($image->getClientOriginalName()).time().rand(99999,1000000).'.'.$extension;
                                $uploadflag = $image->move(public_path('/img/store/menus'),$imagename);
                                  if($uploadflag){
                                    $uploadedimages[] = $imagename;
                                  } 
                              }else{
                                  return back()->withErrors(['images'=>'نوع الصورة التي ادخلتها غير صحيح, الأنواع المسموح بها [gif|jpg|jpeg|png|svg]']);
                              }
                    }
                    foreach($uploadedimages as $upimage){
                        $menu = new storeMenus();
                        $menu->image    = $upimage;
                        $menu->store_id = $store->id;
                        $menu->save();
                    }
                $msg .= 'قوائم الطعام <br/>';
            }            
            if($msg){
               History(Auth::user()->id,$firstmsg.$msg);
            }
            Session::flash('success','تم تعديل بيانات الفرع بنجاح');
            return back();
    }

}
