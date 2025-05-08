<div>
    @once('kitchen-styles')
        @include('livewire.kitchen.cssblade.kitchen-styles') 
    @endonce
    <!-- Larger Fixed Header -->
    <header class="header bg-light border-bottom border-1 border-secondary mb-1">
        <div x-data="kitchenMenuData()" class="container-fluid d-flex justify-content-between align-items-center h-100 pe-5">
            @php
                $logo=Auth::user()->CompanySettings->logo;
            @endphp
            <img src="{{ asset('custom/app/img/clients/' . Auth::user()->companyID . '/' . $logo) }}" 
                class="img-fluid" alt="Card Image" style="width:80px"
                @click="logoutFunction()"
            >
           <span class="header-text fs-4">{{ $no_of_orders }} Orders</span>
           <div class="menu-container d-flex">
                <div  @click="showOpen()" class="menu-item active"  :class="{ 'active': isOpenBtn === true}"  >Open</div>
                <div  @click="showHold()" class="menu-item active"  :class="{ 'active': isHoldBtn === true}"  >Hold</div>
                <div @click="showCompleted()" class="menu-item" :class="{ 'active': isCompletedBtn === true}" >Completed</div>
            </div>
        </div>
    </header>

    <!-- Full-Page Content -->
    <div class="body-content bg-danger">
        @include('livewire.kitchen.fc.kitchen-table')
    </div>
</div>
@script
    <script>
        Alpine.data('kitchenMenuData', () => ({
            isOpenBtn:true,
            isHoldBtn:false,
            isCompletedBtn:false,
            showOpen() {
                this.isHoldBtn=false;
                this.isCompletedBtn=false;
                this.isOpenBtn=true;
                $wire.set('displayStatus', 1);
                $wire.getOrderDetails([1,2]);
            },
            showHold() {
                this.isHoldBtn=true;
                this.isCompletedBtn=false;
                this.isOpenBtn=false;
                $wire.set('displayStatus', 2);
                $wire.getOrderDetails([5]);
            },
            showCompleted() {
               this.isOpenBtn=false;
               this.isHoldBtn=false;
               this.isCompletedBtn=true;
               $wire.set('displayStatus', 3);
               $wire.getOrderDetails([3]);
            },
         }));
    </script>
@endscript