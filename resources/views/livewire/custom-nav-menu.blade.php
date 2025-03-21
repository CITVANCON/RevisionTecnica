<!-- component -->
<!--
Change class "fixed" to "sticky" in "navbar" (l. 33) so the navbar doesn't hide any of your page content!
-->
<div>
    <style>
        ul.breadcrumb li+li::before {
            content: "";
            padding-left: 8px;
            padding-right: 4px;
            color: inherit;
        }

        ul.breadcrumb li span {
            opacity: 60%;
        }

        #sidebar {
            -webkit-transition: all 300ms cubic-bezier(0, 0.77, 0.58, 1);
            transition: all 300ms cubic-bezier(0, 0.77, 0.58, 1);
        }

        #sidebar.show {
            transform: translateX(0);
        }

        #sidebar ul li a.active {
            background: #1f2937;
            background-color: #1f2937;
        }
    </style>

    <!-- Navbar start -->
    <nav id="navbar"
        class="sticky top-0 z-40 flex w-full flex-row justify-between bg-accent px-4 shadow-lg border-b">
        <button id="btnSidebarToggler" type="button" class="py-4 text-2xl text-white hover:text-black">
            <svg id="navClosed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="h-8 w-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
            <svg id="navOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="hidden h-8 w-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <a href="{{ route('dashboard') }}" class="py-2 h-1/2">
            <img src="{{ asset('images/logo.png') }}" width="150" />
        </a>

        <div class="hidden  md:flex  md:items-center">
            <x-dropdown width="48">
                <x-slot name="trigger">
                    <div class="m-4 inline-flex relative w-fit">
                        @if (Auth()->user()->unreadNotifications->count())
                            <span
                                class="absolute inline-block top-0 right-0 bottom-auto left-auto translate-x-2/4 -translate-y-1/2 rotate-0 skew-x-0 skew-y-0 scale-x-100 scale-y-100 rounded-full z-10">
                                <span
                                    class=" absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75 animate-ping"></span>
                                <span
                                    class="relative inline-flex rounded-full px-2 text-white bg-indigo-500 items-center m-auto text-xs">
                                    {{ Auth()->user()->unreadNotifications->count() }}
                                </span>
                            </span>
                        @else
                        @endif
                        <div class="flex items-center justify-center text-center">
                            <i class="fas fa-bell fa-xl text-white hover:text-black"></i>
                        </div>

                    </div>

                </x-slot>
                <x-slot name="content">
                    <!-- Account Management -->

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Tienes ' . Auth()->user()->unreadNotifications->count() . ' notificaciones') }}
                    </div>
                    @foreach (Auth()->user()->unreadNotifications as $notification)
                    @endforeach
                </x-slot>
            </x-dropdown>
            <x-dropdown width="48">
                <x-slot name="trigger">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <button
                            class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                            <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                                alt="{{ Auth::user()->name }}" />
                        </button>
                    @else
                        <span class="inline-flex rounded-md">
                            <button type="button"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white  hover:text-black focus:outline-none transition">
                                <i class="fa-solid fa-user-gear fa-lg"></i>
                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </span>
                    @endif
                </x-slot>

                <x-slot name="content">
                    <!-- Account Management -->
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Administrar Cuenta') }}
                    </div>

                    <x-dropdown-link href="{{ route('profile.show') }}">
                        {{ __('Perfil') }}
                    </x-dropdown-link>

                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                        <x-dropdown-link href="{{ route('api-tokens.index') }}">
                            {{ __('API Tokens') }}
                        </x-dropdown-link>
                    @endif

                    <div class="border-t border-gray-100"></div>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf

                        <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                            {{ __('Salir') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>

    </nav>
    <!-- Navbar end -->

    <!-- Sidebar start-->
    <div id="containerSidebar" class="z-40">
        <div class="navbar-menu relative z-40">
            <nav id="sidebar"
                class="fixed left-0 bottom-0 flex w-3/4 -translate-x-full flex-col bg-secondary pt-2  sm:max-w-xs lg:w-80">
                <!-- one category / navigation group -->
                <div class="px-4 overflow-y-auto">
                    <h3 class="mb-2 text-xs font-medium uppercase text-gray-300">
                        Menu principal
                    </h3>
                    <ul class="text-sm font-medium">
                        <li>
                            <a class="flex items-center rounded py-3 pl-3 pr-4  space-x-6 text-gray-50 hover:bg-accent"
                                href="{{ route('dashboard') }}">
                                <i class="fas fa-home -mt-1"></i>
                                <span class="select-none">Inicio</span>
                            </a>
                        </li>

                    </ul>
                </div>

                <!-- navigation group end-->

                <!-- opciones de cuenta de usuario -->
                <div class="md:hidden block bg-gray-700 bottom-0 left-0 px-4 w-full z-10 mt-2">
                    <h3 class="my-2 text-xs font-medium uppercase text-gray-500">
                        Opciones de la cuenta
                    </h3>
                    <ul class="mb-2 text-sm font-medium ">
                        <li>
                            <a class="flex items-center rounded py-3 pl-3 pr-4  space-x-6 text-gray-50 hover:bg-gray-600 "
                                href="{{ route('profile.show') }}">
                                <i class="fa-solid fa-user-gear -mt-1"></i>
                                <span class="select-none">Configurar Perfil</span>
                            </a>
                        </li>
                        <li>


                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <a class="flex items-center rounded py-3 pl-3 pr-4  space-x-6 text-gray-50 hover:bg-gray-600 "
                                    href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                    <i class="fa-solid fa-arrow-right-from-bracket -mt-1"></i>
                                    <span class="select-none">Salir</span>
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
                <!-- fin -->
            </nav>
        </div>

    </div>
    <!-- Sidebar end -->

    <main>
        <!-- your content goes here -->
    </main>

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", () => {
            const navbar = document.getElementById("navbar");
            const sidebar = document.getElementById("sidebar");
            const btnSidebarToggler = document.getElementById("btnSidebarToggler");
            const navClosed = document.getElementById("navClosed");
            const navOpen = document.getElementById("navOpen");

            btnSidebarToggler.addEventListener("click", (e) => {
                e.preventDefault();
                sidebar.classList.toggle("show");
                navClosed.classList.toggle("hidden");
                navOpen.classList.toggle("hidden");
            });

            sidebar.style.top = parseInt(navbar.clientHeight) - 1 + "px";
        });
    </script>
</div>
