<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Profits;
use App\Exports\ProfitsExport;
use Maatwebsite\Excel\Facades\Excel;
class ProfitsController extends Controller{

    #profits page
    public function Profits(){
        $ProfitsByDay      = Profits::select('*', DB::raw('SUM(total_price) as total_total_price'),DB::raw('SUM(value) as total_value'),DB::raw('SUM(added_value) as total_added_value'),DB::raw('SUM(wasl_value) as total_wasl_value'))->groupBy('date')->orderBy('created_at','desc')->paginate(40);
        $ProfitsByMonth    = Profits::select('*', DB::raw('SUM(total_price) as total_total_price'),DB::raw('SUM(value) as total_value'),DB::raw('SUM(added_value) as total_added_value'),DB::raw('SUM(wasl_value) as total_wasl_value'))->groupBy('month')->orderBy('created_at','desc')->paginate(40);
        $ProfitsByYear     = Profits::select('*', DB::raw('SUM(total_price) as total_total_price'),DB::raw('SUM(value) as total_value'),DB::raw('SUM(added_value) as total_added_value'),DB::raw('SUM(wasl_value) as total_wasl_value'))->groupBy('year')->orderBy('created_at','desc')->paginate(40);
        $ProfitsDetails    = Profits::with('provider')->orderBy('created_at','desc')->paginate(40);
        $count             = Profits::latest()->count();

        return view('dashboard.profits.Profits',compact('ProfitsDetails','ProfitsByDay','ProfitsByMonth','ProfitsByYear','count'));
    }


    public function downloadProfits(){
        return Excel::download( new ProfitsExport(), 'Profits.xlsx');        
    }

}
