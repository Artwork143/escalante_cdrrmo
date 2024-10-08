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
                                @php
                                $vehicles = ['Motorcycle', 'Car', 'Tricycle', 'Van', 'Bus', 'Truck'];
                                $selectedVehicles = explode(', ', $vehicularAccident->vehicles_involved);
                                @endphp
                                @foreach($vehicles as $vehicle)
                                <div>
                                    <input type="checkbox" name="vehicles_involved[]" value="{{ $vehicle }}" id="vehicle_{{ strtolower($vehicle) }}" class="mr-2" {{ in_array($vehicle, old('vehicles_involved', $selectedVehicles)) ? 'checked' : '' }}>
                                    <label for="vehicle_{{ strtolower($vehicle) }}">{{ __($vehicle) }}</label>
                                </div>
                                @endforeach
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