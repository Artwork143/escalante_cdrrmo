<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Medical Case') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('medical_cases.store') }}" id="medical-case-form">
                        @csrf

                        <!-- Date -->
                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700">{{ __('Date') }}</label>
                            <input type="date" name="date" id="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>

                        <!-- Rescue Team (Dropdown) -->
                        <div class="mb-4">
                            <label for="rescue_team" class="block text-sm font-medium text-gray-700">{{ __('Rescue Team') }}</label>
                            <select name="rescue_team" id="rescue_team" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required onchange="toggleOtherRescueTeamInput()">
                                <option value="">{{ __('Select a Rescue Team') }}</option>
                                @foreach ($rescueTeams as $team)
                                <option value="{{ $team->team_name }}">{{ $team->team_name }}</option>
                                @endforeach
                                <option value="other">{{ __('Other') }}</option>
                            </select>
                        </div>

                        <div class="mb-4 hidden" id="other_rescue_team_wrapper">
                            <label for="other_rescue_team" class="block text-sm font-medium text-gray-700">{{ __('New Rescue Team') }}</label>
                            <input
                                type="text"
                                name="other_rescue_team"
                                id="other_rescue_team"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Enter new rescue team">
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

                        <!-- Chief Complaints -->
                        <div class="mb-4">
                            <label for="chief_complaints" class="block text-sm font-medium text-gray-700">{{ __('Chief Complaints') }}</label>
                            <textarea name="chief_complaints" id="chief_complaints" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required></textarea>
                        </div>

                        <!-- Facility Name -->
                        <div class="mb-4">
                            <label for="facility_name" class="block text-sm font-medium text-gray-700">{{ __('Facility Name') }}</label>
                            <input type="text" name="facility_name" id="facility_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <a href="{{ route('medical_cases.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-700 mr-2">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
                                {{ __('Save Medical Case') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
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

        function toggleOtherRescueTeamInput() {
            const rescueTeamSelect = document.getElementById('rescue_team');
            const otherRescueTeamWrapper = document.getElementById('other_rescue_team_wrapper');
            const otherRescueTeamInput = document.getElementById('other_rescue_team');

            if (rescueTeamSelect.value === 'other') {
                otherRescueTeamWrapper.classList.remove('hidden');
                otherRescueTeamInput.setAttribute('required', 'required');
            } else {
                otherRescueTeamWrapper.classList.add('hidden');
                otherRescueTeamInput.removeAttribute('required');
            }
        }
    </script>

</x-app-layout>