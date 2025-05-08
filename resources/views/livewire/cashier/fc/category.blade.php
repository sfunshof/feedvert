@php
    $d_none="d-none";
@endphp
<div class="row">
    <div class="col-1 d-flex align-items-center ">
         <i  id="prev-btn_cat"  class="bi bi-caret-left-square fs-1 {{ $d_none }} "></i>
    </div>
    
    <div class="col-10 ms-3 w-75">
        <div class="slider-container"  id="table-container_cat">
            <table class="slider-table">
                <tbody>
                    @php
                        $data=$categoryResults;
                        $resultSet = $data->toArray();

                        $names = array_column($resultSet, 'name');
                        $IDs=array_column($resultSet, 'id');
                        $bgColors=array_column($resultSet, 'bgColor');
                        $JSONs=array_column($resultSet, 'json');
                        $mealTypeIDs=array_column($resultSet, 'mealTypeID');  

                        $totalItems = count($names);
                        $rowsPerColumn = 1;
                        $columns = ceil($totalItems / $rowsPerColumn);
                    @endphp

                    @for ($row = 0; $row < $rowsPerColumn; $row++)
                        <tr>
                            @for ($col = 0; $col < $columns; $col++)
                                <td>
                                    @php
                                        $index = $col * $rowsPerColumn + $row;
                                        $id=$IDs[$index];
                                        $json=$JSONs[$index];
                                        $mealTypeID=$mealTypeIDs[$index];
                                        if ($index==0){
                                            $this->firstCategoryData = json_encode(array("id" => $id, "json" => $json, "mealTypeID" =>$mealTypeID, "categoryName" => $names[0]));
                                        }
                                    @endphp
                                    @if ($index < $totalItems)
                                        <div  @click="$wire.get_items_from_category( {{$id }}, '{{json_encode($json)}}',  '{{ $names[$index] }}',    {{$mealTypeID}}) "    class="rectangle  {{$bgColors[$index]}}  d-flex justify-content-center align-items-center">
                                            <span class="fs-5"> {{ $names[$index] }}  </span>
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
       
    <div class="col-1 d-flex align-items-center">
        <i  id="next-btn_cat"  class="bi bi-caret-right-square fs-1 {{ $d_none }} "></i>
    </div>
   

    @script
        <script>
            const tableContainer = document.getElementById('table-container_cat');
            const prevBtn = document.getElementById('prev-btn_cat');
            const nextBtn = document.getElementById('next-btn_cat');
        
            const scrollAmount = 300; // Adjust scroll amount as needed
            const offset=1; // just in case a tiny unit
        
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
             
            //call the category
             (function() { 
                $wire.get_items_from_first_category() 
             })();

            Livewire.on('initCategoryList', () => {
                //Make sure that it delays abit before moving                
                function delayByThreeSeconds(callback) {
                    setTimeout(callback, 100);
                }
                // Example usage:
                delayByThreeSeconds(() => {
                    tableContainer.scrollTo({ left: 0, behavior: 'instant' });
                    $wire.get_items_from_first_category();
                    scrollBtns(btnStatus);
                 });
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
                    nextBtn.style.color = "gray";
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
        </script>
    
    @endscript
</div>   