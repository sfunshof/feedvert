   <!-- <div class="scrollable-list"> -->
    {{--  This is the main menu that lists the folders --}}
    
        @foreach ($items as $index => $item)
            @php
                $decoded = json_decode($item->json, true);
                $key = array_key_first($decoded);
                $values = $decoded[$key];
            @endphp
            <li class="menu-item"  wire:key="{{ $item->id }}"   
                @click.prevent="$wire.get_mealsFromMenu('{{ $key }}', {{ json_encode($values) }}, '{{$item->name}}',  {{$item->mealTypeID}} ,  1 ); scrollToTop();">
                <img src="{{ asset('custom/app/img/clients/' .  $companyID  . '/' .   $item->logo) }}" >
            <span class="menu-item-text"> {{ $item->name }}</span>
            </li>
        @endforeach
    
    <!-- </div> -->
   