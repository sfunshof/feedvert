@php
   $currency= Auth::user()->companySettings->currency ;
@endphp
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 w-75">
            <table class="table table-bordered mb-3 text-center">
                <thead class="table-light">
                    <tr>
                        <th>Type</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($totalSales as $index => $payment)
                        @if ($loop->last)
                            <tr>
                                <td class="fw-bold bg-light text-start">Total</td>
                                <td class="fw-bold bg-light text-end">{{ $currency }}{{ number_format($payment['amount'], 2) }}</td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-start">{{ $payment['payName'] }}</td>
                                <td class="text-end">{{ $currency }}{{ number_format($payment['amount'], 2) }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row justify-content-center" x-data="cashUpData">
        <div class="col-auto">
            <button @click="cashUp_endOfDay()" class="btn btn-outline-primary me-2">End of the Day Report</button>
        </div>
        <div class="col-auto">
            <button @click="$wire.print_cashUp_endOfDay()" class="btn btn-outline-secondary">Print Report</button>
        </div>
    </div>
    
</div>
@script
   <script>
        Alpine.data('cashUpData', () => ({
            cashUp_endOfDay() {
                SnapDialog().warning('Please confirm if this is the End of the day report: ', 
                    'Are you sure?', {
                    enableConfirm: true,
                    confirmText: 'Yes. End of the Day Report',
                    onConfirm: function() {
                    $wire.cashUp_endOfDay();
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
                    status: 'Info',
                    title: 'Stating',
                    type:'filled',
                    text: 'Order Successfully cancelled in the Kitchen',
                    position:'center'
                })
            });
        
   </script>
@endscript