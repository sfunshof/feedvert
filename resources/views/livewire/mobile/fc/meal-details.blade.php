
@include('livewire.mobile.fc.inc.details_image')
<div  
    x-data="{ 
               isMealCounter:$wire.entangle('isMealCounter')
            }"      
    x-show="isMealCounter" > 
    @include('livewire.mobile.fc.inc.details_customise')
    @include('livewire.mobile.fc.inc.details_counter')
</div>
@if (count($mealOptions) > 0)
    <p> This meal comes with the following offers: </p>
@endif    
 <ul class="list-group">

    @foreach ($mealOptions as $index => $mealOption)
    {{--  --}}
        <li class="list-group-item">
            <div class="row" x-data="{ mealOptionLogos: $wire.entangle('mealOption_logos') }">
                <div class="col-5 text-start">
                    @if(count($mealOption_logos) > 0 && isset($mealOption_logos[$index]))
                        <div x-show="mealOptionLogos.length > 0 && mealOptionLogos[{{ $index }}]">
                            <img 
                                src="{{ asset('custom/app/img/clients/' . $companyID . '/' . $mealOption_logos[$index]) }}" 
                                alt="Meal Option Logo" 
                                class="img-fluid" 
                                style="width: 64px; height: 64px;"
                            >
                            <p class="mt-2 mb-0">{{ $mealOption_names[$index] }}</p>
                        </div>
                    @endif
                </div>
                <div class="col-7" wire:key="{{ $mealOption->id }}" >
                    <button class="btn btn-outline-secondary w-100"
                            @click="$wire.display_meal_option({{$mealOption->id}}, '{{$mealOption->selectOption}}', {{ $index }} )"
                            x-data="{ hasLogos: @js(!empty($mealOption_logos[$index])) }"
                            x-init="$watch('$wire.mealOption_logos', (value) => hasLogos = value && value[{{ $index }}] !== undefined && value[{{ $index }}].length > 0)"
                       >
                        <span x-show="!hasLogos">{{ $mealOption->selectOption }}</span>
                        <span x-show="hasLogos">Change</span>
                    </button>
                </div>
            </div>
        </li>
    @endforeach
        
 </ul>
