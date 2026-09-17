<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo2.png') }}" />
    <title>{{ config('app.name', 'CITV ANCON') }}</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- CSS Externos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Styles & Scripts de App -->
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 min-h-screen flex flex-col justify-between">
    <x-banner />

    <div class="flex-grow">
        @livewire('custom-nav-menu')

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <footer class="py-4 px-6 border-t border-gray-200 bg-white">
        <div class="max-w-7xl mx-auto flex justify-between items-center text-xs text-slate-500">
            <span>&copy; {{ date('Y') }} CITV ANCON. Todos los derechos reservados.</span>
            <span class="font-semibold tracking-wider text-slate-600">Powered by GHFDEV ®</span>
        </div>
    </footer>

    @stack('modals')
    @livewireScripts
    @stack('js')

    <!-- JS Librerías Externas -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Eventos SweetAlert2 para Livewire 3 -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Livewire.on('minAlert', function(data) {
                const params = Array.isArray(data) ? data[0] : data;
                Swal.fire({
                    title: params.titulo || 'Atención',
                    text: params.mensaje || '',
                    icon: params.icono || 'info',
                    confirmButtonColor: '#0f172a'
                });
            });
        });
    </script>
</body>

</html>
