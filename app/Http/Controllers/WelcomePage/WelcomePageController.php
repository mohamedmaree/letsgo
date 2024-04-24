<?php

namespace App\Http\Controllers\WelcomePage;

use App\Advantage;
use App\CustomerReview;
use App\ImageApp;
use App\WelcomePageSetting;
use App\Social;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WelcomePageController extends Controller
{
    public function index()
    {
        $customersReviews    = CustomerReview::latest()->get();
        $advantages          = Advantage::latest()->get();
        $imagesApp           = ImageApp::latest()->get();
        $welcomePageSettings = WelcomePageSetting::all()->pluck('value', 'key');
        $socials             = Social::latest()->get();
        return view('welcomePage.index', compact('customersReviews', 'advantages', 'imagesApp','welcomePageSettings', 'socials'));
    }

}
