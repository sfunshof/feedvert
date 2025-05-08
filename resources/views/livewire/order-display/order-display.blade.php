<div>
    <div  x-data="orderMenuData()"
        x-init="
            $watch('isCollection', value => {
                if (value) {
                   document.body.style.overflow = 'auto'
                }
            }); 
             $watch('isView', value => {
                if (value) {
                    document.body.style.overflow = 'hidden';
                }
            });
         "
    >
        @once('order-display-styles')
            @include('livewire.order-display.cssblade.order-display-styles')
        @endonce
        <div class="custom-container" x-show="isMenu" x-transition>           
            <div class="custom-rectangle border rounded p-3 
                    text-center d-flex flex-column justify-content-between">
                <p class="fs-4">Please select the appropriate feature for the 
                       Customer Order Status Display
                </p>
                <div class="button-container">
                    <button @click="showCollection()"     type="button" class="btn btn-outline-primary btn-lg ">Order Collection</button>
                    <button @click="showView()" type="button" class="btn btn-outline-primary btn-lg ">View Order Status</button>
                    <button @click.prevent="logoutFunction()"  type="button" class="btn btn-outline-danger btn-lg ">Logout</button>
                </div>
            </div>
        </div>

        <div x-show="isCollection" x-transition>
            <!-- Staff -->
            @include('livewire.order-display.fc.customerCollection')
        </div>
        <div x-show="isView" x-transition>
            <!-- Customer -->
            @include('livewire.order-display.fc.customerView')
        </div>

    </div>
    <div wire:poll.1500ms="checkForUpdates"></div>
</div>
@script
    <script>
        Alpine.data('orderMenuData', () => ({
            isCollection: false,
            isView: false,
            isMenu: true,
            showCollection() {
                this.isCollection = true;
                this.isView = false;
                this.isMenu = false;
            },
            showView() {
                this.isCollection = false;
                this.isView = true;
                this.isMenu = false;
            },
         }));
    </script>
@endscript