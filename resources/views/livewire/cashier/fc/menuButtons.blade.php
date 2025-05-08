   <div 
         x-data="{ 
            is_setPayment:$wire.entangle('is_setPayment'), 
            is_setDiscount:$wire.entangle('is_setDiscount'), 
            eat_in:$wire.entangle('eat_in'), 
            take_away:$wire.entangle('take_away'), 
            logoutFunc : function() {}
         }">
         @if ($is_setPayment)
            <div class="row">
               <div class="col-6"  x-show="!is_paymentSucceed" >
                  <button  @if($is_paymentProcessing) disabled @endif 
                        @click="$wire.toggleDiscount();"
                          class="btn btn-outline-secondary  btn-touch w-100">
                        <span x-text="is_setDiscount ? 'Cancel Discount' : 'Set Discount'"></span>
                  </button>
               </div>
               <div class="col-6" x-show="!is_paymentSucceed" >
                  <button  @if($is_paymentProcessing) disabled @endif   @click="$wire.show_menu();"   
                      class="btn btn-touch btn-warning w-100">
                     Cancel Payments
                  </button>
               </div>
            </div>

         @elseif (!$is_setPayment)
               <div class="row row-cols-2 g-3 pb-1" 
                  x-data="{ empty_cart() {
                     SnapDialog().warning('Do you want to cancel the order?' , 
                           'Are you sure?', {
                        enableConfirm: true,
                        confirmText: 'Void Order',
                        onConfirm: function() {
                            $wire.empty_cart();
                        },
                        enableCancel: true,
                        onCancel: function() {
                           //console.log('Cancelled');
                        }
                     });

                  },
                }"
               >
                  <div class="col">
                     <button @click="empty_cart();"  {{ count($cart) === 0 ? 'disabled' : '' }}    class="btn  btn-touch btn-outline-secondary w-100">
                        Void Order
                     </button>
                  </div>
                  <div class="col">
                     <button  {{ count($cart) === 0 ? 'disabled' : '' }}   @click="$wire.openModalCollectionMethod();"  class="btn btn-touch btn-outline-secondary w-100">
                        Payments
                     </button>
                  </div>
                  <div class="col">
                     <button @click="$wire.getTotalSales();$wire.openModalFunctions();"  class="btn btn-outline-success  btn-touch  w-100">
                        Functions
                     </button>
                  </div>
                  <div class="col">
                     <button   @click="logoutFunction();"   class="btn btn-touch btn-outline-danger w-100">Logout</button>
                  </div>
               </div>  
         @endif
   </div>

   <!-- Bootstrap 5 Modal -->
   @if($showModal)
      <div
         class="modal fade show" 
         tabindex="-1" 
         style="display: block; background-color: rgba(0,0,0,0.5);"
      >
         @php
            $isFullscreen=false;
            if ($modalSize=='modal-fullscreen'){
               $isFullscreen=true;
            }
         @endphp
         <div class="modal-dialog {{ $modalSize }}   ">
            <div class="modal-content">
               <div class="modal-header @if(!$isFullscreen) d-none @endif">
                  <h5 class="modal-title"></h5>
                  <button
                     type="button"
                     class="btn-close"
                     wire:click="closeModal"
                  >
                  </button>
               </div>
    
               <div class="modal-body pt-2">
                  @if($modalFile)
                     @include($modalFile)
                  @endif   
               </div>
            </div>
         </div>
         {{-- 
         <div class="modal-footer">
               <button 
                  type="button" 
                  class="btn btn-secondary" 
                  wire:click="closeModal"
               >
                  Close
               </button>
         </div>
         --}}
      </div>
   @endif