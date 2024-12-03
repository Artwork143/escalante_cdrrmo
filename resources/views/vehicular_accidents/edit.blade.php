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
                                @foreach(['Alpha', 'Bravo', 'Charlie', 'Delta'] as $team)
                                <option value="{{ $team }}" {{ old('rescue_team', $vehicularAccident->rescue_team) == $team ? 'selected' : '' }}>
                                    {{ $team }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="city" class="block text-sm font-medium text-gray-700">{{ __('City') }}</label>
                            <select name="city" id="city"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="">{{ __('Select City') }}</option>
                                <!-- Cities will be populated dynamically -->
                            </select>
                        </div>

                        <!-- Barangay -->
                        <div class="mb-4">
                            <label for="barangay" class="block text-sm font-medium text-gray-700">{{ __('Barangay') }}</label>
                            <select name="barangay" id="barangay"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="">{{ __('Select Barangay') }}</option>
                                <!-- Barangays will be populated dynamically -->

                                <!-- Preselect the old barangay if exists -->
                                @if ($vehicularAccident->barangay || old('barangay'))
                                <option value="{{ old('barangay', $vehicularAccident->barangay) }}" selected>
                                    {{ old('barangay', $vehicularAccident->barangay) }}
                                </option>
                                @endif
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

                        <!-- Cause of Incident Dropdown -->
                        <label for="cause_of_incident" class="block text-sm font-medium text-gray-700">{{ __('Cause of Incident') }}</label>
                        <select id="cause_of_incident" name="cause_of_incident" class="mt-1 mb-4 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" onchange="toggleOtherCauseInput()">
                            @foreach($causes as $cause)
                            <option value="{{ $cause }}"
                                @if($cause===old('cause_of_incident', $vehicularAccident->cause_of_incident)) selected
                                @endif>
                                {{ $cause }}
                            </option>
                            @endforeach
                            <!-- "Other" Option -->
                            <option value="Other"
                                @if(!in_array(old('cause_of_incident', $vehicularAccident->cause_of_incident), $causes)) selected
                                @endif>
                                Other
                            </option>
                        </select>

                        <!-- Other Cause Input Field -->
                        <div id="other_cause_input"
                            class="mb-4 @if(in_array(old('cause_of_incident', $vehicularAccident->cause_of_incident), $causes)) hidden @endif">
                            <label for="other_cause" class="block text-sm font-medium text-gray-700">{{ __('Please specify') }}</label>
                            <input type="text" name="cause_of_incident" id="other_cause"
                                value="{{ in_array(old('cause_of_incident', $vehicularAccident->cause_of_incident), $causes) ? '' : old('cause_of_incident', $vehicularAccident->cause_of_incident) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <!-- Vehicles Involved -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">{{ __('Vehicles Involved') }}</label>
                            <div class="mt-2 grid grid-cols-2 gap-4">
                                @php
                                $vehicles = ['Motorcycle', 'Car', 'Tricycle', 'Van', 'Bus', 'Truck', 'Bike'];
                                @endphp
                                @foreach($vehicles as $vehicle)
                                <div>
                                    <input
                                        type="checkbox"
                                        name="vehicles_involved[]"
                                        value="{{ $vehicle }}"
                                        id="vehicle_{{ strtolower($vehicle) }}"
                                        class="mr-2"
                                        onclick="toggleVehicleInput('{{ strtolower($vehicle) }}')"
                                        {{ in_array($vehicle, old('vehicles_involved', $vehicularAccident->vehicleDetails->pluck('vehicle_type')->toArray())) ? 'checked' : '' }}>
                                    <label for="vehicle_{{ strtolower($vehicle) }}">{{ $vehicle }}</label>

                                    <!-- Specific Vehicle Input -->
                                    <div id="{{ strtolower($vehicle) }}_input"
                                        class="mt-2 {{ in_array($vehicle, old('vehicles_involved', $vehicularAccident->vehicleDetails->pluck('vehicle_type')->toArray())) ? '' : 'hidden' }}">
                                        <label for="{{ strtolower($vehicle) }}_type" class="block text-sm font-medium text-gray-700">{{ __('Specific ' . $vehicle) }}</label>
                                        <input
                                            type="text"
                                            name="{{ strtolower($vehicle) }}_type"
                                            id="{{ strtolower($vehicle) }}_type"
                                            value="{{ old(strtolower($vehicle) . '_type', $vehicleDetails[$vehicle] ?? '') }}"
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
        /**
         * Toggles the visibility of the specific vehicle input field 
         * based on the checkbox state.
         * @param {string} vehicle - The lowercase identifier of the vehicle type.
         */
        function toggleVehicleInput(vehicle) {
            const inputContainer = document.getElementById(`${vehicle}_input`);
            const checkbox = document.getElementById(`vehicle_${vehicle}`);

            if (checkbox && inputContainer) {
                if (checkbox.checked) {
                    inputContainer.classList.remove('hidden');
                } else {
                    inputContainer.classList.add('hidden');
                }
            }
        }

        /**
         * Initializes the visibility of all specific vehicle input fields
         * on page load.
         */
        document.addEventListener('DOMContentLoaded', function() {
            const vehicles = ['motorcycle', 'car', 'tricycle', 'van', 'bus', 'truck'];

            vehicles.forEach(vehicle => {
                toggleVehicleInput(vehicle);
            });

            // Optionally, add a listener for dynamically added checkboxes (if necessary)
            vehicles.forEach(vehicle => {
                const checkbox = document.getElementById(`vehicle_${vehicle}`);
                if (checkbox) {
                    checkbox.addEventListener('change', function() {
                        toggleVehicleInput(vehicle);
                    });
                }
            });
        });


        document.addEventListener('DOMContentLoaded', () => {
            const cityDropdown = document.getElementById('city');
            const barangayDropdown = document.getElementById('barangay');
            let geoData = null; // Store the geoJSON data here

            // Load GeoJSON data
            fetch('/geojson/philippines.json')
                .then(response => response.json())
                .then(data => {
                    geoData = data; // Store the data in the variable
                    populateCityDropdown(data); // Populate city dropdown
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

                // Preselect the city if there is an old value (from Blade)
                const oldCity = "{{ old('city', $vehicularAccident->city) }}";
                if (oldCity) {
                    cityDropdown.value = oldCity;
                    populateBarangayDropdown(oldCity); // Prepopulate barangay if there's an old value
                }
            }

            // When a City is selected, populate the Barangay dropdown
            cityDropdown.addEventListener('change', () => {
                const selectedCity = cityDropdown.value;

                // Clear Barangay dropdown
                barangayDropdown.innerHTML = '<option value="">{{ __("Select Barangay") }}</option>';

                if (selectedCity && geoData) {
                    populateBarangayDropdown(selectedCity);
                }
            });

            // Populate the Barangay dropdown based on the selected City
            function populateBarangayDropdown(city) {
                const barangayList = [];
                const oldBarangay = "{{ old('barangay', $vehicularAccident->barangay) }}"; // Get old barangay value

                Object.keys(geoData).forEach(regionCode => {
                    const provinces = geoData[regionCode].province_list;
                    Object.keys(provinces).forEach(province => {
                        const municipalities = provinces[province].municipality_list;
                        if (municipalities[city]) {
                            barangayList.push(...municipalities[city].barangay_list);
                        }
                    });
                });

                // Clear the Barangay dropdown
                barangayDropdown.innerHTML = '<option value="">{{ __("Select Barangay") }}</option>';

                // Store the preselected barangay
                let preselectedOption = null;

                // Populate the Barangay dropdown and exclude the old barangay
                barangayList.forEach(barangay => {
                    if (oldBarangay && barangay.toUpperCase() === oldBarangay.toUpperCase()) {
                        // Create a preselected option for the old barangay
                        preselectedOption = document.createElement('option');
                        preselectedOption.value = oldBarangay;
                        preselectedOption.textContent = oldBarangay;
                        preselectedOption.selected = true;
                    } else {
                        const option = document.createElement('option');
                        option.value = barangay;
                        option.textContent = barangay;
                        barangayDropdown.appendChild(option);
                    }
                });

                // Insert the preselected option at the top of the dropdown if it exists
                if (preselectedOption) {
                    barangayDropdown.insertBefore(preselectedOption, barangayDropdown.firstChild);
                }
            }

            // Event listener for the Barangay dropdown
            barangayDropdown.addEventListener('click', () => {
                // Only populate barangays if they are not already populated
                if (barangayDropdown.options.length <= 1) { // 1 because the first option is the "Select Barangay" option
                    const selectedCity = cityDropdown.value;
                    if (selectedCity && geoData) {
                        populateBarangayDropdown(selectedCity); // Populate barangays based on selected city
                    }
                }
            });
        });

        function toggleOtherCauseInput() {
            const causeSelect = document.getElementById('cause_of_incident');
            const otherInput = document.getElementById('other_cause_input');
            const otherCauseField = document.getElementById('other_cause');

            if (causeSelect && causeSelect.value === 'Other') {
                otherInput.classList.remove('hidden');
                otherCauseField.disabled = false; // Enable the input field
            } else {
                otherInput.classList.add('hidden');
                otherCauseField.value = ''; // Clear the "Other" field to prevent accidental overwrites
                otherCauseField.disabled = true; // Disable the input field
            }
        }

        // Ensure the correct state is set on page load
        document.addEventListener('DOMContentLoaded', () => {
            toggleOtherCauseInput();
        });
    </script>

</x-app-layout>