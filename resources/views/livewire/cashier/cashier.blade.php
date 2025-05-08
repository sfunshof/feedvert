<div>
    {{-- Define a universal Alpine Js  --}}
    <div x-data="{ 
                    is_mainMenu: $wire.entangle('is_mainMenu'), 
                    is_payment: $wire.entangle('is_payment') ,
                    is_itemDetails: $wire.entangle('is_itemDetails'), 
                    is_menuBtns: $wire.entangle('is_menuBtns'), 
                    is_cash: $wire.entangle('is_cash'), 
                    is_card: $wire.entangle('is_card') ,
                    is_mobile: $wire.entangle('is_mobile'), 
                    is_voucher: $wire.entangle('is_voucher'), 
                    is_house: $wire.entangle('is_house'), 
                    is_paymentSucceed: $wire.entangle('is_paymentSucceed'), 
                }">

        @once('cashier-styles')
            @include('livewire.cashier.cssblade.cashier-styles')  
        @endonce


        <div class="container-fluid">
            <div class="row">
                <div class="col-8">
                    <!-- Left column content (70% width) -->
                    <div x-show="is_mainMenu" x-transition  x-cloak >
                        @include('livewire.cashier.fc.mainMenu') 
                    </div>    
                    <div  x-show="is_itemDetails" x-transition  x-cloak >
                        @include('livewire.cashier.fc.itemDetails') 
                    </div>    
                    <div x-show="is_payment" x-transition  x-cloak >
                        @include('livewire.cashier.fc.payment')
                    </div>

                </div>
                <div class="col-4">
                    <!-- Right column content (30% width) -->
                    <div class="vh-100">
                        <div class="row h-100">
                            <div class="col-12 ps-0 " style="height:78%;;">
                                <!-- Top Right -->
                                @include('livewire.cashier.fc.salesorder')                
                            </div>
                            <div x-show="is_menuBtns" class="col-12" style="height:22%">
                                <!-- Bottom right -->
                                @include('livewire.cashier.fc.menuButtons')       
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        

    </div>
</div>