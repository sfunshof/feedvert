<div class="container mt-5"
    x-data="{ 
        eat_in:$wire.entangle('eat_in'), 
        take_away:$wire.entangle('take_away'), 
     }"
    >
    <div class="d-flex flex-column vh-100">
        <div class="row flex-grow-0 flex-shrink-0 " style="flex-basis: 20%;">
            <div class="kiosk-height-gap"></div>
            <div class="col text-center">
                <h2> 
                    Where would you like to have your meal?
                </h2>   
            </div>
        </div>
        <div class="row flex-grow-0 flex-shrink-0" style="flex-basis: 50%;">
            
                <div class="container">
                    <div class="row justify-content-around">
                        <div @click="$wire.show_paymentPage(1)" class="col-4 border border-primary text-center">
                            <p class="mb-3 fs-3">{{ $eat_in }}</p>
                            <div class="aspect-ratio aspect-ratio-3x4">
                                <img src="{{ asset('custom/app/img/admin/eatin.png') }}" 
                                     class="img-fluid object-fit-cover w-50 h-50 mb-2" alt="Eat In">
                            </div>
                        </div>
                        <div  @click="$wire.show_paymentPage(2)" class="col-4 border border-secondary text-center">
                            <p class="mb-2 fs-3">{{ $take_away }}</p>
                            <div class="aspect-ratio aspect-ratio-3x4">
                                <img src="{{ asset('custom/app/img/admin/paper-bag.png') }}" 
                                    class="img-fluid object-fit-cover w-50 h-50 mb-2" alt="Take Away">
                            </div>
                        </div>
                    </div>
                </div>
            
        </div>
        <div class="row flex-grow-0 flex-shrink-0" style="flex-basis: 30%;">
            <div class="d-flex justify-content-center align-items-center">
                <button @click="$wire.back_fromHowMealIsServed();" type="button"
                       class="btn btn-outline-secondary w-50 kiosk-button">
                    Back
                </button>
            </div>
        </div>
        
    </div>
</div>