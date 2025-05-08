    <div class="container">
        @foreach($cart as $index => $itemObj)
            <div class="row border-primary border-bottom mb-2">
                <div class="col-8 mb-0 mt-0"> <!-- Main image -->
                    <div class="rectangle d-flex flex-column justify-content-center align-items-center">
                        <div class="row ">
                            <img src="{{ asset('custom/app/img/clients/' . $companyID . '/' .   $itemObj->logo) }}"  alt="Logo"  class="checkoutImg">
                        </div>
                        <div class="row mb-0" >
                            <p class="text-center">{{ $itemObj->name }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-4"> <!--- Cost --> 
                    <div class="rectangle d-flex flex-column justify-content-start align-items-end">
                        <div class="row">
                            <p class="text-end">{{$currency}}{{ $itemObj->price}}</p>
                        </div>
                        <div class="row">
                            <select class="form-select custom-select no-shadow"  
                                wire:model="cart.{{ $index }}.qty" 
                                wire:change="updateQuantity({{ $index }}, $event.target.value)">
                                @for ($i = 1; $i <= 20; $i++) 
                                    <option value="{{ $i }}">{{ $i }}</option> 
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-0 mb-0"  x-data="{ isVisible: false }" > <!-- This is available for icons and options -->
                    <div class="row align-items-center mt-0 mb-0"  
                             x-show="isVisible" style="display: none;"
                            x-transition:enter="transition ease-out duration-700"
                            x-transition:enter-start="transform -translate-x-full opacity-0"
                            x-transition:enter-end="transform translate-x-0 opacity-100"
                            x-transition:leave="transition ease-in duration-700"
                            x-transition:leave-start="transform translate-x-0 opacity-100"
                            x-transition:leave-end="transform -translate-x-full opacity-0"
                        > <!-- Image and Text -->
                        @if (in_array($itemObj->mealTypeID, [3,4]))
                            @if(isset($itemObj->optionLogos) && is_array($itemObj->optionLogos) && count($itemObj->optionLogos) > 0)
                                <p> Offers: </p>
                                @foreach($itemObj->optionLogos as $key => $logo)
                                    <div class="d-flex align-items-center mb-2">
                                        <img src="{{ asset('custom/app/img/clients/' . $companyID . '/' . $logo) }}"
                                            class="me-2 rounded" width="64" height="64" alt="{{ $logo }}"
                                        >
                                        <p class="mb-0">
                                            @if(isset($itemObj->optionNames[$key]))
                                                 {{ $itemObj->optionNames[$key] }}
                                            @else
                                                <!-- No options available -->
                                            @endif
                                        </p>
                                    </div>
                                @endforeach
                            @else
                                <!-- No logos available for this item -->
                            @endif
                            @foreach ($itemObj->meal_itemsArray as $key => $item)
                                @if (isset($itemObj->meal_addOnArray[$key])) {{-- Check if the item exists in meal_addOnArray --}}
                                    <div class="d-flex align-items-center mb-2">
                                        <p> Customise: </p>
                                        <img src="{{ asset('custom/app/img/clients/' . $companyID . '/' . $itemObj->meal_itemsArrayLogo[$key] ?? '' ) }}" 
                                            class="me-2 rounded" width="64" height="64" alt="{{ $item }}"
                                        >
                                        <p class="mb-0">{{ $item}}</p>
                                    </div>

                                    <ul class="list-unstyled">
                                        @foreach ($itemObj->meal_addOnArray[$key] as $addonKey => $addonValue)
                                            @php
                                                $addonName = $itemObj->meal_addOnArray3D[$key][$addonKey] ?? '';
                                                $addonLogo = $itemObj->meal_addOnArrayLogo[$key][$addonKey] ?? '';
                                                $textClass = ''; // Default class  
                                                // Prepend "No" or "More" based on value
                                                if ($addonValue == 0) {
                                                    $addonName = "No " . $addonName;
                                                    $textClass = 'text-danger'; // Red text
                                                } elseif ($addonValue == 2) {
                                                    $addonName = "Extra " . $addonName;
                                                    $textClass = 'fw-bold text-success'; // Bold green text
                                                }
                                            @endphp
                        
                                            <li class="d-flex align-items-center mb-2">
                                                <img src="{{ asset('custom/app/img/clients/' . $companyID . '/' . $addonLogo) }}" 
                                                    class="me-2 rounded" width="64" height="64" alt="{{ $addonName }}"
                                                >
                                                <p class="mb-0 {{ $textClass }}">{{ $addonName }}</p>
                                            </li>

                                        @endforeach
                                    </ul>
                                @endif
                            @endforeach
                         @endif
                         @if (in_array($itemObj->mealTypeID, [1,2]))
                            <ul class="list-unstyled">
                                @foreach ($itemObj->addOnArray as $key => $value)
                                    @php
                                        $addonName = $itemObj->addOnArray3D[$key] ?? '';
                                        $addonLogo = $itemObj->addOnArrayLogo[$key] ?? '';
                                        $textClass = ''; // Default class
                                        
                                        // Prepend "No" or "More" based on value and assign Bootstrap classes
                                        if ($value == 0) {
                                            $addonName = "No " . $addonName;
                                            $textClass = 'text-danger'; // Red text
                                        } elseif ($value == 2) {
                                            $addonName = "Extra " . $addonName;
                                            $textClass = 'fw-bold text-success'; // Bold green text
                                        }
                                    @endphp

                                    <li class="d-flex align-items-center mb-2">
                                        <img src="{{ asset('custom/app/img/clients/' . $companyID . '/' . $addonLogo) }}" 
                                            class="me-2 rounded" width="64" height="64" alt="{{ $addonName }}"
                                        >
                                        <p class="mb-0 {{ $textClass }}">{{ $addonName }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                    </div>
                    <div class="row  mt-0 mb-0">
                        <!-- This stay here -->

                    </div>


                    <div class="row mt-0">
                        <div class="col-8 d-flex justify-content-between"
                            x-data="{
                                warnClick(index) {
                                    /*
                                    Fxon.Ask.Warning({
                                            title:'Removing Item ...',
                                            message:'Do you want to remove this item?',
                                            callback:(result)=>{
                                                // callback
                                                if (result){
                                                    $wire.removeItem(index)
                                                }    
                                            }
                                    });
                                    */

                                    SnapDialog().warning('Do you want to remove this item',
                                        'Are you sure?', {
                                        enableConfirm: true,
                                        confirmText: 'Remove Item',
                                        onConfirm: function() {
                                            $wire.removeItem(index)
                                        },
                                        enableCancel: true,
                                        onCancel: function() {
                                            //console.log('Cancelled');
                                        }
                                    });
                                }
                            }" 
                            >
                            <i class="bi bi-eye-slash text-primary fs-4" x-show="isVisible" @click="isVisible = false"></i>
                            <!-- Eye icon (show) -->
                            <i class="bi bi-eye text-primary fs-4" x-show="!isVisible" @click="isVisible = true"></i>
                            <!-- Trash icon -->
                            <i class="bi bi-trash3 text-danger fs-4" @click="warnClick({{$index}})"></i>
                        </div>
                    </div> <!-- End of icons --->
                </div>   <!--- End of icons and options -->

            </div>
        @endforeach  
    </div>
    <div class="row">
        <div class="col-8">
            <p class="text-center fw-bold fs-4">Subtotal (inc Taxes) </p>
        </div>  
        <div class="col-4 text-end">
            <p class="text-center fw-bold fs-4">{{$currency}}{{ $totalCost}} </p>
        </div>    
    </div>
   
