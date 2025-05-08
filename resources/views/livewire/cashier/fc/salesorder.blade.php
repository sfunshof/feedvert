<div class="h-100  position-relative" >
      <!-- Caption bar -->
    <div class="row">
        <div class="col-12 d-flex align-items-center" 
            style="background-color: #0078d7; color: #fff; padding: 5px 10px; border-bottom: 1px solid #005a9e;">
            
            <!-- Window Icon 
            <i class="bi bi-window" style="font-size: 16px; margin-right: 10px;"></i>
            -->
            <!-- Spacer to push the title to the center -->
            <div class="flex-grow-1 d-flex justify-content-center">
                <span 
                  class="d-inline-block text-truncate" 
                  style="font-size: 14px; font-weight: bold; max-width: 100%; min-width: 0; white-space: nowrap;">
                  User: {{Auth::user()->email}}
                </span>
            </div>
            <!-- Put here to be on the samre wavelenght as display -->
            <div class="d-flex">
                <button 
                    x-data="{ isVisible: false }"
                    :class="{ 'hidden': !isVisible }"
                        class="btn  btn-outline-success mb-2 mt-1" style="background: transparent; color: #fff;" title="Back">
                        x
                </button>
            </div>

        </div>
    </div>
    {{--  Sales Order --}}
    <div class="list-container pb-3 mt-2">
        @if ($is_showSalesOrder)
             @include('livewire.cashier.inc.salesOrder-salesItem')
        @endif
        <div class="position-absolute bottom-0 start-0  bg-light p-3 border-top"
             style="width:90%">
             @include('livewire.cashier.inc.salesOrder-totalFixed')
        </div>
    </div>

</div>


@script
    <script>
        const container = document.querySelector('.list-container');
        let startY;
        let scrollTop;
        let isDragging = false;

        container.addEventListener('touchstart', (e) => {
            isDragging = true;
            startY = e.touches[0].pageY;
            scrollTop = container.scrollTop;
        });

        container.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            e.preventDefault();
            const y = e.touches[0].pageY;
            const distance = startY - y;
            container.scrollTop = scrollTop + distance;
        });

        container.addEventListener('touchend', () => {
            isDragging = false;
        });
    </script>
@endscript