<aside
    :class="sidebarToggle ? 'translate-x-0 lg:w-24' : '-translate-x-full lg:translate-x-0'"
    class="fixed left-0 top-0 z-50 flex h-screen w-64 flex-col overflow-y-hidden bg-white shadow-lg border-r border-gray-200 dark:border-gray-800 dark:bg-gray-900 lg:static"
>
    <!-- SIDEBAR HEADER -->
    <div
        :class="sidebarToggle ? 'justify-center px-5' : 'justify-between px-5'"
        class="flex items-center gap-2 pt-6 pb-5 sidebar-header"
    >
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name') }}" class="h-10">
            <span :class="sidebarToggle ? 'hidden' : 'ml-3 text-xl font-semibold'" class="text-gray-800 dark:text-white">
                {{ config('app.name') }}
            </span>
        </a>
        
        <button 
            @click="toggleSidebar"
            class="block lg:hidden"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- SIDEBAR MENU -->
    <div class="flex flex-col overflow-y-auto duration-300 ease-linear">
        <nav class="mt-5 px-4">
            <!-- Dashboard Link -->
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 mb-2 text-gray-600 rounded-lg hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 {{ request()->routeIs('dashboard') ? 'bg-gray-100 dark:bg-gray-800' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                <span :class="sidebarToggle ? 'lg:hidden' : ''" class="ml-3">Dashboard</span>
            </a>

              <!-- Users Link -->
            <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 mb-2 text-gray-600 rounded-lg hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 dark:bg-gray-800' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
                <span :class="sidebarToggle ? 'lg:hidden' : ''" class="ml-3">Usuarios</span>
            </a>
            
            <!-- Devices Link -->
            <a href="{{ route('admin.devices.index') }}" class="flex items-center px-4 py-3 mb-2 text-gray-600 rounded-lg hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 {{ request()->routeIs('admin.devices.*') ? 'bg-gray-100 dark:bg-gray-800' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
                <span :class="sidebarToggle ? 'lg:hidden' : ''" class="ml-3">Dispositivos</span>
            </a>

            <!-- Assignments Link -->
            <a href="{{ route('admin.assignments.index') }}" class="flex items-center px-4 py-3 mb-2 text-gray-600 rounded-lg hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 {{ request()->routeIs('admin.assignments.*') ? 'bg-gray-100 dark:bg-gray-800' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                </svg>
                <span :class="sidebarToggle ? 'lg:hidden' : ''" class="ml-3">Asignaciones</span>
            </a>

          
        </nav>
    </div>
</aside>
