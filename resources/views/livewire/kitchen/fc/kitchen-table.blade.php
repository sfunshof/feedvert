
<div class="bg-light" style="height: 100%; overflow: hidden; position:relative">
    <div id="tableContainer" class="w-100 h-100 overflow-x-auto">
        <div class="table-wrapper d-flex  h-100">
            @php
                if (is_array($columns) || $columns instanceof Countable) {
                    $count = count($columns);
                } else {
                    $count = 0; // Default or fallback value
                }
            @endphp
            @if ($count > 0)
                @foreach($columns as $parentIndex =>$column)
                    @php
                        $timestamp = \Carbon\Carbon::parse($column->date)->timestamp;
                        $order_Json = json_decode($column->order_json, true);
                        $keyID=$column->keyID;
                        $isReady=$column->isReady;
                        $orderNo=$column->orderNo;
                     @endphp
                    <div class="table-column" wire:key="column-{{ $column->keyID }}"
                        x-data="{
                            startTime: {{ $timestamp }},
                            now: Math.floor(Date.now() / 1000),
                            KDSwarning: $wire.entangle('KDSwarning'),
                            KDSlate: $wire.entangle('KDSlate'),
                            displayStatus: {{$displayStatus}},
                            bgColor() {
                                if (this.displayStatus==3) return 'bg-success text-light';
                                if (this.displayStatus==2) return 'bg-secondary text-white';
                                let elapsed = this.now - this.startTime;
                                if (elapsed >= this.KDSlate) return 'bg-danger text-white';  // After 8 seconds → Red
                                if (elapsed >= this.KDSwarning) return 'bg-warning text-dark'; // After 5 seconds → Yellow
                                return 'bg-primary text-white'; // Default: Light (Bootstrap gray)
                            },
                            redoOrder(keyID,orderNo){
                                SnapDialog().warning('Please confrim Re-doing  this Order No: ' +  orderNo, 
                                                'Are you sure?', {
                                    enableConfirm: true,
                                    confirmText: 'Redo Order',
                                    onConfirm: function() {
                                       $wire.prepareOrder(keyID);
                                    },
                                    enableCancel: true,
                                    onCancel: function() {
                                        //console.log('Cancelled');
                                    }
                                });
                            },
                            playOrder(keyID,orderNo){
                                SnapDialog().warning('Please confrim preparing this Order No: ' +  orderNo, 
                                                'Are you sure?', {
                                    enableConfirm: true,
                                    confirmText: 'Prepare Order',
                                    onConfirm: function() {
                                       $wire.prepareOrder(keyID);
                                    },
                                    enableCancel: true,
                                    onCancel: function() {
                                        //console.log('Cancelled');
                                    }
                                });
                            },
                            printKitchenOrder(keyID,orderNo){
                                let printerDetails = store.get('printerDetails');
                                if (printerDetails){
                                $wire.set('printerIPaddress', printerDetails.ip);
                                $wire.complete_printKitchenReceipt(keyID, orderNo);
                                }else{
                                    new Notify({
                                        status: 'error',
                                        title: 'Stating',
                                        type:'filled',
                                        text: 'No Printer is setup on this device',
                                        position:'center'
                                    })
                                }
                            },
                        }"
                        @click="$wire.handleClick({{ $keyID }},{{$isReady}}, {{ $orderNo}} )"
                    >
                        <div class="cell top  rounded transition container" x-data="kitchenData"
                            x-init="setInterval(() => now = Math.floor(Date.now() / 1000), 1000)"
                            :class="bgColor"
                        >
                            <div class="text-center fw-bold">Order No: {{ $column->orderNo }}</div>
                            <div class="d-flex justify-content-between">
                                <span  class="text-start" x-text="Math.floor((now - startTime) / 60).toString().padStart(2, '0') + ':' + ((now - startTime) % 60).toString().padStart(2, '0')"></span>
                                <span class="text-end fw-bold"> {{ $column->collection_method }}</span>
                            </div>
                        </div>
                        <div class="cell middle scroll-container">

                            {{--  Middle One DO NOT TOUCH  --}}
                            @if (is_array($order_Json)) {{-- Check if the decoded JSON is actually an array --}}
                               {{--  This is for printing orders in the middle row --}}
                               @foreach ($order_Json as $index => $item)
                                    @if (is_array($item)) {{-- Ensure $item is an array before accessing offsets --}}
                                        <div class="mb-4 p-3 border rounded">
                                            <h5 class="fw-bold">Item No {{ (int) $index + 1 }}</h5>
                                            <p class="mb-1">{{ $item['qty'] }} X {{ $item['name'] }}</p>
                                            @if (in_array($item['mealTypeID'], [3, 4]))
                                                @if (!empty($item['offers']))
                                                    <p class="fw-bold">Offers:</p>
                                                    <ul class="list-unstyled">
                                                        @foreach ($item['offers'] as $offer)
                                                            <li>{{ $item['qty'] }} X {{ $offer }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                                @if (!empty($item['meal_items_addOns3D']))
                                                    <p class="fw-bold">Customisation:</p>
                                                    <ul class="list-unstyled">
                                                        @foreach ($item['meal_items_addOns3D'] as $meal => $customisations)
                                                            <li><strong>{{ $meal }}</strong>
                                                                <ul>
                                                                    @foreach ($customisations as $customisation)
                                                                        <li>{{ $customisation }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            @endif
                                            @if (in_array($item['mealTypeID'], [1, 2]))
                                                @if (!empty($item['addOns3D']))
                                                    <p class="fw-bold">Customisation:</p>
                                                    <ul class="list-unstyled">
                                                        @foreach ($item['addOns3D'] as $addOn)
                                                            <li>{{ $addOn }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                            {{-- End of DO NOT TOUCH --}}
                       </div>
                        <div class="cell bottom  d-flex justify-content-between align-items-center p-3 border"
                            x-data="kitchenData"
                        >
                            @php
                                $statuslabel="Complete";
                                if ($isReady=='1'){
                                    $statuslabel="Waiting";
                                }else if ($isReady=='2'){
                                    $statuslabel="<span class='fw-bold fs-4 text-success'> Preparing </span>";
                                }else if ($isReady=='3'){
                                    $statuslabel="<span class='fw-bold fs-4 text-success'> Completed </span>";
                                }else if ($isReady=='5'){
                                    $statuslabel="On Hold";
                                }
                            @endphp
                            @if ($isReady==3)
                                <i @click.stop="printKitchenOrder({{ $keyID }}, {{ $orderNo }} )" class="fas fa-print text-primary fa-3x" style="cursor: pointer;"></i>
                            @endif
                            <p>{!! $statuslabel !!} </p>
                            @if (($isReady==1)||($isReady==2))
                                <i @click.stop="holdOrder({{ $keyID }}, {{ $orderNo }} )" class="fas fa-pause-circle text-warning fa-2x" style="cursor: pointer;"></i>
                            @elseif ($isReady==3)
                            <i  @click.stop="redoOrder({{ $keyID }}, {{ $orderNo }})"  class="fas fa-redo text-success fa-2x" style="cursor: pointer;"></i>
                            @elseif ($isReady==5)
                            <i  @click.stop="playOrder({{ $keyID }}, {{ $orderNo }})"  class="fas fa-play-circle text-success fa-2x" style="cursor: pointer;"></i>
                            @endif
                        </div>

                    </div>
                @endforeach
            @else
               @for ($i=0; $i<=3; $i++)
                    <div class="table-column">
                        <div class="cell middle scroll-container pt-5 ">
                            <span class="fw-bold fs-3 pt-5">
                                Waiting for Orders
                            </span>
                        </div>
                    </div>
               @endfor
            @endif
        </div>
    </div>
</div>
<!-- Use wire:poll to periodically call the pollForUpdates method -->
<div wire:poll.5s="checkForUpdates"></div>

@script
    <script>
        Alpine.data('kitchenData', () => ({
            holdOrder(keyID, orderNo){
                SnapDialog().warning('Please confrim putting this orderNo: ' +  orderNo + " On Hold ", 
                              'Are you sure?', {
                enableConfirm: true,
                confirmText: 'Put on Hold',
                onConfirm: function() {
                    $wire.holdOrder(keyID);
                },
                enableCancel: true,
                onCancel: function() {
                    //console.log('Cancelled');
                }
            });

            }
        }))

        $wire.on('startPrepNotification', () => {
             new Notify({
                status: 'info',
                title: 'Starting',
                type:'filled',
                text: 'Preparation Starts',
                position:'center'
            })
        });

        $wire.on('checkBeforeComplete',  (event) => {
            SnapDialog().warning('Please confrim the completion of this order No: ' +  event.orderNo, 
                              'Are you sure?', {
                enableConfirm: true,
                confirmText: 'Complete',
                onConfirm: function() {
                    $wire.completeOrder(event.keyID);
                },
                enableCancel: true,
                onCancel: function() {
                    //console.log('Cancelled');
                }
            });
        });
        Livewire.on('printerErrorMsg', (event) => {
            new Notify({
                status: 'error',
                title: 'Starting',
                type:'filled',
                text: 'Error: ' + event.errorMsg,
                position:'center'
            })
        });


        const tableContainer = document.getElementById('tableContainer');

        // Function to detect scroll direction
        function getScrollDirection(event) {
            const deltaX = Math.abs(event.deltaX);
            const deltaY = Math.abs(event.deltaY);

            if (deltaX > deltaY) {
                return 'horizontal';
            } else {
                return 'vertical';
            }
        }

        // Add event listener to handle scroll events
        tableContainer.addEventListener('wheel', (event) => {
            const direction = getScrollDirection(event);

            // If the scroll direction is horizontal, prevent default and scroll the container
            if (direction === 'horizontal') {
                event.preventDefault();
                tableContainer.scrollBy({
                    left: -event.deltaX, // Invert deltaX to fix the direction
                    behavior: 'smooth'
                });
            }
        });

        // Add touch event listeners for mobile devices
        let startX, startY;

        tableContainer.addEventListener('touchstart', (event) => {
            const touch = event.touches[0];
            startX = touch.clientX;
            startY = touch.clientY;
        });

        tableContainer.addEventListener('touchmove', (event) => {
            if (!startX || !startY) return;

            const touch = event.touches[0];
            const deltaX = touch.clientX - startX;
            const deltaY = touch.clientY - startY;

            // Determine scroll direction based on touch movement
            if (Math.abs(deltaX) > Math.abs(deltaY)) {
                // Horizontal scroll
                event.preventDefault();
                tableContainer.scrollBy({
                    left: -deltaX, // Invert deltaX to fix the direction
                    behavior: 'smooth'
                });
            } else {
                // Vertical scroll (let it propagate to the scroll-container)
                return;
            }
        });

        tableContainer.addEventListener('touchend', () => {
            startX = null;
            startY = null;
        });

   </script>
@endscript