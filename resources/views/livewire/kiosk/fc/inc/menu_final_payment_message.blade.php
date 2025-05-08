<div class="container mt-5">
    <div class="d-flex flex-column vh-100">
        <div class="row flex-grow-0 flex-shrink-0 " style="flex-basis: 30%;">
            <div class="co mt-5 pt-5 mx-2" >
                <h3> 
                   {!! $final_payment_message !!}
                </h3>
            </div>
        </div>
        <div class="row flex-grow-0 flex-shrink-0" style="flex-basis: 40%;">

            <div class="container-fluid  d-flex align-items-end justify-content-center">
                <div class="w-30 mb-4 p-3 border border-4 border-primary rounded-3 shadow-lg
                     text-center" style="border-style: double !important;">
                    <p class="mb-0 fs-5 px-5">Your  Order <br>
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
<div class="footer-div text-center pb-3">
    <div class="row">
        <div class="col-12">
            @include('livewire.kiosk.fc.inc.menu_startAgainBtn')
        </div>
    </div>
</div>
@include('livewire.kiosk.fc.inc.timer')