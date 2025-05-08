<div x-data="{ showAdd: true }">
    <!-- Visible initially -->
    <div x-show="showAdd">
        @include('livewire.admin.inc.items.items_table')
    </div>
    <div x-show="!showAdd">
        @include('livewire.admin.inc.items.items_addNew')
    </div>
</div>