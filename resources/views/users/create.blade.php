<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <!-- Show validation errors if any -->
                    @if ($errors->any())
                        <div class="mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li class="text-red-500">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="block text-gray-700">{{ __('Name') }}</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="mt-1 block w-full rounded-md shadow-sm border-gray-300">
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-gray-700">{{ __('Email') }}</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded-md shadow-sm border-gray-300">
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-gray-700">{{ __('Password') }}</label>
                            <input id="password" type="password" name="password" required class="mt-1 block w-full rounded-md shadow-sm border-gray-300">
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-gray-700">{{ __('Confirm Password') }}</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required class="mt-1 block w-full rounded-md shadow-sm border-gray-300">
                        </div>

                        <!-- <div class="mb-4">
                            <label for="role" class="block text-gray-700">{{ __('Role') }}</label>
                            <select id="role" name="role" class="mt-1 block w-full rounded-md shadow-sm border-gray-300">
                                <option value="0">Admin</option>
                                <option value="1">User</option>
                            </select>
                        </div> -->

                        <div>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
                                {{ __('Create User') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
