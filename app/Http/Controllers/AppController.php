<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppController extends Controller
{
    //
	public function index(){
        return view('app.homePage');
    }

    public function get_orderNo($orderData, $addOn, $ingredients, $companyID, $timezone, $isReady = 1) {
        $limit = 200;
        // Get the current datetime in the specified timezone
        $currentDateTime = Carbon::now($timezone);

        // Insert the order data into the database and retrieve the inserted ID
        $id = DB::table('orderstable')->insertGetId([
            'orderDatetime' => $currentDateTime,
            'order_json' => json_encode($orderData),
            'ingredients_json' => json_encode($ingredients),
            'addOn_json' => json_encode($addOn),
            'companyID' => $companyID,
            'isReady' => $isReady
        ]);

        // Step 1: Get the current date
        $currentDate = Carbon::now($timezone)->format('Y-m-d');

        // Step 2: Count the number of records with the same date and id <= $id
        $count = DB::table('orderstable')
            ->whereDate('orderDatetime', $currentDate)
            ->where('keyID', '<=', $id)
            ->count();
       // Step 3: Adjust the count to ensure it does not exceed the limit (200)
        if ($count > $limit) {
            $count = $count % $limit;
            // If the count is exactly divisible by the limit, set it to the limit
            if ($count === 0) {
                $count = $limit;
            }
        }
        // Step 4: Use the count directly as the orderNo (no need to add 1)
        $orderNo = $count; // The count already starts from 1
        // Step 5: Update the `orderNo` in the `orderstable` table for the given $id
        DB::table('orderstable')
            ->where('keyID', $id)
            ->update(['orderNo' => $orderNo]);
        // Return the generated orderNo
        return $orderNo;
    }
}
