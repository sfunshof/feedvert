<div>
    {{-- Define a universal Alpine Js  --}}
    <div x-data="{ intro: @entangle('intro'), menu: @entangle('menu') }">
        
    
        @once('kiosk-styles')
            @include('livewire.kiosk.cssblade.kiosk-styles')  
        @endonce
   
        <div x-show="intro" x-transition >
            @include('livewire.kiosk.fc.intro') 
        </div>
        
        <div x-show="menu" x-transition  x-cloak >
            @include('livewire.kiosk.fc.menu')
        </div>
        

    </div>
</div>
