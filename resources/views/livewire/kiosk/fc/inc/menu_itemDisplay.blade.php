  <button class="btn btn-outline-secondary kiosk-button w-25"  
        style="float: right;"   
        :style="{ visibility: itemBackToSubMenu ?  'visible' : 'hidden' }"
        @click="$wire.itemBackToSubMenuFunc()" >
          Back
    </button>
 

<!-- Scrollable  items/submenu table container -->
@include('livewire.kiosk.fc.inc.menu_itemDisplayTable')
{{--
@if ($isMainMenu)
   
@endif
@if ($isMealOption)
     @include('livewire.kiosk.fc.inc.menu_itemDisplayTable')    
@endif     
--}}