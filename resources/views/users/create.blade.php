<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New User') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('properties.users.store', $property) }}">
                @csrf

                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" required>
                    @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror" required>
                    @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('phone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password *</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror" required>
                    @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Roles * <span class="text-gray-500 font-normal">(Select one or more)</span></label>
                    <div class="grid grid-cols-2 gap-3 p-4 border border-gray-300 rounded-lg bg-gray-50">
                        @foreach($roles as $role)
                            <label class="flex items-center space-x-2 cursor-pointer hover:bg-white p-2 rounded">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                    @checked(is_array(old('roles')) && in_array($role->id, old('roles')))>
                                <span class="text-sm text-gray-700">{{ $role->display_name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('roles') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    @error('roles.*') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                    <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror" required>
                        <option value="active" @selected(old('status') == 'active')>Active</option>
                        <option value="inactive" @selected(old('status') == 'inactive')>Inactive</option>
                    </select>
                    @error('status') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Create User
                    </button>
                    <a href="{{ route('properties.users.index', $property) }}" class="flex-1 bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

