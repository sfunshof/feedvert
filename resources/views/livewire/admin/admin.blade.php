<div>
    <div
        x-data="{ 
            isItems: $wire.entangle('isItems'),
            isMeals: $wire.entangle('isMeals') ,
            isCategories: $wire.entangle('isCategories'),
            isSubCategories: $wire.entangle('isSubCategories'),
            isIngredients: $wire.entangle('isIngredients'),
            isModifiers: $wire.entangle('isModifiers') ,
            isReports: $wire.entangle('isReports') ,
            isMyAccount: $wire.entangle('isMyAccount'),
            isUsers: $wire.entangle('isUsers'),
        }"
    >

        @once('admin-styles')
            @include('livewire.admin.cssblade.admin-styles')
        @endonce
        @include('livewire.admin.inc.navbar')
        <!-- Content area with custom-div that will be covered by the navbar when menu is active -->
        <div class="p-3">
            <div id="custom-div" class="content">
                <div x-show="isItems" x-transition x-cloak>
                    @include('livewire.admin.fc.items')
                </div>
                <div x-show="isMeals" x-transition x-cloak>
                    @include('livewire.admin.fc.meals')
                </div>
                <div x-show="isCategories" x-transition x-cloak>
                    @include('livewire.admin.fc.categories')
                </div>
                <div x-show="isSubCategories" x-transition x-cloak>
                    @include('livewire.admin.fc.sub_categories')
                </div>
                <div x-show="isIngredients" x-transition x-cloak>
                     @include('livewire.admin.fc.ingredients')
                </div>
                <div x-show="isModifiers" x-transition x-cloak>
                    @include('livewire.admin.fc.modifiers')
                </div>
                <div x-show="isMyAccount" x-transition x-cloak>
                    @include('livewire.admin.fc.my_account')
                </div>
                <div x-show="isReports" x-transition x-cloak>
                    @include('livewire.admin.fc.reports')
                </div>
                <div x-show="isUsers" x-transition x-cloak>
                    @include('livewire.admin.fc.users')
                </div>
            </div>
        </div>
    </div>  
</div>
@script
    <script>
        // Get all navbar links
        navLinks = document.querySelectorAll('[data-nav-link]');

        // Get the navbar toggler and collapse element
        navbarToggler = document.querySelector('.navbar-toggler');
        navbarCollapse = document.querySelector('.navbar-collapse');

        // Add click event to each navigation link
        navLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                // If navbar is expanded (visible) in mobile view
                if (navbarCollapse.classList.contains('show')) {
                    // Programmatically click the toggler to collapse the menu
                    navbarToggler.click();
                }
            });
        });
    </script>
@endscript