<?php

namespace App\Livewire\Cdu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

use Livewire\Component;

class Cdu extends Component
{
    public $companyID;
    public $userID;
    public $CDUpolling="CDUpolling";
    public $cart = [];
    public $aux_cart=[];
    public $lastUpdated = null;
    public $totalOrder=0;
    public $discount=0;
    public $subTotal=0;
    public $totalCost="0.00";
    public $tax=0;

    public function mount(){
        $this->companyID=Auth::user()->companyID;
        $this->userID=Auth::user()->id;
    }

    public function show_itemDetails($name, $qty,  $mealTypeID, $index){
        //Nothing just a placeholdet
    }
    private function  clearOtherCart(){
        $this->totalOrder=0; 
        $this->discount=0;
        $this->subTotal = 0; 
        $this->totalCost = "0.00"; 
        $this->tax=0;
    }
    public function checkForUpdates(){
        $key = "cdu_polling:{$this->companyID}:{$this->userID}:{$this->CDUpolling}";
        $cached = Cache::get($key);

        //if (!$cached) return;
        if (!$cached) {
            $this->cart = [];
            $this->clearOtherCart();
            $this->lastUpdated = null;
            return;
        }


        $newTimestamp = $cached['timestamp'] ?? null;
        // If no update for 10 minutes, clear cart
        if ($newTimestamp && now()->timestamp - $newTimestamp > 600) {
            $this->cart = [];
            $this->clearOtherCart();
            $this->aux_cart=[];
            $this->lastUpdated = null;
            return;
        }


        if ($newTimestamp !== $this->lastUpdated) {
            $this->lastUpdated = $newTimestamp;
            $this->cart = $cached['cart'] ?? [];
            $this->aux_cart = $cached['aux_cart'] ?? [];
            $this->updateCart();
            $this->dispatch('scrollToCDUbottom'); 
        }
        
    }
    private function  updateCart(){
        $subTotal=0;
        $totalOrder=0;
        $tax=Auth::user()->companySettings->tax;
        $discount=Auth::user()->companySettings->discount;
        foreach ($this->cart as $obj) {
            $subTotal += $obj->price *  $obj->qty;  // Add price to total
            $totalOrder += $obj->qty;  // Add order to total
            //$this->js('console.log('  . $totalOrder  . ')');
        }
        $this->totalOrder=$totalOrder; 
        $this->discount= $subTotal * $discount / 100;
        $this->subTotal = $subTotal + $this->discount; 

        $this->totalCost = $subTotal + ($subTotal * $tax / 100); 
        $this->tax=$this->totalCost-$subTotal;

        $this->totalCost= number_format($this->totalCost, 2);
        $this->tax= number_format($this->tax, 2);
        $this->subTotal= number_format($this->subTotal, 2);
       
    }
    public function render()
    {
        return view('livewire.cdu.cdu');
    }
}
