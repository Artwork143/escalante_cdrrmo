<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Disaster Types') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Button to create new disaster type -->
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">{{ __("List of Disaster Types") }}</h3>

                        @if (session('error'))
                        <div class="alert alert-danger bg-red-500 text-white w-1/2 mb-4 pl-4">
                            {{ session('error') }}
                        </div>
                        @endif
                        <a href="{{ route('disaster_type.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
                            {{ __('Create New Disaster Type') }}
                        </a>
                    </div>

                    @if ($disasterTypes->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NO</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($disasterTypes as $type)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $type->type_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <!-- Edit button -->
                                    <a href="{{ route('disaster_type.edit', $type->id) }}" class="px-4 py-[10.5px] bg-yellow-500 text-white rounded-lg hover:bg-yellow-700 mr-2">
                                        {{ __('Edit') }}
                                    </a>

                                    <!-- Delete button (with confirmation) -->
                                    <form action="{{ route('disaster_type.destroy', $type->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-700" onclick="return confirm('Are you sure you want to delete this disaster type?')">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p>{{ __("No disaster types found.") }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>