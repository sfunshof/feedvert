<div>
    @once('cdu-styles')
        @include('livewire.cdu.cssblade.cdu-styles')
    @endonce
    <div class="row">
        <!-- Left Section: Visible on all screen sizes -->
        <div class="col-12 col-lg-6 " >
            @include('livewire.cdu.fc.cdu-salesOrder')
        </div>

        <!-- Right Section: Hidden on small screens, visible on large screens -->
        <div class="col-lg-6 d-none d-lg-block">
            @include('livewire.cdu.fc.cdu-displayAdvert')
        </div>
    </div>
   
</div>
@script
    <script>
        //document.body.style.overflow = 'hidden'; // Hide scrollbar for the body
        document.documentElement.style.overflow = 'hidden'; 
    </script>
@endscript    
