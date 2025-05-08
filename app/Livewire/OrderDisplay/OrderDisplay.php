<?php

namespace App\Livewire\OrderDisplay;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\PayRefsTrait;

class OrderDisplay extends Component
{
    use PayRefsTrait;
    public $showCurrentOrderBox=true;
    public $showOrderBox=true; //orderbox can be shown
    public $currentOrder = [];
    public $preparingOrders = [];
    public $readyOrders = [];
    public $companyID=null;
    public $userID=null;
    public $orderPolling="orderPolling";
    public $lastUpdated = null;
    public $orderWithinPolling="orderWithinPolling";

    public function  get_preparingOrders(){
        $this->preparingOrders = DB::table('orderstable')
            ->whereIn('isReady', [1, 2])
            ->where('companyID', $this->companyID)
            ->get();
    }
    public function  get_readyOrders(){
        $this->readyOrders = DB::table('orderstable')
            ->whereIn('isReady', [3])
            ->where('companyID', $this->companyID)
            ->get();
    }
    
    public function checkForUpdates(){
        $this->checkForUpdatesExternal();
        $this->checkForUpdatesWithin();
    }

    //Coming from kitchen when isReady=3 or complete
    private function checkForUpdatesExternal(){
        $key = "cart_polling:{$this->companyID}:{$this->orderPolling}";
        $cached = Cache::get($key);
        if (!$cached) {
            $this->lastUpdated = null;
            return;
        }
        $newTimestamp = $cached['timestamp'] ?? null;

        if ($newTimestamp !== $this->lastUpdated) {
            $this->lastUpdated = $newTimestamp;
            $this->get_preparingOrders();
            $this->get_readyOrders();
        }
    }

    private function checkForUpdatesWithin(){
        $key = "cart_polling:{$this->companyID}:{$this->orderWithinPolling}";
        $cached = Cache::get($key);
        if (!$cached) { //Nothing has been saved or started so clear existing one
            $this->clearOrderFunc();
            return;
        }
        $orderNo = $cached['orderNo'] ?? null;
        if ($orderNo){
            $this->assignOrderFunc($orderNo);
            $this->showCurrentOrderBox =true;
        }
        
    }

    private function assignOrderFunc($orderNo){
        $this->currentOrder=[
            'orderNo'=>$orderNo
        ];
    }
    public function collectOrderFunc($keyID){
        $this->clearOrderFunc();
        DB::table('orderstable')
            ->where('keyID', $keyID)
            ->update(['isready' => 4]);

        //Move it to the archive
        $conditions=['keyID'=> $keyID];
        $this->setup_archive("orderstable", "orderstable_archive",$conditions);
            
        $this->syncCustomerOrder();
    }

    public function clearOrderFunc(){
        $this->showCurrentOrderBox=false;
        $this->currentOrder=[];
         // Remove the cached data
        $key = "cart_polling:{$this->companyID}:{$this->orderWithinPolling}";
        Cache::forget($key);
    }
     //This is for updating order views and collect
     public function syncWithinCustomerOrder($orderNo){
        $key = "cart_polling:{$this->companyID}:{$this->orderWithinPolling}";
        $data = [
            'orderNo' => $orderNo,
        ];
        Cache::put($key, $data, now()->addMinutes(10));
    }
     //External: From the kitchen copied so thath complete will take it
     private function syncCustomerOrder(){
        $key = "cart_polling:{$this->companyID}:{$this->orderPolling}";
        $data = [
            'timestamp' => now()->timestamp,
        ];
        Cache::put($key, $data, now()->addMinutes(10));
    }
    public function mount(){
        $this->companyID=Auth::user()->companyID;
        $this->userID=Auth::user()->id;
        $this->get_preparingOrders();
        $this->get_readyOrders();
    }

    public function render()
    {
        return view('livewire.order-display.order-display');
    }
}
