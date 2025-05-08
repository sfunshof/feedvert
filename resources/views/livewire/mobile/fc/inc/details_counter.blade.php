<div class="counter-container" x-data="{ itemOrder: $wire.entangle('itemOrder') }">
    <button class="counter-btn minus"  :disabled="itemOrder <= 1" @click="itemOrder > 1 ? itemOrder-- : null" >-</button>
    <div class="counter-display" x-text="itemOrder"></div>
    <button class="counter-btn plus" @click="itemOrder++">+</button>
</div>