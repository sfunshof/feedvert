<div class="container-fluid vh-100">
    <div class="header-div">
        <div class="kiosk-height-gap"></div>
        <div class="col text-center mb-3 pt-3 pt-lg-5">
            <h3>View Order</h3>
        </div>
    </div>
    <div class="top-div pb-3">
        <div class="table-container">
            <table class="table mt-0" style="border-collapse: collapse; width: 100%;">
                <tbody>

                    @foreach($this->cart as $index_parent => $itemObj) 
                        <tr>
                            <td>
                                <!-- Start all sections here -->
                                <div x-data="{ isExpanded: false }">
                                    <!-- Section 1 -->
                                    <div class="row align-items-center mb-3">
                                        <div class="col-8 d-flex align-items-center text-center">
                                            <img src="{{ asset('custom/app/img/clients/' . Auth::user()->companyID . '/' . $itemObj->logo) }}" 
                                            class="img-fluid" style="width: 128px; height: 128px; object-fit: cover;">
                                            <p class="ms-3 fw-bold">{{$itemObj->name}}</p>
                                        </div>
                                        <div class="col-4 text-center">
                                            @php
                                                $price=number_format($itemObj->qty * $itemObj->price,2 );
                                            @endphp
                                            <p class="fw-bold">{{Auth::user()->companySettings->currency}}{{ $price }}</p>
                                        </div>
                                    </div>

                                    <!-- Section 2 -->
                                    <div class="row mb-3 row transition-all ease-in-out duration-500 transform w-100"
                                                    x-show="isExpanded"
                                                    x-transition.opacity.duration.500ms
                                                    :class="isExpanded ? 'max-h-screen opacity-100' : 'max-h-0 opacity-0'"
                                        >
                                        <div class="col-8">
                                            @if (count($itemObj->options) > 0)
                                                <div class="text-center mb-2">
                                                    <p>Offers:</p>
                                                </div>
                                            @endif
                                            <div class="d-flex justify-content-start align-items-center">
                                                @foreach($itemObj->options as $option)
                                                    <img src="{{ asset('custom/app/img/clients/' . Auth::user()->companyID . '/' . $option->logo) }}" 
                                                    class="img-fluid me-2" style="width: 64px; height: 64px; object-fit: cover;">
                                                <p class="me-4 fw-bold">{{$option->name}}</p>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            @if  (count($itemObj->customise) > 0)
                                                <div class="text-center mb-2">
                                                    <p>Customised:</p>
                                                </div>
                                            @endif
                                            <div class="text-center">
                                                <ul class="list-unstyled">
                                                    @foreach($itemObj->customise as $customise)
                                                        <h6 class="mb-2">{{ $customise->itemName }}</h6>
                                                        <ul class="list-disc pl-6">
                                                            @foreach($customise->addOn_names as $index => $name)
                                                                @if($customise->addOnJson_customise[$index] === 0)
                                                                    <li class="mb-1">No {{ $name }}</li>
                                                                @elseif($customise->addOnJson_customise[$index] === 2)
                                                                    <li class="mb-1">Extra {{ $name }}</li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Section 3 -->
                                    <div class="row">
                                        <div class="col-4">
                                            <button class="btn btn-outline-secondary kiosk-button "
                                                wire:loading.attr="disabled" 
                                                wire:loading.class="opacity-50 cursor-not-allowed" 
                                                {{ empty($itemObj->options) && empty($itemObj->customise) ? 'disabled' : '' }}
                                                x-text="isExpanded ? 'Less' : 'Details'"
                                                @click="isExpanded = !isExpanded"
                                            >
                                            </button>
                                        </div>
                                        <div class="col-4">
                                            <button @click="$wire.removeItem({{ $index_parent }})"
                                                x-on:click="$nextTick(() => { if ($wire.cart.length === 0) $wire.emptyCart() })" 
                                                class="btn btn-outline-secondary  kiosk-button">
                                                    Remove
                                            </button>
                                        </div>
                                        <div class="col-4">
                                            <div class="col-12 text-end mt-auto">
                                                <div class="input-group justify-content-end">
                                                    <button class="btn btn-outline-secondary w-25 kiosk-button" type="button"
                                                        wire:click="decrementQty({{ $index_parent }})" 
                                                        wire:loading.attr="disabled"
                                                        @if(($this->cart[$index_parent]->qty ?? 1) <= 1) disabled @endif
                                                    >
                                                        <span><strong>&minus;</strong></span>
                                                    </button>
                                                    <span class="mx-2 pt-1">{{ ($this->cart[$index_parent]->qty ?? 1) }}</span>
                                                    <button class="btn btn-outline-secondary w-25 kiosk-button" type="button"
                                                            wire:click="incrementQty({{ $index_parent }})"
                                                            wire:loading.attr="disabled">
                                                        <span><strong>&plus;</strong></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <!-- End of all sections here -->
                            </td>
                        </tr>
                
                    @endforeach
                    
                </tbody>
            </table>
        </div>
    </div>

    <div class="middle-div d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-md-6">
                    <div class="d-flex justify-content-between">
                        <p class="mb-1">Sub Total:</p>
                        <p class="mb-1">{{Auth::user()->companySettings->currency}}{{ $totalCost}}</p>
                    </div>
                    @if(Auth::user()->companySettings->tax > 0)
                        <div class="d-flex justify-content-between">
                            <p class="mb-1">Tax:</p>
                            <p class="mb-1">{{Auth::user()->companySettings->currency}}{{Auth::user()->companySettings->tax}}</p>
                        </div>
                    @endif    
                    <div class="d-flex justify-content-between fw-bold border-top pt-2">
                        @php 
                            //$subCost = 100; // Example subCost 
                            //$tax = 0.2; // Example tax rate (20%) 
                            $grandCost = number_format($totalCost + ($totalCost * Auth::user()->companySettings->tax),2); 
                        @endphp
                        <p class="mb-0">Total:</p>
                        <p class="mb-0">{{Auth::user()->companySettings->currency}}{{$grandCost}}</p>
                    </div>
                </div>
            </div>
        </div>   
    </div>
</div>

<div class="footer-div d-flex align-items-center justify-content-center pb-3">
    <div class="container">
        <!-- First Row -->
        <div class="row mb-lg-5 pt-lg-3">
            <div class="col-4">
                <button class="btn btn-primary  w-100 kiosk-button"
                    @click="$wire.orderMoreFromViewMyOrder();"
                >
                    Order More
                </button>
            </div>
            <div class="col-8">
                <button class="btn btn-warning w-100 kiosk-button"
                    @click="$wire.show_howMealIsServed();"
                >
                    Complete Order
                </button>
            </div>
        </div>
    
        <!-- Second Row -->
        <div class="row mt-lg-3">
            <div class="col-8 offset-4">
                @include('livewire.kiosk.fc.inc.menu_startAgainBtn')
            </div>
        </div>
    </div>
</div>
