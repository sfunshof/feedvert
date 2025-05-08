<div class="d-flex flex-column flex-md-row justify-content-between" style="width:100%">
    <!-- 1st object Counter container -->
    <div class="counter-container d-inline-flex align-items-center mb-3 mb-md-0">
        <!-- Decrease Button -->
        <button @click="$wire.decreaseQty();" class="btn btn-touch btn-outline-secondary rounded-start"
        {{ $details_qty <= 1 ? 'disabled' : '' }}>
        -
        </button>
        <!-- Counter Display -->
        <span id="counter" class="counter-display px-1 border-top border-bottom">{{ $details_qty }}</span>
        <!-- Increase Button -->
        <button @click="$wire.increaseQty();" class="btn btn-touch btn-outline-secondary rounded-end"
        {{ $details_qty >= 20 ? 'disabled' : '' }}>
        +
        </button>
    </div>
    <!-- 2nd object Delete button container -->
    <div class="d-inline-flex align-items-center">
        <!-- Delete Button -->
        <button @click="$wire.delete_meal();" class="btn btn-touch btn-outline-danger rounded">
            Void Item
        </button>
    </div>
</div>