<x-guest-layout>
    {{-- 
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Correo') }}" />
                <x-input id="email" class="block mt-1 w-full focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50" 
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Contraseña') }}" />
                <x-input id="password" class="block mt-1 w-full focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50" 
                type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" class="text-accent focus:ring-accent focus:ring-opacity-50" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Recordar') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Olvidaste tu contraseña?') }}
                    </a>
                @endif

                <x-button class="ms-4">
                    {{ __('Ingresar') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
    --}}
    <div class="relative min-h-screen flex items-center justify-center font-sans bg-slate-950 text-slate-100 overflow-hidden selection:bg-emerald-500 selection:text-white">

        <!-- FONDO DE PANTALLA CON GRADIENTE Y PATRÓN -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80"
                alt="Fondo CITV"
                class="object-cover w-full h-full scale-105 filter brightness-40 contrast-125 saturate-50">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-950/95 via-emerald-950/85 to-slate-950/95"></div>
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:24px_24px]"></div>
        </div>

        <!-- CONTENEDOR PRINCIPAL DEL LOGIN -->
        <div class="relative z-10 w-full max-w-md px-6 py-10">
            <!-- TARJETA GLASSMORPHISM -->
            <div class="bg-slate-900/60 backdrop-blur-2xl border border-white/10 rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden">
                <div class="absolute -right-12 -top-12 w-36 h-36 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>
                <!-- LOGO Y CABECERA -->
                <div class="flex flex-col items-center text-center space-y-4 mb-8">
                    <div class="bg-white/95 p-3 rounded-2xl shadow-2xl border border-white/20 inline-flex items-center justify-center">
                        <img src="{{ asset('images/logo.png') }}" class="h-12 w-auto object-contain" alt="Logo CITV">
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest bg-emerald-950/60 px-3 py-1 rounded-full border border-emerald-500/30">
                            Acceso Administrativo
                        </span>
                        <h1 class="text-2xl font-black text-white uppercase tracking-tight mt-3">
                            CITV <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-200">Ancón</span>
                        </h1>
                        <p class="text-xs text-slate-400 mt-1 font-normal">
                            Ingrese sus credenciales para acceder al sistema
                        </p>
                    </div>
                </div>

                <!-- ERRORES DE VALIDACIÓN -->
                <x-validation-errors class="mb-5 p-3.5 bg-red-950/60 border border-red-500/30 rounded-xl text-xs text-red-300" />

                <!-- MENSAJE DE ESTADO DE SESIÓN -->
                @session('status')
                    <div class="mb-5 p-3.5 bg-emerald-950/60 border border-emerald-500/30 rounded-xl text-xs font-semibold text-emerald-300 text-center">
                        {{ $value }}
                    </div>
                @endsession

                <!-- FORMULARIO DE LOGIN -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- CAMPO: CORREO ELECTRÓNICO -->
                    <div class="space-y-1.5">
                        <label for="email" class="text-xs font-bold text-slate-300 uppercase tracking-wider block">
                            {{ __('Correo Electrónico') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <x-input id="email" 
                                class="w-full bg-slate-950/80 border border-slate-700/80 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-white rounded-xl pl-11 pr-4 py-3.5 text-sm transition-all shadow-inner placeholder:text-slate-600" 
                                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                                placeholder="ejemplo@citvancon.com" />
                        </div>
                    </div>

                    <!-- CAMPO: CONTRASEÑA -->
                    <div class="space-y-1.5">
                        <label for="password" class="text-xs font-bold text-slate-300 uppercase tracking-wider block">
                            {{ __('Contraseña') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <x-input id="password" 
                                class="w-full bg-slate-950/80 border border-slate-700/80 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-white rounded-xl pl-11 pr-4 py-3.5 text-sm transition-all shadow-inner placeholder:text-slate-600" 
                                type="password" name="password" required autocomplete="current-password" 
                                placeholder="••••••••" />
                        </div>
                    </div>

                    <!-- RECORDAR MI SESIÓN Y RECUPERACIÓN -->
                    <div class="flex items-center justify-between pt-1">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <x-checkbox id="remember_me" name="remember" 
                                class="rounded bg-slate-950 border-slate-700 text-emerald-500 focus:ring-emerald-500/30 focus:ring-offset-slate-900" />
                            <span class="ms-2 text-xs font-medium text-slate-400 hover:text-slate-300 transition-colors">
                                {{ __('Recordar') }}
                            </span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-xs text-emerald-400 hover:text-emerald-300 font-medium hover:underline transition-colors" href="{{ route('password.request') }}">
                                {{ __('¿Olvidaste tu contraseña?') }}
                            </a>
                        @endif
                    </div>

                    <!-- BOTÓN INGRESAR -->
                    <div class="pt-2">
                        <button type="submit" 
                            class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold py-3.5 px-4 rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-emerald-950/50 active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2">
                            <span>{{ __('Ingresar') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- REGRESO A LA PANTALLA PRINCIPAL -->
            <div class="text-center mt-6">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs text-slate-400 hover:text-emerald-400 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Volver a Marcado de Asistencia</span>
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
