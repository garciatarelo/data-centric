@extends('admin.layouts.main')

@section('header-title', 'Dashboard')

@section('content')
    <div class="bg-white shadow rounded-lg dark:bg-gray-800 mb-8">
        <div class="p-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Bienvenido al Panel de Administración</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1 -->
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 dark:bg-gray-700 dark:border-gray-600">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-blue-100 dark:bg-blue-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Dispositivos Totales</h3>
                            <p class="text-2xl font-semibold text-gray-700 dark:text-white">{{ $totalDevices ?? 10 }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Card 2 -->
                <div class="bg-green-50 p-4 rounded-lg border border-green-100 dark:bg-gray-700 dark:border-gray-600">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-green-100 dark:bg-green-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Dispositivos Disponibles</h3>
                            <p class="text-2xl font-semibold text-gray-700 dark:text-white">{{ $availableDevices ?? 5 }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Card 3 -->
                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-100 dark:bg-gray-700 dark:border-gray-600">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-yellow-100 dark:bg-yellow-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Asignaciones Activas</h3>
                            <p class="text-2xl font-semibold text-gray-700 dark:text-white">{{ $activeAssignments ?? 8 }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Card 4 -->
                <div class="bg-red-50 p-4 rounded-lg border border-red-100 dark:bg-gray-700 dark:border-gray-600">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-red-100 dark:bg-red-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Usuarios</h3>
                            <p class="text-2xl font-semibold text-gray-700 dark:text-white">{{ $totalUsers ?? 15 }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Assignments -->
            <div class="mt-8">
                <h3 class="text-lg font-medium mb-4 text-gray-800 dark:text-white">Asignaciones Recientes</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                    Dispositivo
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                    Usuario
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                    Fecha
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                    Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            <!-- Ejemplo de filas -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">iPad Pro</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">SN123456</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">Juan Pérez</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">10/10/2025</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Activo
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">iPhone 15</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">SN789012</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">María Gómez</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">08/10/2025</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Activo
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Samsung Galaxy Tab</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">SN345678</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">Carlos Rodríguez</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">05/10/2025</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        Devuelto
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection