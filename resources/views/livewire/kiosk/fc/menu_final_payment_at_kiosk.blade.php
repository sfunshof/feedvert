<div class="container mt-5">
    <div class="d-flex flex-column vh-100"   
         x-data="{ isInitCardPayment: $wire.entangle('isInitCardPayment'),  }"
        >
        <div class="row flex-grow-0 flex-shrink-0 " style="flex-basis: 30%;">
            <div class="col">
                <h3 x-show="isInitCardPayment"> 
                    Please wait while we set up the card payment system
                </h3>
                <h3 x-show="!isInitCardPayment"> 
                    Unfortunately the card payment system on this kiosk failed. <br>
                    Please take a picture or note your Order No. <br>
                    Then proceed to the cashier to  make your payment of
                    @php
                        $grandCost = number_format($totalCost + ($totalCost * Auth::user()->companySettings->tax),2); 
                    @endphp
                    <span class="fw-bold fs-3 text-success">
                         {{Auth::user()->companySettings->currency}}{{$grandCost}} 
                    </span>
                </h3>
            </div>
        </div>
        <div class="row flex-grow-0 flex-shrink-0" style="flex-basis: 40%;">

            <div class="container-fluid  d-flex align-items-end justify-content-center mb-0 pb-0">
                <div :class="{ 'd-none': !isInitCardPayment }"  class="text-center" style="height: 100vh;">             <!-- Spinner -->
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div  x-show="!isInitCardPayment"    class="w-30 mb-4 p-3 border border-4 border-secondary rounded-3 shadow-lg
                        text-center" style="border-style: double !important;">
                    <p class="mb-0 fs-5 px=5">Your  Order <br>
                        No: <span class="fw-bold fs-3"> {{$orderNo}} </span>
                    </p>
                </div>
            </div>
            
        </div>
        <div class="row flex-grow-0 flex-shrink-0" style="flex-basis: 30%;">
            <div class="d-flex justify-content-center align-items-center">
                <p> This will close in
                    <span class="text-success fw-bold fs-3">{{ $countdown }}</span>sec
                </p>
            </div>
        </div>
        
    </div>
</div>
<div class="footer-div text-center p-3">
    <div class="row">
        <div class="col-12">
            @include('livewire.kiosk.fc.inc.menu_startAgainBtn')
        </div>
    </div>
</div>
@include('livewire.kiosk.fc.inc.timer')