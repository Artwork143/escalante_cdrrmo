<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Medical Case') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Error Messages -->
                    @if ($errors->any())
                    <div class="mb-4">
                        <ul class="text-red-500">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Edit Form -->
                    <form action="{{ route('medical_cases.update', $medicalCase->id) }}" method="POST" id="medical-case-form">
                        @csrf
                        @method('PUT')

                        <!-- Date -->
                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700">{{ __('Date') }}</label>
                            <input type="date" name="date" id="date" value="{{ old('date', $medicalCase->date) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>

                        <!-- Rescue Team -->
                        <div class="mb-4">
                            <label for="rescue_team" class="block text-sm font-medium text-gray-700">{{ __('Rescue Team') }}</label>
                            <select name="rescue_team" id="rescue_team"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value=""></option>
                                <option value="Alpha" {{ old('rescue_team', $medicalCase->rescue_team) == 'Alpha' ? 'selected' : '' }}>Alpha</option>
                                <option value="Bravo" {{ old('rescue_team', $medicalCase->rescue_team) == 'Bravo' ? 'selected' : '' }}>Bravo</option>
                                <option value="Charlie" {{ old('rescue_team', $medicalCase->rescue_team) == 'Charlie' ? 'selected' : '' }}>Charlie</option>
                                <option value="Delta" {{ old('rescue_team', $medicalCase->rescue_team) == 'Delta' ? 'selected' : '' }}>Delta</option>
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
                                @if ($medicalCase->barangay || old('barangay'))
                                <option value="{{ old('barangay', $medicalCase->barangay) }}" selected>
                                    {{ old('barangay', $medicalCase->barangay) }}
                                </option>
                                @endif
                            </select>
                        </div>



                        <!-- Specific Location -->
                        <div class="mb-4">
                            <label for="place_of_incident" class="block text-sm font-medium text-gray-700">{{ __('Specific Location') }}</label>
                            <input type="text" name="place_of_incident" id="place_of_incident" value="{{ old('place_of_incident', $medicalCase->place_of_incident) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>

                        <!-- Number of Patients -->
                        <div class="mb-4">
                            <label for="no_of_patients" class="block text-sm font-medium text-gray-700">{{ __('Number of Patients') }}</label>
                            <input type="number" name="no_of_patients" id="no_of_patients" value="{{ old('no_of_patients', $medicalCase->no_of_patients) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>

                        <!-- Chief Complaints -->
                        <div class="mb-4">
                            <label for="chief_complaints" class="block text-sm font-medium text-gray-700">{{ __('Chief Complaints') }}</label>
                            <textarea name="chief_complaints" id="chief_complaints" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>{{ old('chief_complaints', $medicalCase->chief_complaints) }}</textarea>
                        </div>

                        <!-- Facility Name -->
                        <div class="mb-4">
                            <label for="facility_name" class="block text-sm font-medium text-gray-700">{{ __('Facility Name') }}</label>
                            <input type="text" name="facility_name" id="facility_name" value="{{ old('facility_name', $medicalCase->facility_name) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <a href="{{ route('medical_cases.index') }}"
                                class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-700 mr-2">{{ __('Cancel') }}</a>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">{{ __('Update Case') }}</button>
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
                const oldCity = "{{ old('city', $medicalCase->city) }}";
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
                const oldBarangay = "{{ old('barangay', $medicalCase->barangay) }}"; // Get old barangay value

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
    </script>


</x-app-layout>