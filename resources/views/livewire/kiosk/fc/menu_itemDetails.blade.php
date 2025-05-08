    <div style="height: 100vh;">
        <!-- Spacer div to push content down -->
        <div style="height: 20%;"></div>

        <div class="d-flex flex-column align-items-center text-center w-100 pb-3" style="height: 100vh;">
            <h3> Meal Details </h3>
            <div class="height-5"></div>
            <!-- Image Section -->
            <div class="mb-2 mb-lg-5">
                <div class="kiosk-menu-item  rounded-3 overflow-hidden d-flex flex-column rounded border border-secondary">
                    <img src="{{ asset('custom/app/img/clients/' . Auth::user()->companyID . '/' . $imageName) }}" 
                        alt="Client Image" class="resize-image-fluid">
                    <div class="kiosk-text-container p-2 d-flex align-items-start justify-content-center text-center">
                        {{$itemName}}
                        <br>
                        {{Auth::user()->companySettings->currency}}{{$itemPrice}} 
                    </div>
                </div>
            </div>

            <!-- Customize Button -->
            @if(in_array($mealTypeID, [1, 2]))
                <div class="w-50 mb-2 mt-lg-5">
                    <button type="button" 
                        @if(is_null($addOnJson) || empty($addOnJson)) 
                            disabled 
                        @endif
                        @click="$wire.show_customise()"
                        class="btn btn-outline-secondary w-100 kiosk-button">Customise 
                    </button>
                </div>
            @endif

            @if(in_array($mealTypeID, [3, 4]))
                <div class="container mb-2 mt-2 ">
                    <!--  Menu  Options -->
                    <div class="mt-lg-5 mb-lg-5  border-secondary border-bottom">
                        @if (count($mealOptions) > 0 )
                            <p> This meal comes with the following offers: </p>
                        @endif
                        @foreach($mealOptions as $index => $menuOption)
                            <div class="row mb-3 align-items-center " style="visibility: visible">
                                <div class="col-md-1">
                                    @if (!empty($mealOption_logos[$index]))
                                        <img src="{{ asset('custom/app/img/clients/' . Auth::user()->companyID . '/' .  $mealOption_logos[$index]  )  }}" 
                                            alt="Client Image" class="resize-image-fluid64">
                                    @endif
                                </div>
                                <div class="col-md-5 text-start pt-5">
                                    @if (!empty($mealOption_names[$index]))
                                        <p>{{ $mealOption_names[$index]}}</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-outline-secondary kiosk-button w-100"
                                            @click="$wire.getMealOptions({{ $menuOption->id }}, '{{ $menuOption->selectOption }}', {{ $index }} )">
                                            @if (!empty($mealOption_names[$index]))
                                                Change
                                            @else
                                                {{ $menuOption->selectOption }}
                                            @endif
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!---End of Meal Options -->

                    <!--  Meal  Items -->
                    @if (count($this->mealOption_names)== count($this->mealOptions))
                        <div class="mt-lg-5 mb-lg-5 ">
                            <p> This meal is made up with : </p>
                            @foreach($mealItems as $index => $mealItem)
                                <div class="row mb-3 align-items-center" style="visibility: visible">
                                    <div class="col-md-1">
                                        <img src="{{ asset('custom/app/img/clients/' . Auth::user()->companyID . '/' .  $mealItem->logo )  }}" 
                                                alt="Client Image" class="resize-image-fluid64">
                                    </div>
                                    <div class="col-md-5 text-start pt-5">
                                    <p>{{ $mealItem->name}}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-outline-secondary kiosk-button w-100"
                                            @click="$wire.show_customise_item( {{$mealItem->id}}, '{{$mealItem->logo}}', '{{$mealItem->name}}', @js($mealItem->json) )"
                                        >
                                            Customise
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>    
                    @endif
                    <!---End of Meal Items -->

                </div>
            @endif

            <!-- Counter -->
            @if (count($this->mealOption_names)== count($this->mealOptions))
                <div  x-data="{ itemCountInit: $wire.entangle('itemCountInit') }"   class="row justify-content-center mb-2 w-50 mt-2 mb-lg-5 mt-lg-5">
                    <div class="col-auto w-100">
                        <p> How Many ? </p>
                        <div class="d-flex align-items-center w-100">
                            <button   :disabled="itemCountInit <= 1"   @click="itemCountInit--"  class="btn btn-outline-danger w-50 me-2 kiosk-button "> 
                                <span> <strong>&minus; </strong></span>
                            </button>
                            <span class="fs-4 fw-bold mx-3"  x-text="itemCountInit" ></span>
                            <button :disabled="itemCountInit >= 20"    @click="itemCountInit++" class="btn btn-outline-success w-50 ms-2 kiosk-button ">
                                <span> <strong>&plus; </strong></span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Cancel and Order Btn -->    
            <div class="row justify-content-center mb-5 w-100 mt-lg-5 mb-lg-5">
                <div class="col-auto w-100">
                    <div class="d-flex align-items-center w-100">
                        <button class="btn btn-outline-secondary w-25 me-2 kiosk-button"   @click="$wire.cancelItemDetailsFunc()" >Cancel Item</button>
                        @if (count($this->mealOption_names)== count($this->mealOptions))
                            <button class="btn btn-warning w-75 ms-2 kiosk-button"   @click="$wire.updateItemFunc();" >Add to  Order</button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Start Over Button  -->
            <div class="w-50 mt-3 d-flex justify-content-end  mt-lg-5 mb-lg-5 pe-5" style="margin-left: auto;"">
                @include('livewire.kiosk.fc.inc.menu_startAgainBtn')
            </div>

        </div>
    </div>   


