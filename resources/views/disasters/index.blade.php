@php
use Carbon\Carbon;
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight print-hidden">
            {{ __('Disasters Management') }}
        </h2>

        <div class="hidden print-show text-center">
            <div class="flex items-center justify-center">
                <img src="{{ asset('images/escaLogo.jpg') }}" class="w-1/5 max-w-sm">
                <div class="mx-4">
                    <p class="font-bold">
                        REPUBLIC OF THE PHILIPPINES<br>PROVINCE OF NEGROS OCCIDENTAL<br> ESCALANTE CITY
                    </p>
                    <p class="text-blue-900 font-bold">Disaster Risk Reduction & Management Office</p>
                    <p class="text-blue-900 font-bold">Gomez Street, Brgy. Balintawak, Escalante City, Neg. Occ.</p>
                    <p class="text-red-600">09152627121 | 09089376724</p>
                </div>
                <img src="{{ asset('images/logo.png') }}" class="w-1/5 max-w-sm">
            </div>
        </div>
    </x-slot>

    <div class="py-12 print-adjust">
        <div class="{{ auth()->user()->role === 0 ? 'xl:max-w-[90rem]' : 'max-w-7xl' }} mx-auto sm:px-6 lg:px-8 relative">
            <!-- Dropdown for selecting disaster -->
            <div class="mb-4">
                <label for="disaster-select" class="block font-medium text-gray-700">Select Disaster Type:</label>
                <select id="disaster-select" class=" w-1/6 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="" disabled selected>Select a disaster</option>
                    <option value="Flood">Flood</option>
                    <option value="Earthquake">Earthquake</option>
                    <option value="Volcanic Eruption">Volcanic Eruption</option>
                    <option value="Rebel Encounter">Rebel Encounter</option>
                </select>
            </div>
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg mb-1">
                <div class="p-5 bg-[#295F98] print-hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold text-white">{{ __("List of Disasters") }}</h3>

                        <!-- Create button visible to both admins and non-admins -->
                        <a href="{{ route('disasters.create') }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
                            Create New Case
                        </a>
                    </div>

                    <form method="GET" action="{{ route('disasters.index') }}" class="mb-4">
                        <label for="month" class="block text-sm font-medium text-white mb-1">{{ __("Filter Date Range") }}</label>
                        <div class="flex space-x-4 justify-between">
                            <div>
                                <input
                                    type="date"
                                    name="start_date"
                                    id="start_date"
                                    value="{{ request('start_date') }}"
                                    class="py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                                <input
                                    type="date"
                                    name="end_date"
                                    id="end_date"
                                    value="{{ request('end_date') }}"
                                    class="py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-700 transition ease-in-out duration-300">
                                    {{ __('Filter') }}
                                </button>
                            </div>

                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search cases..." class=" w-1/4 py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 grid grid-row-2 hover:bg-gray-200" />
                        </div>
                    </form>
                </div>

                <div class="p-6 text-gray-900 print-header print-hidden">
                    <!-- Dynamic Table -->
                    <div class="overflow-x-auto">
                        <div class="relative 2xl:max-h-[30rem] overflow-y-auto">
                            <table id="disaster-table" class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0 z-10">
                                    <tr id="table-header">
                                        <!-- Headers will be dynamically populated -->
                                    </tr>
                                </thead>
                                <tbody id="table-body" class="bg-white divide-y divide-gray-200">
                                    <!-- Rows will be dynamically populated -->
                                </tbody>
                            </table>
                            <p class="" id="warning">{{ __("Please select a disaster type you want to manage.") }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.getElementById("disaster-select").addEventListener("change", (e) => {
        const selectedDisaster = e.target.value;
        document.getElementById('warning').classList.add('hidden');

        if (selectedDisaster) {
            // Ensure the correct URL is generated with the selected type
            updateTable(selectedDisaster);
        }
    });

    // Fetch and update table data based on the selected disaster type
    async function updateTable(disasterType) {
        try {
            const response = await fetch(`/disaster-data/${disasterType}`); // Corrected URL with disaster type
            const { headers, rows } = await response.json();

            const tableHeader = document.getElementById("table-header");
            const tableBody = document.getElementById("table-body");

            // Clear existing table content
            tableHeader.innerHTML = "";
            tableBody.innerHTML = "";

            // Populate table headers
            headers.forEach(header => {
                const th = document.createElement("th");
                th.className = "px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider";
                th.textContent = header;
                tableHeader.appendChild(th);
            });

            // Populate table rows
            rows.forEach(row => {
                const tr = document.createElement("tr");
                tr.className = "even:bg-gray-50 odd:bg-white hover:bg-gray-200";

                row.forEach(cell => {
                    const td = document.createElement("td");
                    td.className = "px-6 py-4 whitespace-nowrap";
                    td.textContent = cell;
                    tr.appendChild(td);
                });

                tableBody.appendChild(tr);
            });
        } catch (error) {
            console.error("Error updating table:", error);
        }
    }
</script>


</x-app-layout>