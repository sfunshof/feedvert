<div class="container mb-5 pb-5"   x-data="{    paymentMethod: 'Online Payments',
                                                paymentReference: '',
                                                errorMessage: ''
                                            }" >
    <p class="text-center">
        Order Summary
    </p>
    @php
        $subTotal= number_format($totalCost-$totalTax,2);
        $curr=$currency;
    @endphp
    <div class="row">
        <div class="col-8">     
            <p> Sub Total: </p>
        </div>
        <div class="col-4">
            <p> {{ $curr }}{{$subTotal}} </p>
        </div>   
    </div>   
    <div class="row">
        <div class="col-8">     
            <p> Tax: </p>
        </div>
        <div class="col-4">
            <p>  {{ $curr }}{{ $totalTax }} </p>
        </div>   
    </div>  
    <div class="row">
        <div class="col-8">     
            <p> Total: </p>
        </div>
        <div class="col-4">
            <p>  {{ $curr }}{{ $totalCost }} </p>
        </div>   
    </div> 

    <div class="row ">
        <h6 class="underline-thick-fade text-center text-secondary">Choose the Payment Method</h6>
    </div>
    <!-- Make a choice -->
    <div class="centered-content mb-2" >
        <div class="form-check custom-radio">
            <input class="form-check-input" type="radio" name="paymentRadio"
             id="online" value="Online Payments"  x-model="paymentMethod">
            <label class="form-check-label" for="online">
                Card Payments
            </label>
        </div>
        <div class="form-check custom-radio">
            <input class="form-check-input" type="radio" name="paymentRadio" 
            id="cash" value="Cash Payments" x-model="paymentMethod">
            <label class="form-check-label" for="cash">
                Cash Payments
            </label>
        </div>
        <div class="form-check custom-radio">
            <input class="form-check-input" type="radio" name="paymentRadio" 
            id="mobile" value="Mobile Payments" x-model="paymentMethod">
            <label class="form-check-label" for="mobile">
                Mobile Wallet Payments
            </label>
        </div>
    </div>
    
    <!-- Pay with cash -->
    <div class="row mt-5 " x-show="paymentMethod === 'Cash Payments'">
        <button type="button"  @click="$wire.show_payment_cash();"    class="btn btn-warning">Pay With Cash</button>
    </div>
    <!-- Mobile Wallet -->
    <div class="row mt-5 " x-show="paymentMethod === 'Mobile Payments'">
        <!-- Centered Text Field -->
        <div class="col-12 text-center mb-3">
            <input type="text" class="form-control mx-auto w-50"
            placeholder="Enter payment reference"
            x-model="paymentReference"
             wire:model="paymentRef"
            >
            <!-- Error Message -->
            <div class="text-danger small mt-2" x-text="errorMessage"></div>
        </div>

        <!-- Button Below the Text Field -->
        <div class="col-12 text-center">
            <button
                type="button"
                @click=" errorMessage = ''; if (paymentReference.trim() !== '') { $wire.show_payment_mobile();} else {errorMessage = 'Error: Please enter the payment reference.';}"
                class="btn btn-warning" >
                Pay With Mobile
        </button>
        </div>
    </div>

    <!-- card payment -->
    <div x-show="paymentMethod === 'Online Payments'">
        <div class="d-flex justify-content-center align-items-center w-75"  >
            <div class="centered-content">
                <div class="row row-cols-2 g-4">
                    <div class="col">
                        <div class="cell-wrapper">
                            <div class="clickable-rectangle">
                                <img src="{{ asset('custom/app/img/admin/visa-master.png') }}"   alt="Logo 1" class="logo-img">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>       

</div>    
