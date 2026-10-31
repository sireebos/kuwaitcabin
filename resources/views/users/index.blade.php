<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('User Management') }}
            </h2>
            <a href="{{ route('properties.users.create', $property) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Add User
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Phone</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Role</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Bookings</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-sm">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-sm">{{ $user->phone ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->roles as $role)
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            @if($role->name === 'admin') bg-red-100 text-red-800
                                            @elseif($role->name === 'manager') bg-blue-100 text-blue-800
                                            @elseif($role->name === 'receptionist') bg-green-100 text-green-800
                                            @elseif($role->name === 'bartender' || $role->name === 'bar_manager') bg-purple-100 text-purple-800
                                            @elseif($role->name === 'waiter' || $role->name === 'chef') bg-orange-100 text-orange-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $role->display_name }}
                                        </span>
                                    @empty
                                        <span class="text-gray-400">No roles</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($user->status === 'active') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $user->bookings()->count() }}</td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <a href="{{ route('properties.users.edit', [$property, $user]) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                <form method="POST" action="{{ route('properties.users.destroy', [$property, $user]) }}" class="inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>

