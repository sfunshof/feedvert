<div class="container-fluid">
    <div class="row">
        <!-- Left Column -->
        <div class="col-5">
            <!-- Nested row for the two left columns -->
            <div class="row">

                <div class="col-4">
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
                </div>
                <div class="col-8">
                    <div class="mb-3">
                        <input type="text" 
                            wire:model="orderDateTime" 
                            class="form-control" 
                            placeholder="YY-MM-DD HH:MM:SS"
                            onkeypress="return /[0-9\-:\s]/.test(event.key)"  />
                    </div>
                    <div class="text-danger small" style="{{ $showErrorV2 ? '' : 'display: none;' }}">
                        {{ $errorMsgV2 }}
                    </div>
                </div>
            </div> <!-- End of the  1st Row Left Col -->
            <!-- Start of Row -2 Left Col-->
            <div class="row">
                <div class="col-12">
                    <button class="btn btn-outline-secondary mt-2"  @click="$wire.generateV2()";>Generate</button>
                </div>
            </div>
        </div> <!-- End of left Column -->
        @php
            //dump($menu_order);
            $currency= Auth::user()->companySettings->currency;
            $totalCost=number_format($totalCost, 2);
            $collection_method=$menu_order['collection_method'] ?? null;
            $payment_method=$menu_order['payment_method'] ?? null;
        @endphp
        <!-- Right Column (6 cols) -->
        <div class="col-7 "  x-data="refundData">
            <div class="d-flex flex-nowrap align-items-baseline justify-content-center mb-2">
                <span class="me-2">Order No</span>
                <span class="me-3">{{$orderNo}}</span>
                <span class="me-2">Total</span>
                <span>
                     {{$currency}}{{ $totalCost }}
                </span>
            </div>

            <div class="bg-light border rounded p-3 mb-3 " style="height: 65vh;">
                @if ($totalCost > 0)
                    <div class="container w-100"> <!-- Change width by modifying w-50 or w-100 -->
                        <div class="row">
                            <div class="col-8 text-start"> <!-- Left-aligned -->
                                <p><strong>Amount to be refunded:</strong>  </p>
                                <p><strong>Payment Method:</strong></p>
                            </div>
                            <div class="col-4 text-end"> <!-- Right-aligned -->
                                <p>{{$currency}}{{ $totalCost }}</p>
                                <p> {{$payment_method}} </p>
                            </div>
                        </div>
                        <div class="row pt-5">
                            <p class="text-success fs-5"> 
                                Now initiate the process to refund this amount
                            </p>    
                        </div>    
                    </div>
                 @endif   
            </div>

            <div x-show="is_refundReady">
                <button @click="init_refund();"  class="w-100 btn btn-outline-danger"> Complete Refunding this Order</button>
            </div> 
        </div>
    </div>
</div>
@script
   <script>
        Alpine.data('refundData', () => ({
            is_refundReady:$wire.entangle('is_refundReady'),
            init_refund() {
                SnapDialog().warning('Confirm the refund of this order: ', 
                    'Are you sure?', {
                    enableConfirm: true,
                    confirmText: 'Refund the order',
                    onConfirm: function() {
                    $wire.refund_order();
                    },
                    enableCancel: true,
                    onCancel: function() {
                        //console.log('Cancelled');
                    }
                });
            }
        }));

        $wire.on('refund_orderSuccessMsg', () => {
            new Notify({
                status: 'info',
                title: 'Refund',
                type:'filled',
                text: 'Order successfully refunded',
                position:'center'
            })
        });
        
   </script>
@endscript