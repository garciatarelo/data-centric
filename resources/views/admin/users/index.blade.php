@extends('admin.layouts.main')

@section('header-title', 'Usuarios')

@section('content')
    <div class="bg-gray-800 shadow rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-xl font-semibold text-white">Lista de Usuarios</h1>
                <button type="button" 
                    @click="$dispatch('open-modal', 'userModal')"
                    class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-blue-600 transition duration-150 ease-in-out border border-gray-700">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Agregar Usuario
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
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Nombre</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Nombre de Usuario</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Correo</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Teléfono</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Rol</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800 divide-y divide-gray-700">
                        @foreach($users ?? [] as $user)
                            <tr class="hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $user->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $user->nickname ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $user->phone ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $user->role == 'admin' ? 'bg-purple-800 text-purple-100' : 'bg-green-800 text-green-100' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button type="button" 
                                        @click="$dispatch('open-modal', {id: 'editUserModal', userId: '{{ $user->id }}'})"
                                        class="text-blue-500 hover:text-blue-700 mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </button>
                                    <button type="button" 
                                        @click="$dispatch('open-modal', {id: 'deleteUserModal', userId: '{{ $user->id }}'})"
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

    <!-- Modal de Agregar Usuario -->
    <div id="userModal" 
        x-data="{ show: false }" 
        x-show="show" 
        @open-modal.window="if ($event.detail === 'userModal') show = true"
        @keydown.escape.window="show = false"
        class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center"
        x-cloak>
        <div class="relative p-4 w-full max-w-xl" @click.away="show = false">
            <div class="relative bg-gray-900 rounded-lg shadow-lg border border-gray-800">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white">Agregar Usuario</h3>
                    <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-300 focus:outline-none">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST" class="p-6">
                    @csrf
                    <div class="space-y-4 px-2">
                        <div>
                            <label for="name" class="block text-base font-medium text-white mb-2">Nombre</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" 
                                class="block w-full px-5 py-3 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                        </div>
                        <div>
                            <label for="username" class="block text-base font-medium text-white mb-2 mt-3">Usuario</label>
                            <input type="text" id="username" name="username" value="{{ old('username') }}" 
                                class="block w-full px-5 py-3 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                        </div>
                        <div>
                            <label for="email" class="block text-base font-medium text-white mb-2 mt-3">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" 
                                class="block w-full px-5 py-3 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-base font-medium text-white mb-2 mt-3">Contraseña</label>
                                <input type="password" id="password" name="password" 
                                    class="block w-full px-5 py-3 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                            </div>
                            <div>
                                <label for="phone" class="block text-base font-medium text-white mb-2 mt-3">Teléfono</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" 
                                    class="block w-full px-5 py-3 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            </div>
                        </div>
                        <div>
                            <label for="role" class="block text-base font-medium text-white mb-2 mt-3">Rol</label>
                            <select id="role" name="role" 
                                class="block w-full px-5 py-3 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                <option value="user" class="text-white bg-gray-800">Usuario</option>
                                <option value="admin" class="text-white bg-gray-800">Administrador</option>
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
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Editar Usuario -->
    <div id="editUserModal" 
        x-data="{ 
            show: false, 
            userId: null, 
            name: '', 
            username: '', 
            email: '', 
            phone: '', 
            role: 'user' 
        }" 
        x-show="show" 
        @open-modal.window="if ($event.detail.id === 'editUserModal') { 
            show = true; 
            userId = $event.detail.userId;
            fetch(`/admin/users/${userId}/edit`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    name = data.name || '';
                    username = data.username || '';
                    email = data.email || '';
                    phone = data.phone || '';
                    role = data.role || 'user';
                    $nextTick(() => {
                        document.getElementById('editUserForm').action = `/admin/users/${userId}`;
                    });
                })
                .catch(error => {
                    console.error('Error al cargar datos del usuario:', error);
                });
        }"
        @keydown.escape.window="show = false"
        class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center"
        x-cloak>
        <div class="relative p-4 w-full max-w-xl" @click.away="show = false">
            <div class="relative bg-gray-900 rounded-lg shadow-lg border border-gray-800">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white">Editar Usuario</h3>
                    <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-300 focus:outline-none">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <form id="editUserForm" action="" method="POST" class="p-6">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4 px-2">
                        <div>
                            <label for="edit_name" class="block text-base font-medium text-white mb-2">Nombre</label>
                            <input type="text" id="edit_name" name="name" x-model="name"
                                class="block w-full px-5 py-3 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                        </div>
                        <div>
                            <label for="edit_username" class="block text-base font-medium text-white mb-2 mt-3">Usuario</label>
                            <input type="text" id="edit_username" name="username" x-model="username"
                                class="block w-full px-5 py-3 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                        </div>
                        <div>
                            <label for="edit_email" class="block text-base font-medium text-white mb-2 mt-3">Email</label>
                            <input type="email" id="edit_email" name="email" x-model="email"
                                class="block w-full px-5 py-3 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="edit_password" class="block text-base font-medium text-white mb-2 mt-3">Contraseña</label>
                                <input type="password" id="edit_password" name="password" 
                                    class="block w-full px-5 py-3 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    placeholder="Dejar en blanco para no cambiar">
                            </div>
                            <div>
                                <label for="edit_phone" class="block text-base font-medium text-white mb-2 mt-3">Teléfono</label>
                                <input type="text" id="edit_phone" name="phone" x-model="phone"
                                    class="block w-full px-5 py-3 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            </div>
                        </div>
                        <div>
                            <label for="edit_role" class="block text-base font-medium text-white mb-2 mt-3">Rol</label>
                            <select id="edit_role" name="role" x-model="role"
                                class="block w-full px-5 py-3 text-base bg-gray-800 border border-gray-600 text-white rounded-lg 
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                <option value="user" class="text-white bg-gray-800">Usuario</option>
                                <option value="admin" class="text-white bg-gray-800">Administrador</option>
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

    <!-- Modal de Eliminar Usuario -->
    <div id="deleteUserModal" 
        x-data="{ show: false, userId: null }" 
        x-show="show" 
        @open-modal.window="if ($event.detail.id === 'deleteUserModal') { show = true; userId = $event.detail.userId; $nextTick(() => { document.getElementById('deleteUserForm').action = `/admin/users/${userId}`; }) }"
        @keydown.escape.window="show = false"
        class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center"
        x-cloak>
        <div class="relative p-4 w-full max-w-xl" @click.away="show = false">
            <div class="relative bg-gray-900 rounded-lg shadow-lg border border-gray-800">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white">Eliminar Usuario</h3>
                    <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-300 focus:outline-none">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <form id="deleteUserForm" action="" method="POST" class="p-6">
                    @csrf
                    @method('DELETE')
                    <div class="text-center px-2">
                        <svg class="w-16 h-16 mx-auto text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h2 class="text-xl font-normal text-white mt-4">¿Deseas eliminar este usuario?</h2>
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
        console.log('Sistema de modales Alpine.js inicializado');
    });
</script>
@endsection