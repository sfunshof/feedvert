@php
    $d_none="d-none";
@endphp
<div class="row">
    <!-- Caption bar -->
    <div class="col-12 d-flex align-items-center" 
        style="background-color: #0078d7; color: #fff; padding: 5px 10px; border-bottom: 1px solid #005a9e;">
        
        <!-- Window Icon 
        <i class="bi bi-window" style="font-size: 16px; margin-right: 10px;"></i>
        -->
        <!-- Window Control Buttons -->
        <div class="d-flex">
            <button 
                @click="$wire.menu_backFunc();"
                x-data="{ is_backVisible: $wire.entangle('is_backVisible') }" 
                :class="{ 'hidden': !is_backVisible }"
                    class="btn  btn-outline-success mb-2 mt-1" style="background: transparent; color: #fff;" title="Back">
                        Back
            </button>
            <!--
            <button class="btn btn-sm" style="background: transparent; color: #fff;" title="Minimize">
                <i class="bi bi-dash"></i>
            </button>
            <button class="btn btn-sm" style="background: transparent; color: #fff;" title="Maximize">
                <i class="bi bi-square"></i>
            </button>
            <button class="btn btn-sm" style="background: transparent; color: #fff;" title="Close">
                <i class="bi bi-x"></i>
            </button>
            -->
        </div>
        <!-- Spacer to push the title to the center -->
        <div class="flex-grow-1 d-flex justify-content-center">
            <span style="font-size: 14px; font-weight: bold;">{{$caption}}</span>
        </div>
    </div>
</div>
   
 
<div class="row h-75 ">   
    <div class=" {{ 'col-1 d-flex align-items-center ' . ($resultValue ? '' : 'd-none') }}">
        <i  id="prev-btn"  class="bi bi-caret-left-square fs-1 {{ $d_none }} "></i>
    </div>


    <div class="col-10  ms-4  w-75">
        <div class="slider-container"  id="table-container1">
            <table class="slider-table">
                <tbody>
                    @php
                        //$data=$resultValue;
                        //$resultSet = $data->toArray();
                        $resultSet=[];
                        if ($resultValue){
                            $resultSet = $resultValue?->toArray() ?? [];
                        }
                        $names = array_column($resultSet, 'name');
                        $IDs=array_column($resultSet, 'id');
                        $bgColors=array_column($resultSet, 'bgColor');
                        $JSONs=array_column($resultSet, 'json');
                        $prices=array_column($resultSet, 'price');

                        $totalItems = count($names);
                        $rowsPerColumn = 3;
                        $columns = ceil($totalItems / $rowsPerColumn);
                    @endphp

                    @for ($row = 0; $row < $rowsPerColumn; $row++)
                        <tr>
                            @for ($col = 0; $col < $columns; $col++)
                                <td>
                                    @php
                                        $index = $col * $rowsPerColumn + $row;
                                        //** Do not include the array[index] here
                                        //$id=$IDs[$index];
                                        //$json=$JSONs[$index];
                                        $json=json_encode($JSONs[$index] ?? []);
                                        $price = $prices[$index] ?? null;
                                         $priceCur='';
                                        if ($price){
                                            $priceCur=number_format($price,2);
                                            $priceCur=Auth::user()->companySettings->currency . $priceCur;
                                        }
                                    @endphp
                                    @if ($index < $totalItems)
                                        <div  @click="$wire.get_items_from_menu_or_meal( {{$IDs[$index] }},  '{{ $names[$index] }}', '{{$price}}' )"     
                                             class="rectangle  {{$bgColors[$index]}} 
                                             d-flex flex-column justify-content-between align-items-center">
                                           
                                                <div class="flex-grow-1 d-flex align-items-center">
                                                    <span class="fs-5"> {{ $names[$index] }}  </span>
                                                </div>
                                                @if (!$is_options)
                                                    <p class="mb-3"> {{ $priceCur}} </p>
                                                 @endif   
                                                  
                                        </div>
                                     @endif
                                </td>
                            @endfor
                        </tr>
                    @endfor

                </tbody>
            </table>
        </div>
    </div>
    
    <div  class=" {{ 'col-1 d-flex align-items-center ' . ($resultValue ? '' : 'd-none') }}">
        <i  id="next-btn"  class="bi bi-caret-right-square fs-1 {{ $d_none }} "></i>
    </div>
    
    
    @script
        <script>
            const tableContainer = document.getElementById('table-container1');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
        
            const scrollAmount = 300; // Adjust scroll amount as needed
            const offset=1;
            prevBtn.addEventListener('click', () => {
                tableContainer.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
                scrollBtns(btnStatus);
            });
        
            nextBtn.addEventListener('click', () => {
                tableContainer.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                scrollBtns(btnStatus);
            });
            // Touch and swipe functionality
            let startX;
            let isDown = false;

            tableContainer.addEventListener('touchstart', (e) => {
                isDown = true;
                startX = e.touches[0].clientX;
            });

            tableContainer.addEventListener('touchmove', (e) => {
                if (!isDown) return;
                const touch = e.touches[0];
                const deltaX = startX - touch.clientX;
                tableContainer.scrollBy({ left: deltaX, behavior: 'auto' });
                startX = touch.clientX;
            });

            tableContainer.addEventListener('touchend', () => {
                isDown = false;
            });
            function isAtExtremeLeft() {
                // Determines if the table container is at the extreme left
                return tableContainer.scrollLeft === 0;
            }
            function isAtExtremeRight() {
                // Determines if the table container is at the extreme right
                return tableContainer.scrollLeft + tableContainer.clientWidth +offset >= tableContainer.scrollWidth;
            }
            function canScrollLeft() {
                // Determines if the table container can still move to the left
                return tableContainer.scrollLeft > 0;
            }
            function canScrollRight() {
                // Determines if the table container can still move to the right
                return tableContainer.scrollLeft + tableContainer.clientWidth + offset < tableContainer.scrollWidth;
            }
            //We shall delay the status a bit. It msust render before the status is accessed
            function btnStatus(){
                if (isAtExtremeLeft()){
                    prevBtn.disabled = true;
                    prevBtn.style.opacity = "0.5"; 
                    prevBtn.style.color = "gray";
                }else if (canScrollLeft()){
                   prevBtn.disabled = false;
                   prevBtn.style.opacity = "1"; 
                   prevBtn.style.color = "";
                }
                if (isAtExtremeRight()){
                    nextBtn.disabled=true; 
                    nextBtn.style.opacity = "0.5"; 
                    nextBtn.style.color = "gray"
                }else if (canScrollRight()){
                    nextBtn.disabled=false;
                    nextBtn.style.opacity = "1"; 
                    nextBtn.style.color = "";
                } 
            }
           function  scrollBtns(callback) {
                setTimeout(callback, 1000); // 3000 milliseconds = 3 seconds
            }
            scrollBtns(btnStatus);

            Livewire.on('scrollBtnStatus', () => {
                scrollBtns(btnStatus);
            });

        </script>
    
    @endscript
