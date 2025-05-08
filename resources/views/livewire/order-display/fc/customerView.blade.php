 <!-- Fixed Header -->
 <div class="fixed-header container-fluid">
    <div class="row h-100">
        <!-- Left Header -->
        <div class="col-6 d-flex align-items-center justify-content-between pe-0 header-divider">
            <div class="ms-3">
                @php
                    $logo=Auth::user()->CompanySettings->logo;
                @endphp
                <img src="{{ asset('custom/app/img/clients/' . Auth::user()->companyID . '/' . $logo) }}" 
                    class="img-fluid" alt="Card Image" style="width:80px"
                    @click="logoutFunction()"
                 >
            </div>
            <div class="text-center flex-grow-1">
                <h3 class="mb-0">Preparing Orders</h3>
            </div>
        </div>
        
        <!-- Right Header -->
        <div class="col-6 d-flex align-items-center justify-content-between">
            <div class="text-center flex-grow-1">
                <h3 class="mb-0">Orders Ready</h3>
            </div>
            <div class="me-3">
                <!-- Optional: Could add another logo or element here -->
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container-fluid">
    <div class="row">

        <!-- Left Column (Preparing Orders) -->
        <div class="col-6 border-end border-1 border-primary">
            <div class="row">
                @foreach($preparingOrders as $order)
                    <div class="col-12 col-lg-6">
                        <div class="order-box w-100">{{ $order->orderNo }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Right Column (Orders Ready) -->
        <div class="col-6 border-start border-1 border-primary">
            <!-- Big Rectangle - Manually triggered -->
            @if($showCurrentOrderBox)
                <div class="big-rectangle">
                    @if(!empty($currentOrder['orderNo']))
                        {{ $currentOrder['orderNo'] }}
                    @endif
                </div>
            @endif
            
            <div class="row" id="readyOrders">
                @foreach($readyOrders as $order)
                    <div class="col-12 col-lg-6">
                        <div class="order-box ready-box w-100">{{ $order->orderNo }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
{{--  
<button class="btn btn-primary toggle-btn" wire:click="toggleOrderBox">
    {{ $showOrderBox ? 'Hide Current Order' : 'Show Current Order' }}
</button>
--}}
