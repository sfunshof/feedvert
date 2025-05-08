<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

trait PayRefsTrait
{
    public $items=[];
    public $companyID;
    public $currency;

    public $refResults=[];
    //Polling
    public $refPolling="refPolling";
    public $orderPolling="orderPolling";
    public $lastUpdated = null;
    public $is_pure_payRef=null;

    public function get_refNoFromTable(){
        $this->companyID=Auth::user()->companyID;
        $this->currency= Auth::user()->companySettings->currency;
        $this->refResults = DB::table('payrefstable')
            ->where('companyID', $this->companyID)
            ->get();
    }
    public  function checkForUpdatesPayRefs(){
        $key = "ref_polling:{$this->companyID}:{$this->refPolling}";
        $cached = Cache::get($key);
        if (!$cached) {
            $this->lastUpdated = null;
            return;
        }
        $newTimestamp = $cached['timestamp'] ?? null;

        if ($newTimestamp !== $this->lastUpdated) {
            $this->lastUpdated = $newTimestamp;
            $this->get_refNoFromTable();
        }
    }

    private function syncCustomerOrder(){
        $companyID=Auth::user()->companyID;
        $key = "cart_polling:{$companyID}:{$this->orderPolling}";
        $data = [
            'timestamp' => now()->timestamp,
        ];
        Cache::put($key, $data, now()->addMinutes(10));
    }

    public function confirm_paymentRefNo($orderNo, $amount,$refNo){
        //1st get the collection methodand medium
        $result = DB::table('orderstable')
            ->select(
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(order_json, '$.collection_method')) as collection_method"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(order_json, '$.medium')) as medium")
            )
            ->where('companyID', $this->companyID)
            ->where('isReady', 0)
            ->where('orderNo', $orderNo)
            ->first();
        $collection_method=$result->collection_method;
        $medium=$result->collection_method;
       //update orderstable
        DB::table('orderstable')
            ->where('companyID', $this->companyID)
            ->where('isReady', 0)
            ->where('orderNo', $orderNo)
            ->update(['isReady' => 1]);
        //delete payrefstable
        DB::table('payrefstable')
            ->where('companyID', $this->companyID)
            ->where('refNo', $refNo)
            ->where('amount', $amount)
            ->delete();
            $this->get_refNoFromTable();
            $this->syncCustomerOrder();
        //insert into paymenttable
        $timezone = Auth::user()->companySettings->timezone;
        $currentDate = Carbon::now($timezone)->format('Y-m-d H:i:s');

        DB::table('paymenttable')->insert([
            'dateTime' => $currentDate, // Replace with dynamic user ID
            'companyID'=> $this->companyID,
            'userName' => Auth::user()->email,
            'payNameID' =>  4, //payment ref
            'amount' =>  $amount,
            'collectionMethod' => $collection_method,
            'medium' => $medium
        ]);

    }
    
    public function print_to_receiptPrinter($orderData, $orderNo,$receiptDate,$printerIPaddress, $isCashier){
        $currency = $orderData['currency'] ?? '$';
        $cashier =$orderData['cashier_name'] ?? 'cashier';
        $companyName=Auth::user()->companySettings->companyName;
        try {
            // Connect to the network printer (update IP and port as needed)
            $connector = new NetworkPrintConnector($printerIPaddress, 9100);
            $printer = new Printer($connector);
            // Header
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2);
            $printer->text("Order No: {$orderNo}\n");
            $printer->setTextSize(1, 1);
            $printer->text( "{$receiptDate}\n");
            if ($isCashier){
                $printer->text( "{$companyName}\n");
            }
            $linesize=42;
            $printer->text(str_repeat("-", $linesize) . "\n");
            // Line items
            foreach ($orderData as $key => $item) {
                if (!is_array($item) || !isset($item['qty'])) continue;
                $qty = $item['qty'];
                $name = $item['name'];
                $price = $item['price'];
                $mealTypeID = $item['mealTypeID'] ?? null;
                $line = "{$qty} X {$name}";
                $space = $linesize - strlen($line . $currency . $price);
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text($line . str_repeat(' ', $space) . "{$currency}{$price}\n");
                // Offers for meal types 3 or 4
                if (in_array($mealTypeID, [3, 4])) {
                    if (!empty($item['offers'])) {
                        $printer->text("    Offers:\n");
                        foreach ($item['offers'] as $offer) {
                            $printer->text("        {$qty} X {$offer}\n");
                        }
                    }
                    // Customisations (meal_items_addOns3D)
                    if (!empty($item['meal_items_addOns3D'])) {
                        $printer->text("    Customisation:\n");
                        foreach ($item['meal_items_addOns3D'] as $meal => $mods) {
                            $printer->text("        {$meal}\n");
                            foreach ($mods as $mod) {
                                $printer->text("            {$mod}\n");
                            }
                        }
                    }
                }
                // Add-ons for other meal types
                if (in_array($mealTypeID, [1, 2]) && !empty($item['addOns3D'])) {
                    $printer->text("    Customisation:\n");
                    foreach ($item['addOns3D'] as $addon) {
                        $printer->text("        {$addon}\n");
                    }
                }
            }
            // Footer info
            $printer->text(str_repeat("-", $linesize) . "\n");
            $printer->text("Collection Method: " . $orderData['collection_method'] . "\n");
            $printer->text("Payment Method   : " . $orderData['payment_method'] . "\n");
            if (in_array($orderData['payment_method'], ['Cash', 'Voucher'])) {
                $printer->text("Amount Tendered  : {$currency}{$orderData['amount_tendred']}\n");
                $printer->text("Change Given     : {$currency}{$orderData['change_given']}\n");
            }
            $printer->text("Total Cost       : {$currency}{$orderData['total_cost']}\n");
            if ($isCashier){
                $printer->text("Cashier: " . $cashier . "\n");
            }
            // Finish
            $printer->cut();
            // Open cash drawer
            if ($isCashier){
                $printer->pulse(); // This uses the default pulse settings
            }
            $printer->close();
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'connection attempt failed')) {
                $userMessage = "Printer is offline or unreachable.";
            } else {
                $userMessage = "Printing failed: " . $e->getMessage();
            }
            $this->dispatch('printerErrorMsg', errorMsg:$userMessage ); //This is to reduce time processing 
        }
    }
    public function setup_archive($main_table, $archive_table,$conditions){
        // Step 1: Get all records from tableA where companyID matches, excluding keyID
        $records = DB::table($main_table)
        ->where($conditions)
        ->get()
        ->map(function ($record) {
            $data = (array) $record;
            unset($data['keyID']); // Remove the auto-increment key
            return $data;
        });
       // Step 2: Insert the records into tableB
        DB::table($archive_table)->insert($records->toArray());

        // Step 3: Delete the moved records from tableA
        DB::table($main_table)->where($conditions)->delete();
    }
}