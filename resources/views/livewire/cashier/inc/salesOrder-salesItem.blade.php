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