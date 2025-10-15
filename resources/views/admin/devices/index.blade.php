@extends('admin.layouts.main')

@section('header-title', 'Dispositivos')

@section('content')
    <div class="bg-gray-800 shadow rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-xl font-semibold text-white">Lista de Dispositivos</h1>
                <button type="button" 
                    @click="$dispatch('open-modal', 'deviceModal')"
                    class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-blue-600 transition duration-150 ease-in-out border border-gray-700">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Agregar Dispositivo
                    </span>
                </button>
            </div>

            @if(session('success'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200" role="alert">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="overflow-x-auto mt-4 border border-gray-700 rounded-lg">
                <table class="min-w-full divide-y divide-gray-700 border-collapse">
                    <thead class="bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">#</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Serial</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Marca</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Modelo</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Tipo</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">IMEI</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Estado</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800 divide-y divide-gray-700">
                        @foreach($devices ?? [] as $device)
                            <tr class="hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $device->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $device->serial }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $device->brand }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $device->model }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $device->type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $device->imei ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $device->status == 'available' ? 'bg-green-800 text-green-100' : ($device->status == 'assigned' ? 'bg-blue-800 text-blue-100' : 'bg-red-800 text-red-100') }}">
                                        {{ ucfirst($device->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button type="button" 
                                        @click="$dispatch('open-modal', {id: 'editDeviceModal', deviceId: '{{ $device->id }}'})"
                                        class="text-blue-500 hover:text-blue-700 mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </button>
                                    <button type="button" 
                                        @click="$dispatch('open-modal', {id: 'deleteDeviceModal', deviceId: '{{ $device->id }}'})"
                                        class="text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal de Agregar Dispositivo -->
    <div id="deviceModal" 
        x-data="{ show: false }" 
        x-show="show" 
        @open-modal.window="if ($event.detail === 'deviceModal') show = true"
        @keydown.escape.window="show = false"
        class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-start justify-center p-4"
        x-cloak>
        <div class="relative w-full max-w-xl my-6" @click.away="show = false">
            <div class="relative bg-gray-900 rounded-lg shadow-lg border border-gray-800">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white">Agregar Dispositivo</h3>
                    <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-300 focus:outline-none">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <form action="{{ route('admin.devices.store') }}" method="POST" class="p-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Columna 1 -->
                        <div class="space-y-6">
                            <div>
                                <label for="serial" class="block text-base font-medium text-white mb-2">Serial</label>
                                <input type="text" id="serial" name="serial" value="{{ old('serial') }}" 
                                    class="block w-full px-4 py-2 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                            </div>
                            <div>
                                <label for="brand" class="block text-base font-medium text-white mb-2">Marca</label>
                                <input type="text" id="brand" name="brand" value="{{ old('brand') }}" 
                                    class="block w-full px-4 py-2 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                            </div>
                            <div>
                                <label for="model" class="block text-base font-medium text-white mb-2">Modelo</label>
                                <input type="text" id="model" name="model" value="{{ old('model') }}" 
                                    class="block w-full px-4 py-2 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                            </div>
                            <div>
                                <label for="type" class="block text-base font-medium text-white mb-2">Tipo</label>
                                <select id="type" name="type" 
                                    class="block w-full px-4 py-2 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                                    <option value="tablet" class="text-white bg-gray-800">Tablet</option>
                                    <option value="phone" class="text-white bg-gray-800">Teléfono</option>
                                    <option value="other" class="text-white bg-gray-800">Otro</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Columna 2 -->
                        <div class="space-y-6">
                            <div>
                                <label for="imei" class="block text-base font-medium text-white mb-2">IMEI</label>
                                <input type="text" id="imei" name="imei" value="{{ old('imei') }}" 
                                    class="block w-full px-4 py-2 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            </div>
                            <div>
                                <label for="status" class="block text-base font-medium text-white mb-2">Estado</label>
                                <select id="status" name="status" 
                                    class="block w-full px-4 py-2 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                                    <option value="available" class="text-white bg-gray-800">Disponible</option>
                                    <option value="assigned" class="text-white bg-gray-800">Asignado</option>
                                    <option value="maintenance" class="text-white bg-gray-800">Mantenimiento</option>
                                    <option value="retired" class="text-white bg-gray-800">Retirado</option>
                                </select>
                            </div>
                            <div>
                                <label for="notes" class="block text-base font-medium text-white mb-2">Notas</label>
                                <textarea id="notes" name="notes" rows="3"
                                    class="block w-full px-4 py-2 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 pt-6 mt-6 border-t border-gray-700">
                        <button type="button" @click="show = false" 
                            class="px-4 py-2 bg-gray-900 text-white rounded-lg border border-gray-700">
                            Cancelar
                        </button>
                        <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg border border-blue-600">
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Editar Dispositivo -->
    <div id="editDeviceModal" 
        x-data="{ 
            show: false, 
            deviceId: null, 
            serial: '', 
            brand: '', 
            model: '', 
            type: 'laptop',
            imei: '', 
            status: 'available',
            notes: ''
        }" 
        x-show="show" 
        class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-start justify-center p-4"
        @open-modal.window="if ($event.detail.id === 'editDeviceModal') { 
            show = true; 
            deviceId = $event.detail.deviceId;
            fetch(`/admin/devices/${deviceId}/edit`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    serial = data.serial || '';
                    brand = data.brand || '';
                    model = data.model || '';
                    type = data.type || 'laptop';
                    imei = data.imei || '';
                    status = data.status || 'available';
                    notes = data.notes || '';
                    $nextTick(() => {
                        document.getElementById('editDeviceForm').action = `/admin/devices/${deviceId}`;
                    });
                })
                .catch(error => {
                    console.error('Error al cargar datos del dispositivo:', error);
                });
        }"
        @keydown.escape.window="show = false"
        class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4"
        x-cloak>
        <div class="relative w-full max-w-xl my-6" @click.away="show = false">
            <div class="relative bg-gray-900 rounded-lg shadow-lg border border-gray-800">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white">Editar Dispositivo</h3>
                    <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-300 focus:outline-none">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <form :action="'/admin/devices/' + deviceId" method="POST" class="p-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Columna 1 -->
                        <div class="space-y-6">
                            <div>
                                <label for="edit_serial" class="block text-base font-medium text-white mb-2">Serial</label>
                                <input type="text" id="edit_serial" name="serial" x-model="serial"
                                    class="block w-full px-4 py-2 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                            </div>
                            <div>
                                <label for="edit_brand" class="block text-base font-medium text-white mb-2">Marca</label>
                                <input type="text" id="edit_brand" name="brand" x-model="brand"
                                    class="block w-full px-4 py-2 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                            </div>
                            <div>
                                <label for="edit_model" class="block text-base font-medium text-white mb-2">Modelo</label>
                                <input type="text" id="edit_model" name="model" x-model="model"
                                    class="block w-full px-4 py-2 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                            </div>
                            <div>
                                <label for="edit_type" class="block text-base font-medium text-white mb-2">Tipo</label>
                                <select id="edit_type" name="type" x-model="type"
                                    class="block w-full px-4 py-2 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                                    <option value="tablet" class="text-white bg-gray-800">Tablet</option>
                                    <option value="phone" class="text-white bg-gray-800">Teléfono</option>
                                    <option value="other" class="text-white bg-gray-800">Otro</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Columna 2 -->
                        <div class="space-y-6">
                            <div>
                                <label for="edit_imei" class="block text-base font-medium text-white mb-2">IMEI</label>
                                <input type="text" id="edit_imei" name="imei" x-model="imei"
                                    class="block w-full px-4 py-2 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            </div>
                            <div>
                                <label for="edit_status" class="block text-base font-medium text-white mb-2">Estado</label>
                                <select id="edit_status" name="status" x-model="status"
                                    class="block w-full px-4 py-2 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                                    <option value="available" class="text-white bg-gray-800">Disponible</option>
                                    <option value="assigned" class="text-white bg-gray-800">Asignado</option>
                                    <option value="maintenance" class="text-white bg-gray-800">Mantenimiento</option>
                                    <option value="retired" class="text-white bg-gray-800">Retirado</option>
                                </select>
                            </div>
                            <div>
                                <label for="edit_notes" class="block text-base font-medium text-white mb-2">Notas</label>
                                <textarea id="edit_notes" name="notes" rows="3" x-model="notes"
                                    class="block w-full px-4 py-2 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 pt-6 mt-6 border-t border-gray-700">
                        <button type="button" @click="show = false" 
                             class="px-4 py-2 bg-blue-600 text-white rounded-lg border border-blue-600">
                            Cancelar
                        </button>
                        <button type="submit" 
                             class="px-4 py-2 bg-blue-600 text-white rounded-lg border border-blue-600">
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Eliminar Dispositivo -->
    <div id="deleteDeviceModal" 
        x-data="{ show: false, deviceId: null }" 
        x-show="show" 
        @open-modal.window="if ($event.detail.id === 'deleteDeviceModal') { show = true; deviceId = $event.detail.deviceId; $nextTick(() => { document.getElementById('deleteDeviceForm').action = `/admin/devices/${deviceId}`; }) }"
        @keydown.escape.window="show = false"
        class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center"
        x-cloak>
        <div class="relative p-4 w-full max-w-xl" @click.away="show = false">
            <div class="relative bg-gray-900 rounded-lg shadow-lg border border-gray-800">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white">Eliminar Dispositivo</h3>
                    <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-300 focus:outline-none">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <form id="deleteDeviceForm" action="" method="POST" class="p-6">
                    @csrf
                    @method('DELETE')
                    <div class="text-center px-2">
                        <svg class="w-16 h-16 mx-auto text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h2 class="text-xl font-normal text-white mt-4">¿Deseas eliminar este dispositivo?</h2>
                        <p class="text-sm text-gray-400 mt-2">Esta acción no se puede deshacer.</p>
                    </div>
                    <div class="flex justify-end space-x-3 pt-6 mt-6 border-t border-gray-700">
                        <button type="button" @click="show = false" 
                            class="px-4 py-2 bg-gray-900 text-white rounded-lg border border-gray-700">
                            Cancelar
                        </button>
                        <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg border border-red-600">
                            Eliminar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Sistema de modales Alpine.js inicializado para dispositivos');
    });
</script>
@endsection