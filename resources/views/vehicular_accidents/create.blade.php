<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Vehicular Accident') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('vehicular_accidents.store') }}" id="vehicular-accident-form">
                        @csrf

                        <!-- Date -->
                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700">{{ __('Date') }}</label>
                            <input type="date" name="date" id="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>

                        <!-- Rescue Team (Dropdown) -->
                        <div class="mb-4">
                            <label for="rescue_team" class="block text-sm font-medium text-gray-700">{{ __('Rescue Team') }}</label>
                            <select name="rescue_team" id="rescue_team" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value=""></option>
                                <option value="Alpha">Alpha</option>
                                <option value="Bravo">Bravo</option>
                                <option value="Charlie">Charlie</option>
                            </select>
                        </div>

                        <!-- Barangay (Dropdown) -->
                        <div class="mb-4">
                            <label for="barangay" class="block text-sm font-medium text-gray-700">{{ __('Barangay') }}</label>
                            <select name="barangay" id="barangay" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value=""></option>
                                <option value="Alimango">Alimango</option>
                                <option value="Balintawak">Balintawak</option>
                                <option value="Binaguiohan">Binaguiohan</option>
                                <option value="Buenavista">Buenavista</option>
                                <option value="Cervantes">Cervantes</option>
                                <option value="Dian-ay">Dian-ay</option>
                                <option value="Hacienda Fe">Hacienda Fe</option>
                                <option value="Japitan">Japitan</option>
                                <option value="Jonob-jonob">Jonob-jonob</option>
                                <option value="Langub">Langub</option>
                                <option value="Libertad">Libertad</option>
                                <option value="Mabini">Mabini</option>
                                <option value="Magsaysay">Magsaysay</option>
                                <option value="Malasibog">Malasibog</option>
                                <option value="Old Poblacion">Old Poblacion</option>
                                <option value="Paitan">Paitan</option>
                                <option value="Pinapugasan">Pinapugasan</option>
                                <option value="Rizal">Rizal</option>
                                <option value="Tamlang">Tamlang</option>
                                <option value="Udtongan">Udtongan</option>
                                <option value="Washington">Washington</option>
                            </select>
                        </div>

                        <!-- Specific Location (Text Field) -->
                        <div class="mb-4">
                            <label for="place_of_incident" class="block text-sm font-medium text-gray-700">{{ __('Specific Location') }}</label>
                            <input type="text" name="place_of_incident" id="place_of_incident" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>

                        <!-- Number of Patients -->
                        <div class="mb-4">
                            <label for="no_of_patients" class="block text-sm font-medium text-gray-700">{{ __('Number of Patients') }}</label>
                            <input type="number" name="no_of_patients" id="no_of_patients" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>

                        <!-- Cause of Incident (Dropdown) -->
                        <div class="mb-4">
                            <label for="cause_of_incident" class="block text-sm font-medium text-gray-700">{{ __('Cause of Incident') }}</label>
                            <select name="cause_of_incident" id="cause_of_incident" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value=""></option>
                                <option value="Over Speeding">Over Speeding</option>
                                <option value="Mechanical Failure">Mechanical Failure</option>
                                <option value="Collision">Collision</option>
                                <option value="Hit & Run">Hit & Run</option>
                                <option value="Road Condition">Road Condition</option>
                                <option value="Drunk Driving">Drunk Driving</option>
                                <option value="Distracted Driving">Distracted Driving</option>
                                <option value="Weather Conditions">Weather Conditions</option>
                                <option value="Reckless Driving">Reckless Driving</option>
                                <option value="Overloading">Overloading</option>
                                <option value="Improper Turning">Improper Turning</option>
                                <option value="Pedestrian Error">Pedestrian Error</option>
                                <option value="Fatigue">Fatigue</option>
                                <option value="Road Hazards">Road Hazards</option>
                                <option value="Lack of Signage">Lack of Signage</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <!-- Vehicles Involved (Checkboxes) -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">{{ __('Vehicles Involved') }}</label>
                            <div class="mt-2 grid grid-cols-2 gap-4"> <!-- Added grid layout with 2 columns -->
                                <div>
                                    <input type="checkbox" name="vehicles_involved[]" value="Motorcycle" id="vehicle_motorcycle" class="mr-2">
                                    <label for="vehicle_motorcycle">{{ __('Motorcycle') }}</label>
                                </div>
                                <div>
                                    <input type="checkbox" name="vehicles_involved[]" value="Car" id="vehicle_car" class="mr-2">
                                    <label for="vehicle_car">{{ __('Car') }}</label>
                                </div>
                                <div>
                                    <input type="checkbox" name="vehicles_involved[]" value="Tricycle" id="vehicle_tricycle" class="mr-2">
                                    <label for="vehicle_tricycle">{{ __('Tricycle') }}</label>
                                </div>
                                <div>
                                    <input type="checkbox" name="vehicles_involved[]" value="Van" id="vehicle_van" class="mr-2">
                                    <label for="vehicle_van">{{ __('Van') }}</label>
                                </div>
                                <div>
                                    <input type="checkbox" name="vehicles_involved[]" value="Bus" id="vehicle_bus" class="mr-2">
                                    <label for="vehicle_bus">{{ __('Bus') }}</label>
                                </div>
                                <div>
                                    <input type="checkbox" name="vehicles_involved[]" value="Truck" id="vehicle_truck" class="mr-2">
                                    <label for="vehicle_truck">{{ __('Truck') }}</label>
                                </div>
                            </div>
                        </div>

                        <!-- Facility Name -->
                        <div class="mb-4">
                            <label for="facility_name" class="block text-sm font-medium text-gray-700">{{ __('Facility Name') }}</label>
                            <input type="text" name="facility_name" id="facility_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <a href="{{ route('vehicular_accidents.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-700 mr-2">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
                                {{ __('Save Vehicular Accident') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
