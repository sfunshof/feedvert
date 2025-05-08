<!-- foodApp Component -->
<div class="container mt-3"
    x-data="{ 
            is_printerSettings: $wire.entangle('is_printerSettings'),
            printerName: $wire.entangle('printerName'),
            delPrinter() { 
                /*
                Fxon.Ask.Warning('Do you want to remove the printer' + this.$wire.printerName ,'Confirm Title','Ok', 'Cancel',
                    (result)=>{
                        // callback
                        $wire.removePrinter();    
                    });
                */
                 SnapDialog().warning('Do you want to remove the printer ' + this.$wire.printerName,
                    'Are you sure?', {
                    enableConfirm: true,
                    confirmText: 'Remove Printer',
                    onConfirm: function() {
                         $wire.removePrinter();   
                    },
                    enableCancel: true,
                    onCancel: function() {
                        //console.log('Cancelled');
                    }
                });

            },

        }"  
    >

    @once('menu-styles')
        @include('livewire.fakecomponents.cssblade.menu-styles')  
    @endonce

    <div class="rectangle-container">
        <div    @click.prevent="$wire.showKiosk()"  class="rectangle">Kiosk</div>
        <div    @click.prevent="$wire.showKitchen()"  class="rectangle">Kitchen</div>
        <div    @click.prevent="$wire.showCashier()"   class="rectangle">Cashier</div>
        <div    @click.prevent="$wire.showOrderDisplay()" class="rectangle text-center">Order Status Display</div>
        <div    @click.prevent="$wire.showAdmin()"  class="rectangle">Admin</div>
        <div    @click.prevent="$wire.showCDU()" class="rectangle text-center">Cusomer Display Unit</div>
        <div   @click.prevent="$wire.showPrinterSettings();" class="rectangle text-success">Printer Settings</div>
    </div>
      
    <div class="border-line"></div>
      
    <div class="bottom-wrapper">
        <div class="bottom-border-line"></div>
        <div  @click.prevent="logoutFunction()" class="rectangle bottom-rectangle text-danger">Logout</div>
    </div>
     
    <div>
        <p>
            Welcome back {{ Auth::user()->firstName }}  {{ Auth::user()->lastName }}<br>
            Please select the feature you want on this device
        </p>
        <p>
            Note: If this device will be use for the <strong> Kiosk </strong>, 
            <strong> Kitchen </strong>  or  <strong>Cashier </strong>
             use the Printer Settings to assign the  appropriate printer for this device. 
        </p>    
    </div>


    <!-- Info about printer setting -->
    <div x-data="printerData">       
        <!-- Display Warning no printer -->
        <div x-show="!printerName_stored" class="alert alert-warning" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
             <span class= "fw-bold">Warning: </span>  NO printer is assigned to this device
        </div>
    
        <!-- Display Info Printer name -->
        <div x-show="printerName_stored" class="alert alert-info" role="alert">
            <i class="bi bi-printer-fill me-2"></i>
            The Printer assigned to this device is : <strong x-text="printerName_stored"></strong>
        </div>
        
    </div>

    <div x-show="is_printerSettings"> 
        <div class="col-11">
            <div class="row">
                <!-- Left column (60%) -->
                <div class="col-lg-7 col-md-7 col-sm-12">
                   
                    <div class="card shadow pb-2">
                        <div class="card-header bg-primary text-white">
                            <h5 >{{ $printerTitle }}</h5>
                        </div>
                        
                        <form class="needs-validation" novalidate>
                            <div class="mb-3 px-2 pt-1">
                                <input type="text" class="form-control" wire:model="printerName" placeholder="Printer Name" required>
                                @error('printerName') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        
                            <!-- Conditional rendering of the IP Address field -->
                            @if(!$isSwitchOn)
                                <div class="mb-3 px-2">
                                    <input type="text" 
                                           class="form-control" 
                                           wire:model="ipAddress" 
                                           placeholder="IP address (: : : :)" 
                                           required 
                                           pattern="^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$">
                                    @error('ipAddress') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            @endif
                        
                            <div class="mb-3 form-check form-switch ps-5">
                                <div class="row">
                                    <div class="col-4">
                                        <input class="form-check-input" 
                                            type="checkbox" 
                                            @click="$wire.toggleSwitch"
                                            id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">
                                            {{ $isSwitchOn ? 'Local Connection' : 'Network Connection' }}
                                        </label>
                                    </div>  
                                    <div class="col-8">
                                        @if ($printerStatus==-1) 
                                            <p class="text-danger">
                                                This printer is not accessible
                                            <p>
                                         @endif
                                         @if ($printerStatus==1)   
                                            <p class="text-success">
                                                This printer seems accessible
                                            <p>
                                        @endif        
                                    </div>   
                                </div>

                            </div>
                            <div class="row mx-1 border-top pt-2"> 
                                <div class="col-3"> 
                                    <button type="button"  class="btn btn-outline-secondary w-100" @click="$wire.addPrinter();"
                                    :disabled="$wire.printerName === ''" 
                                    >Clear
                                    </button>
                                </div> 
                                <div class="col-3"> 
                                    <button type= "button"  class="btn btn-outline-secondary w-100"  @click="$wire.testPrinter();"
                                    :disabled="$wire.printerName === ''"  
                                    >Test Print
                                </button> 
                                </div> 
                                <div class="col-3"> 
                                    <button type="button"  class="btn btn-outline-secondary w-100" @click="$wire.savePrinter();"
                                    :disabled="$wire.printerName === ''" 
                                    > Save
                                    </button>
                                </div> 
                                
                                <div class="col-3"> 
                                    <button type="button" class="btn btn-outline-danger w-100" @click="delPrinter();" 
                                    :disabled="$wire.printerName === ''"   
                                    >Remove
                                </button> 
                                </div> 
                            </div>
                        </form>

                    </div>   
       
                </div>
                
                <!-- RHS Printer List -->
                <div class="col-lg-5 col-md-5 col-sm-12">
                  
                                       
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white  d-flex justify-content-between align-items-center">
                            <h5>  List of Printers</h5>
                            <h5> Available to this Device</h5>
                        </div>
                        <div class="scroll-container">
                            <div class="list-group list-group-flush"  x-data="printerData">  
                                @foreach($printerList as $printer)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <span  @click="$wire.showPrinterDetails({{$printer->keyID}},  
                                              '{{$printer->printerName}}', '{{$printer->ipAddress}}' )" 
                                             >
                                             {{ $printer->printerName }}
                                        </span>
                                        <input type="radio" name="printer" value="{{ $printer->printerName }}"
                                        @click="storePrinterDetails({{$printer->keyID}},  
                                        '{{$printer->printerName}}', '{{$printer->ipAddress}}' )" 
                                        {{ $printer->printerName == $printerName_stored ? 'checked' : '' }}
                                        > 
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer text-muted" x-data="printerData">
                            <div class="row">
                                <div class="col-4 d-flex align-items-center">
                                     {{count($printerList)}}  Printer(s)
                                </div>
                                <div class="col-8 text-end">
                                    <button type="button" class="btn btn-outline-warning w-100" @click="clearAssignedPrinter();" >Clear Assigned  Printer</button> 
                               </div>
                            </div>      

                        </div>
                    </div>
                       
                    



                </div>
            </div>
        </div>
       
    </div>
