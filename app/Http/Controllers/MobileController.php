<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class MobileController extends Controller
{
    //
    private function get_initMenus(){
        // Assume $randomNo is passed from the route
        $randomNo = request()->segment(app()->environment('production') ? 1 : 2); // Get randomNo from the appropriate URL segment
        // Step 1: Fetch the user ID from the users table
        $user = DB::table('companysettingstable')->where('randomNo', $randomNo)->first();
        $items=null;
        if ($user) {
            // Step 2: Save the ID in a session key named 'companyID'
            Session::put('companyID', $user->companyID);
            // Step 3: Use the saved company ID to fetch items from the itemfoldertable
            $companyID = Session::get('companyID');
            $items = DB::table('itemfoldertable') ->where('companyID', $companyID) ->orderBy('position', 'asc')->get();
        } else {
            //$this->isError=true;
            //$this->isSplash=false;
        }
        $output['user']=$user;
        $output['items']=$items;
        return $output;    
    }

    public function index(){
        $output=$this->get_initMenus();
        $user=$output['user'];
        $items=$output['items'];
        $data = [ 'user' => $user, 'items' => $items, 'error' => null]; 
        if (!$user) { 
            $data['error'] = 'User not found';
            $data['user'] = null; 
            $data['items'] = collect(); 
        } 
         return view('mobile.homePage', ['data' => $data]);
    }
}
