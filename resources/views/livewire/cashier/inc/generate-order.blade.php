<div class="container-fluid">
    <div class="row">
        <!-- Left Column (2 cols) -->
        <div class="col-2">
            <div class="mb-3">
                <input type="text" 
                       wire:model="orderNo" 
                       class="form-control" 
                       placeholder="OrderNo."
                       onkeypress="return event.charCode >= 48 && event.charCode <= 57" />
            </div>
            <div class="text-danger small" style="{{ $showError ? '' : 'display: none;' }}">
                Enter the orderNo.
            </div>
            <button class="btn btn-outline-secondary mt-2" @click="$wire.generate()">
                Generate
            </button>
        </div>

        <!-- Right Column (10 cols) -->
        <div class="col-10 "  x-data="orderData">
            <div class="d-flex flex-nowrap align-items-baseline justify-content-center mb-2">
                <span class="me-2">Order No</span>
                <span class="me-3">{{$orderNo}}</span>
                <span class="me-2">Total</span>
                <span>
                     {{ Auth::user()->companySettings->currency}}{{ number_format($totalCost, 2) }}
                </span>
            </div>

            <div class="bg-light border rounded p-3 mb-3 " style="height: 65vh;">
               
                @if (!empty($menu_order))
                    <div class="parentDiv">
                        <div class="scrollable_container"> <!-- Content for the right-hand side -->
                            @include('livewire.cashier.inc.order_printerComplete')
                        </div>
                    </div>
                @elseif (!empty($orderNo))
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="border p-4 text-center" style="width: 50%;">
                            <p class="m-0">{{ $noOrderMsg }}</p>
                        </div>
                    </div>
                @endif
            </div>
            @if (!empty($menu_order))
                @if ($order_menu=='pay')
                    <button @click="$wire.pay_fromOrder()"   class="w-100 btn btn-outline-success"> Pay for this Order</button>
                @elseif ($order_menu=='cancel')
                    <button @click="cancel_fromOrder()"   class="w-100 btn btn-outline-danger">Cancel: Order not wanted </button>
                @endif
            @endif
        </div>
        <div class="text-center">
            <p class="text-primary"> {{ $explainMsg}} </p>
        </div>
    </div>
</div>
@script
   <script>
        Alpine.data('orderData', () => ({
            cancel_fromOrder() {
                SnapDialog().warning('Please confirm if the order should be cancelled in the kitchen: ', 
                    'Are you sure?', {
                    enableConfirm: true,
                    confirmText: 'Cancel Order in the Kitchen',
                    onConfirm: function() {
                    $wire.cancel_fromOrder();
                    },
                    enableCancel: true,
                    onCancel: function() {
                        //console.log('Cancelled');
                    }
                });
            }
        }));
          $wire.on('cancel_orderSuccessMsg', () => {
                new Notify({
                    status: 'info',
                    title: 'Stating',
                    type:'filled',
                    text: 'Order Successfully cancelled in the Kitchen',
                    position:'center'
                })
            });
        
   </script>
@endscript