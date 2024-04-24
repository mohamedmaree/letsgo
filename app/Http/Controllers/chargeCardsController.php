<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\chargeCards;
use App\chargeCardsUsers;
use Auth;
use App\Exports\chargeCardsExport;
use Maatwebsite\Excel\Facades\Excel;
class chargeCardsController extends Controller{

    public function chargeCards(){
        $allNewCards   = chargeCards::where('used','=','false')->count();
        $cards = chargeCards::where('used','=','false')->orderBy('created_at','DESC')->paginate(50);
        return view('dashboard.chargeCards.cards',compact('cards','allNewCards'));
    }

    public function usedChargeCards(){
        $allUsedCards = chargeCardsUsers::count();
        $usedcards = chargeCardsUsers::with('user')->orderBy('created_at','DESC')->paginate(50);
        return view('dashboard.chargeCards.usedcards',compact('usedcards','allUsedCards'));        
    }

    public function downloadchargeCards(){
        return Excel::download( new chargeCardsExport(), 'chargeCards.xlsx');        
    }
    #add coupon
    public function createchargeCard(Request $request){
        $this->validate($request,[
            'num_cards'  => 'required',
            'value'      => 'required'
        ]);
        $length = $request->num_cards;
        for($i = 0; $i < $length; $i++) {
            $card           = new chargeCards();
            $card->code     = $this->generatechargeCardCode();      
            $card->value    = $request->value;
            // $card->used     = 'false';
            $card->save();
        }
        History(Auth::user()->id,'بأضافة كوبون شحن جديد');
        Session::flash('success','تم اضافة كوبون الشحن بنجاح');
        return back();
    }

 
    public function DeletechargeCard(Request $request){
            $card = chargeCards::findOrFail($request->id);
            $card->delete();
            History(Auth::user()->id,'بحذف كوبون شحن');
            return 1;
    }

    #delete user
    public function DeletechargeCards(Request $request){
        $this->validate(Request(),['deleteids'=>'required']);
            foreach($request->deleteids as $id){
                if($card = chargeCards::find($id)){
                $card->delete();
            }
        }
            History(Auth::user()->id,'بحذف أكثر من كوبون شحن ');
            return back()->with('success','تم الحذف');   
    }


    public function DeleteUsedchargeCard(Request $request){
            $card = chargeCardsUsers::findOrFail($request->id);
            $card->delete();
            History(Auth::user()->id,'بحذف كوبون شحن');
            return 1;
    }

    #delete user
    public function DeleteUsedchargeCards(Request $request){
        $this->validate(Request(),['deleteids'=>'required']);
            foreach($request->deleteids as $id){
                if($card = chargeCardsUsers::find($id)){
                $card->delete();
            }
        }
            History(Auth::user()->id,'بحذف أكثر من كوبون شحن ');
            return back()->with('success','تم الحذف');   
    }


   public function generatechargeCardCode(){
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 12; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        if($card = chargeCards::where(['code'=>$randomString])->first()){
            return $this->generatechargeCardCode();
        }
        return $randomString;
   }

}
