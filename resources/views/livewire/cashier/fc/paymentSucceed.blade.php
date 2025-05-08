<div class="container-fluid vh-100 d-flex align-items-center">
    <div class="border border-3 border-primary rounded w-100 p-4">
        
        @if ($is_cash)
            <div class="text-center mb-3">
                <h2>Cash Payment</h2>
            </div>
            
            @php
                $tendered= number_format((float)$display, 2);
            @endphp
            <div class="text-center mb-3">
                <p class= "fs-3 fw-bold text-success">Amount Given: {{ Auth::user()->companySettings->currency}}{{ $tendered}} </p>
            </div>
            
            @if ($change_given==0)
                <div class="text-center mb-3">
                    <p class= "text-primry">Payment Successfully Made</h2>
                </div>
            @elseif($change_given > 0)
                @php
                    $change_format=number_format($change_given, 2);
                @endphp
                <div class="text-center mb-5">
                    <p class="fs-1 fw-bold text-danger"> Change to be given:  
                        {{Auth::user()->companySettings->currency}}{{ $change_format}} 
                    </p>  
                </div> 
             @endif 
        @endif

        @if ($is_card)
            <div class="text-center mb-3">
                <h2>Card Payment</h2>
            </div>
            <div class="text-center mb-3">
                <p class= "text-primry">Payment Successfully Made</h2>
            </div>
        @endif

        @if ($is_mobile)
            <div class="text-center mb-3">
                <h2>Mobile Payment</h2>
            </div>
            <div class="text-center mb-3">
                <p class= "text-primry">Payment Successfully Made</h2>
            </div>
        @endif
        
        @if ($is_voucher)
            <div class="text-center mb-3">
                <h2>Voucher Payment</h2>
            </div>
            @php
                $tendered= number_format((float)$display, 2);
            @endphp
            <div class="text-center mb-3">
                <p class= "fs-3 fw-bold text-success">Amount Given: {{ Auth::user()->companySettings->currency}}{{ $tendered}} </p>
            </div>
            
            @if ($change_given==0)
                <div class="text-center mb-3">
                    <p class= "text-primry">Payment Successfully Made</h2>
                </div>
            @elseif($change_given > 0)
                @php
                    $change_format=number_format($change_given, 2);
                @endphp
                <div class="text-center mb-5">
                    <p class="fs-1 fw-bold text-danger"> Change to be given:  
                        {{Auth::user()->companySettings->currency}}{{ $change_format}} 
                    </p>  
                </div> 
             @endif 
        @endif
        @if ($is_house) 
            <div class="text-center mb-3">
                <h2>On the House</h2>
            </div>
            <div class="text-center mb-3">
                <p class= "text-primry">Payment Successfully Accepted</h2>
            </div>
        @endif

        <div class="text-end mb-1 " >
            <button   @click= "$wire.empty_cart();$wire.init_menu();"    class="btn btn-touch btn-outline-secondary">
                Next Customer
             </button>
         </div>

    </div>
  </div>
  
     
