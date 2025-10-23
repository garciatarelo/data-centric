@extends('admin.layouts.main')

@section('header-title', 'Asignaciones')

@section('content')
    <div class="bg-gray-800 shadow rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-xl font-semibold text-white">Lista de Asignaciones</h1>
                <button type="button" 
                    @click="$dispatch('open-modal', 'assignmentModal')"
                    class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-blue-600 transition duration-150 ease-in-out border border-gray-700">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nueva Asignación
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
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Usuario</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Asignado por</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Dispositivo</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Fecha Asignación</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Fecha Devolución</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Carta Poder</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Estado</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800 divide-y divide-gray-700">
                        @foreach($assignments ?? [] as $assignment)
                            <tr class="hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $assignment->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $assignment->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $assignment->assignedBy->name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $assignment->device->brand }} {{ $assignment->device->model }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $assignment->assigned_at }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $assignment->returned_at ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    @if($assignment->power_of_attorney)
                                        <a href="{{ route('admin.assignments.download-pdf', $assignment->id) }}" target="_blank" 
                                           class="text-blue-500 hover:text-blue-400">
                                            Ver PDF
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $assignment->status == 'active' ? 'bg-green-800 text-green-100' : 
                                           ($assignment->status == 'returned' ? 'bg-blue-800 text-blue-100' : 'bg-red-800 text-red-100') }}">
                                        {{ ucfirst($assignment->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button type="button" 
                                        @click="$dispatch('open-modal', {id: 'editAssignmentModal', assignmentId: '{{ $assignment->id }}'})"
                                        class="text-blue-500 hover:text-blue-700 mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </button>
                                    <button type="button" 
                                        @click="$dispatch('open-modal', {id: 'deleteAssignmentModal', assignmentId: '{{ $assignment->id }}'})"
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

    <!-- Modal de Agregar Asignación -->
    <div id="assignmentModal" 
        x-data="{ show: false }" 
        x-show="show" 
        @open-modal.window="if ($event.detail === 'assignmentModal') show = true"
        @keydown.escape.window="show = false"
        class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-start justify-center p-4"
        x-cloak>
        <div class="relative w-full max-w-xl my-6" @click.away="show = false">
            <div class="relative bg-gray-900 rounded-lg shadow-lg border border-gray-800">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white">Nueva Asignación</h3>
                    <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-300 focus:outline-none">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <form action="{{ route('admin.assignments.store') }}" 
                    method="POST" 
                    @submit.prevent="
                        const formData = new FormData($event.target);
                        const btn = $event.target.querySelector('button[type=submit]');
                        btn.disabled = true;
                        btn.textContent = 'Procesando...';
                        
                        fetch('{{ route('admin.assignments.store') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(async response => {
                            console.log('Status:', response.status);
                            const ct = response.headers.get('content-type') || '';
                            if (!response.ok) {
                                // intenta parsear JSON de error si viene
                                if (ct.includes('application/json')) {
                                    const errJson = await response.json();
                                    throw new Error(errJson.message || 'Error del servidor');
                                }
                                const text = await response.text();
                                console.error('Respuesta de error no-JSON:', text);
                                throw new Error('Error del servidor: ' + response.status);
                            }
                            if (ct.includes('application/json')) {
                                return await response.json();
                            } else {
                                const text = await response.text();
                                console.error('Respuesta no JSON:', text.substring(0, 200));
                                throw new Error('Respuesta inválida del servidor');
                            }
                        })
                        .then(data => {
                            console.log('Data recibida:', data);
                            if (data.success) {
                                window.location.reload();
                            } else {
                                btn.disabled = false;
                                btn.textContent = 'Asignar';
                                alert(data.message || 'Error desconocido');
                            }
                        })
                        .catch(error => {
                            console.error('Error completo:', error);
                            btn.disabled = false;
                            btn.textContent = 'Asignar';
                            alert('Error: ' + error.message);
                        });
                    "
                    class="p-6">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="user_id" class="block text-base font-medium text-white mb-2">Usuario</label>
                            <select id="user_id" name="user_id" 
                                class="block w-full px-4 py-2 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                                <option value="">Seleccionar usuario</option>
                                @foreach($users ?? [] as $user)
                                    <option value="{{ $user->id }}" class="text-white bg-gray-800">
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="device_id" class="block text-base font-medium text-white mb-2">Dispositivo</label>
                            <select id="device_id" name="device_id" 
                                class="block w-full px-4 py-2 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                                <option value="">Seleccionar dispositivo</option>
                                @forelse($availableDevices ?? [] as $device)
                                    <option value="{{ $device->id }}" class="text-white bg-gray-800">
                                        {{ $device->brand }} {{ $device->model }} ({{ $device->serial }})
                                    </option>
                                @empty
                                    <option value="" disabled class="text-gray-400">No hay dispositivos disponibles</option>
                                @endforelse
                            </select>
                            @if(($availableDevices ?? collect())->isEmpty())
                                <p class="text-sm text-white mt-2">Todos los dispositivos están asignados o no disponibles.</p>
                            @endif
                        </div>

                    </div>
                    <div class="flex justify-end space-x-3 pt-6 mt-6 border-t border-gray-700">
                        <button type="button" @click="show = false" 
                            class="px-4 py-2 bg-gray-900 text-white rounded-lg border border-gray-700">
                            Cancelar
                        </button>
                        <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg border border-blue-600">
                            Asignar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Editar Asignación -->
    <div id="editAssignmentModal" 
        x-data="{ 
            show: false, 
            assignmentId: null,
            userId: '',
            deviceId: '',
            assignedDate: '',
            returnedAt: '',
            status: 'active',
            notes: ''
        }" 
        x-show="show" 
        @open-modal.window="if ($event.detail.id === 'editAssignmentModal') { 
            show = true; 
            assignmentId = $event.detail.assignmentId;
            fetch(`/admin/assignments/${assignmentId}/edit`)
                .then(response => response.json())
                .then(data => {
                    userId = data.user_id;
                    deviceId = data.device_id;
                    assignedDate = data.assigned_date;
                    returnedAt = data.return_date || '';
                    status = data.status;
                    notes = data.notes || '';
                    $nextTick(() => {
                        document.getElementById('editAssignmentForm').action = `/admin/assignments/${assignmentId}`;
                    });
                });
        }"
        @keydown.escape.window="show = false"
        class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center"
        x-cloak>
        <div class="relative w-full max-w-xl my-6" @click.away="show = false">
            <div class="relative bg-gray-900 rounded-lg shadow-lg border border-gray-800">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white">Editar Asignación</h3>
                    <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-300 focus:outline-none">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <form id="editAssignmentForm" method="POST" class="p-6">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                        <div>
                            <label for="edit_user_id" class="block text-base font-medium text-white mb-2">Usuario</label>
                            <select id="edit_user_id" name="user_id" x-model="userId"
                                class="block w-full px-4 py-2 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                                @foreach($users ?? [] as $user)
                                    <option value="{{ $user->id }}" class="text-white bg-gray-800">
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="edit_device_id" class="block text-base font-medium text-white mb-2">Dispositivo</label>
                            <select id="edit_device_id" name="device_id" x-model="deviceId"
                                class="block w-full px-4 py-2 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                                @foreach($devices ?? [] as $device)
                                    <option value="{{ $device->id }}" class="text-white bg-gray-800">
                                        {{ $device->brand }} {{ $device->model }} ({{ $device->serial }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="edit_returned_at" class="block text-base font-medium text-white mb-2">Fecha de Devolución</label>
                            <input type="datetime-local" id="edit_returned_at" name="returned_at" x-model="returnedAt"
                                class="block w-full px-4 py-2 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>

                        <div>
                            <label for="edit_status" class="block text-base font-medium text-white mb-2">Estado</label>
                            <select id="edit_status" name="status" x-model="status"
                                class="block w-full px-4 py-2 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                                <option value="active" class="text-white bg-gray-800">Activa</option>
                                <option value="returned" class="text-white bg-gray-800">Devuelto</option>
                                <option value="cancelled" class="text-white bg-gray-800">Cancelada</option>
                            </select>
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

    <!-- Modal de Eliminar Asignación -->
    <div id="deleteAssignmentModal" 
        x-data="{ show: false, assignmentId: null }" 
        x-show="show" 
        @open-modal.window="if ($event.detail.id === 'deleteAssignmentModal') { 
            show = true; 
            assignmentId = $event.detail.assignmentId; 
            $nextTick(() => { 
                document.getElementById('deleteAssignmentForm').action = `/admin/assignments/${assignmentId}`; 
            }); 
        }"
        @keydown.escape.window="show = false"
        class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center"
        x-cloak>
        <div class="relative p-4 w-full max-w-xl" @click.away="show = false">
            <div class="relative bg-gray-900 rounded-lg shadow-lg border border-gray-800">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white">Eliminar Asignación</h3>
                    <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-300 focus:outline-none">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <form id="deleteAssignmentForm" action="" method="POST" class="p-6">
                    @csrf
                    @method('DELETE')
                    <div class="text-center px-2">
                        <svg class="w-16 h-16 mx-auto text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h2 class="text-xl font-normal text-white mt-4">¿Deseas eliminar esta asignación?</h2>
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
        console.log('Sistema de modales Alpine.js inicializado para asignaciones');
    });
</script>
@endsection