@if($subMenuResults && $subMenuResults->isNotEmpty())
    <div class="kiosk-height-gap"></div>
    <h2 class="text-center">{{ $menuName }} </h2>
    <div class="table-container" x-data="{isPriced:$wire.entangle('isPriced')}" >
        <table style="border-collapse: collapse; width: 100%;">
            <tbody>
                @foreach($subMenuResults->chunk(3) as $chunk)
                <tr style="margin:0;padding:0;">
                    <td style="padding:0;margin:0;" >
                        <div class="logo-container">
                            @foreach($chunk as $result)
                                @php
                                    $json=$result->json;
                                    //$json=  addslashes(json_encode($json))

                                @endphp 
                                <div class="logo-slot">
                                    <div class="logo-wrapper border border-secondary rounded"
                                        @click="isPriced ? $wire.showItemDetails({{ $result->id}},  
                                        {{ $result->price = $result->price ?? 0 }} ,  
                                         '{{$result->name}}', '{{$result->logo}}',  @js($json) ) :
                                          $wire.showItemsFromSubMenu({{ $result->id }}, 
                                         '{{$result->name}}',{{$result->price}},'{{$result->logo}}', @js($json) 
                                        )" 
                                     >
                                        
                                        <div class="logo-image">
                                            <img src="{{ asset('custom/app/img/clients/' . Auth::user()->companyID . '/' . $result->logo) }}" 
                                            alt="Client Image">
                                        </div>
                                        
                                     
                                        <div class="logo-text">
                                            <p style="margin: 0 0 5px 0;">{{ $result->name }}</p>
                                            @if ($result->price) 
                                                @php
                                                    $price=$result->price;
                                                    if ($this->isMealOption){
                                                        $price="0.00";
                                                    }
                                                @endphp
                                                @if (!$isMealOption)
                                                     <p style="margin: 0;">  {{Auth::user()->companySettings->currency}}{{$price}}</p>
                                                @endif
                                             @endif
                                        </div>
                                    </div>   
                                </div>
                            @endforeach
        
                            @for($i = $chunk->count(); $i < 3; $i++)
                                <div class="logo-slot"></div>
                            @endfor
                        </div>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>    
@endif