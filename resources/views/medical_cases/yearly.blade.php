<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight print-hidden">
            {{ __('Yearly Reports') }}
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
        <div class="max-w-7xl mx-auto sm:px-6 xl:px-0 2xl:px-8">
            @if(auth()->user()->role === 0)
            <div class="flex gap-3 print-hidden">
                <div class="bg-white overflow-hidden w-full shadow-md sm:rounded-lg">
                    <div class="p-6">
                        <canvas id="medicalCasesChart" class="p-6"></canvas>
                    </div>
                </div>
            </div>
            @endif

            <!-- Medical Cases Report -->
            <div id="medicalReportBlock" class="bg-white overflow-hidden shadow-md sm:rounded-lg print-header mt-10 hidden">
                <div class="p-6 text-gray-900 print-header">
                    <p class="hidden print-show mt-1 mb-1 border-b-2 text-center">
                        <span class="font-bold text-2xl">Medical Case</span> <br>
                        Response summary from year <span class="text-red-600 font-bold">2020 to Present</span>
                    </p>
                    <h3 class="text-lg font-semibold mb-4">{{ __("Yearly Report of Medical Cases") }}</h3>
                    @if($yearlyMedicals)
                    <div class="overflow-x-auto">
                        <div class="relative max-h-96 2xl:max-h-[30rem] overflow-y-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barangay</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Chief Complaints</th>
                                        @foreach(range(2020, now()->year) as $year)
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ $year }}</th>
                                        @endforeach
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($yearlyMedicals as $barangay => $cases)
                                    <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-200">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $barangay }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap capitalize text-wrap">{{ $cases['chief_complaints'] }}</td>
                                        @php $total = 0; @endphp
                                        @foreach(range(2020, now()->year) as $year)
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $cases[$year] ?? 0 }}
                                            @php $total += $cases[$year] ?? 0; @endphp
                                        </td>
                                        @endforeach
                                        <td class="px-6 py-4 font-semibold">{{ $total }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="grid place-items-end pt-5 mt-5 border-t-2 print-hidden">
                        <div id="paginationControls"></div>
                        <button onclick="window.print()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-700">
                            {{ __('Print') }}
                        </button>
                    </div>
                    @else
                    <p>{{ __("No medical case data found.") }}</p>
                    @endif
                </div>
            </div>

            <!-- Vehicular Accident Report -->
            <div id="accidentReportBlock" class="bg-white overflow-hidden shadow-md sm:rounded-lg print-header mt-10 hidden">
                <div class="p-6 text-gray-900 print-header">
                    <p class="hidden print-show mt-1 mb-1 border-b-2 text-center">
                        <span class="font-bold text-2xl">Vehicular Accident</span> <br>
                        Response summary from year <span class="text-red-600 font-bold">2020 to Present</span>
                    </p>
                    <h3 class="text-lg font-semibold mb-4">{{ __("Yearly Report of Vehicular Accidents") }}</h3>
                    @if($yearlyAccidents)
                    <div class="overflow-x-auto">
                        <div class="relative max-h-96 2xl:max-h-[30rem] overflow-y-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barangay</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cause of Incident</th>
                                        @foreach(range(2020, now()->year) as $year)
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ $year }}</th>
                                        @endforeach
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($yearlyAccidents as $barangay => $accidents)
                                    <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-200">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $barangay }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-wrap">{{ $accidents['causes_of_incident'] }}</td>
                                        @php $total = 0; @endphp
                                        @foreach(range(2020, now()->year) as $year)
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $accidents[$year] ?? 0 }}
                                            @php $total += $accidents[$year] ?? 0; @endphp
                                        </td>
                                        @endforeach
                                        <td class="px-6 py-4 font-semibold">{{ $total }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="grid place-items-end pt-5 mt-5 border-t-2 print-hidden">
                        <div id="paginationControls"></div>
                        <button onclick="window.print()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-700">
                            {{ __('Print') }}
                        </button>
                    </div>
                    @else
                    <p>{{ __("No vehicular accident data found.") }}</p>
                    @endif
                </div>
            </div>

            <!-- Total Responded Report -->
            <div id="totalRespondedReportBlock" class="bg-white overflow-hidden shadow-md sm:rounded-lg print-header mt-10 hidden">
                <div class="p-6 text-gray-900 print-header">
                    <p class="hidden print-show mt-1 mb-1 border-b-2 text-center">
                        <span class="font-bold text-2xl">Total Responded Cases</span> <br>
                        Response summary from year <span class="text-red-600 font-bold">2020 to Present</span>
                    </p>
                    <h3 class="text-lg font-semibold mb-4">{{ __("Yearly Total Responded Cases") }}</h3>
                    @if($yearlyAccidents && $yearlyMedicals)
                    <div class="overflow-x-auto">
                        <div class="relative max-h-96 2xl:max-h-[30rem] overflow-y-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barangay</th>
                                        @foreach(range(2020, now()->year) as $year)
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ $year }}</th>
                                        @endforeach
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    </tr>
                                </thead>
                                <div>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($yearlyResponses as $barangay => $responses)
                                        <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-200">
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $barangay }}</td>
                                            @php $total = 0; @endphp
                                            @foreach(range(2020, now()->year) as $year)
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $responses[$year] ?? 0 }}
                                                @php $total += $responses[$year] ?? 0; @endphp
                                            </td>
                                            @endforeach
                                            <td class="px-6 py-4 font-semibold">{{ $total }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                            </table>
                            <div id="paginationControls"></div>
                        </div>
                    </div>
                    <div class="grid place-items-end pt-5 mt-5 border-t-2 print-hidden">
                        <button onclick="window.print()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-700">
                            {{ __('Print') }}
                        </button>
                    </div>
                    @else
                    <p>{{ __("No data found for total responded cases.") }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            fetch('/yearly-medicals')
                .then(response => response.json())
                .then(data => {
                    const yearlyMedicals = data.yearlyMedicals;
                    const years = yearlyMedicals.map(item => item.year);
                    const totalCases = yearlyMedicals.map(item => item.total_medicals);
                    const vehicularAccidents = yearlyMedicals.map(item => item.vehicular_accidents);
                    const totalSum = yearlyMedicals.map(item => item.total_sum);

                    const ctx = document.getElementById('medicalCasesChart').getContext('2d');
                    const chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: years,
                            datasets: [{
                                    label: 'Medical Cases',
                                    data: totalCases,
                                    borderColor: 'rgb(75, 192, 192)',
                                    fill: false,
                                    tension: 0.1,
                                    pointRadius: 5,
                                    pointHoverRadius: 8
                                },
                                {
                                    label: 'Vehicular Accidents',
                                    data: vehicularAccidents,
                                    borderColor: 'rgb(255, 99, 132)',
                                    fill: false,
                                    tension: 0.1,
                                    pointRadius: 5,
                                    pointHoverRadius: 8
                                },
                                {
                                    label: 'Total Responded',
                                    data: totalSum,
                                    borderColor: 'rgb(54, 162, 235)',
                                    fill: false,
                                    tension: 0.1,
                                    pointRadius: 5,
                                    pointHoverRadius: 8
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: true
                                }
                            },
                            interaction: {
                                mode: 'nearest',
                                intersect: false
                            },
                            hover: {
                                mode: 'nearest',
                                intersect: false
                            },
                            onClick: function(event, activeElements) {
                                const datasetLabel = chart.data.datasets[activeElements[0].datasetIndex].label;
                                const medicalReportBlock = document.getElementById('medicalReportBlock');
                                const accidentReportBlock = document.getElementById('accidentReportBlock');
                                const totalRespondedReportBlock = document.getElementById('totalRespondedReportBlock');

                                // Ensure pagination is initialized only once for each block
                                let blockToDisplay;
                                let paginateSelector;

                                if (datasetLabel === 'Vehicular Accidents') {
                                    blockToDisplay = accidentReportBlock;
                                    paginateSelector = '#accidentReportBlock table';
                                } else if (datasetLabel === 'Medical Cases') {
                                    blockToDisplay = medicalReportBlock;
                                    paginateSelector = '#medicalReportBlock table';
                                } else if (datasetLabel === 'Total Responded') {
                                    blockToDisplay = totalRespondedReportBlock;
                                    paginateSelector = '#totalRespondedReportBlock table';
                                }

                                // Hide all blocks first
                                [medicalReportBlock, accidentReportBlock, totalRespondedReportBlock].forEach(block => {
                                    block.classList.add('hidden');
                                });

                                // Show the selected block
                                blockToDisplay.classList.remove('hidden');

                                // Initialize pagination only for the active block
                                paginateTable(paginateSelector);

                                // Scroll into view
                                blockToDisplay.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'start'
                                });
                            }
                        }
                    });
                })
                .catch(error => console.error('Error fetching data:', error));

            // Pagination Function
            function paginateTable(tableSelector) {
                const rowsPerPage = 5; // Customize rows per page
                const table = document.querySelector(tableSelector);
                const rows = table.querySelectorAll('tbody tr');
                const totalPages = Math.ceil(rows.length / rowsPerPage);
                const paginationControls = document.createElement('div');
                paginationControls.classList.add('pagination-controls');
                const resultsInfo = document.createElement('div');
                resultsInfo.classList.add('results-info');
                const paginationWrapper = document.createElement('div');
                paginationWrapper.classList.add('pagination-wrapper'); // Wrapper for pagination and results info

                // Avoid re-adding pagination controls if already present
                if (table.parentNode.querySelector('.pagination-controls')) {
                    return; // Exit if pagination controls are already initialized
                }

                table.parentNode.appendChild(paginationWrapper); // Add new wrapper
                paginationWrapper.appendChild(paginationControls); // Append pagination controls to wrapper
                paginationWrapper.appendChild(resultsInfo); // Append results info to wrapper

                let currentPage = 1; // Start on the first page

                function updateResultsInfo(page) {
                    const start = (page - 1) * rowsPerPage + 1;
                    const end = Math.min(page * rowsPerPage, rows.length);
                    resultsInfo.textContent = `Showing ${start} to ${end} of ${rows.length} results`;
                }

                function displayPage(page) {
                    currentPage = page;
                    rows.forEach((row, index) => {
                        row.style.display = (index >= (page - 1) * rowsPerPage && index < page * rowsPerPage) ? '' : 'none';
                    });
                    updateResultsInfo(page); // Update results info
                    updatePaginationButtons(); // Update the active state of pagination buttons
                }

                function createPaginationButtons() {
                    paginationControls.innerHTML = ''; // Clear existing buttons

                    // Create Previous Button
                    const prevButton = document.createElement('button');
                    prevButton.textContent = '<';
                    prevButton.classList.add('pagination-btn');
                    prevButton.addEventListener('click', () => {
                        if (currentPage > 1) {
                            displayPage(currentPage - 1);
                        }
                    });
                    paginationControls.appendChild(prevButton);

                    // Create page number buttons
                    for (let i = 1; i <= totalPages; i++) {
                        const button = document.createElement('button');
                        button.textContent = i;
                        button.classList.add('pagination-btn');
                        if (i === currentPage) button.classList.add('active'); // Highlight active page

                        button.addEventListener('click', () => {
                            displayPage(i);
                        });

                        paginationControls.appendChild(button);
                    }

                    // Create Next Button
                    const nextButton = document.createElement('button');
                    nextButton.textContent = '>';
                    nextButton.classList.add('pagination-btn');
                    nextButton.disabled = currentPage === totalPages; // Disable on last page
                    nextButton.addEventListener('click', () => {
                        if (currentPage < totalPages) {
                            displayPage(currentPage + 1);
                        }
                    });
                    paginationControls.appendChild(nextButton);
                }

                function updatePaginationButtons() {
                    const buttons = paginationControls.querySelectorAll('button');
                    buttons.forEach(button => {
                        if (button.textContent === 'Previous') {
                            button.disabled = currentPage === 1;
                        } else if (button.textContent === 'Next') {
                            button.disabled = currentPage === totalPages;
                        } else {
                            button.classList.toggle('active', parseInt(button.textContent) === currentPage);
                        }
                    });
                }

                createPaginationButtons();
                displayPage(currentPage); // Show first page by default
            }
        });
    </script>


    <style>
        


        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
            flex-direction: row-reverse;
        }

        .pagination-controls {
            display: flex;
            gap: 0.5rem;
        }

        .pagination-btn {
            padding: 0.5rem 1rem;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
            cursor: pointer;
        }

        .pagination-btn.active {
            background-color: #f0f0f0;
            border: 2px solid #EB8317;
            color: black;
        }

        .results-info {
            font-size: 1rem;
            color: #333;
        }
        
        @media print {

            /* Hide elements not required in print mode */
            nav,
            .header,
            .flex.justify-between,
            .mb-4,
            .bg-blue-500,
            .bg-green-500,
            .bg-yellow-500,
            .bg-red-500,
            .print-hidden,
            .pagination-wrapper {
                display: none;
            }

            /* Ensure tables show all rows in print */
            .relative {
                max-height: none !important;
                overflow: visible !important;
            }

            table {
                font-size: 15px;
            }

            th,
            td {
                padding: 4px !important;
            }

            .print-show {
                display: block;
            }

            .print-adjust {
                padding-bottom: 0px;
                padding-top: 9px;
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