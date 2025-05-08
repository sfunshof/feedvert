
    <div class="row">
        @php $count = 0; @endphp
        @foreach($meal_itemsArray as $item)
            @if($count == 2)
                </div><div class="row">
            @endif
            <div class="col-6">
                <div class="row justify-content-center">
                    <div class="col-auto">
                        <div class="card mb-0" style="width: 120px;" @click="$wire.show_customisation( '{{$item->name}}' , {{ $item->id }} )">
                            <img 
                               src="{{ asset('custom/app/img/clients/' . $companyID . '/' . $item->logo) }}"
                               class="card-img-top" style="height: 120px; object-fit: cover;" alt="Description"
                            >
                            <div class="card-body text-center">
                                <p class="card-text">{{ $item->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @php $count++; @endphp
            @if($count == 4)
                @break
            @endif
        @endforeach
        <p class="text-center">{{ $customise_item}} </p>
    </div>
    @include('livewire.mobile.fc.inc.details_scrollList')
    