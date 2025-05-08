@php
    $index = 1;
    $totalCost = 0;
@endphp

<div class="container my-4">
    @if (!empty($data))
        @foreach ($data as $key => $item)
            @if (is_numeric($key))
                @php
                    $itemTotal = $item['qty'] * (float) $item['price'];
                    $totalCost += $itemTotal;
                @endphp

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Item No {{ $index }}</h5>
                        <p class="card-text">
                            {{ $item['qty'] }} X {{ $item['name'] }} 
                            <span class="float-end fw-bold">{{ $data['currency'] ?? '$' }}{{ number_format($item['price'], 2) }}</span>
                        </p>

                        {{-- Meal Type 3 or 4 --}}
                        @if (in_array($item['mealTypeID'], [3, 4]))
                            @if (!empty($item['offers']))
                                <p class="mb-1"><strong>Offers:</strong></p>
                                <ul class="list-group list-group-flush mb-2">
                                    @foreach ($item['offers'] as $offer)
                                        <li class="list-group-item">{{ $item['qty'] }} X {{ $offer }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            {{-- Customisation for meal_items_addOns3D --}}
                            @if (!empty($item['meal_items_addOns3D']))
                                <p class="mb-1"><strong>Customisation:</strong></p>
                                <ul class="list-group list-group-flush">
                                    @foreach ($item['meal_items_addOns3D'] as $meal => $addons)
                                        <li class="list-group-item">
                                            <strong>{{ $meal }}</strong>
                                            <ul class="ms-3">
                                                @foreach ($addons as $addon)
                                                    <li>{{ $addon }}</li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        @endif

                        {{-- Meal Type 1 or 2 --}}
                        @if (in_array($item['mealTypeID'], [1, 2]))
                            @if (!empty($item['addOns3D']) && count($item['addOns3D']) > 0)
                                <p class="mb-1"><strong>Customisation:</strong></p>
                                <ul class="list-group list-group-flush">
                                    @foreach ($item['addOns3D'] as $addon)
                                        <li class="list-group-item">{{ $addon }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        @endif
                    </div>
                </div>
                @php
                    $index++;
                @endphp
            @endif
        @endforeach

        {{-- Total Amount --}}
        <div class="alert alert-light d-flex justify-content-between align-items-center fw-bold">
            <span>Total Amount:</span>
            <span>{{ $data['currency'] ?? '$' }}{{ number_format($totalCost, 2) }}</span>
        </div>

        {{-- Collection Method --}}
        <div class="alert alert-light">
            <strong>Collection Method:</strong> {{ $data['collection_method'] ?? 'N/A' }}
        </div>
     @endif   
</div>

