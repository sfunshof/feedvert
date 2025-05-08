    <div class="d-flex flex-column h-100 p-5">
        <header class="fixed-top mb-5 pt-3">
            <div class="d-flex justify-content-between align-items-center mt-2">
                @if (in_array($mealTypeID, [1, 2]))
                    <button class="btn btn-secondary invisible">Back</button>
                    <h3 class="mb-0 mx-auto">Customise</h3>
                    <span class="me-2 w-25  ps-3">
                        <button @click="$wire.back_customise()" class="btn btn-outline-secondary w-100 kiosk-button">
                            Back
                        </button>
                    </span>
                @elseif (in_array($mealTypeID, [3, 4]))
                    <button class="btn btn-secondary invisible">Back</button>
                    <h3 class="mb-0">Customise</h3>
                    <span class="me-2 w-25 ps-3"> 
                        <button @click="$wire.back_customise()" class="btn btn-outline-secondary w-100 kiosk-button">
                            Back
                        </button> 
                    </span>
                @endif
            </div>
        </header>

      
        <main class="flex-grow-1 customise-wrapper mt-5 ps-3 pt-3 mb-3">
            <div class="row text-center">
                <!-- Container for both image and text -->
                <div class="col d-flex justify-content-center align-items-end">
                    <!-- Image on the left side -->
                     <img src="{{ asset('custom/app/img/clients/' . Auth::user()->companyID . '/' . $customise_logo) }}" class="img-fluid" alt="Card Image">
                     <!-- Text on the right side -->
                    <p class="card-text menu-item ms-2">{{$customise_name}}</p>
                </div>
            </div>
            <div class="row text-center pt-5">
                <p> This item comes up with : </p>
            </div>    
            <div class="table-container menu-group" style="height:70vh">
                <table style="border-collapse: collapse; width: 100%;" >
                    @foreach ($customise_addOnQuery as $index => $addOn)
                        @php
                            $count = $addOnJson_customise[$index] ?? 0;
                            $fontClass = '';
                            $prependText = '';
                    
                            if ($count == 0) {
                                $fontClass = 'text-danger';
                                $prependText = 'No ';
                            } elseif ($count == 2) {
                                $fontClass = 'text-success fw-bold';
                                $prependText = 'Extra ';
                            }
                        @endphp
            
                        <tr>
                            <td>
                                <div class="row align-items-end" >
                                    <div class="col-4 text-center">
                                        <img src="{{ asset('custom/app/img/clients/' . Auth::user()->companyID . '/' . $addOn->logo) }}" 
                                        class="customise-img" alt="Card Image">
                                    </div>
                                    <div class="col-4 text-start">
                                        <p class="mb-0 {{ $fontClass }}">{{ $prependText }}{{ $addOn->name }}</p>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="counter-container">
                                            <button   class="counter-btn counter-minus"
                                                @click="$wire.dec_customise({{ $index }})" 
                                                    @disabled(($addOnJson_customise[$index] ?? 0) <= 0)
                                                >
                                                -
                                           </button>
                                            <div class="counter-value border border-secondary">{{ $addOnJson_customise[$index] ?? '' }}</div>
                                            <button class="counter-btn counter-plus" 
                                                @click="$wire.inc_customise({{ $index }})" 
                                                    @disabled(($addOnJson_customise[$index] ?? 0) >= 2)
                                                 >
                                                +
                                            </button>
                                        </div>
                                    </div>
                                </div>   
                            </td>
                        </tr>
                     @endforeach         
                </table>
            </div>       

             
        </main>

        <footer class="fixed-bottom bg-light py-3">
            <div class="row">
                <div class="col-6">
                    <button  @click="$wire.cancel_customise()" class="btn btn-outline-secondary w-100 kiosk-button">Cancel Changes</button>
                </div>
                <div class="col-6">
                    <button  @click="$wire.save_customise()"   class="btn btn-warning w-100 kiosk-button">Save  Changes</button>
                </div>   
            </div>   
        </footer>
    </div>