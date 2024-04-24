<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\captainMoneyHistory;
use App\Exports\captainMoneyHistoriesExport;
use Maatwebsite\Excel\Facades\Excel;
class MoneyArchiveController extends Controller{


    public function captainsMoneyArchive(){
        $captainMoneyHistories = captainMoneyHistory::with('captain')->orderBy('created_at','DESC')->get();
    	return view('dashboard.captainsMoneyArchive.index',compact('captainMoneyHistories'));
    }


    public function downloadCaptainsMoneyArchive(){
        return Excel::download( new captainMoneyHistoriesExport(), 'captainsMoneyArchive.xlsx');        
    }

}
