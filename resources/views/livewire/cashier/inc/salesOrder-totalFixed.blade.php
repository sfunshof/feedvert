        <!-- Add sub total  -->
        @if ($discount > 0)
            <div class="row">
                <div class="col-10">
                    <span class="fs-3">Discount</span>
                </div>   
                <div class="col-2">
                    <span class="fs-3">{{ Auth::user()->companySettings->currency }}{{$discount}}</span>
                </div>
            </div>
        @endif
        @if ($tax > 0)
            <div class="row">
                <div class="col-10">
                    <span class="fs-3">SubTotal</span>
                </div>
                <div class="col-2">
                    <span class="fs-3">{{ Auth::user()->companySettings->currency }}{{$SubTotal}}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-10">
                    <span class="fs-3">Tax</span>
                </div>
                <div class="col-2">
                    <span class="fs-3">{{ Auth::user()->companySettings->currency }}{{$tax}}</span>
                </div>
            </div>
        @endif
        <div class="row mt-auto">
            <div class="col-10">
                <span class="fs-2">Total Balance</span>
            </div>
            <div class="col-2">
                <span class="fs-3">{{ Auth::user()->companySettings->currency }}{{$totalCost}}</span>
            </div>
        </div>