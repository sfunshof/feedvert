<div class="vh-100"
     x-data="{ 
         is_category: $wire.entangle('is_category'), 
    }"      
    >
    <div class="row h-100">
        <div class="col-12" style="height:75%">
            <!-- Top Left -->
            @include('livewire.cashier.inc.display', 
              ['caption'=>$current_name, 'resultValue' => $current_results])
        </div>
        <div x-show="is_category"   class="col-12 pt-3 px-0 mx-1" style="height:25%;">
            <!-- Bottom Left -->
            @include('livewire.cashier.fc.category')
        </div>
    </div>
</div>