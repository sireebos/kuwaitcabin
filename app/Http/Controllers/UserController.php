<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Property $property)
    {
        $users = $property->users()->with('roles')->paginate(15);
        return view('users.index', compact('property', 'users'));
    }

    public function create(Property $property)
    {
        $roles = Role::all();
        return view('users.create', compact('property', 'roles'));
    }

    public function store(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['property_id'] = $property->id;
        $validated['password'] = Hash::make($validated['password']);
        $validated['role_id'] = $validated['roles'][0]; // Set primary role for backward compatibility

        $roleIds = $validated['roles'];
        unset($validated['roles']);

        $user = User::create($validated);
        $user->roles()->sync($roleIds);

        return redirect()->route('properties.users.index', $property)
            ->with('success', 'User created successfully');
    }

    public function edit(Property $property, User $user)
    {
        $roles = Role::all();
        $user->load('roles');
        return view('users.edit', compact('property', 'user', 'roles'));
    }

    public function update(Request $request, Property $property, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['role_id'] = $validated['roles'][0]; // Set primary role for backward compatibility
        $roleIds = $validated['roles'];
        unset($validated['roles']);

        $user->update($validated);
        $user->roles()->sync($roleIds);

        return redirect()->route('properties.users.index', $property)
            ->with('success', 'User updated successfully');
    }

    public function destroy(Property $property, User $user)
    {
        $user->roles()->detach(); // Remove role associations
        $user->delete();

        return redirect()->route('properties.users.index', $property)
            ->with('success', 'User deleted successfully');
    }
}
