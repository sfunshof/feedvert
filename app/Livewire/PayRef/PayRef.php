<?php

namespace App\Livewire\PayRef;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Traits\PayRefsTrait;

class PayRef extends Component
{
    use PayRefsTrait;
    public function  mount(){
        $this->is_pure_payRef=true;
        $this->get_refNoFromTable();
    }

    public function render()
    {
        return view('livewire.pay-ref.pay-ref');
    }
}
