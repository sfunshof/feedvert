<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
	 //
	public function index(){
       return view('home.homePage');
    }
    public function appPage(){
        return view('app.homePage');
    }
}
