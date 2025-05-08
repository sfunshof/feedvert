<div x-data="{ showAdd_subCategory: true }">
    <!-- Visible initially -->
    <div x-show="showAdd_subCategory">
        @include('livewire.admin.inc.sub_categories.subCategories_table')
    </div>
    <div x-show="!showAdd_subCategory">
        @include('livewire.admin.inc.sub_categories.subCategories_addNew')
    </div>
</div>