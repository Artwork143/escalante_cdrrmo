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
                            <input type="date" name="date" id="date" value="{{ old('date', $vehicularAccident->date) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>

                        <!-- Rescue Team (Dropdown) -->
                        <div class="mb-4">
                            <label for="rescue_team" class="block text-sm font-medium text-gray-700">{{ __('Rescue Team') }}</label>
                            <select name="rescue_team" id="rescue_team" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value=""></option>
                                <option value="Alpha" {{ old('rescue_team', $vehicularAccident->rescue_team) == 'Alpha' ? 'selected' : '' }}>Alpha</option>
                                <option value="Bravo" {{ old('rescue_team', $vehicularAccident->rescue_team) == 'Bravo' ? 'selected' : '' }}>Bravo</option>
                                <option value="Charlie" {{ old('rescue_team', $vehicularAccident->rescue_team) == 'Charlie' ? 'selected' : '' }}>Charlie</option>
                            </select>
                        </div>

                        <!-- Barangay (Dropdown) -->
                        <div class="mb-4">
                            <label for="barangay" class="block text-sm font-medium text-gray-700">{{ __('Barangay') }}</label>
                            <select name="barangay" id="barangay" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value=""></option>
                                <option value="Alimango" {{ old('barangay', $vehicularAccident->barangay) == 'Alimango' ? 'selected' : '' }}>Alimango</option>
                                <option value="Balintawak" {{ old('barangay', $vehicularAccident->barangay) == 'Balintawak' ? 'selected' : '' }}>Balintawak</option>
                                <option value="Binaguiohan" {{ old('barangay', $vehicularAccident->barangay) == 'Binaguiohan' ? 'selected' : '' }}>Binaguiohan</option>
                                <option value="Buenavista" {{ old('barangay', $vehicularAccident->barangay) == 'Buenavista' ? 'selected' : '' }}>Buenavista</option>
                                <option value="Cervantes" {{ old('barangay', $vehicularAccident->barangay) == 'Cervantes' ? 'selected' : '' }}>Cervantes</option>
                                <option value="Dian-ay" {{ old('barangay', $vehicularAccident->barangay) == 'Dian-ay' ? 'selected' : '' }}>Dian-ay</option>
                                <option value="Hacienda Fe" {{ old('barangay', $vehicularAccident->barangay) == 'Hacienda Fe' ? 'selected' : '' }}>Hacienda Fe</option>
                                <option value="Japitan" {{ old('barangay', $vehicularAccident->barangay) == 'Japitan' ? 'selected' : '' }}>Japitan</option>
                                <option value="Jonob-jonob" {{ old('barangay', $vehicularAccident->barangay) == 'Jonob-jonob' ? 'selected' : '' }}>Jonob-jonob</option>
                                <option value="Langub" {{ old('barangay', $vehicularAccident->barangay) == 'Langub' ? 'selected' : '' }}>Langub</option>
                                <option value="Libertad" {{ old('barangay', $vehicularAccident->barangay) == 'Libertad' ? 'selected' : '' }}>Libertad</option>
                                <option value="Mabini" {{ old('barangay', $vehicularAccident->barangay) == 'Mabini' ? 'selected' : '' }}>Mabini</option>
                                <option value="Magsaysay" {{ old('barangay', $vehicularAccident->barangay) == 'Magsaysay' ? 'selected' : '' }}>Magsaysay</option>
                                <option value="Malasibog" {{ old('barangay', $vehicularAccident->barangay) == 'Malasibog' ? 'selected' : '' }}>Malasibog</option>
                                <option value="Old Poblacion" {{ old('barangay', $vehicularAccident->barangay) == 'Old Poblacion' ? 'selected' : '' }}>Old Poblacion</option>
                                <option value="Paitan" {{ old('barangay', $vehicularAccident->barangay) == 'Paitan' ? 'selected' : '' }}>Paitan</option>
                                <option value="Pinagpugasan" {{ old('barangay', $vehicularAccident->barangay) == 'Pinagpugasan' ? 'selected' : '' }}>Pinagpugasan</option>
                                <option value="Rizal" {{ old('barangay', $vehicularAccident->barangay) == 'Rizal' ? 'selected' : '' }}>Rizal</option>
                                <option value="Tamlang" {{ old('barangay', $vehicularAccident->barangay) == 'Tamlang' ? 'selected' : '' }}>Tamlang</option>
                                <option value="Udtongan" {{ old('barangay', $vehicularAccident->barangay) == 'Udtongan' ? 'selected' : '' }}>Udtongan</option>
                                <option value="Washington" {{ old('barangay', $vehicularAccident->barangay) == 'Washington' ? 'selected' : '' }}>Washington</option>
                            </select>
                        </div>

                        <!-- Specific Location (Text Field) -->
                        <div class="mb-4">
                            <label for="place_of_incident" class="block text-sm font-medium text-gray-700">{{ __('Specific Location') }}</label>
                            <input type="text" name="place_of_incident" id="place_of_incident" value="{{ old('place_of_incident', $vehicularAccident) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>

                        <!-- Number of Patients -->
                        <div class="mb-4">
                            <label for="no_of_patients" class="block text-sm font-medium text-gray-700">{{ __('Number of Patients') }}</label>
                            <input type="number" name="no_of_patients" id="no_of_patients" value="{{ old('no_of_patients', $vehicularAccident->no_of_patients) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>

                        <!-- Cause of Incident (Dropdown) -->
                        <div class="mb-4">
                            <label for="cause_of_incident" class="block text-sm font-medium text-gray-700">{{ __('Cause of Incident') }}</label>
                            <select name="cause_of_incident" id="cause_of_incident" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value=""></option>
                                @php
                                $causes = [
                                'Over Speeding', 'Mechanical Failure', 'Collision', 'Hit & Run', 'Road Condition', 'Drunk Driving',
                                'Distracted Driving', 'Weather Conditions', 'Reckless Driving', 'Overloading',
                                'Improper Turning', 'Pedestrian Error', 'Fatigue', 'Road Hazards',
                                'Lack of Signage', 'Other'
                                ];
                                @endphp
                                @foreach($causes as $cause)
                                <option value="{{ $cause }}" {{ old('cause_of_incident', $vehicularAccident->cause_of_incident) == $cause ? 'selected' : '' }}>
                                    {{ $cause }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Vehicles Involved (Checkboxes) -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">{{ __('Vehicles Involved') }}</label>
                            <div class="mt-2 grid grid-cols-2 gap-4">
                                <!-- Motorcycle -->
                                <div>
                                    <input type="checkbox" name="vehicles_involved[]" value="Motorcycle" id="vehicle_motorcycle" class="mr-2"
                                        onclick="toggleVehicleInput('motorcycle')"
                                        {{ in_array('Motorcycle', old('vehicles_involved', explode(', ', $vehicularAccident->vehicles_involved))) ? 'checked' : '' }}>
                                    <label for="vehicle_motorcycle">{{ __('Motorcycle') }}</label>
                                    <div id="motorcycle_input" class="mt-2 {{ in_array('Motorcycle', old('vehicles_involved', explode(', ', $vehicularAccident->vehicles_involved))) ? '' : 'hidden' }}">
                                        <label for="motorcycle_type" class="block text-sm font-medium text-gray-700">{{ __('Specific Motorcycle') }}</label>
                                        <input type="text" name="motorcycle_type" id="motorcycle_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            value="{{ old('motorcycle_type', $vehicularAccident->motorcycle_type) }}">
                                    </div>
                                </div>

                                <!-- Car -->
                                <div>
                                    <input type="checkbox" name="vehicles_involved[]" value="Car" id="vehicle_car" class="mr-2"
                                        onclick="toggleVehicleInput('car')"
                                        {{ in_array('Car', old('vehicles_involved', explode(', ', $vehicularAccident->vehicles_involved))) ? 'checked' : '' }}>
                                    <label for="vehicle_car">{{ __('Car') }}</label>
                                    <div id="car_input" class="mt-2 {{ in_array('Car', old('vehicles_involved', explode(', ', $vehicularAccident->vehicles_involved))) ? '' : 'hidden' }}">
                                        <label for="car_type" class="block text-sm font-medium text-gray-700">{{ __('Specific Car') }}</label>
                                        <input type="text" name="car_type" id="car_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            value="{{ old('car_type', $vehicularAccident->car_type) }}">
                                    </div>
                                </div>

                                <!-- Tricycle -->
                                <div>
                                    <input type="checkbox" name="vehicles_involved[]" value="Tricycle" id="vehicle_tricycle" class="mr-2"
                                        onclick="toggleVehicleInput('tricycle')"
                                        {{ in_array('Tricycle', old('vehicles_involved', explode(', ', $vehicularAccident->vehicles_involved))) ? 'checked' : '' }}>
                                    <label for="vehicle_tricycle">{{ __('Tricycle') }}</label>
                                    <div id="tricycle_input" class="mt-2 {{ in_array('Tricycle', old('vehicles_involved', explode(', ', $vehicularAccident->vehicles_involved))) ? '' : 'hidden' }}">
                                        <label for="tricycle_type" class="block text-sm font-medium text-gray-700">{{ __('Specific Tricycle') }}</label>
                                        <input type="text" name="tricycle_type" id="tricycle_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            value="{{ old('tricycle_type', $vehicularAccident->tricycle_type) }}">
                                    </div>
                                </div>

                                <!-- Van -->
                                <div>
                                    <input type="checkbox" name="vehicles_involved[]" value="Van" id="vehicle_van" class="mr-2"
                                        onclick="toggleVehicleInput('van')"
                                        {{ in_array('Van', old('vehicles_involved', explode(', ', $vehicularAccident->vehicles_involved))) ? 'checked' : '' }}>
                                    <label for="vehicle_van">{{ __('Van') }}</label>
                                    <div id="van_input" class="mt-2 {{ in_array('Van', old('vehicles_involved', explode(', ', $vehicularAccident->vehicles_involved))) ? '' : 'hidden' }}">
                                        <label for="van_type" class="block text-sm font-medium text-gray-700">{{ __('Specific Van') }}</label>
                                        <input type="text" name="van_type" id="van_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            value="{{ old('van_type', $vehicularAccident->van_type) }}">
                                    </div>
                                </div>

                                <!-- Bus -->
                                <div>
                                    <input type="checkbox" name="vehicles_involved[]" value="Bus" id="vehicle_bus" class="mr-2"
                                        onclick="toggleVehicleInput('bus')"
                                        {{ in_array('Bus', old('vehicles_involved', explode(', ', $vehicularAccident->vehicles_involved))) ? 'checked' : '' }}>
                                    <label for="vehicle_bus">{{ __('Bus') }}</label>
                                    <div id="bus_input" class="mt-2 {{ in_array('Bus', old('vehicles_involved', explode(', ', $vehicularAccident->vehicles_involved))) ? '' : 'hidden' }}">
                                        <label for="bus_type" class="block text-sm font-medium text-gray-700">{{ __('Specific Bus') }}</label>
                                        <input type="text" name="bus_type" id="bus_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            value="{{ old('bus_type', $vehicularAccident->bus_type) }}">
                                    </div>
                                </div>

                                <!-- Truck -->
                                <div>
                                    <input type="checkbox" name="vehicles_involved[]" value="Truck" id="vehicle_truck" class="mr-2"
                                        onclick="toggleVehicleInput('truck')"
                                        {{ in_array('Truck', old('vehicles_involved', explode(', ', $vehicularAccident->vehicles_involved))) ? 'checked' : '' }}>
                                    <label for="vehicle_truck">{{ __('Truck') }}</label>
                                    <div id="truck_input" class="mt-2 {{ in_array('Truck', old('vehicles_involved', explode(', ', $vehicularAccident->vehicles_involved))) ? '' : 'hidden' }}">
                                        <label for="truck_type" class="block text-sm font-medium text-gray-700">{{ __('Specific Truck') }}</label>
                                        <input type="text" name="truck_type" id="truck_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            value="{{ old('truck_type', $vehicularAccident->truck_type) }}">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Facility Name -->
                        <div class="mb-4">
                            <label for="facility_name" class="block text-sm font-medium text-gray-700">{{ __('Facility Name') }}</label>
                            <input type="text" name="facility_name" id="facility_name" value="{{ old('facility_name', $vehicularAccident->facility_name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
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
</x-app-layout>