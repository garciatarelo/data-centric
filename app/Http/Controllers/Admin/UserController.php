<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['nullable', 'string', 'in:admin,user'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'nickname' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => $request->role ?? 'user',
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, User $user)
    {
        // Si la petición espera JSON, retornar los datos del usuario
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->nickname,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
            ]);
        }
        
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'string', 'in:admin,user'],
        ];

        // Solo validar la contraseña si se está actualizando
        if ($request->filled('password')) {
            $rules['password'] = ['string', 'min:8'];
        }

        $request->validate($rules);

        $user->name = $request->name;
        $user->nickname = $request->username;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // No permitir eliminar al usuario administrador principal
        if ($user->email === 'admin@gmail.com') {
            return redirect()->route('admin.users.index')->with('error', 'No se puede eliminar al usuario administrador principal');
        }
        
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente');
    }
}