</div>   

   
    <div  class="row" x-data="{ 
            is_options: $wire.entangle('is_options'), 
        }" 
        >
        <div x-show ="is_options">
            @if ($options_results && $options_results->isNotEmpty())
                <div class="container">
                    <div class="row mt-4">
                        @foreach ($options_results as $index => $result)
                            <div class="col-6 mb-3">
                                <!-- This is the option -->
                                <button class="btn btn-touch btn-outline-secondary w-100" 
                                         @click="$wire.show_mealOptions({{$result->id }}, {{ $index}}, '{{$result->selectOption}}' )" 
                                     >
                                     <span class="text-success"> Offers:</span> &nbsp; {{$result->selectOption }}
                                </button>
                            </div>
            
                            @if (($index + 1) % 2 == 0 && !$loop->last)
                                </div><div class="row">
                            @endif
                        @endforeach
                    </div>
                </div>
            @else
               <!--  <p>No records found.</p> -->
            @endif
            
        </div>

    </div>   
     
    <div 
        x-data="{ 
            is_optionsComplete: $wire.entangle('is_optionsComplete'), 
        }"
        >
        <div x-show="is_optionsComplete"   class="w-100  my-5">
            <div class="row">
                <div class="col-7 ">
                    @include('livewire.cashier.inc.counter')
                </div>    

                <div class="col-2">
                    <button @click="$wire.customise_meal();"  class="btn btn-touch btn-outline-secondary w-100" 
                    >
                    Customise
                    </button>
                </div>    
                <div class="col-3">
                    <button  @click="$wire.close_mealDetails();" class="btn btn-touch btn-primary w-100" 
                    >
                     Update Item
                    </button>
                </div>    
                
            </div> 
        </div>    
    </div>
