<div>
    @once('pay-ref-styles')
        @include('livewire.pay-ref.cssblade.pay-ref-styles')
    @endonce

    <div class="scrollable-table-container" x-data="payRefData">
        <table class="scrollable-table mb-2">
          <thead>
            <tr>
              <th>Date</th>
              <th>Ref No</th>
              <th>Amount</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <!-- Table rows go here -->
            @foreach ($refResults as $result)
                <tr> 
                    <td>{{$result->dateTime}}</td>
                     <td>{{$result->refNo}}</td> 
                     <td>{{$result->amount }}</td>
                     <td>
                            <button  class="btn btn btn-outline-dark" @click="confirm_paymentRefNo( {{$result->orderNo}},  {{$result->amount }},  '{{$result->refNo}}'   )">
                                <i class="fas fa-check-square text-primary"></i>
                            </button>
                     </td>
                 </tr>
            @endforeach
            <!-- Add more rows as needed -->
          </tbody>
        </table>
        <p class="text-success text-center">
           These are payment references that need to be verified
        </p>
      </div>

    @if ($is_pure_payRef)
        <footer class="bg-light text-dark text-center py-3">
            <button class="btn btn-primary w-50" @click="logoutFunction()">Logout</button>
        </footer>
     @endif

</div>
@script
    <script>
        Alpine.data('payRefData', () => ({
            currency:$wire.currency,
            confirm_paymentRefNo(orderNo,amount,refNo){
                SnapDialog().warning('Confirm this Payment Reference to the Amount: <br>' +  refNo + ' .. to .. ' +  this.currency +  amount, 
                    'Are you sure?', {
                    enableConfirm: true,
                    confirmText: 'Confrm Ref No',
                    onConfirm: function() {
                    $wire.confirm_paymentRefNo(orderNo,amount,refNo);
                    },
                    enableCancel: true,
                    onCancel: function() {
                       // alert (this.currency);
                    }
                });
            }
         }));
     </script>
@endscript