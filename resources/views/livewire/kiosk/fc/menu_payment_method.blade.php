<div class="container mt-5 pt-5">
    <div class="d-flex flex-column vh-100">
        <div class="row flex-grow-0 flex-shrink-0 " style="flex-basis: 10%;">
            <div class="col text-center">
                <h3> 
                    How you would you like to pay for your meal?
                </h3>   
            </div>
        </div>
        <div class="row flex-grow-0 flex-shrink-0" style="flex-basis: 60%;">

            <div class="container-fluid d-flex flex-column">
                <!-- First Row -->
                <div class="row flex-grow-0">
                    <div class="col-12 d-flex justify-content-center">
                        <div @click="$wire.show_finalPaymentWithMobile();"    class="position-relative border border-secondary rounded-3 d-flex flex-column" style="width: 40vw; height: 20vw;">
                            <p class="text-center mb-2 pt-3">Mobile Payment Here</p>
                            <div class="mt-auto d-flex justify-content-center align-items-end" style="height: 50%;">
                                <img src="{{ asset('custom/app/img/admin/e-wallet.png')}}" 
                                    alt="Client Image" 
                                    class="img-fluid object-fit-contain h-100">
                            </div>
                        </div>
                    </div>
                </div>
         
              
                <!-- Second Row -->
                <div class="row flex-grow-1 align-items-center">
                    <div class="col-12 text-center">
                    <p class="m-0">Or </p>
                    </div>
                </div>
                
                <!-- Third Row -->
                <div class="row flex-grow-0">
                    <div class="col-12 d-flex justify-content-center">
                        <div @click="$wire.show_finalPaymentAtCashier();"   class="position-relative border border-secondary rounded-3 d-flex flex-column" 
                        style="width: 40vw; height: 20vw;">
                            <p class="text-center mb-2 pt-3">Payment at the Cashier<br> (Cash and Cards)</p>
                            <div class="mt-auto d-flex justify-content-center align-items-end" style="height: 50%;">
                                <img src="{{ asset('custom/app/img/admin/cash-payment.png')}}" 
                                    alt="Client Image" 
                                    class="img-fluid object-fit-contain h-100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
            
        </div>
        <div class="row flex-grow-0 flex-shrink-0" style="flex-basis: 30%;">
            <div class="d-flex justify-content-center align-items-center">
                <button @click="$wire.back_fromPaymentPage();" type="button"
                       class="btn btn-outline-secondary w-50 kiosk-button">
                    Back
                </button>
            </div>
        </div>
        
    </div>
</div>