</div>  

@script
    <script>
        let resetPtrinterSwitch=function(){
            const switchElement = document.getElementById('flexSwitchCheckDefault'); 
            if (switchElement) { 
                switchElement.checked = false; 
                // Set the switch to inactive
            }
        };
        $wire.on('savePrinterNotice', () => {
            resetPtrinterSwitch();
            /*
            Fxon.Hint.Info('Printer Saved', {
                callback:function(){
                // callback
                },
                position:'left-bottom',
                animation: 'slide-bottom'
            });
            */
            new Notify({
                status: 'info',
                title: 'Saving ...',
                type:'filled',
                text: 'Printer Name saved on device',
                position:'center'
            });
        });
        $wire.on('resetSwitch', () => {
            resetPtrinterSwitch();
        });
        $wire.on('js_showPrinterDetails', () => {
            let ip= $wire.ipAddress;
            const switchElement = document.getElementById('flexSwitchCheckDefault'); 
            if (switchElement) {
                if (ip){
                    switchElement.checked = false; 
                    $wire.set('isSwitchOn', false);
                }else {
                    switchElement.checked = true; 
                    $wire.set('isSwitchOn', true);
                }    
            }
        });
                
        Alpine.data('printerData', () => ({
            printerName_stored: null,
            
            init() {
                
                // Initial load from store
                this.printerName_stored = null;
                let printerDetails = store.get('printerDetails');
                if (printerDetails){
                    this.printerName_stored=printerDetails.name;
                    // Make printerName_stored available to the Blade template
                    $wire.set('printerName_stored', this.printerName_stored);
                }
                // Set initial Livewire value
                //stored printer name got no business with printer name
               // $wire.set('printerName', this.printerName_stored);
                
                // Set up interval to check store
                setInterval(() => {
                    this.printerName_stored = null;
                    printerDetails = store.get('printerDetails');
                    if (printerDetails){
                        this.printerName_stored=printerDetails.name;
                    }    
                }, 100); // Check every 100ms
                
                
             },

            storePrinterDetails(id, name, ipAddress) {
                // You can handle the printer details here
                let printerDetails={"id":id, "name": name, "ip":ipAddress};
                store.set('printerDetails',  printerDetails);
            },
            clearAssignedPrinter(){
                // Select all radio buttons with the name attribute "printer"
                let radioButtons = document.querySelectorAll('input[type="radio"][name="printer"]');
                // Loop through each radio button and uncheck it
                radioButtons.forEach(function(radioButton) {
                    radioButton.checked = false;
                }); 
               store.remove('printerDetails'); 
             }


        }))
        
    </script>

 @endscript   
