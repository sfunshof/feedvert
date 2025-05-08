  
    <!-- <div class="scrollable-list"> -->
    @foreach ($itemList as $item)
        @php
            $decoded = [];
            $key = null;
            $values = [];
            if (!empty($item->json)) {
                $decoded = json_decode($item->json, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && !empty($decoded)) {
                    $key = array_key_first($decoded);
                    $values = $decoded[$key] ?? [];

                    if (!is_array($values)) {
                        $values = [];
                       // Log::warning('Unexpected structure in JSON field.', ['item_id' => $item->id]);
                    }
                } 
                //else {
                //    Log::error('JSON decoding failed.', [
                //       'error' => json_last_error_msg(),
                //       'item_id' => $item->id
                 //   ]);
                //}
            }
            $json=$item->json;
        @endphp
        <li class="menu-item"  wire:key="{{ $item->id }}"
             @click="$wire.showItemDetails( {{$item->id}}, {{$item->price = $item->price ?? 0 }} ,  
                                         '{{ $item->name}}', '{{$item->logo}}',  @js($json)  ); "

            >
            <img src="{{ asset('custom/app/img/clients/' . $companyID . '/' .   $item->logo) }}" >
            <div class="menu-item-content">
                <span class="menu-item-text-top">{{ $item->name }}</span>
                <span class="menu-item-text-bottom">{{$currency}}{{ $item->price }}</span>
            </div>
        </li>
    @endforeach
        
    <!-- </div> -->
    
