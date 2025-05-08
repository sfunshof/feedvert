<div x-data="{ showAdd_category: true }">
    <!-- Visible initially -->
    <div x-show="showAdd_category">
        @include('livewire.admin.inc.categories.categories_table')
    </div>
    <div x-show="!showAdd_category">
        @include('livewire.admin.inc.categories.categories_addNew')
    </div>
</div>