
     <button   x-data="{ simulateClick() {
        const firstRow = document.querySelector('table#menuTable tr:first-child');
            if (firstRow) {
                firstRow.click();
            }
         const table = document.getElementById('menuTableContainer');
            if (table){
                table.scrollTop = 0;
            }
        }}"
        type="button"  id="initBtnId" class="btn btn-outline-secondary kiosk-button "  
            @click="$wire.ks_menu();$wire.mainMenuInit();simulateClick();"> Start All Over
    </button>