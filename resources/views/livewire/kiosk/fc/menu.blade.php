<div  x-data="{ isMainMenu: $wire.entangle('isMainMenu'),
               isCustomise: $wire.entangle('isCustomise'),
                isItemDetails: $wire.entangle('isItemDetails'),
                isItemUpdate: $wire.entangle('isItemUpdate'),
                isViewOrder: $wire.entangle('isViewOrder'),
                isMealOption: $wire.entangle('isMealOption'),
                isHowMealIsServed: $wire.entangle('isHowMealIsServed'),
                isPaymentMethod: $wire.entangle('isPaymentMethod'),
                isFinalPaymentAtCashier: $wire.entangle('isFinalPaymentAtCashier'),
                isFinalPaymentAtKiosk: $wire.entangle('isFinalPaymentAtKiosk'),
                isFinalPaymentWithMobile: $wire.entangle('isFinalPaymentWithMobile'),
                isFinalPaymentMessage: $wire.entangle('isFinalPaymentMessage'),
               }"  >
     <div x-show="isCustomise" x-transition  x-cloak >
          @include('livewire.kiosk.fc.menu_customise')
     </div>
     <div x-show="isMainMenu" x-transition  x-cloak >
         @include('livewire.kiosk.fc.menu_main')
     </div>
     <div x-show="isItemDetails" x-transition  x-cloak >
          @include('livewire.kiosk.fc.menu_itemDetails')
     </div>
     <div x-show="isItemUpdate" x-transition  x-cloak >
          @include('livewire.kiosk.fc.menu_itemUpdate')
     </div>
     <div x-show="isMealOption" x-transition  x-cloak >
          @include('livewire.kiosk.fc.menu_mealOption')
     </div>
     <div x-show="isViewOrder" x-transition  x-cloak >
          @include('livewire.kiosk.fc.menu_viewOrder')
     </div>
     <div x-show="isHowMealIsServed" x-transition  x-cloak >
          @include('livewire.kiosk.fc.menu_how_meal_is_served')
     </div>
     <div x-show="isPaymentMethod" x-transition  x-cloak >
          @include('livewire.kiosk.fc.menu_payment_method')
     </div>
     <div x-show="isFinalPaymentAtCashier" x-transition  x-cloak >
          @include('livewire.kiosk.fc.inc.menu_final_payment_message')
     </div>
     <div x-show="isFinalPaymentAtKiosk" x-transition  x-cloak >
          @include('livewire.kiosk.fc.menu_final_payment_at_kiosk')
     </div>
     <div x-show="isFinalPaymentWithMobile" x-transition  x-cloak >
          @include('livewire.kiosk.fc.menu_final_payment_with_mobile')
     </div>
     <div x-show="isFinalPaymentMessage" x-transition  x-cloak >
          @include('livewire.kiosk.fc.inc.menu_final_payment_message')
     </div>
</div>