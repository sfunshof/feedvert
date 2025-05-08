<div wire:poll.1500ms="checkForUpdates">
    {{--  Sales Order --Copied from cashier--}}
    <div class="vh-100">
         <div class="row" style="height:100%">
            <div class="col-12 ps-0 " style="height:80%;">
                <!-- Logo positioned at the top left -->
                @php
                    $logo=Auth::user()->CompanySettings->logo;
                @endphp
                <div class="position-relative ps-3 pt-2">
                    <img src="{{ asset('custom/app/img/clients/' . Auth::user()->companyID . '/' . $logo) }}" 
                         class="img-fluid" alt="Card Image" style="width:80px"
                        @click="logoutFunction()"
                     >
                </div>
                <div class="scrollable-container custom-scrollbar p-3">
                    <div class="list-container py-3 my-2">
                        @include('livewire.cashier.inc.salesOrder-salesItem')
                        @include('livewire.cashier.inc.salesOrder-collectionMethod')
                    </div>
                </div>
            </div>
            <div class="col-12 d-flex flex-column px-5" style="height:20%">
                @include('livewire.cashier.inc.salesOrder-totalFixed')
            </div>
        </div>
    </div>
    
</div>
@script
    <script>
         $wire.on('scrollToCDUbottom', () => {
            scrollableContainer = document.querySelector('.scrollable-container');
    
            if (scrollableContainer) {
                // Initial scroll to bottom
                scrollableContainer.scrollTop = scrollableContainer.scrollHeight;
                
                // Create a MutationObserver instance
                const observer = new MutationObserver(() => {
                    scrollableContainer.scrollTop = scrollableContainer.scrollHeight;
                });
                
                // Start observing the target node for changes in its children
                observer.observe(scrollableContainer, { 
                    childList: true,
                    subtree: true
                });
            }
         });
    </script>
@endscript        