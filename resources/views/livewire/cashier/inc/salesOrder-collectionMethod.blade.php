@if(!empty($aux_cart['collection_method'] ?? null))
    <div class="list-row row">
        <div class="col-10">
            Collection Method:
        </div>
        <div class="col-2">
            {{ $aux_cart['collection_method']}}
        </div>
        @if(!empty($aux_cart['payment_method'] ?? null))
            @if($aux_cart['payment_method']=="Cash")
                @if($aux_cart['amount_tendered'] > 0)
                    <div class="col-10">
                        Amount Tendered:
                    </div>
                    <div class="col-2">
                        {{ $aux_cart['amount_tendered'] }}
                    </div>
                    <div class="col-10">
                        Change Given:
                    </div>
                    <div class="col-2">
                        {{ $aux_cart['change_given'] }}
                    </div>
                @endif
            @else
                <div class="col-10">
                    Payment method:
                </div>
                <div class="col-2">
                    {{ $aux_cart['payment_method'] }}
                </div> 
            @endif
        @endif
    </div>
@endif