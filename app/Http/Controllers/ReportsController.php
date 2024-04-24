<?php

namespace App\Http\Controllers;
use App\History;
use Illuminate\Http\Request;
use Session;

class ReportsController extends Controller
{
    #reports page
    public function ReportsPage()
    {
        $usersReports      = History::where('supervisor','0')->with('User')->latest()->paginate(40);
    	$supervisorReports = History::where('supervisor','1')->with('User.Role')->latest()->paginate(40);
    	return view('dashboard.reports.reports',
        compact('usersReports',$usersReports,'supervisorReports',$supervisorReports));
    }

    #delete users reports 
    public function DeleteUsersReports()
    {
        $usersReports = History::where('supervisor','0')->get();
		foreach ($usersReports  as $r)
		{
			$r->delete();
		}
		Session::flash('success','تم الحذف');
		return back();
    }

    #delete supervisors reports 
    public function DeleteSupervisorsReports()
    {
        $supervisorReports = History::where('supervisor','1')->get();
        foreach ($supervisorReports  as $r)
        {
            $r->delete();
        }
        Session::flash('success','تم الحذف');
        return back();
    }
}
