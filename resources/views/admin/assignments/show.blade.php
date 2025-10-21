@extends('admin.layouts.main')

@section('header-title', 'Detalles de la Asignación')

@section('content')
    <div class="bg-gray-800 shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-semibold text-white">Detalles de la Asignación #{{ $assignment->id }}</h1>
            <a href="{{ route('admin.assignments.index') }}" 
               class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-700 transition duration-150 ease-in-out border border-gray-700">
                Volver a la lista
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Información del Dispositivo -->
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-700">
                <h2 class="text-lg font-semibold text-white mb-4">Información del Dispositivo</h2>
                <div class="space-y-3">
                    <div>
                        <label class="text-gray-400">Marca:</label>
                        <p class="text-white">{{ $assignment->device->brand }}</p>
                    </div>
                    <div>
                        <label class="text-gray-400">Modelo:</label>
                        <p class="text-white">{{ $assignment->device->model }}</p>
                    </div>
                    <div>
                        <label class="text-gray-400">Número de Serie:</label>
                        <p class="text-white">{{ $assignment->device->serial }}</p>
                    </div>
                </div>
            </div>

            <!-- Información del Usuario -->
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-700">
                <h2 class="text-lg font-semibold text-white mb-4">Información del Usuario</h2>
                <div class="space-y-3">
                    <div>
                        <label class="text-gray-400">Nombre:</label>
                        <p class="text-white">{{ $assignment->user->name }}</p>
                    </div>
                    <div>
                        <label class="text-gray-400">Email:</label>
                        <p class="text-white">{{ $assignment->user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Detalles de la Asignación -->
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-700">
                <h2 class="text-lg font-semibold text-white mb-4">Detalles de la Asignación</h2>
                <div class="space-y-3">
                    <div>
                        <label class="text-gray-400">Estado:</label>
                        <span class="px-2 py-1 text-sm font-semibold rounded-full 
                            {{ $assignment->status == 'active' ? 'bg-green-800 text-green-100' : 
                               ($assignment->status == 'returned' ? 'bg-blue-800 text-blue-100' : 'bg-red-800 text-red-100') }}">
                            {{ ucfirst($assignment->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="text-gray-400">Fecha de Asignación:</label>
                        <p class="text-white">{{ $assignment->assigned_at->locale('es')->isoFormat('DD [de] MMMM [de] YYYY, HH:mm') }}</p>
                    </div>
                    @if($assignment->returned_at)
                    <div>
                        <label class="text-gray-400">Fecha de Devolución:</label>
                        <p class="text-white">{{ $assignment->returned_at->locale('es')->isoFormat('DD [de] MMMM [de] YYYY, HH:mm') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Documentos -->
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-700">
                <h2 class="text-lg font-semibold text-white mb-4">Documentos</h2>
                <div class="space-y-3">
                    <div>
                        <label class="text-gray-400">Carta Poder:</label>
                        @if($assignment->power_of_attorney)
                            <div class="flex items-center space-x-4 mt-2">
                                <a href="{{ route('admin.assignments.download-pdf', $assignment) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-150 ease-in-out">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Descargar PDF
                                </a>
                                <a href="{{ asset('storage/' . $assignment->power_of_attorney) }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition duration-150 ease-in-out">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Ver PDF
                                </a>
                            </div>
                        @else
                            <p class="text-gray-500">No hay carta poder disponible</p>
                        @endif
                    </div>
                    
                    <!-- QR Code -->
                    <div class="mt-4">
                        <label class="text-gray-400">Código QR:</label>
                        <div class="mt-2">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('admin.assignments.show', $assignment->id)) }}" 
                                 alt="QR Code" 
                                 class="w-32 h-32 bg-white p-2 rounded-lg">
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Este código QR contiene el enlace a esta página de detalles</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection