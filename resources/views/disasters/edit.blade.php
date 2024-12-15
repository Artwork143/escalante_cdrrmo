<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Disaster Case') }}
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
                    <form action="{{ route('disasters.update', $disaster->id) }}" method="POST" id="medical-case-form">
                        @csrf
                        @method('PUT')

                        <!-- Date -->
                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700">{{ __('Date') }}</label>
                            <input type="date" name="date" id="date" value="{{ old('date', $disaster->date) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>

                        <!-- Disaster Type -->
                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700">{{ __('Disaster Type') }}</label>
                            <select name="type" id="type"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required onchange="toggleOtherInput(this)">
                                <option value="">{{ __('Select Disaster Type') }}</option>
                                @foreach ($disasterTypes as $disasterType)
                                <option value="{{ $disasterType->type_name }}" {{ old('type', $disaster->type ?? '') == $disasterType->type_name ? 'selected' : '' }}>{{ $disasterType->type_name }}</option>
                                @endforeach
                                <!-- <option value="other">{{ __('Other') }}</option> -->
                            </select>
                        </div>

                        <!-- <div id="other-disaster-type" class="mb-4 hidden">
                            <label for="other_type" class="block text-sm font-medium text-gray-700">{{ __('Specify Disaster Type') }}</label>
                            <input type="text" name="other_type" id="other_type"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="{{ __('Enter disaster type') }}">
                        </div> -->


                        <!-- Rescue Team -->
                        <div class="mb-4">
                            <label for="rescue_team" class="block text-sm font-medium text-gray-700">{{ __('Rescue Team') }}</label>
                            <select name="rescue_team" id="rescue_team"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="">{{ __('Select a Rescue Team') }}</option>
                                @foreach ($rescueTeams as $team)
                                <option value="{{ $team->team_name }}" {{ old('rescue_team', $disaster->rescue_team ?? '') == $team->team_name ? 'selected' : '' }}>
                                    {{ $team->team_name }}
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
                                @if ($disaster->barangay || old('barangay'))
                                <option value="{{ old('barangay', $disaster->barangay) }}" selected>
                                    {{ old('barangay', $disaster->barangay) }}
                                </option>
                                @endif
                            </select>
                        </div>



                        <!-- Specific Location -->
                        <div class="mb-4">
                            <label for="place_of_incident" class="block text-sm font-medium text-gray-700">{{ __('Specific Location') }}</label>
                            <input type="text" name="place_of_incident" id="place_of_incident" value="{{ old('place_of_incident', $disaster->place_of_incident) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>

                    <!-- Affected Infrastructure -->
                    <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">{{ __('Affected Infrastructure') }}</label>
                            <div id="infrastructure-options" class="space-y-2">

                                <!-- Roads -->
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" id="roads" name="infrastructure_types[]" value="roads" class="mr-2">
                                        <span>{{ __('Roads') }}</span>
                                    </label>
                                    <select id="roads-dropdown" name="roads_condition" class="mt-2 hidden w-40 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="flooded">{{ __('Flooded') }}</option>
                                        <option value="impassable">{{ __('Impassable') }}</option>
                                        <option value="damaged">{{ __('Damaged') }}</option>
                                    </select>
                                </div>

                                <!-- Bridges -->
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" id="bridges" name="infrastructure_types[]" value="bridges" class="mr-2">
                                        <span>{{ __('Bridges') }}</span>
                                    </label>
                                    <select id="bridges-dropdown" name="bridges_condition" class="mt-2 hidden w-40 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="flooded">{{ __('Flooded') }}</option>
                                        <option value="impassable">{{ __('Impasseable') }}</option>
                                        <option value="damaged">{{ __('Damaged') }}</option>
                                    </select>
                                </div>

                                <!-- Buildings -->
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" id="buildings" name="infrastructure_types[]" value="buildings" class="mr-2">
                                        <span>{{ __('Buildings') }}</span>
                                    </label>
                                    <div id="buildings-options" class="mt-2 hidden space-y-2">
                                        <div>
                                            <label class="flex items-center">
                                                <input type="checkbox" id="school" name="buildings_types[]" value="school" class="mr-2">
                                                <span>{{ __('School') }}</span>
                                            </label>
                                            <select id="school-dropdown" name="school_condition" class="mt-2 hidden w-40 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="flooded">{{ __('Flooded') }}</option>
                                                <option value="impassable">{{ __('Impasseable') }}</option>
                                                <option value="damaged">{{ __('Damaged') }}</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="flex items-center">
                                                <input type="checkbox" id="house" name="buildings_types[]" value="house" class="mr-2">
                                                <span>{{ __('House') }}</span>
                                            </label>
                                            <select id="house-dropdown" name="house_condition" class="mt-2 hidden w-40 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="flooded">{{ __('Flooded') }}</option>
                                                <option value="impassable">{{ __('Impasseable') }}</option>
                                                <option value="damaged">{{ __('Damaged') }}</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="flex items-center">
                                                <input type="checkbox" id="government" name="buildings_types[]" value="government" class="mr-2">
                                                <span>{{ __('Government Facility') }}</span>
                                            </label>
                                            <select id="government-dropdown" name="government_condition" class="mt-2 hidden w-40 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="flooded">{{ __('Flooded') }}</option>
                                                <option value="impassable">{{ __('Impasseable') }}</option>
                                                <option value="damaged">{{ __('Damaged') }}</option>
                                            </select>
                                        </div>
                                        <!-- Add other building types similarly -->
                                    </div>
                                </div>

                                <!-- Powerlines -->
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" id="powerlines" name="infrastructure_types[]" value="powerlines" class="mr-2">
                                        <span>{{ __('Powerlines') }}</span>
                                    </label>
                                    <select id="powerlines-dropdown" name="powerlines_condition" class="mt-2 hidden w-40 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="flooded">{{ __('Flooded') }}</option>
                                        <option value="impassable">{{ __('Impasseable') }}</option>
                                        <option value="damaged">{{ __('Damaged') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <!-- Casualties Section -->
                        <div id="casualties-section" class="mb-4">
                            <label for="casualties" class="block text-sm font-medium text-gray-700">{{ __('Casualties') }}</label>
                            <div id="casualties-options" class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="killed" name="casualties_types[]" value="killed" class="mr-2">
                                    <label for="killed" class="text-sm font-medium text-gray-700">{{ __('Killed') }}</label>
                                    <input type="number" id="killed-count" name="killed_count" placeholder="Number" class="ml-4 w-24 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm hidden">
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="injured" name="casualties_types[]" value="injured" class="mr-2">
                                    <label for="injured" class="text-sm font-medium text-gray-700">{{ __('Injured') }}</label>
                                    <input type="number" id="injured-count" name="injured_count" placeholder="Number" class="ml-4 w-24 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm hidden">
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="missing" name="casualties_types[]" value="missing" class="mr-2">
                                    <label for="missing" class="text-sm font-medium text-gray-700">{{ __('Missing') }}</label>
                                    <input type="number" id="missing-count" name="missing_count" placeholder="Number" class="ml-4 w-24 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm hidden">
                                </div>
                            </div>
                        </div>

                        <!-- Casualties for Rebel Encounter -->
                        <div id="rebel-casualties" class="space-y-2 hidden">
                            <label for="casualties" class="block text-sm font-medium text-gray-700">{{ __('Casualties') }}</label>
                            <div class="flex items-center">
                                <input type="checkbox" id="police-killed" name="rebel_casualties_types[]" value="police_killed" class="mr-2">
                                <label for="police-killed" class="text-sm font-medium text-gray-700">{{ __('Police Killed') }}</label>
                                <input type="number" id="police-killed-count" name="police_killed_count" placeholder="Number" class="ml-4 w-24 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm hidden">
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="police-injured" name="rebel_casualties_types[]" value="police_injured" class="mr-2">
                                <label for="police-injured" class="text-sm font-medium text-gray-700">{{ __('Police Injured') }}</label>
                                <input type="number" id="police-injured-count" name="police_injured_count" placeholder="Number" class="ml-4 w-24 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm hidden">
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="rebel-killed" name="rebel_casualties_types[]" value="rebel_killed" class="mr-2">
                                <label for="rebel-killed" class="text-sm font-medium text-gray-700">{{ __('Rebel Killed') }}</label>
                                <input type="number" id="rebel-killed-count" name="rebel_killed_count" placeholder="Number" class="ml-4 w-24 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm hidden">
                            </div>
                            <div class="flex items-centerx`">
                                <input type="checkbox" id="rebel-injured" name="rebel_casualties_types[]" value="rebel_injured" class="mr-2">
                                <label for="rebel-injured" class="text-sm font-medium text-gray-700">{{ __('Rebel Injured') }}</label>
                                <input type="number" id="rebel-injured-count" name="rebel_injured_count" placeholder="Number" class="ml-4 w-24 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm hidden">
                            </div>
                        </div>

                        <!-- Dynamic Fields based on Disaster Type -->
                        <div id="dynamic-fields" class="space-y-4"></div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <a href="{{ route('disasters.index') }}"
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
        document.addEventListener("DOMContentLoaded", function() {
            const infrastructures = ["roads", "bridges", "powerlines", "buildings"];
            const buildingTypes = ["school", "house", "government"]; // Add more building types here

            infrastructures.forEach((id) => {
                const checkbox = document.getElementById(id);
                const dropdown = document.getElementById(`${id}-dropdown`);
                if (checkbox && dropdown) {
                    checkbox.addEventListener("change", () => {
                        dropdown.classList.toggle("hidden", !checkbox.checked);
                    });
                }

                if (id === "buildings") {
                    const optionsDiv = document.getElementById("buildings-options");
                    checkbox.addEventListener("change", () => {
                        optionsDiv.classList.toggle("hidden", !checkbox.checked);
                    });

                    buildingTypes.forEach((type) => {
                        const typeCheckbox = document.getElementById(type);
                        const typeDropdown = document.getElementById(`${type}-dropdown`);
                        if (typeCheckbox && typeDropdown) {
                            typeCheckbox.addEventListener("change", () => {
                                typeDropdown.classList.toggle("hidden", !typeCheckbox.checked);
                            });
                        }
                    });
                }
            });
        });

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
                                <label for="police_unit" class="block text-sm font-medium text-gray-700 mt-4">{{ __('Police Unit/Agency') }}</label>
                                <input type="text" name="police_unit" id="police_unit" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            </div>
                            <div class="mb-4">
                                <label for="rebel_group" class="block text-sm font-medium text-gray-700">{{ __('Rebel Group') }}</label>
                                <input type="text" name="rebel_group" id="rebel_group" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            </div>
                            <div class="mb-4">
                                <label for="triggering_event" class="block text-sm font-medium text-gray-700">{{ __('Triggering Event') }}</label>
                                <select name="triggering_event" id="triggering_event" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <option value="">{{ __('Select Triggering Event') }}</option>
                                    <option value="Checkpoint">Checkpoint</option>
                                    <option value="Pursuit Ambush">Pursuit Ambush</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="nature_of_encounter" class="block text-sm font-medium text-gray-700">{{ __('Nature of Encounter') }}</label>
                                <select name="nature_of_encounter" id="nature_of_encounter" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <option value="">{{ __('Select Nature of Encounter') }}</option>
                                    <option value="Firefight">Firefight</option>
                                    <option value="Standoff">Standoff</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="duration" class="block text-sm font-medium text-gray-700">{{ __('Duration') }}</label>
                                <input type="text" name="duration" id="duration" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            </div>
                        `;
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

        document.addEventListener('DOMContentLoaded', () => {
            const typeDropdown = document.getElementById('type');
            const casualtiesSection = document.getElementById('casualties-section');
            const rebelCasualties = document.getElementById('rebel-casualties');

            const casualtiesFields = ['killed', 'injured', 'missing'];
            const rebelFields = ['police-killed', 'police-injured', 'rebel-killed', 'rebel-injured'];

            // Toggle input visibility for checked checkboxes
            [...casualtiesFields, ...rebelFields].forEach(field => {
                const checkbox = document.getElementById(field);
                const input = document.getElementById(`${field}-count`);

                if (checkbox && input) {
                    checkbox.addEventListener('change', () => {
                        input.classList.toggle('hidden', !checkbox.checked);
                        input.classList.toggle('block', checkbox.checked);
                    });
                }
            });

            // Handle Disaster Type change
            typeDropdown.addEventListener('change', () => {
                const selectedType = typeDropdown.value;

                if (selectedType === 'Rebel Encounter') {
                    casualtiesSection.classList.add('hidden');
                    rebelCasualties.classList.remove('hidden');
                } else {
                    casualtiesSection.classList.remove('hidden');
                    rebelCasualties.classList.add('hidden');
                }
            });
        });
    </script>

</x-app-layout>