<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Austath Fanan')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">

    <!-- Navbar -->
    <nav class="bg-white shadow">
        <div class="container mx-auto px-6 py-3">
            <div class="flex justify-between items-center">
                <a href="/" class="text-xl font-bold text-gray-800">Austath Fanan</a>
                <div>
                    <a href="/" class="text-gray-600 hover:text-gray-900 mx-4">Home</a>
                    <a href="/about" class="text-gray-600 hover:text-gray-900 mx-4">About Us</a>
                 
                    <a href="/contact" class="text-gray-600 hover:text-gray-900 mx-4">Contact</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto mt-6">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12 py-6">
        <div class="container mx-auto text-center">
            <p>&copy; {{ date('Y') }} Austath Fanan. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>

