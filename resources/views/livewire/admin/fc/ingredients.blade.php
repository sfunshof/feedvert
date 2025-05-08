<div x-data="{ showAdd_ingredients: true }">
    <!-- Visible initially -->
    <div x-show="showAdd_ingredients">
        @include('livewire.admin.inc.ingredients.ingredients_table')
    </div>
</div>