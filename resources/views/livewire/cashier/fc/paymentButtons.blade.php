<div class="container mt-4 w-100">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <span class="fs-5 text-center">Payments</span>
                    <div class="d-flex flex-column align-items-center gap-4 pt-3">
                        <button  @if($is_paymentProcessing) disabled @endif   @click="$wire.show_cashPayment();" class="btn  btn-touch btn-outline-secondary w-100">
                            {{$payNames[0]}} Payment
                        </button>
                        <button  @if($is_paymentProcessing) disabled @endif   @click="$wire.show_cardPayment();"  class="btn  btn-touch btn-outline-primary w-100">
                            {{$payNames[1]}} Payment
                        </button>
                        <button  @if($is_paymentProcessing) disabled @endif   @click="$wire.show_mobilePayment();"   class="btn  btn-touch btn-outline-success w-100">
                            {{$payNames[2]}} 
                        </button>
                        <button  @if($is_paymentProcessing) disabled @endif   @click="$wire.show_voucher();"    class="btn  btn-touch btn-outline-warning w-100">
                            {{$payNames[3]}}
                        </button>
                        <button  @if($is_paymentProcessing) disabled @endif   @click="$wire.show_onTheHouse();"   class="btn  btn-touch  btn-outline-danger w-100">
                            {{$payNames[4]}}
                        </button>
                    </div>
                </div>
            </div>
        </div>            
    </div>   
</div>   
@script
    <script>
        $wire.on('init_cardPayment', () => {
            SnapDialog().warning('Please confirm payment by Card', 
                'Are you sure?', {
                enableConfirm: true,
                confirmText: 'Confirm Card Payment',
                onConfirm: function() {
                $wire.complete_cardPayment();
                },
                enableCancel: true,
                onCancel: function() {
                    //console.log('Cancelled');
                    $wire.set('is_cash', true);
                }
            });
        });
        $wire.on('init_mobilePayment', () => {
            SnapDialog().warning('Please confirm payment by Mobile wallet', 
                'Are you sure?', {
                enableConfirm: true,
                confirmText: ' Confirm Mobile Payment',
                onConfirm: function() {
                $wire.complete_mobilePayment();
                },
                enableCancel: true,
                onCancel: function() {
                    //console.log('Cancelled');
                    $wire.set('is_cash', true);
                }
            });
        });
       
        $wire.on('init_onTheHousePayment', () => {
            SnapDialog().warning('Please confirm payment on the House', 
                'Are you sure?', {
                enableConfirm: true,
                confirmText: ' Confirm Payment On the House',
                onConfirm: function() {
                $wire.complete_onTheHouse();
                },
                enableCancel: true,
                onCancel: function() {
                    //console.log('Cancelled');
                    $wire.set('is_cash', true);
                }
            });
        });
         $wire.on('set_printerIPaddress', () => {
            let printerDetails = store.get('printerDetails');
            if (printerDetails){
                $wire.set('printerIPaddress', printerDetails.ip);
                $wire.complete_printReceipt();
            }else{
                new Notify({
                    status: 'error',
                    title: 'Stating',
                    type:'filled',
                    text: 'No Printer is setup on this device',
                    position:'center'
                })
            }   
        });
        $wire.on('set_printerIPaddress_endOfDay', () => {
            let printerDetails = store.get('printerDetails');
            if (printerDetails){
                $wire.set('printerIPaddress', printerDetails.ip);
                $wire.complete_print_cashUp_endOfDay();
                new Notify({
                    status: 'info',
                    title: 'Info',
                    type:'filled',
                    text: 'End of day printed',
                    position:'center'
                })
            }else{
                new Notify({
                    status: 'error',
                    title: 'Info',
                    type:'filled',
                    text: 'No Printer is setup on this device',
                    position:'center'
                })
            }
        });
        Livewire.on('printerErrorMsg', (event) => {
            new Notify({
                status: 'error',
                title: 'Starting',
                type:'filled',
                text: 'Error: ' + event.errorMsg,
                position:'center'
            })
        });
    </script>   
@endscript