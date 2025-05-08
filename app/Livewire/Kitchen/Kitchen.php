<?php

namespace App\Livewire\Kitchen;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Polling;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Cache;
use App\Traits\PayRefsTrait;

class Kitchen extends Component
{
    use PayRefsTrait;
    public $columns = [];
    public $companyID;
    public $KDSwarning;
    public $KDSlate;
    public $no_of_orders;
    public $pollName="kitchen";  //This is from waiting to prepation to completion
    public $orderPolling="orderPolling"; // Also used in the kitchen
    public $userID=null; // added becuase of polling
    public $lastUpdated = null;
    public $status = [];
    public $printerIPaddress="";
    public $displayStatus=0; //open =1, hold=2, completed=3

    public function getOrderDetails($status){
        $columns = DB::table('orderstable')
            ->select(
                DB::raw('(orderDatetime) as date'), // Extracting date part from orderDatetime
                DB::raw('TIME(orderDatetime) as time'), // Extracting time part from orderDatetime
                'orderNo',
                'keyID',
                'isReady',
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(order_json, "$.collection_method")) as collection_method'), // Extracting collection_method from JSON
                'order_json' // Selecting the entire JSON field
            )
            ->where('companyID', $this->companyID)
            ->whereIn('isReady', $status) // Adding the isReady = 1 condition
            ->get();
        $this->status = $status;
        $this->columns= $columns;
        $this->no_of_orders=count($this->columns);
    }

    public function prepareOrder($keyID){
        DB::table('orderstable')
            ->where('keyID', $keyID)
            ->update(['isready' => 2]);
            //dump($keyID);
        $this->updateLastUpdatedTime();
    }

    public function completeOrder($keyID){
        //dump($keyID);
        DB::table('orderstable')
            ->where('keyID', $keyID)
            ->update(['isready' => 3]);
        $this->syncCustomerOrder(); //customer sees his orderNo 
        $this->dispatch('kitchen-polling');
    }
    //Internal: Prepration to Completion
    #[On('kitchen-polling')]
    public function repeatFunction(){
        // Update the last updated timestamp in memcached
        sleep(1);
        $this->updateLastUpdatedTime();
    }
    //External:Completion shown in order display: Sames as cashier
    private function syncCustomerOrder(){
        $key = "cart_polling:{$this->companyID}:{$this->orderPolling}";
        $data = [
            'timestamp' => now()->timestamp,
        ];
        Cache::put($key, $data, now()->addMinutes(10));
    }


    public function holdOrder($keyID){
        DB::table('orderstable')
        ->where('keyID', $keyID)
        ->update(['isready' => 5]);
        //dump($keyID);
        $this->updateLastUpdatedTime();
    }


    public function complete_printKitchenReceipt($keyID, $orderNo){
        $orderData = DB::table('orderstable')->where('keyID', $keyID)->select('orderDatetime', 'order_json')->first();
        $receiptDate = $orderData->orderDatetime;
        $receiptData = $orderData->order_json;
        $this->print_to_receiptPrinter($receiptData, $orderNo,
        $receiptDate,$this->printerIPaddress, false);
    }
    public function  handleClick($keyID, $isReady, $orderNo) {
        if ($isReady === 1) {
            $this->prepareOrder($keyID);
            $this->dispatch('startPrepNotification');
         } else if ($isReady === 2) { //Prepation to Complete
            $this->dispatch('checkBeforeComplete', keyID:$keyID, orderNo:$orderNo);
       }
    }

    public function mount(){
        $this->companyID=Auth::user()->companyID;
        $this->userID=Auth::user()->id;
        $this->KDSlate= Auth::user()->companySettings->KDSlate *60;
        $this->KDSwarning= $tax=Auth::user()->companySettings->KDSwarning *60;
        $this->status=[1,2];
        $this->getOrderDetails($this->status); //Open
        // Initialize the last updated timestamp
        $this->lastUpdated = $this->getLastUpdatedTime();
        //$this->update_text = array_fill(0, $this->no_of_orders, 'Waiting');
    }

    public function render()
    {
        return view('livewire.kitchen.kitchen');
    }
    // Method to check if we need to refresh data: This runs everytime
    //There are 2 cchecks 
    //1. Internal (waiting preption and completion)
    //2. External bring ing a new one
    public function checkForUpdates(){
        //Internal
        $currentLastUpdated = $this->getLastUpdatedTime();
        if ($currentLastUpdated && $this->lastUpdated !== $currentLastUpdated) {
            $this->lastUpdated = $currentLastUpdated;
            // Reload data with current status
            if ($this->status) {
                $this->getOrderDetails($this->status);
                //dump('pass');
            }
            return true;
        }
        //External from other sources: cashier kiosk mobile
        $key = "cart_polling:{$this->companyID}:{$this->orderPolling}";
        $cached = Cache::get($key);
        if (!$cached) {
            $this->lastUpdated = null;
            return;
        }
        $newTimestamp = $cached['timestamp'] ?? null;
        if ($newTimestamp !== $this->lastUpdated) {
            $this->lastUpdated = $newTimestamp;
            if ($this->status) {
                $this->getOrderDetails($this->status);
            }
        }
        return false;
    }

    // Get the timestamp of the last update from memcached
    protected function getLastUpdatedTime(){
        $cacheKey = "lastUpdated:{$this->pollName}:{$this->companyID}";
        return Cache::get($cacheKey);
   }
     // Set the last updated timestamp in memcached
     protected function updateLastUpdatedTime(){
        $cacheKey = "lastUpdated:{$this->pollName}:{$this->companyID}";
        Cache::put($cacheKey, now()->timestamp, 3600); // Store for 1 hour
        //dump( $cacheKey);
     }

}
