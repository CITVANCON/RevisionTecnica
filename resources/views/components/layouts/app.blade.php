<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo2.png') }}" />
    <title>CITV ANCON</title>
    <!-- Este es el app.blade.php de components/layouts --> 

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


    <!-- Styles -->
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-gray-100">
        @livewire('custom-nav-menu')
        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @stack('modals')

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Script para SweetAlert2 con Livewire -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Livewire.on('minAlert', function(params) {
                Swal.fire({
                    title: params['titulo'  ],
                    text: params["mensaje"],
                    icon: params["icono"]
                });
            });
        });
    </script>


    <footer>
        <div class="text-xs text-slate-700  float-right">
            Powered by GHFDEV ®
        </div>
    </footer>
</body>

</html>
