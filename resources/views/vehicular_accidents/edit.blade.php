<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Vehicular Accident') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('vehicular_accidents.update', $vehicularAccident->id) }}" id="vehicular-accident-form">
                        @csrf
                        @method('PUT')

                        <!-- Date -->
                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700">{{ __('Date') }}</label>
                            <input type="date" name="date" id="date" value="{{ old('date', $vehicularAccident->date) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>

                        <!-- Rescue Team (Dropdown) -->
                        <div class="mb-4">
                            <label for="rescue_team" class="block text-sm font-medium text-gray-700">{{ __('Rescue Team') }}</label>
                            <select name="rescue_team" id="rescue_team"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value=""></option>
                                @foreach(['Alpha', 'Bravo', 'Charlie'] as $team)
                                    <option value="{{ $team }}" {{ old('rescue_team', $vehicularAccident->rescue_team) == $team ? 'selected' : '' }}>
                                        {{ $team }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Barangay (Dropdown) -->
                        <div class="mb-4">
                            <label for="barangay" class="block text-sm font-medium text-gray-700">{{ __('Barangay') }}</label>
                            <select name="barangay" id="barangay"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value=""></option>
                                @foreach([
                                    'Alimango', 'Balintawak', 'Binaguiohan', 'Buenavista', 'Cervantes',
                                    'Dian-ay', 'Hacienda Fe', 'Japitan', 'Jonob-jonob', 'Langub',
                                    'Libertad', 'Mabini', 'Magsaysay', 'Malasibog', 'Old Poblacion',
                                    'Paitan', 'Pinagpugasan', 'Rizal', 'Tamlang', 'Udtongan', 'Washington'
                                ] as $barangay)
                                    <option value="{{ $barangay }}" {{ old('barangay', $vehicularAccident->barangay) == $barangay ? 'selected' : '' }}>
                                        {{ $barangay }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Specific Location -->
                        <div class="mb-4">
                            <label for="place_of_incident" class="block text-sm font-medium text-gray-700">{{ __('Specific Location') }}</label>
                            <input type="text" name="place_of_incident" id="place_of_incident"
                                value="{{ old('place_of_incident', $vehicularAccident->place_of_incident) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>

                        <!-- Number of Patients -->
                        <div class="mb-4">
                            <label for="no_of_patients" class="block text-sm font-medium text-gray-700">{{ __('Number of Patients') }}</label>
                            <input type="number" name="no_of_patients" id="no_of_patients"
                                value="{{ old('no_of_patients', $vehicularAccident->no_of_patients) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>

                        <!-- Cause of Incident -->
                        <div class="mb-4">
                            <label for="cause_of_incident" class="block text-sm font-medium text-gray-700">{{ __('Cause of Incident') }}</label>
                            <select name="cause_of_incident" id="cause_of_incident"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value=""></option>
                                @foreach([
                                    'Over Speeding', 'Mechanical Failure', 'Collision', 'Hit & Run', 'Road Condition',
                                    'Drunk Driving', 'Distracted Driving', 'Weather Conditions', 'Reckless Driving',
                                    'Overloading', 'Improper Turning', 'Pedestrian Error', 'Fatigue', 'Road Hazards',
                                    'Lack of Signage', 'Other'
                                ] as $cause)
                                    <option value="{{ $cause }}" {{ old('cause_of_incident', $vehicularAccident->cause_of_incident) == $cause ? 'selected' : '' }}>
                                        {{ $cause }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Vehicles Involved -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">{{ __('Vehicles Involved') }}</label>
                            <div class="mt-2 grid grid-cols-2 gap-4">
                                @php
                                    $vehicles = ['Motorcycle', 'Car', 'Tricycle', 'Van', 'Bus', 'Truck'];
                                @endphp
                                @foreach($vehicles as $vehicle)
                                    <div>
                                        <input type="checkbox" name="vehicles_involved[]" value="{{ $vehicle }}" id="vehicle_{{ strtolower($vehicle) }}" class="mr-2"
                                            onclick="toggleVehicleInput('{{ strtolower($vehicle) }}')"
                                            {{ in_array($vehicle, old('vehicles_involved', explode(', ', $vehicularAccident->vehicles_involved))) ? 'checked' : '' }}>
                                        <label for="vehicle_{{ strtolower($vehicle) }}">{{ $vehicle }}</label>
                                        <div id="{{ strtolower($vehicle) }}_input"
                                            class="mt-2 {{ in_array($vehicle, old('vehicles_involved', explode(', ', $vehicularAccident->vehicles_involved))) ? '' : 'hidden' }}">
                                            <label for="{{ strtolower($vehicle) }}_type" class="block text-sm font-medium text-gray-700">{{ __('Specific ' . $vehicle) }}</label>
                                            <input type="text" name="{{ strtolower($vehicle) }}_type" id="{{ strtolower($vehicle) }}_type"
                                                value="{{ old(strtolower($vehicle) . '_type', $vehicularAccident->{strtolower($vehicle) . '_type'}) }}"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Facility Name -->
                        <div class="mb-4">
                            <label for="facility_name" class="block text-sm font-medium text-gray-700">{{ __('Facility Name') }}</label>
                            <input type="text" name="facility_name" id="facility_name"
                                value="{{ old('facility_name', $vehicularAccident->facility_name) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <a href="{{ route('vehicular_accidents.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-700 mr-2">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
                                {{ __('Update Vehicular Accident') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for dynamic toggling -->
    <script>
        function toggleVehicleInput(vehicle) {
            const inputContainer = document.getElementById(`${vehicle}_input`);
            const checkbox = document.getElementById(`vehicle_${vehicle}`);
            if (checkbox.checked) {
                inputContainer.classList.remove('hidden');
            } else {
                inputContainer.classList.add('hidden');
            }
        }

        // Initialize visibility on page load
        document.addEventListener('DOMContentLoaded', function () {
            const vehicles = ['motorcycle', 'car', 'tricycle', 'van', 'bus', 'truck'];
            vehicles.forEach(vehicle => toggleVehicleInput(vehicle));
        });
    </script>
</x-app-layout>
