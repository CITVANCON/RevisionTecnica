<div class="relative min-h-screen flex items-center justify-center font-sans bg-slate-950 text-slate-100 overflow-hidden selection:bg-emerald-500 selection:text-white">

    <!-- IMAGEN DE FONDO + OVERLAY PROFESIONAL -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80"
            alt="Taller de inspección vehicular"
            class="object-cover w-full h-full scale-105 filter brightness-50 contrast-125 saturate-50">
        <!-- Overlay Gradiente Oscuro Ejecutivo -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950/95 via-emerald-950/80 to-slate-950/95"></div>
        <!-- Malla/Patrón sutil -->
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:24px_24px]"></div>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="relative z-10 w-full max-w-6xl px-6 py-10 flex flex-col lg:flex-row items-stretch gap-8">

        <!-- LADO IZQUIERDO: Formulario & Cabecera -->
        <div class="w-full lg:w-7/12 flex flex-col justify-between space-y-8">

            <!-- Header / Branding -->
            <div class="space-y-6">
                <!-- Top Badge / Reloj y Fecha -->
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="inline-flex items-center gap-2.5 bg-white/5 backdrop-blur-md px-4 py-2 rounded-full border border-white/10 text-xs font-medium text-slate-300 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ now()->translatedFormat('l j \d\e F \d\e\l Y') }}</span>
                    </div>

                    <div wire:poll.1000ms class="flex items-baseline gap-1.5 font-mono bg-black/40 backdrop-blur-md px-4 py-1.5 rounded-full border border-white/10 shadow-inner">
                        <span class="text-2xl font-extrabold text-white tracking-tight">{{ now()->format('H:i') }}</span>
                        <span class="text-sm font-semibold text-emerald-400">:{{ now()->format('s') }}</span>
                        <span class="text-xs font-medium text-slate-400 uppercase ml-1">{{ now()->format('A') }}</span>
                    </div>
                </div>

                <!-- Logo & Títulos -->
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="bg-white p-2.5 rounded-xl shadow-xl border border-white/20 flex items-center justify-center">
                            <img src="{{ asset('images/logo.png') }}" class="h-10 object-contain" alt="Logo CITV">
                        </div>
                        <div class="h-9 w-px bg-slate-700/60"></div>
                        <div>
                            <p class="text-xs font-bold text-emerald-400 uppercase tracking-widest">CITV Ancon SAC</p>
                            <p class="text-[11px] text-slate-400 font-medium">Centro de Inspección Técnica Vehicular</p>
                        </div>
                    </div>

                    <div class="pt-2">
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black uppercase tracking-tight text-white leading-tight">
                            Registro de <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-200">Asistencia</span>
                        </h1>
                        <p class="text-slate-400 text-sm mt-2 font-normal flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                            Ingrese su DNI en la lectora o teclado para marcar su ingreso/salida.
                        </p>
                    </div>
                </div>
            </div>

            <!-- FORMULARIO DNI -->
            @if ($isDeviceAuthorized)
                <div class="bg-slate-900/60 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden group">
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all duration-500"></div>

                    <form wire:submit.prevent="registrarMarcado" class="space-y-5 relative z-10">
                        <div>
                            <label class="text-xs font-bold text-emerald-400 uppercase tracking-wider mb-2 block flex items-center justify-between">
                                <span>Documento Nacional de Identidad</span>
                                <span class="text-[10px] text-slate-400 normal-case font-normal">(Automático)</span>
                            </label>
                            
                            <div class="flex flex-col sm:flex-row items-stretch gap-3">
                                <div class="relative flex-1">
                                    <input type="text" id="dni_input" wire:model="dni"
                                        placeholder="DNI (8 dígitos)"
                                        class="w-full text-center text-3xl font-black text-white bg-slate-950/80 border border-slate-700/80 
                                            focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none
                                            rounded-xl py-4 px-5 placeholder:text-slate-600 placeholder:font-normal placeholder:text-lg
                                            transition-all duration-200 shadow-inner tracking-widest font-mono"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8)"
                                        maxlength="8" autofocus />
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                        </svg>
                                    </div>
                                </div>

                                <button type="submit"
                                    class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 
                                           text-white font-bold px-8 py-4 rounded-xl text-sm uppercase tracking-wider 
                                           shadow-lg shadow-emerald-900/30 hover:shadow-emerald-900/50 active:scale-[0.98] 
                                           transition-all duration-200 flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Registrar</span>
                                </button>
                            </div>
                        </div>

                        @error('dni')
                            <div class="text-red-400 text-sm font-medium flex items-center gap-2.5 bg-red-950/50 border border-red-500/30 p-3.5 rounded-xl animate-fade-in">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </form>

                    <!-- Status Bar Footer -->
                    <div class="mt-6 pt-4 border-t border-white/5 flex items-center justify-between text-xs text-slate-400">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span>Terminal Autorizada</span>
                        </div>
                        <div class="flex items-center gap-2 font-semibold text-emerald-400">
                            <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                            Sistema Operativo
                        </div>
                    </div>
                </div>
            @else
                <!-- Dispositivo No Autorizado -->
                <div class="bg-slate-900/80 backdrop-blur-xl border border-red-500/30 p-8 rounded-2xl text-center shadow-2xl relative overflow-hidden">
                    <div class="w-14 h-14 bg-red-500/10 border border-red-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 text-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-white uppercase tracking-wide mb-1">Terminal No Habilitada</h2>
                    <p class="text-slate-400 text-xs max-w-sm mx-auto mb-6">
                        Esta estación de trabajo no se encuentra registrada para el control de asistencia.
                    </p>
                    
                    <div class="space-y-3 max-w-xs mx-auto">
                        <button onclick="comprobarDispositivoManual()" 
                            class="w-full bg-slate-800 hover:bg-slate-700 text-slate-100 font-semibold text-xs px-5 py-3 rounded-xl border border-slate-600/50 transition-all duration-200 flex items-center justify-center gap-2 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Validar Autorización</span>
                        </button>
                        <p class="text-[11px] text-slate-500">
                            Ruta de vinculación: <span class="font-mono text-slate-400">/autorizar-dispositivo</span>
                        </p>
                    </div>
                </div>
            @endif

        </div>

        <!-- LADO DERECHO: Informática & Tarjetas Informativas -->
        <div class="hidden lg:flex w-5/12 flex-col justify-between gap-4">

            <!-- Tarjeta: Horario laboral -->
            <div class="bg-slate-900/40 backdrop-blur-md border border-white/10 rounded-2xl p-5 shadow-lg">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">Jornada Laboral</p>
                        <p class="text-slate-400 text-[10px] uppercase font-semibold tracking-wider">Lunes a Viernes</p>
                    </div>
                </div>
                <div class="flex items-center justify-between bg-black/30 border border-white/5 rounded-xl p-3.5">
                    <div class="text-center px-2">
                        <p class="text-xl font-bold font-mono text-emerald-400">08:00</p>
                        <p class="text-[9px] text-slate-400 uppercase font-semibold tracking-widest mt-0.5">Entrada</p>
                    </div>
                    <div class="flex-1 mx-3 border-t border-dashed border-slate-700"></div>
                    <div class="text-center px-2">
                        <p class="text-xl font-bold font-mono text-emerald-400">19:00</p>
                        <p class="text-[9px] text-slate-400 uppercase font-semibold tracking-widest mt-0.5">Salida</p>
                    </div>
                </div>
            </div>

            <!-- Tarjeta: Servicios del Centro -->
            <div class="bg-slate-900/40 backdrop-blur-md border border-white/10 rounded-2xl p-5 shadow-lg">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">Servicios CITV</p>
                        <p class="text-slate-400 text-[10px] uppercase font-semibold tracking-wider">Ancón S.A.C.</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center gap-2.5 bg-black/20 border border-white/5 rounded-lg p-2.5 text-xs text-slate-300">
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                        <span class="font-medium">Revisión Técnica Vehicular</span>
                    </div>
                    <div class="flex items-center gap-2.5 bg-black/20 border border-white/5 rounded-lg p-2.5 text-xs text-slate-300">
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                        <span class="font-medium">Inspección de Hermeticidad</span>
                    </div>
                    <div class="flex items-center gap-2.5 bg-black/20 border border-white/5 rounded-lg p-2.5 text-xs text-slate-300">
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                        <span class="font-medium">Prueba de Opacidad</span>
                    </div>
                </div>
            </div>

            <!-- Widget Informativo Adicional / Status -->
            <div class="bg-emerald-950/30 backdrop-blur-md border border-emerald-500/20 rounded-2xl p-4 flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></div>
                    <span class="text-xs font-semibold text-slate-200">Servidor en línea</span>
                </div>
                <span class="text-[10px] font-mono text-emerald-400 bg-emerald-900/40 px-2 py-0.5 rounded border border-emerald-500/30">v2.4 Active</span>
            </div>

        </div>
    </div>

    <!-- BOTÓN DE LOGIN FLOTANTE -->
    <div class="fixed bottom-6 right-6 z-20">
        <a href="{{ route('login') }}"
            class="group flex items-center gap-2.5 bg-slate-900/80 hover:bg-emerald-600 backdrop-blur-md border border-white/10 hover:border-emerald-500
                   text-slate-200 hover:text-white px-4 py-2.5 rounded-full transition-all duration-300 shadow-2xl hover:shadow-emerald-900/50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:translate-x-0.5 transition-transform"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
            </svg>
            <span class="text-xs font-bold uppercase tracking-wider hidden sm:inline">Acceso Administrativo</span>
        </a>
    </div>    
</div>

@script
    <script>
        window.comprobarDispositivoManual = function() {
            const token = localStorage.getItem('citvancon_device_token');
            if (token) {
                $wire.verificarToken(token);
            } else {
                alert('No se encontró ningún token en LocalStorage. Autorice este equipo primero.');
            }
        };

        // Verificación directa inmediata
        const tokenInicial = localStorage.getItem('citvancon_device_token');
        if (tokenInicial) {
            $wire.verificarToken(tokenInicial);
        }

        // Mantener Autofocus permanente en el campo DNI
        const input = document.getElementById('dni_input');
        if (input) {
            input.focus();
            document.addEventListener('click', (e) => {
                if (!['INPUT', 'BUTTON', 'LABEL'].includes(e.target.tagName)) {
                    input.focus();
                }
            });
        }
    </script>
@endscript