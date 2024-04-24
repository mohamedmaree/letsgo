<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Questions;
class QuestionsController extends Controller
{
    public function index(){
       $this->data['faqs'] = Questions::where('show','=','true')->orderBy('id','ASC')->get();
       return view('questions.index',$this->data);
    }
}
