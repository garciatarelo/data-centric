<header class="sticky top-0 z-40 flex w-full bg-white border-b border-gray-200 dark:border-gray-800 dark:bg-gray-900">
    <div class="flex items-center justify-between w-full px-4 py-3 lg:px-6">
        <!-- Left side - Mobile menu button -->
        <div class="flex items-center">
            <button
                class="p-2 mr-3 text-gray-600 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700"
                @click="toggleSidebar"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <span class="sr-only">Toggle menu</span>
            </button>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                @yield('header-title', 'Dashboard')
            </h2>
        </div>

        <!-- Right side - User menu -->
        <div class="flex items-center space-x-3">

            <!-- User dropdown -->
            <div class="relative" x-data="{ isOpen: false }">
                <button
                    @click="isOpen = !isOpen"
                    class="flex items-center text-sm font-medium text-gray-700 rounded-full hover:text-blue-600 dark:text-gray-300"
                >
                    <span class="sr-only">Open user menu</span>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full overflow-hidden">
                            @if(Auth::user()->img)
                                <img src="{{ asset('img/' . Auth::user()->img) }}" alt="{{ Auth::user()->name }}" class="h-full w-full object-cover">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full text-gray-500 bg-gray-200" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </div>
                        <span class="ml-2 hidden sm:block">{{ Auth::user()->name }}</span>
                        <svg class="w-5 h-5 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>

                <!-- Dropdown menu -->
                <div
                    x-show="isOpen"
                    @click.away="isOpen = false"
                    class="absolute right-0 z-50 w-48 py-1 mt-2 origin-top-right bg-white rounded-lg shadow-lg dark:bg-gray-800"
                >
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                        Perfil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
