<?php

namespace App\Http\Controllers;


use App\CustomerReview;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;


class CustomerReviewController extends Controller
{

    /** public function index **/
    public function index()
    {
        $customerReviews = CustomerReview::latest()->get();
        return view('dashboard.welcomePageSettings.customerReviews.index', compact('customerReviews'));
    }



    /** public function store **/
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'             =>  'required|min:3',
            'comment'          =>  'required|min:3',
            'rate'             =>  'required',
            'image'            =>  'required',
        ],[],[
            'name'              =>  'الاسم',
            'comment'           =>  'التعليق',
            'rate'              =>  'التقيم',
        ]);
        $data['name']           = $request['name'];
        $data['comment']        = $request['comment'];
        $data['comment']        = $request['comment'];
        $data['rate']           = $request['rate'];


        if ($request->hasFile('image')){
            $file = $request->file('image');
            $path = Uuid::uuid4()->toString() . '.' . $file->getClientOriginalExtension();
            $data['image']          = $path;
            $file->move('assets/uploads/customer_reviews', $path);

        }

        CustomerReview::create($data);

        session()->flash('success', 'تم الاضافه بنجاح');

        return back();
    }



    /** public function update Request Id **/
    public function update(Request $request)
    {
        $this->validate($request, [
            'name'              =>  'required|min:3',
            'comment'           =>  'required|min:3',
            'rate'              =>  'required',
            'image'             =>  'nullable',
            'customerReview_id' =>  'required'
        ],[],[
            'name'              =>  'الاسم',
            'comment'           =>  'التعليق',
            'rate'              =>  'التقيم',
        ]);
        $data['name']           = $request['name'];
        $data['comment']        = $request['comment'];
        $data['comment']        = $request['comment'];
        $data['rate']           = $request['rate'];


        if ($request->hasFile('image')){
            $file = $request->file('image');
            $path = Uuid::uuid4()->toString() . '.' . $file->getClientOriginalExtension();
            $data['image']          = $path;
            $file->move('assets/uploads/customer_reviews', $path);

        }

        $CustomerReview = CustomerReview::findOrFail($request['customerReview_id']);

        $CustomerReview->update($data);

        session()->flash('success', 'تم التعديل بنجاح');

        return back();

    }

    /** public function destroy Id **/
    public function destroy($id)
    {
        CustomerReview::findOrFail($id)->delete();

        session()->flash('success', 'تم الحذف بنجاح');

        return back();
    }

}
