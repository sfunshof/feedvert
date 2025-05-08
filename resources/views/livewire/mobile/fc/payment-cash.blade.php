<div class="container-fluid vh-90 d-flex align-items-center justify-content-center">
    <div class="row">
        <div class="col-12 mb-4">
            <!-- This div is now above the text -->
            <p>
                Please show the cashier this order No and make your payment of
                {{$currency}}{{$totalCost}}
            </p>
        
        </div>
        <div class="col-12">
            <div class="p-5 border border-3 border-primary rounded-3 shadow-lg text-center">
                <h1 class="display-4 fw-bold text-primary">
                    <small class="text-muted">No: </small> {{ $orderNo }}
                </h1>
            </div>
        </div>
    </div>
</div>  