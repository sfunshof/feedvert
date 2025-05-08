<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Your Laravel application">
    <title>Home page</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    {{--   Optional: Add your custom styles 
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    --}}
    
    <!-- Favicon (optional) -->
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
</head>

<body>

    <!-- Navbar 
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      </nav>
    -->
   
    <!-- Main Content -->
    <div class="container mt-4">
        <h1>Home Page</h1>
		<br>
		<a href="{{ route('appLogin') }}">Go to the App </a>
    </div>
    
	
	
    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-4">
        <p>&copy; {{ date('Y') }} My Laravel App. All rights reserved.</p>
    </footer>

    <!-- Bootstrap 5 Bundle JS (Includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Optional: Add custom JavaScript 
    <script src="{{ asset('js/app.js') }}"></script>
     -->
</body>

</html>