<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Rescue Team') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">{{ __("Edit Rescue Team") }}</h3>

                    <!-- Form to Edit a Rescue Team -->
                    <form action="{{ route('rescue_team.update', $rescueTeam->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Team Name Field -->
                        <div class="mb-4">
                            <label for="team_name" class="block text-sm font-medium text-gray-700">{{ __('Team Name') }}</label>
                            <input type="text" name="team_name" id="team_name"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                value="{{ old('team_name', $rescueTeam->team_name) }}" required>
                        </div>
                        
                        <!-- Submit Button -->
                        <div>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
                                {{ __('Update') }}
                            </button>
                            <a href="{{ route('rescue_team.index') }}"
                                class="ml-2 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-700">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
