    <!-- <div class="scrollable-list"> -->
    @foreach ($subMenuList as $item)
        @php
            $decoded = json_decode($item->json, true);
            $key = array_key_first($decoded);
            $values = $decoded[$key];
        @endphp
        <li class="menu-item"  wire:key="{{ $item->id }}" 
            @click.prevent="$wire.get_mealsFromMenu('{{ $key }}', {{ json_encode($values) }}, '{{$item->name}}',  1 ,  2 );scrollToTop();" >   
            <img src="{{ asset('custom/app/img/clients/' . $companyID . '/' .   $item->logo) }}" >
            <div class="menu-item-content">
                <span class="">{{ $item->name }}</span>
            </div>
        </li>
    @endforeach
