@php
use Carbon\Carbon;
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight print-hidden">
            {{ __('Medical Cases') }}
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
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg mb-1">
                <div class="p-5 bg-[#295F98] print-hidden">
                    <!-- Button to create new medical case -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold text-white">{{ __("List of Medical Cases") }}</h3>

                        <!-- Create button visible to both admins and non-admins -->
                        <a href="{{ route('medical_cases.create') }}" id="create-button" class="px-5 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-700 transition ease-in-out duration-300">
                            {{ __('Create New Case') }}
                        </a>
                    </div>

                    <!-- Filter by Month and Year -->
                    <form method="GET" action="{{ route('medical_cases.index') }}" class="mb-4">
                        <label for="month" class="block text-sm font-medium text-white mb-1">{{ __("Filter by Date") }}</label>
                        <div class="flex space-x-4">
                            <select name="month" id="month" class="w-40 py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">{{ __("Select Month") }}</option>
                                @foreach($months as $key => $month)
                                <option value="{{ $key + 1 }}" {{ request('month') == ($key + 1) ? 'selected' : '' }}>
                                    {{ $month }}
                                </option>
                                @endforeach
                            </select>

                            <select name="year" id="year" class="w-40 py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">{{ __("Select Year") }}</option>
                                @for ($i = date('Y'); $i >= 2000; $i--)
                                <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                                @endfor
                            </select>

                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-700 transition ease-in-out duration-300">
                                {{ __('Filter') }}
                            </button>
                        </div>
                    </form>
                </div>

                <div class="p-6 text-gray-900 print-header">

                    @if(request('month'))
                    <p class="hidden print-show mt-1 pt-1 mb-1 pb-1 border-b-2 border-b-gray-300 text-center">
                        <span class=" font-bold text-2xl">Medical Case</span> <br> Response summary for the month of<span class=" text-red-600 font-bold"> {{ $months[request('month') - 1] }} {{ request('year') }}</span>
                    </p>
                    @endif

                    @if ($medicalCases->count() > 0)
                    <div class="overflow-x-auto">
                        <div class="relative max-h-96 2xl:max-h-[30rem] overflow-y-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rescue Team</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Place of Incident</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. of Patients</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chief Complaints</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facility Name</th>
                                        @if(auth()->user()->role === 0) <!-- Show actions only for admin users -->
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider print-hidden">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider print-hidden">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                    // Sort medical cases: pending first, then approved
                                    $sortedCases = $medicalCases->sortByDesc(function ($case) {
                                    return !$case->is_approved; // Pending cases (false) come before approved (true)
                                    });
                                    @endphp

                                    @foreach ($sortedCases as $case)
                                    <tr class="{{ $case->is_approved ? 'even:bg-gray-50 odd:bg-white hover:bg-gray-200' : 'bg-yellow-100' }} {{ $case->is_approved ? '' : 'print-hidden' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($case->date)->format('m/d/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $case->rescue_team }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-wrap capitalize">{{ $case->place_of_incident }}, Brgy. {{ $case->barangay }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $case->no_of_patients }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-wrap capitalize">{{ $case->chief_complaints }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $case->facility_name }}</td>

                                        @if(auth()->user()->role === 0)
                                        <td class="px-6 py-4 whitespace-nowrap print-hidden">
                                            {{ $case->is_approved ? 'Approved' : 'Pending' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap print-hidden">
                                            @if($case->is_approved)
                                            <a href="{{ route('medical_cases.edit', $case->id) }}" class="px-4 py-[10.5px] bg-yellow-500 text-white rounded-lg hover:bg-yellow-700 mr-2">
                                                {{ __('Edit') }}
                                            </a>
                                            @endif

                                            @if (!$case->is_approved)
                                            <form action="{{ route('medical_cases.approve', $case->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-700">
                                                    {{ __('Approve') }}
                                                </button>
                                            </form>
                                            @endif

                                            <form action="{{ route('medical_cases.destroy', $case->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-700" onclick="return confirm('Are you sure you want to delete this medical case?')">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if (request('month') && $totalPatients > 0)
                    <p class="text-lg font-medium mt-4 border-t-2 border-t-gray-300 pt-2">
                        {{ __("Total Responses for the Month: ") }} {{ $totalPatients }}
                    </p>
                    <!-- Print Button -->
                    <div class="grid place-items-end">
                        <button onclick="window.print()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-700 {{ auth()->user()->role === 0 ? '' : 'hidden' }}">
                            {{ __('Print') }}
                        </button>
                    </div>
                    @endif

                    @if (!request('month'))
                    <p class="border-t-2 mt-4 border-t-gray-300 pt-2">{{ __("Please select a month to view the total responses per month.") }}</p>
                    @endif

                    @elseif ($medicalCases->isEmpty())
                    <p>{{ __("No medical cases found for the selected month.") }}</p>
                    @endif

                </div>
            </div>

            <div class="{{ auth()->user()->role === 0 ? 'flex' : 'hidden' }} print-hidden gap-3">
                <!-- Pie chart section -->
                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg mt-10 print-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 mt-2">{{ __("Medical Cases by Barangay") }}</h3>
                        <div class="mb-6" style="width: 500px;">
                            <canvas id="barangayPieChart" width="400" height="400" class="pt-5"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Bar chart section -->
                <div class="bg-white shadow-md sm:rounded-lg w-3/5 p-6 mt-10">
                    <h3 class="text-lg font-semibold mb-4 mt-2">Ranking from Most to Lowest Cases</h3>
                    <canvas id="barangayRankingChart"></canvas>

                    <!-- Suggestion Box -->
                    <div id="suggestionBox" class="mt-6 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 hidden">
                        <p class="font-semibold">Focus on Barangay: <span id="focusBarangay"></span></p>
                        <p>This barangay has the highest number of cases and might require more attention and resources.</p>
                    </div>
                </div>
            </div>


            <!-- Detailed Table Section: Initially hidden -->
            <div id="barangayDetails" class="hidden bg-white shadow-md sm:rounded-lg p-6 mt-10 print-hidden">
                <h3 id="barangayTitle" class="text-lg font-semibold mb-4 mt-2"></h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rescue Team</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Place of Incident</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. of Patients</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chief Complaints</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facility Name</th>
                        </tr>
                    </thead>
                    <tbody id="barangayTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Table rows will be inserted here dynamically -->
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Chart.js script and pie chart logic -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch data from backend (you can pass the data from controller or fetch via API)
            fetch(`/get-barangay-cases?month={{ request('month') }}&year={{ request('year') }}`)
                .then(response => response.json())
                .then(data => {
                    // Sort data by total_cases in ascending order (lowest to highest) for pie chart
                    const sortedForChart = [...data].sort((a, b) => a.total_cases - b.total_cases);

                    // Sort data by total_cases in descending order (highest to lowest) for ranking
                    const sortedForRanking = [...data].sort((a, b) => b.total_cases - a.total_cases);

                    const labels = sortedForChart.map(item => item.barangay);
                    const values = sortedForChart.map(item => item.total_cases);

                    // Generate random colors for each value
                    function getRandomColor() {
                        const letters = '0123456789ABCDEF';
                        let color = '#';
                        for (let i = 0; i < 6; i++) {
                            color += letters[Math.floor(Math.random() * 16)];
                        }
                        return color;
                    }

                    const backgroundColors = values.map(() => getRandomColor());

                    // Render the Pie Chart
                    const ctxPie = document.getElementById('barangayPieChart').getContext('2d');
                    const barangayPieChart = new Chart(ctxPie, {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Medical Cases',
                                data: values,
                                backgroundColor: backgroundColors,
                                hoverOffset: 4,
                            }]
                        },
                        options: {
                            responsive: true,
                            onClick: function(e, item) {
                                if (item.length > 0) {
                                    const index = item[0].index;
                                    const barangay = labels[index];

                                    // Fetch barangay case details
                                    fetch(`/get-barangay-details/${barangay}?month={{ request('month') }}&year={{ request('year') }} `)
                                        .then(response => response.json())
                                        .then(barangayData => {
                                            // Show the table
                                            document.getElementById('barangayDetails').classList.remove('hidden');

                                            // Update the title
                                            document.getElementById('barangayTitle').innerText = `Details of case for Brgy. ${barangay}`;

                                            // Clear existing rows
                                            const tableBody = document.getElementById('barangayTableBody');
                                            tableBody.innerHTML = '';

                                            // Insert new rows into the table
                                            barangayData.forEach(caseItem => {
                                                const row = `
                    <tr class="even:bg-gray-50 odd:bg-white hover:bg-gray-200">
                        <td class="px-6 py-4 whitespace-nowrap">${caseItem.date}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${caseItem.rescue_team}</td>
                        <td class="px-6 py-4 whitespace-nowrap capitalize text-wrap">${caseItem.place_of_incident}, Brgy. ${caseItem.barangay}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${caseItem.no_of_patients}</td>
                        <td class="px-6 py-4 whitespace-nowrap capitalize text-wrap">${caseItem.chief_complaints}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${caseItem.facility_name}</td>
                    </tr>
                `;
                                                tableBody.insertAdjacentHTML('beforeend', row);
                                            });

                                            // Scroll to footer
                                            document.getElementById('pageFooter').scrollIntoView({
                                                behavior: 'smooth'
                                            });
                                        });
                                }
                            }

                        }
                    });


                    // Prepare data for horizontal bar chart (ranking)
                    const rankingLabels = sortedForRanking.map(item => item.barangay);
                    const rankingValues = sortedForRanking.map(item => item.total_cases);

                    // Get the barangay with the highest number of cases
                    const highestCaseBarangay = sortedForRanking[0].barangay;
                    const highestCaseCount = sortedForRanking[0].total_cases;

                    // Show the suggestion box and display the barangay with the highest cases
                    const suggestionBox = document.getElementById('suggestionBox');
                    const focusBarangay = document.getElementById('focusBarangay');
                    focusBarangay.innerText = `${highestCaseBarangay} (${highestCaseCount} cases)`;

                    if (highestCaseCount > 1) {
                        suggestionBox.classList.remove('hidden');
                    }

                    // Render the Horizontal Bar Chart
                    const ctxBar = document.getElementById('barangayRankingChart').getContext('2d');
                    const barangayRankingChart = new Chart(ctxBar, {
                        type: 'bar',
                        data: {
                            labels: rankingLabels,
                            datasets: [{
                                label: 'Total Cases',
                                data: rankingValues,
                                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            indexAxis: 'y', // This makes it horizontal
                            scales: {
                                x: {
                                    beginAtZero: true
                                }
                            },
                            responsive: true,
                            onClick: function(e, item) {
                                if (item.length > 0) {
                                    const index = item[0].index;
                                    const barangay = rankingLabels[index];

                                    // Fetch barangay case details
                                    fetch(`/get-barangay-details/${barangay}?month={{ request('month') }}&year={{ request('year') }}`)
                                        .then(response => response.json())
                                        .then(barangayData => {
                                            // Show the table
                                            document.getElementById('barangayDetails').classList.remove('hidden');

                                            // Update the title
                                            document.getElementById('barangayTitle').innerText = `Details of case for Brgy. ${barangay}`;

                                            // Clear existing rows
                                            const tableBody = document.getElementById('barangayTableBody');
                                            tableBody.innerHTML = '';

                                            // Insert new rows into the table
                                            barangayData.forEach(caseItem => {
                                                const row = `
                    <tr class="even:bg-gray-50 odd:bg-white hover:bg-gray-200">
                        <td class="px-6 py-4 whitespace-nowrap">${caseItem.date}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${caseItem.rescue_team}</td>
                        <td class="px-6 py-4 whitespace-nowrap capitalize text-wrap">${caseItem.place_of_incident}, Brgy. ${caseItem.barangay}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${caseItem.no_of_patients}</td>
                        <td class="px-6 py-4 whitespace-nowrap capitalize text-wrap">${caseItem.chief_complaints}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${caseItem.facility_name}</td>
                    </tr>
                `;
                                                tableBody.insertAdjacentHTML('beforeend', row);
                                            });

                                            // Scroll to footer
                                            document.getElementById('pageFooter').scrollIntoView({
                                                behavior: 'smooth'
                                            });
                                        });
                                }
                            }
                        }
                    });
                });
        });
    </script>


    <!-- CSS for print view -->
    <style>
        @media print {

            nav,
            .header,
            .flex.justify-between,
            .mb-4,
            .bg-blue-500,
            .bg-green-500,
            .bg-yellow-500,
            .bg-red-500,
            .print-hidden {
                display: none;
            }

            .relative {
                max-height: none !important;
            }

            table {
                font-size: 15px;
                /* Reduce the font size for printing */
            }

            th,
            td {
                padding: 4px !important;
                /* Reduce padding to fit columns */
            }

            .print-show {
                display: block;
            }

            .print-adjust {
                padding-bottom: 0px;
                padding-top: 10px;
            }

            .print-header {
                padding-top: 0px;
                margin-top: 0px;
                padding-bottom: 10px;
                margin-bottom: 0px;
            }
        }
    </style>
</x-app-layout>