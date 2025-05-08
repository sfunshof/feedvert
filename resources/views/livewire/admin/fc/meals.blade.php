<div x-data="{ showAdd_meal: true }">
    <!-- Visible initially -->
    <div x-show="showAdd_meal">
        @include('livewire.admin.inc.meals.meals_table')
    </div>
    <div x-show="!showAdd_meal">
        @include('livewire.admin.inc.meals.meals_addNew')
    </div>
</div>