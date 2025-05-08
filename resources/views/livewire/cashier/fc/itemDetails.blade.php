    <div class="position-relative" style="height: 100vh;"
        x-data="{ 
            is_customiseMeal: $wire.entangle('is_customiseMeal'), 
        }"
       >
        <!-- Caption bar -->
        <div class="row w-80">
            <div class="col-12 d-flex align-items-center" 
                style="background-color: #0078d7; color: #fff; padding: 5px 10px; border-bottom: 1px solid #005a9e;">
                
                <!-- Window Icon 
                <i class="bi bi-window" style="font-size: 16px; margin-right: 10px;"></i>
                -->
                <!-- Spacer to push the title to the center -->
                <div class="flex-grow-1 d-flex justify-content-center">
                    <span style="font-size: 14px; font-weight: bold;">Item Details  for {{ $details_name }}</span>
                </div>
                
                <div class="d-flex">
                    <button 
                        x-data="{ isVisible: false }" 
                        :class="{ 'hidden': !isVisible }"
                            class="btn  btn-outline-success mb-2 mt-1" style="background: transparent; color: #fff;" title="Back">
                           X
                    </button>
                </div>

            </div>
        </div>
       
        
        <div class="row w-100">        
            <div class="h-90 mx-auto">
                <div class="row h-90">
                    <div class="col-10 h-75">
                        <!-- Main content goes here -->
                        <div class="list-container pb-3 mt-2 w-80">
                            @php
                                //dump($addOns);
                            @endphp
                               
                            @foreach($addOns as $index => $addOn)
                                <div  class="list-item" wire:key="addon-{{ $addOn['id'] }}">
                                    <div class="list-row row">
                                        <div class="col-7">
                                            <span class="{{ $addOn['state'] == 0 ? 'text-decoration-line-through text-danger' : '' }} 
                                                {{ $addOn['state'] == 2 ? 'fw-bold' : '' }}">
                                                {{ $addOn['name'] }}
                                            </span>
                                        </div>
                                        <div class="col-5">
                                            {{--  
                                            <button class="w-75 btn   btn-touch    {{ $addOn['state'] == 0 ? 'btn-outline-danger' : 'btn-outline-secondary' }}"
                                                    wire:click="updateAddOn({{ $index }}, {{ $addOn['state'] == 0 ? 1 : 0 }} )">
                                                {{ $addOn['state'] == 0 ? 'Reset' : 'Remove' }}
                                            </button>
                                            --}}
                                            @php
                                                $state= $addOn['state'] ;
                                            @endphp
                                                   
                                            <div class="btn-group w-100" role="group">
                                                <!-- Remove Button -->
                                                <button class="btn  btn-touch  {{ $state == 0 ? 'btn-danger' : 'btn-outline-danger' }} w-33"
                                                    wire:click="updateAddOn({{ $index }},0)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <!-- Reset Button -->
                                                <button class="btn btn-touch  {{ $state == 1 ? 'btn-secondary' : 'btn-outline-secondary' }} w-33"
                                                wire:click="updateAddOn({{ $index }},1)">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>

                                                <!-- Increase Button -->
                                                <button class="btn  btn-touch  {{ $state == 2 ? 'btn-success' : 'btn-outline-success' }} w-33"
                                                   wire:click="updateAddOn({{ $index }},2)">
                                                    <i class="fas fa-plus-circle"></i>
                                                </button>
                                            </div>   
                                            
                                            
                                       
                                        

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                           
                        </div>
                    </div>
                    <div class="col-2 m-0 p-0" x-show="is_customiseMeal">
                           <!-- Customise -->                    
                                    
                            @foreach($meal_items as $id => $name)
                                <div class="row mb-1 p-0">
                                    <div class="col-12">
                                        <div  @click="$wire.get_addOns_from_meal_item({{ $id }}, '{{ $name}}' );"   class="square-box bg-primary d-flex justify-content-center align-items-center" 
                                           style="aspect-ratio: 4 / 3; width:100%;">
                                            <span class="text-white "> {{ $name }} </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        

                    </div>   
                </div>

            </div>
        </div>
        
           
        <div  class="footer  fixed-bottom p-3 w-50 mb-3 d-flex flex-column justify-content-between" 
             style="height:30%">
    
            <!-- Counter and delete button container -->
            @include('livewire.cashier.inc.counter')
        
            <!-- Button forced to the bottom -->
            <button @click="$wire.close_itemDetails();" type="button" 
                    class="btn btn-touch btn-outline-secondary align-self-center mt-auto">
                Update Item 
            </button>
        
        </div>
    
        
        
        

    </div>
    