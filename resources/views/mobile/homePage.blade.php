<!DOCTYPE html>
<html lang="en">

    <head>
        @include('app.inc.head')
    </head>

    <body>
        <!-- Navigation Bar 
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
            </nav>
        -->

        <!-- Main Content -->
        <div class="">
            <livewire:mobile.mobile :data="$data" />
        </div>
        
        @include('app.inc.foot')
       
    </body>

</html>