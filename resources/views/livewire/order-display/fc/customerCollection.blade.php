 <!-- Fixed Header -->
 <div class="fixed-header container-fluid">
    <div class="row h-100">
        <!-- Left Header -->
        <div class="col-10 d-flex align-items-center justify-content-between pe-0 header-divider">
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
                <h3 class="mb-0">Calling Orders</h3>
            </div>
            {{--  
            <div class="search-container d-flex align-items-center pe-2">
                <input type="text" class="form-control me-2" placeholder="Search..." style="width: 50px;">
                <i class="fas fa-search" style="cursor: pointer; font-size: 2em;"></i>
            </div>
            --}}

        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container-fluid">
    <div class="row">

        <!-- Left Column (Preparing Orders) -->
        <div class="col-10 border-end border-1 border-primary">
            <div class="row" x-data="orderCollectData">
                @foreach($readyOrders as $order)
                    <div class="col-12 col-lg-6" @click="$wire.syncWithinCustomerOrder({{ $order->orderNo }}); orderCollectFunc({{ $order->keyID }}, {{ $order->orderNo }} )">
                        <div class="order-box w-100">{{ $order->orderNo }}</div>
                    </div>
                @endforeach
            </div>
        </div>
     </div>
</div>


@script
    <script>
        Alpine.data('orderCollectData', () => ({
            orderCollectFunc(keyID, orderNo){
                SnapDialog().alert('Calling OrderNo: ' +'<span class="fw-bold fs-3 text-danger">'
                    + orderNo  + '</span> for collection ' , 'Are you sure?', {
                    enableConfirm: true,
                    confirmText: 'Collect Now',
                    onConfirm: function() {
                        $wire.collectOrderFunc(keyID);
                    },
                    enableCancel: true,
                    onCancel: function() {
                        $wire.clearOrderFunc(orderNo);
                    }
                });
            }
        }))
    </script>
@endscript    