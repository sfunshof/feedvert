 <!-- Show sales Order json is used in component -->
 <div class="list-container pb-3 mt-2">
    {{-- Start of  Sales Item  --}}
    @foreach($cart as $index =>$obj)
        <div class="list-item" 
           @click="$wire.show_itemDetails('{{ $obj->name }}', {{ $obj->qty }}, {{ $obj->mealTypeID }}, {{ $index }})"
           > <!-- Main list -->
           <div class="list-row row">
                <div class="col-8">
                   {{ $obj->name }}
                </div>
                
                <div class="col-2">
                   {{ $obj->qty }}
                </div>
                <div class="col-2">
                       {{ Auth::user()->companySettings->currency }}{{ $obj->price }}
                </div>
           </div>
           <!-- These are the no salt etc. -->
           @php
               $addOns=$obj->addOns3D;
               $options=$obj->options_array3D;
               $mealTypeID=$obj->mealTypeID;
               $meal_items_addOns3D =$obj->meal_items_addOns3D;
           @endphp
           @if(!empty($addOns))
               @foreach($addOns as $item)
                   @if($item['state'] == 0)
                       <div class="list-row row ms-3">
                           No  {{ $item['name'] }}
                       </div>
                   @elseif ($item['state'] == 2)
                       <div class="list-row row ms-3">
                           Extra  {{ $item['name'] }}
                       </div>
                   @endif

               @endforeach
           @endif
           @if(!empty($meal_items_addOns3D)) 
               @foreach ($meal_items_addOns3D as $key => $items)
                   {{-- Check if there are any items with state == 0 or state == 2 --}}
                   @php
                       $filteredItems = collect($items)->whereIn('state', [0, 2]);
                   @endphp

                   @if ($filteredItems->isNotEmpty())
                       {{ ucfirst($key) }} {{-- Display the key (one, two, etc.) --}}
                       <ul>
                           @foreach ($filteredItems as $item)
                               <li>
                                   @if ($item['state'] == 0)
                                       No {{ $item['name'] }}
                                   @elseif ($item['state'] == 2)
                                       Extra {{ $item['name'] }}
                                   @endif
                               </li>
                           @endforeach
                       </ul>
                   @endif
               @endforeach
           @endif

           @if (collect($options)->filter()->isNotEmpty())
               <div class="list-row row ms-3">
                   {{--  
                  <p> Offers:   {{ implode('', collect($options)->filter()->toArray()) }}</p>
                   --}}
                   <p>Offers:<br>
                       @foreach($options as $option)
                           <span>{{ $option }}</span> <br>
                       @endforeach
                   </p>   
               </div>  
           @else

           @endif

        </div>
    @endforeach
    {{--  End of the sales Items  --}}

    {{-- Start of  Collection method --}}
    @if ($isPCtype=="CDU")
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
    @endif

    {{--  End of Collection mehtods --}}

    {{--  Sales Order Footer tax and total --}}
    <div class="position-absolute bottom-0 start-0  bg-light p-3 border-top"
            style="width:90%">
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
        <div class="row">
            <div class="col-10">
                <span class="fs-2">Total Balance</span>
            </div>
            <div class="col-2">
                <span class="fs-3">{{ Auth::user()->companySettings->currency }}{{$totalCost}}</span>
            </div>
        </div>
    </div>
    {{--  End of the  footer --}}

</div>
