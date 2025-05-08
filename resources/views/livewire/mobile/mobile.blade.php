<div 
    x-data="{ 
        scrollToTop() {
            window.scrollTo({
                top: 0, 
                behavior: 'smooth' 
            });
        }
    }"  
>
    <div x-data="{ 
                  isSplash: $wire.entangle('isSplash'),  isMenu: $wire.entangle('isMenu'),isError: $wire.entangle('isError'),
                  isSubMenu: $wire.entangle('isSubMenu'),  isMeal: $wire.entangle('isMeal'), isItem: $wire.entangle('isItem'),
                  isBackBtn: $wire.entangle('isBackBtn'), isSubMeal: $wire.entangle('isSubMeal'),  isItemDetails: $wire.entangle('isItemDetails'), 
                  isFooter: $wire.entangle('isFooter'),  isAddToOrder: $wire.entangle('isAddToOrder'), 
                  isCheckout: $wire.entangle('isCheckout'),  isCheckout_payment: $wire.entangle('isCheckout_payment'), 
                  isMealDetails: $wire.entangle('isMealDetails'),  isMealOption_show: $wire.entangle('isMealOption_show'), 
                  isCustomise: $wire.entangle('isCustomise'),
                  isOrderCollectionMethod: $wire.entangle('isOrderCollectionMethod'),
                  isCloseBtn: $wire.entangle('isCloseBtn'), isPayment: $wire.entangle('isPayment'), 
                  isPayment_cash: $wire.entangle('isPayment_cash'),   isPayment_mobile: $wire.entangle('isPayment_mobile'),
                  }" x-init="if (isSplash) {
                      setTimeout(() => { 
                          isSplash = false; 
                          isMenu = true; 
                      }, 2000);
                  }">
            
        @once('mobile-styles')
            @include('livewire.mobile.cssblade.mobile-styles')  
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        @endonce
        
        <div x-show="isError" x-cloak >
            @include('livewire.mobile.fc.error') 
        </div>
        
        <div x-show="isSplash" x-cloak >
            @include('livewire.mobile.fc.splash') 
        </div>
        
        <div class="content-wrapper">

            <div class="container">
                <div class="status-bar" x-show="!isCustomise">
                    <div class="status-bar-left btn btn-primary"    x-show="isCloseBtn"   @click=" isPayment ? $wire.close_payment() : $wire.optionClose() && scrollToTop();" >Close</div>
                    <div class="status-bar-left"    x-show="isBackBtn"   @click="$wire.goBackMenu();scrollToTop();"  >{{ $backMenuName }}</div>
                    <div class="status-bar-center "  x-show="!isSplash">{{$itemParentName}}</div>
                </div>
                <div class="status-bar" x-show="isCustomise">
                    <div class="status-bar-center">Customise</div>
                </div>

                <!-- Start of  List -->
                <ul class="menu-list mt-5">
                    <div x-show="isMenu" x-transition  x-cloak>
                        @include('livewire.mobile.fc.menu')
                    </div>

                    <div x-show="isSubMenu" x-transition  x-cloak>
                        @include('livewire.mobile.fc.sub-menu')
                    </div>

                    <div x-show="isMeal" x-transition  x-cloak >
                        @include('livewire.mobile.fc.meal')
                    </div>

                    <div x-show="isSubMeal" x-transition  x-cloak >
                        @include('livewire.mobile.fc.sub-meal')
                    </div>

                    <div x-show="isItem" x-transition   x-cloak>
                        @include('livewire.mobile.fc.item')
                    </div>

                    <div x-show="isMealOption_show" x-transition   x-cloak>
                        @include('livewire.mobile.fc.meal-option')
                    </div>

                </ul>
                <!-- End of List -->
                <div x-show="isItemDetails" x-transition  x-cloak >
                    @include('livewire.mobile.fc.item-details')
                </div>
                <div x-show="isMealDetails" x-transition  x-cloak >
                    @include('livewire.mobile.fc.meal-details')
                </div>
                <div x-show="isCustomise" x-transition  x-cloak >
                    @if (in_array($mealTypeID, [1,2]))
                        @include('livewire.mobile.fc.item-customise')
                    @elseif (in_array($mealTypeID, [3,4]))
                        @include('livewire.mobile.fc.meal-customise')
                    @endif
                </div>
                <div x-show="isCheckout_payment" x-transition  x-cloak >
                    @include('livewire.mobile.fc.checkout')
                </div>
                <div x-show="isPayment" x-transition  x-cloak >
                    @include('livewire.mobile.fc.payment')
                </div>
                <div x-show="isPayment_cash" x-transition  x-cloak >
                    @include('livewire.mobile.fc.payment-cash')
                </div>
                <div x-show="isPayment_mobile" x-transition  x-cloak >
                    @include('livewire.mobile.fc.payment-mobile')
                </div>
                <div x-show="isOrderCollectionMethod" x-transition  x-cloak >
                    @include('livewire.mobile.fc.order-collectionMethod')
                </div>
            </div>
        </div>

        <footer   :class="{ 'd-none': !isFooter }"
                  class="footer d-flex align-items-center justify-content-center"
                  x-data="{ notification() {
                           /*
                           Fxon.Hint.Light('Item Added to Order', 
                           {
                                   callback:function(){
                                  // callback
                            },
                            position:'right-bottom',
                            animation:'slide-right'
                            });
                            */
                            new Notify({
                                status: 'info',
                                title: '',
                                type:'filled',
                                text: 'Item Added to Order',
                                position:'center'
                             });
                            
                     }}"
                  >
             <button  x-show="isCustomise"  class=" btn btn-outline-secondary  w-100"   
                  @click= "$wire.back_toMyOrder();">Back to My Order </button> 
            <button  x-show="isAddToOrder"  class="btn btn-warning w-100"   
                @click= "$wire.addToOrderFunc();notification();">Add to Order </button> 
            <button  x-show="isCheckout"  class="btn btn-warning w-100 btn-custom"   
                @click= "$wire.checkoutFunc();">
                <span class="col text-start">Checkout</span> 
                <span class="col text-center">Items: {{$totalOrder}}</span> 
                <span class="col text-end fw-bold">Total Cost: {{$currency}}{{$totalCost}} </span>
            </button> 

            <button  x-show="isCheckout_payment"   @click= "$wire.show_menu();"  class="btn btn-outline-secondary w-50">   
                Order More </button>
            <button  x-show="isCheckout_payment"    @click= "$wire.show_orderCollectionMethod();scrollToTop();"   class="btn btn-warning w-50">   
                    Payment
            </button>
            <button  x-show="isPayment_cash || isPayment_mobile"   @click= "$wire.init_menu();"  class="btn btn-outline-secondary w-100">   
                 Start Ordering 
            </button>

            <!-- This is the writing Area 
            <div class="text-center">
                <span class="text-muted">Footer Content</span>
            </div>
            -->
         </footer>
    </div> 
</div>