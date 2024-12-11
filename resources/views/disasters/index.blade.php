<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight print-hidden">
            {{ __('Disasters Management') }}
        </h2>
    </x-slot>

    <div class="py-12 print-adjust">
        <div class="{{ auth()->user()->role === 0 ? 'xl:max-w-[90rem]' : 'max-w-7xl' }} mx-auto sm:px-6 lg:px-8 relative">
            <!-- Dropdown for selecting disaster -->
            <div class="mb-4">
                <form method="GET" action="{{ route('disasters.index') }}" id="disaster-filter-form">
                    <!-- Hidden inputs to retain current filters -->
                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">

                    <label for="disaster-select" class="block font-medium text-gray-700">Select Disaster Type:</label>
                    <select
                        id="disaster-select"
                        name="type"
                        onchange="document.getElementById('disaster-filter-form').submit()"
                        class="w-1/6 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="" disabled {{ is_null(request('type')) ? 'selected' : '' }}>Select a disaster</option>
                        <option value="Flood" {{ request('type') === 'Flood' ? 'selected' : '' }}>Flood</option>
                        <option value="Earthquake" {{ request('type') === 'Earthquake' ? 'selected' : '' }}>Earthquake</option>
                        <option value="Volcanic Eruption" {{ request('type') === 'Volcanic Eruption' ? 'selected' : '' }}>Volcanic Eruption</option>
                        <option value="Rebel Encounter" {{ request('type') === 'Rebel Encounter' ? 'selected' : '' }}>Rebel Encounter</option>
                    </select>
                </form>
            </div>

            <div class="bg-white shadow-md sm:rounded-lg mb-1">
                <div class="p-5 bg-[#295F98] print-hidden">
                    <div class="flex justify-between items-center mb-6">
                        @if(request('type'))
                        <h3 class="text-xl font-semibold text-white">{{ request('type') }} Cases</h3>
                        @else
                        <h3 class="text-xl font-semibold text-white">List of Disasters</h3>
                        @endif

                        <!-- Create button visible to both admins and non-admins -->
                        <a href="{{ route('disasters.create') }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
                            Create New Case
                        </a>
                    </div>

                    <form method="GET" action="{{ route('disasters.index') }}" class="mb-4">
                        <input type="hidden" name="type" value="{{ request('type') }}">

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

                <div class="p-6 text-gray-900 print-header">
                    <!-- Tables for different disasters -->
                    @if($disasters->isEmpty())
                    <p class="text-gray-500 text-center">No disasters found for the selected filters.</p>
                    @else
                    @include('partials.disaster-table', ['disasters' => $disasters])
                    @endif
                </div>
            </div>

            <div class="{{ auth()->user()->role === 0 ? 'flex' : 'hidden' }} print-hidden gap-3">
                <!-- Pie chart section -->
                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg mt-10 print-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 mt-2">{{ __("Disasters by Type") }}</h3>
                        <div class="mb-6" style="width: 500px;">
                            <canvas id="disasterPieChart" width="400" height="400" class="pt-5"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Bar chart section -->
                <div class="bg-white shadow-md sm:rounded-lg w-3/5 p-6 mt-10">
                    <h3 class="text-lg font-semibold mb-4 mt-2">{{ __("Ranking of Disasters (Most to Least) by Casualties") }}</h3>
                    <canvas id="disasterRankingChart"></canvas>

                    <!-- Suggestion Box -->
                    <div id="suggestionBox" class="mt-6 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 hidden">
                        <p class="font-semibold">Focus on Disaster Type: <span id="focusDisaster"></span></p>
                        <p>This disaster type has the highest number of casualties and might require more attention and resources.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch disaster data from the backend
            fetch(`/get-disaster-data?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}`)
                .then(response => response.json())
                .then(data => {
                    const sortedForChart = [...data].sort((a, b) => a.total_casualties - b.total_casualties);
                    const sortedForRanking = [...data].sort((a, b) => b.total_casualties - a.total_casualties);

                    const labels = sortedForChart.map(item => item.disaster_type);
                    const values = sortedForChart.map(item => item.total_casualties);
                    const backgroundColors = values.map(() => getRandomColor());

                    // Function to generate random colors
                    function getRandomColor() {
                        const letters = '0123456789ABCDEF';
                        let color = '#';
                        for (let i = 0; i < 6; i++) {
                            color += letters[Math.floor(Math.random() * 16)];
                        }
                        return color;
                    }

                    // Render the Pie Chart for disaster types
                    renderPieChart(labels, values, backgroundColors);

                    // Render the Bar Chart for ranking disasters by casualties
                    renderBarChart(sortedForRanking);

                    // Function to render the Pie Chart
                    function renderPieChart(labels, values, backgroundColors) {
                        const ctxPie = document.getElementById('disasterPieChart').getContext('2d');
                        new Chart(ctxPie, {
                            type: 'pie',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Disasters by Type',
                                    data: values,
                                    backgroundColor: backgroundColors,
                                    hoverOffset: 4,
                                }]
                            },
                            options: {
                                responsive: true,
                                onClick: (e, item) => {
                                    if (item.length > 0) {
                                        const index = item[0].index;
                                        const disasterType = labels[index];
                                        // Optionally, you can load disaster details for that type
                                        // loadDisasterDetails(disasterType);
                                    }
                                }
                            }
                        });
                    }

                    // Render the Horizontal Bar Chart for ranking disasters
                    function renderBarChart(sortedForRanking) {
                        const rankingLabels = sortedForRanking.map(item => item.disaster_type);
                        const rankingValues = sortedForRanking.map(item => item.total_casualties);
                        const ctxBar = document.getElementById('disasterRankingChart').getContext('2d');

                        new Chart(ctxBar, {
                            type: 'bar',
                            data: {
                                labels: rankingLabels,
                                datasets: [{
                                    label: 'Total Casualties',
                                    data: rankingValues,
                                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                scales: {
                                    x: {
                                        beginAtZero: true
                                    }
                                },
                                responsive: true,
                                onClick: (e, item) => {
                                    if (item.length > 0) {
                                        const index = item[0].index;
                                        const disasterType = rankingLabels[index];
                                        // Optionally, you can load disaster details for that type
                                        // loadDisasterDetails(disasterType);
                                    }
                                }
                            }
                        });
                    }

                    // Show suggestion for disaster type with highest casualties
                    const highestCaseDisaster = sortedForRanking[0].disaster_type;
                    const highestCaseCount = sortedForRanking[0].total_casualties;
                    const suggestionBox = document.getElementById('suggestionBox');
                    const focusDisaster = document.getElementById('focusDisaster');
                    focusDisaster.innerText = `${highestCaseDisaster} (${highestCaseCount} casualties)`;
                    if (highestCaseCount > 0) {
                        suggestionBox.classList.remove('hidden');
                    }
                });
        });
    </script>

</x-app-layout>