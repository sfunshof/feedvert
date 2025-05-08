<div>
    <div class="row vh-100">
        <div class="col-4 h-100" x-show="!is_paymentSucceed">
            <!-- Content for the left column (40% width) -->
            @include('livewire.cashier.fc.paymentButtons')
        </div>
        <div x-show="is_cash && !is_paymentSucceed" class="col-8 h-100" style="z-index: 999">
            <!-- Content for the right column (60% width) -->
            @include('livewire.cashier.fc.payments.keyPad')
        </div>
        <div x-show="is_card && !is_paymentSucceed" class="col-8 h-100" style="z-index: 999">
            <!-- Content for the right column (60% width) -->
            @include('livewire.cashier.fc.payments.cardPayment')
        </div>
        <div x-show="is_mobile && !is_paymentSucceed" class="col-8 h-100" style="z-index: 999">
            <!-- Content for the right column (60% width) -->
            @include('livewire.cashier.fc.payments.mobilePayment')
        </div>
        <div x-show="is_voucher && !is_paymentSucceed" class="col-8 h-100" style="z-index: 999">
            <!-- Content for the right column (60% width) -->
            @include('livewire.cashier.fc.payments.keyPad')
        </div>
        <div x-show="is_house && !is_paymentSucceed" class="col-8 h-100" style="z-index: 999">
            <!-- Content for the right column (60% width) -->
            @include('livewire.cashier.fc.payments.onTheHouse')
        </div>
             
        <div class="col-12 h-100" x-show="is_paymentSucceed">
            @include('livewire.cashier.fc.paymentSucceed')
        </div>
        

    </div>
</div>
