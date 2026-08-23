<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->filled('role')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $request->role));
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%")
                    ->orWhere('phone', 'ilike', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15);
        $roles = Role::all();

        return \Inertia\Inertia::render('Users/Index', [
            'users' => $users,
            'roles' => $roles,
            'filters' => request()->only(['search', 'role', 'status'])
        ]);
    }

    public function create()
    {
        $roles = Role::all();
        return response()->json([
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'base_salary' => 'nullable|numeric|min:0',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'base_salary' => $validated['base_salary'] ?? 0,
            'is_active' => true,
        ];

        if ($request->hasFile('avatar')) {
            $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create($userData);
        $user->roles()->attach($validated['role_id']);

        return response()->json(['message' => 'Foydalanuvchi muvaffaqiyatli yaratildi.']);
    }

    public function show(User $user)
    {
        $user->load('roles');
        return response()->json(['user' => $user]);
    }

    public function edit(User $user)
    {
        $user->load('roles');
        $roles = Role::all();
        return response()->json([
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'base_salary' => 'nullable|numeric|min:0',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'base_salary' => $validated['base_salary'] ?? 0,
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($userData);
        $user->roles()->sync([$validated['role_id']]);

        return response()->json(['message' => 'Foydalanuvchi muvaffaqiyatli yangilandi.']);
    }

    public function destroy(User $user)
    {
        if ($user->isSuperAdmin()) {
            return response()->json(['message' => 'Super Admin o\'chirib bo\'lmaydi.']);
        }

        $user->roles()->detach();
        $user->delete();

        return response()->json(['message' => 'Foydalanuvchi o\'chirildi.']);
    }

    public function toggleActive(User $user)
    {
        if ($user->isSuperAdmin()) {
            return response()->json(['message' => 'Super Admin faolsizlantirib bo\'lmaydi.']);
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'faollashtirildi' : 'faolsizlantirildi';

        return response()->json(['message' => "Foydalanuvchi {$status}."]);
    }
}
