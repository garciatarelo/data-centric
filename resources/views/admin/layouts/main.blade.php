<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Data-Centric') }} - Panel de Administración</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine JS y Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Ensure content fills available space */
        #app {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        main {
            flex: 1;
        }
        
        /* Fix for Safari scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #1f2937;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
    </style>
</head>
<body 
    x-data="{ 
        sidebarToggle: false,
        toggleSidebar() { this.sidebarToggle = !this.sidebarToggle; }
    }" 
    class="dark bg-gray-900"
>
    <!-- Page Wrapper -->
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('admin.layouts.sidebar')

        <!-- Content Area -->
        <div class="relative flex flex-1 flex-col overflow-x-hidden overflow-y-auto bg-gray-900">
            <!-- Header -->
            @include('admin.layouts.nav')

            <!-- Main Content -->
            <main class="flex-1">
                <div class="container px-4 py-6 mx-auto md:px-6 md:py-8">
                    @if (session('status'))
                        <div class="p-4 mb-6 text-green-700 bg-green-100 border border-green-400 rounded-lg dark:bg-green-800 dark:text-green-200">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="p-4 mb-6 text-red-700 bg-red-100 border border-red-400 rounded-lg dark:bg-red-800 dark:text-red-200">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            @include('admin.layouts.footer')
        </div>
    </div>

    @stack('scripts')
</body>
</html>
