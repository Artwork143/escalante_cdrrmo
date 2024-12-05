<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Disaster Case') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('disasters.store') }}" method="POST" id="disaster-form">
                        @csrf

                        <!-- Date -->
                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700">{{ __('Date') }}</label>
                            <input type="date" name="date" id="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>

                        <!-- Disaster Type -->
                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700">{{ __('Disaster Type') }}</label>
                            <select name="type" id="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="">{{ __('Select Disaster Type') }}</option>
                                <option value="Flood">Flood</option>
                                <option value="Earthquake">Earthquake</option>
                                <option value="Volcanic Eruption">Volcanic Eruption</option>
                                <option value="Rebel Encounter">Rebel Encounter</option>
                            </select>
                        </div>

                        <!-- Rescue Team -->
                        <div class="mb-4">
                            <label for="rescue_team" class="block text-sm font-medium text-gray-700">{{ __('Rescue Team') }}</label>
                            <select name="rescue_team" id="rescue_team" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="">{{ __('Select Rescue Team') }}</option>
                                <option value="Alpha">Alpha</option>
                                <option value="Bravo">Bravo</option>
                                <option value="Charlie">Charlie</option>
                                <option value="Delta">Delta</option>
                            </select>
                        </div>

                        <!-- City -->
                        <div class="mb-4">
                            <label for="city" class="block text-sm font-medium text-gray-700">{{ __('City') }}</label>
                            <select name="city" id="city" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="">{{ __('Select City') }}</option>
                            </select>
                        </div>

                        <!-- Barangay -->
                        <div class="mb-4">
                            <label for="barangay" class="block text-sm font-medium text-gray-700">{{ __('Barangay') }}</label>
                            <select name="barangay" id="barangay" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="">{{ __('Select Barangay') }}</option>
                            </select>
                        </div>

                        <!-- Specific Location -->
                        <div class="mb-4">
                            <label for="place_of_incident" class="block text-sm font-medium text-gray-700">{{ __('Specific Location') }}</label>
                            <input type="text" name="place_of_incident" id="place_of_incident" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>

                        <!-- Affected Infrastructure -->
                        <div class="mb-4">
                            <label for="affected_infrastructure" class="block text-sm font-medium text-gray-700">{{ __('Affected Infrastructure') }}</label>
                            <input type="text" name="affected_infrastructure" id="affected_infrastructure" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <!-- Casualties -->
                        <div class="mb-4">
                            <label for="casualties" class="block text-sm font-medium text-gray-700">{{ __('Casualties') }}</label>
                            <input type="number" name="casualties" id="casualties" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>

                        <!-- Dynamic Fields based on Disaster Type -->
                        <div id="dynamic-fields" class="space-y-4"></div>

                        <!-- Submit Button -->
                        <div class="flex justify-end mt-4">
                            <a href="{{ route('disasters.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-700 mr-2">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
                                {{ __('Save Disaster Case') }}
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
            const disasterTypeDropdown = document.getElementById('type');
            const dynamicFieldsContainer = document.getElementById('dynamic-fields');
            let geoData = null;

            // Load GeoJSON data
            fetch('/geojson/philippines.json')
                .then(response => response.json())
                .then(data => {
                    geoData = data;
                    populateCityDropdown(data);
                })
                .catch(error => console.error('Error loading GeoJSON:', error));

            // Populate City dropdown
            function populateCityDropdown(data) {
                const cities = [];
                Object.keys(data).forEach(region => {
                    Object.values(data[region].province_list).forEach(province => {
                        Object.keys(province.municipality_list).forEach(city => {
                            cities.push(city);
                        });
                    });
                });
                cities.sort().forEach(city => {
                    const option = document.createElement('option');
                    option.value = city;
                    option.textContent = city;
                    cityDropdown.appendChild(option);
                });
            }

            // When disaster type is changed, show relevant fields
            disasterTypeDropdown.addEventListener('change', () => {
                const selectedType = disasterTypeDropdown.value;
                dynamicFieldsContainer.innerHTML = ''; // Clear previous dynamic fields

                switch (selectedType) {
                    case 'Flood':
                        dynamicFieldsContainer.innerHTML = `
                            <div class="mb-4">
                                <label for="current_water_level" class="block text-sm font-medium text-gray-700">{{ __('Current Water Level') }}</label>
                                <select name="current_water_level" id="current_water_level" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <option value="">{{ __('Select Water Level') }}</option>
                                    <option value="Knee Deep">Knee Deep</option>
                                    <option value="Waist Deep">Waist Deep</option>
                                    <option value="Chest Deep">Chest Deepe</option>
                                    <option value="Roof Top">Roof Top</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="water_level_trend" class="block text-sm font-medium text-gray-700">{{ __('Water Level Trend') }}</label>
                                <select name="water_level_trend" id="water_level_trend" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <option value="">{{ __('Select Water Trend') }}</option>
                                    <option value="Rising">Rising</option>
                                    <option value="Falling">Falling</option>
                                </select>
                            </div>
                        `;
                        break;

                    case 'Earthquake':
                        dynamicFieldsContainer.innerHTML = `
                            <div class="mb-4">
                                <label for="intensity_level" class="block text-sm font-medium text-gray-700">{{ __('Intensity Level') }}</label>
                                <select name="intensity_level" id="intensity_level" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <option value="">{{ __('Select Intensity') }}</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="aftershocks" class="block text-sm font-medium text-gray-700">{{ __('Aftershocks') }}</label>
                                <input type="number" name="aftershocks" id="aftershocks" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            </div>
                        `;
                        break;

                    case 'Volcanic Eruption':
                        dynamicFieldsContainer.innerHTML = `
                            <div class="mb-4">
                                <label for="eruption_type" class="block text-sm font-medium text-gray-700">{{ __('Eruption Type') }}</label>
                                <select name="eruption_type" id="eruption_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <option value="">{{ __('Select Eruption Type') }}</option>
                                    <option value="Phreatic">Phreatic</option>
                                    <option value="Phreatomagmatic">Phreatomagmatic</option>
                                    <option value="Strombolian">Strombolian</option>
                                    <option value="Vulcanian">Vulcanian</option>
                                    <option value="Plinian">Plinian</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="eruption_intensity" class="block text-sm font-medium text-gray-700">{{ __('Eruption Intensity') }}</label>
                                <select name="eruption_intensity" id="eruption_intensity" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <option value="">{{ __('Select Eruption Intensity') }}</option>
                                    <option value="Weak">Weak</option>
                                    <option value="Moderate">Moderate</option>
                                    <option value="Strong">Strong</option>
                                </select>
                            </div>
                        `;
                        break;

                    case 'Rebel Encounter':
                        dynamicFieldsContainer.innerHTML = `
                            <div class="mb-4">
                                <label for="involved_parties" class="block text-sm font-medium text-gray-700">{{ __('Involved Parties') }}</label>
                                <input type="text" name="involved_parties" id="involved_parties" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            </div>
                            <div class="mb-4">
                                <label for="triggering_event" class="block text-sm font-medium text-gray-700">{{ __('Triggering Event') }}</label>
                                <input type="text" name="triggering_event" id="triggering_event" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            </div>
                            <div class="mb-4">
                                <label for="nature_of_encounter" class="block text-sm font-medium text-gray-700">{{ __('Nature of Encounter') }}</label>
                                <input type="text" name="nature_of_encounter" id="nature_of_encounter" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            </div>
                            <div class="mb-4">
                                <label for="duration" class="block text-sm font-medium text-gray-700">{{ __('Duration') }}</label>
                                <input type="text" name="duration" id="duration" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            </div>
                        `;
                        break;

                    default:
                        dynamicFieldsContainer.innerHTML = '';
                        break;
                }
            });

            // When city is changed, populate barangay dropdown
            cityDropdown.addEventListener('change', () => {
                const city = cityDropdown.value;
                barangayDropdown.innerHTML = '<option value="">{{ __("Select Barangay") }}</option>';
                if (geoData) {
                    populateBarangayDropdown(city);
                }
            });

            function populateBarangayDropdown(city) {
                const barangays = [];
                Object.keys(geoData).forEach(region => {
                    Object.values(geoData[region].province_list).forEach(province => {
                        const municipalities = province.municipality_list;
                        if (municipalities[city]) {
                            barangays.push(...municipalities[city].barangay_list);
                        }
                    });
                });

                barangays.sort().forEach(barangay => {
                    const option = document.createElement('option');
                    option.value = barangay;
                    option.textContent = barangay;
                    barangayDropdown.appendChild(option);
                });
            }
        });
    </script>
</x-app-layout>