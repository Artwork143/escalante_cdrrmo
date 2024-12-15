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
                                <option value="">{{ __('Select a Rescue Team') }}</option>
                                @foreach ($rescueTeams as $team)
                                <option value="{{ $team->team_name }}">{{ $team->team_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="city" class="block text-sm font-medium text-gray-700">{{ __('City') }}</label>
                            <select name="city" id="city" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="">{{ __('Select City') }}</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="barangay" class="block text-sm font-medium text-gray-700">{{ __('Barangay') }}</label>
                            <select name="barangay" id="barangay" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="">{{ __('Select Barangay') }}</option>
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
                        <!-- Cause of Incident (Dropdown with "Other" Input) -->
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

                        <!-- Other Cause of Incident Input -->
                        <div id="other_cause_input" class="mb-4 hidden">
                            <label for="other_cause" class="block text-sm font-medium text-gray-700">{{ __('Please specify') }}</label>
                            <input type="text" name="other_cause" id="other_cause" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <!-- Vehicles Involved (Checkboxes) -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">{{ __('Vehicles Involved') }}</label>
                            <div class="mt-2 grid grid-cols-2 gap-4">
                                <!-- Motorcycle -->
                                <div>
                                    <input type="checkbox" name="vehicles_involved[]" value="Motorcycle" id="vehicle_motorcycle" class="mr-2" onclick="toggleVehicleInput('motorcycle')">
                                    <label for="vehicle_motorcycle">{{ __('Motorcycle') }}</label>
                                    <div id="motorcycle_input" class="mt-2 hidden">
                                        <label for="motorcycle_type" class="block text-sm font-medium text-gray-700">{{ __('Specific Motorcycle') }}</label>
                                        <input type="text" name="motorcycle_type" id="motorcycle_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <!-- Car -->
                                <div>
                                    <input type="checkbox" name="vehicles_involved[]" value="Car" id="vehicle_car" class="mr-2" onclick="toggleVehicleInput('car')">
                                    <label for="vehicle_car">{{ __('Car') }}</label>
                                    <div id="car_input" class="mt-2 hidden">
                                        <label for="car_type" class="block text-sm font-medium text-gray-700">{{ __('Specific Car') }}</label>
                                        <input type="text" name="car_type" id="car_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <!-- Tricycle -->
                                <div>
                                    <input type="checkbox" name="vehicles_involved[]" value="Tricycle" id="vehicle_tricycle" class="mr-2" onclick="toggleVehicleInput('tricycle')">
                                    <label for="vehicle_tricycle">{{ __('Tricycle') }}</label>
                                    <div id="tricycle_input" class="mt-2 hidden">
                                        <label for="tricycle_type" class="block text-sm font-medium text-gray-700">{{ __('Specific Tricycle') }}</label>
                                        <input type="text" name="tricycle_type" id="tricycle_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <!-- Van -->
                                <div>
                                    <input type="checkbox" name="vehicles_involved[]" value="Van" id="vehicle_van" class="mr-2" onclick="toggleVehicleInput('van')">
                                    <label for="vehicle_van">{{ __('Van') }}</label>
                                    <div id="van_input" class="mt-2 hidden">
                                        <label for="van_type" class="block text-sm font-medium text-gray-700">{{ __('Specific Van') }}</label>
                                        <input type="text" name="van_type" id="van_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <!-- Bus -->
                                <div>
                                    <input type="checkbox" name="vehicles_involved[]" value="Bus" id="vehicle_bus" class="mr-2" onclick="toggleVehicleInput('bus')">
                                    <label for="vehicle_bus">{{ __('Bus') }}</label>
                                    <div id="bus_input" class="mt-2 hidden">
                                        <label for="bus_type" class="block text-sm font-medium text-gray-700">{{ __('Specific Bus') }}</label>
                                        <input type="text" name="bus_type" id="bus_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <!-- Truck -->
                                <div>
                                    <input type="checkbox" name="vehicles_involved[]" value="Truck" id="vehicle_truck" class="mr-2" onclick="toggleVehicleInput('truck')">
                                    <label for="vehicle_truck">{{ __('Truck') }}</label>
                                    <div id="truck_input" class="mt-2 hidden">
                                        <label for="truck_type" class="block text-sm font-medium text-gray-700">{{ __('Specific Truck') }}</label>
                                        <input type="text" name="truck_type" id="truck_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>
                                <!-- Bike -->
                                <div>
                                    <input type="checkbox" name="vehicles_involved[]" value="Bike" id="vehicle_bike" class="mr-2" onclick="toggleVehicleInput('bike')">
                                    <label for="vehicle_motorcycle">{{ __('Bike') }}</label>
                                    <div id="bike_input" class="mt-2 hidden">
                                        <label for="bike_type" class="block text-sm font-medium text-gray-700">{{ __('Specific Bike') }}</label>
                                        <input type="text" name="bike_type" id="bike_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
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
    <script>
        function toggleVehicleInput(vehicle) {
            const vehicleInput = document.getElementById(vehicle + '_input');
            const vehicleCheckbox = document.getElementById('vehicle_' + vehicle);

            // Toggle visibility of the input field based on checkbox state
            if (vehicleCheckbox.checked) {
                vehicleInput.classList.remove('hidden');
            } else {
                vehicleInput.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const causeOfIncidentSelect = document.getElementById('cause_of_incident');
            const otherCauseInput = document.getElementById('other_cause_input');

            // Toggle visibility of the "Other" input based on selection
            causeOfIncidentSelect.addEventListener('change', function() {
                if (this.value === 'Other') {
                    otherCauseInput.classList.remove('hidden');
                } else {
                    otherCauseInput.classList.add('hidden');
                }
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const cityDropdown = document.getElementById('city');
            const barangayDropdown = document.getElementById('barangay');
            let geoData = null; // Store the geoJSON data here

            // Load GeoJSON data once when the page loads
            fetch('/geojson/philippines.json')
                .then(response => response.json())
                .then(data => {
                    geoData = data; // Store the data in the variable
                    populateCityDropdown(data); // Populate city dropdown on load
                })
                .catch(error => console.error('Error loading GeoJSON:', error));

            // Populate the City dropdown with cities from GeoJSON data
            function populateCityDropdown(data) {
                const cityList = [];

                // Collect all cities into a single array
                Object.keys(data).forEach(regionCode => {
                    const provinces = data[regionCode].province_list;
                    Object.keys(provinces).forEach(province => {
                        const municipalities = provinces[province].municipality_list;
                        Object.keys(municipalities).forEach(municipality => {
                            cityList.push(municipality);
                        });
                    });
                });

                // Sort the city list alphabetically
                cityList.sort((a, b) => a.localeCompare(b));

                // Populate the City dropdown
                cityList.forEach(cityName => {
                    const option = document.createElement('option');
                    option.value = cityName;
                    option.textContent = cityName;
                    cityDropdown.appendChild(option);
                });
            }

            // When a City is selected, populate the Barangay dropdown
            cityDropdown.addEventListener('change', () => {
                const selectedCity = cityDropdown.value;

                // Clear Barangay dropdown
                barangayDropdown.innerHTML = '<option value="">{{ __("Select Barangay") }}</option>';

                // Check if the selected City exists in the GeoJSON data
                if (selectedCity && geoData) {
                    populateBarangayDropdown(selectedCity);
                }
            });

            // Populate the Barangay dropdown based on the selected City
            function populateBarangayDropdown(city) {
                const barangayList = [];

                Object.keys(geoData).forEach(regionCode => {
                    const provinces = geoData[regionCode].province_list;
                    Object.keys(provinces).forEach(province => {
                        const municipalities = provinces[province].municipality_list;
                        if (municipalities[city]) {
                            barangayList.push(...municipalities[city].barangay_list);
                        }
                    });
                });

                // Populate the Barangay dropdown
                barangayList.forEach(barangay => {
                    const option = document.createElement('option');
                    option.value = barangay;
                    option.textContent = barangay;
                    barangayDropdown.appendChild(option);
                });
            }

            // Add form submission check
            const form = document.getElementById('medical-case-form');
            form.addEventListener('submit', (e) => {
                if (!cityDropdown.value) {
                    e.preventDefault(); // Prevent form submission
                    alert('Please select a city before submitting the form.');
                }
            });
        });
    </script>
</x-app-layout>