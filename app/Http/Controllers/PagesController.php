<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Page;
use Auth;
use App\WelcomePageSetting;

class PagesController extends Controller{

    public function conditions(){
       $this->data['terms'] = setting('terms_ar');
       return view('pages.conditions',$this->data);
    }

    public function privacy(){
        $this->data['privacy'] = setting('privacy_ar');
        return view('pages.privacy',$this->data);
    }

    public function aboutApp(){
        $this->data['about_app'] = setting('about_app');
        return view('pages.about_app',$this->data);
    }

    public function contactUs(){
        $this->data['welcomePageSettings'] = WelcomePageSetting::pluck('value', 'key');
        return view('pages.contactUs',$this->data);
    }


    public function pages(){
        $pages = Page::orderBy('created_at','ASC')->get();
        return view('dashboard.pages.pages',compact('pages',$pages));
    }

    #add user
    public function AddPage(Request $request){
        $this->validate($request,[
            'title_ar'     =>'required|min:2|max:255',
            'title_en'     =>'required|min:2|max:255',
            'content_ar'   =>'required|min:10',
            'content_en'   =>'required|min:10'
        ]);
        $page = new Page();
        $page->title_ar    = $request->title_ar;
        $page->title_en    = $request->title_en;
        $page->content_ar  = $request->content_ar;
        $page->content_en  = $request->content_en;
        $page->save();
        History(Auth::user()->id,'بأضافة صفحة جديدة');
        Session::flash('success','تم اضافة الصفحة بنجاح');
        return back();
    }

    public function UpdatePage(Request $request) {
        $this->validate($request,[
            'pageid'       => 'required',
            'title_ar'     => 'required|min:2|max:255',
            'title_en'     => 'required|min:2|max:255',
            'content_ar'   => 'required|min:10',
            'content_en'   => 'required|min:10'
        ]);
        if($page = Page::find($request->pageid)){
            $page->title_ar    = $request->title_ar;
            $page->title_en    = $request->title_en;
            $page->content_ar  = $request->content_ar;
            $page->content_en  = $request->content_en;
            $page->save();
            Session::flash('success','تم تعديل الصفحة بنجاح');
        }
        return back();
    }

    public function deletepage(Request $request){
            $page = Page::findOrFail($request->id);          
            History(Auth::user()->id,'بحذف الصفحة رقم #'.$page->title_ar);
            $page->delete();
            return back()->with('success','تم الحذف');
    }
}
