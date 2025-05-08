<!-- Upper body -->
<img src="{{ asset('custom/app/img/admin/kiosk_top.png') }}" alt="Top Image" class="kiosk_top-image">

 <!-- Bottom Image (25% height) -->
 <div class="kiosk_lower-container"   
        x-data="{ simulateClick() {
            const firstRow = document.querySelector('table#menuTable tr:first-child');
            if (firstRow) {
                firstRow.click();
               // $wire.initMealTypeID();
            }
        }}"
     >
    <img src="{{ asset('custom/app/img/admin/kiosk_lower.png') }}" alt="Bottom Image" class="kiosk_lower-image">
        <div style="position: absolute; top: 50%; left: 70%; transform: translate(-50%, -50%);
             background-color: rgba(0, 0, 0, 0.7); color: white;
             padding: 40px 80px; font-size: 20px; font-weight: bold; text-align: center;"  @click="$wire.ks_menu();simulateClick()">
            Start My Order
        </div>
</div>   