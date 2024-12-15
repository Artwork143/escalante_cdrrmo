@php
use Carbon\Carbon;
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight print-hidden">
            {{ __('Medical Case DSS') }}
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

            <div class="p-5 bg-[#295F98] print-hidden">

                <form method="GET" action="{{ route('dss_tools.index') }}" class="mb-4">
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

                        <!-- <input type="text" name="search" value="{{ request('search') }}" placeholder="Search cases..." class=" w-1/4 py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 grid grid-row-2 hover:bg-gray-200" /> -->
                    </div>
                </form>
            </div>


            <div class="{{ auth()->user()->role === 0 ? 'flex' : 'hidden' }} print-hidden gap-3">
                <!-- Pie chart section -->
                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg print-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 mt-2">{{ __("Medical Cases by Barangay") }}</h3>
                        <div class="mb-6" style="width: 500px;">
                            <canvas id="barangayPieChart" width="400" height="400" class="pt-5"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Bar chart section -->
                <div class="bg-white shadow-md sm:rounded-lg w-3/5 p-6">
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

                <div class="mb-4 flex justify-between">
                    <h3 id="barangayTitle" class="text-lg font-semibold mb-4 mt-2"></h3>
                    <input
                        type="text"
                        id="searchBar"
                        placeholder="Search cases..."
                        class="border rounded px-3 py-2 w-1/4"
                        oninput="searchBarangayCases()" />
                </div>
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
                        <!-- Table rows will be dynamically added here -->
                    </tbody>
                </table>

                <!-- Pagination controls -->
                <div id="paginationControls" class="mt-4 pt-4 flex gap-2 justify-between border-t-2 border-t-gray-400">
                    <!-- Pagination buttons will be dynamically added here -->
                </div>

                <div class="grid place-items-end mt-4">
                    <button
                        onclick="printBarangayCases()"
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700">
                        Print
                    </button>
                </div>
            </div>


        </div>
    </div>

    <!-- Chart.js script and pie chart logic -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch barangay cases data from the backend
            fetch(`/get-barangay-cases?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}`)
                .then(response => response.json())
                .then(data => {
                    const sortedForChart = [...data].sort((a, b) => a.total_cases - b.total_cases);
                    const sortedForRanking = [...data].sort((a, b) => b.total_cases - a.total_cases);

                    const labels = sortedForChart.map(item => item.barangay);
                    const values = sortedForChart.map(item => item.total_cases);
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

                    // Render the Pie Chart
                    renderPieChart(labels, values, backgroundColors);

                    // Function to render the Pie Chart
                    function renderPieChart(labels, values, backgroundColors) {
                        const ctxPie = document.getElementById('barangayPieChart').getContext('2d');
                        new Chart(ctxPie, {
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
                                onClick: (e, item) => {
                                    if (item.length > 0) {
                                        const index = item[0].index;
                                        const barangay = labels[index];
                                        loadBarangayDetails(barangay, 1); // Start from page 1
                                    }
                                }
                            }
                        });
                    }

                    // Attach loadBarangayDetails and renderPaginationControls to the window object
                    window.loadBarangayDetails = function(barangay, page = 1) {
                        fetch(`/get-barangay-details/${barangay}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}&page=${page}`)
                            .then(response => response.json())
                            .then(data => {
                                displayBarangayDetails(barangay, data.data);
                                renderPaginationControls(data, barangay);
                            });
                    };

                    // Display barangay details in the table
                    function displayBarangayDetails(barangay, cases) {
                        document.getElementById('barangayDetails').classList.remove('hidden');
                        document.getElementById('barangayTitle').innerText = `Details of cases for Brgy. ${barangay}`;
                        const tableBody = document.getElementById('barangayTableBody');
                        tableBody.innerHTML = cases.map(caseItem => `
                        <tr class="even:bg-gray-50 odd:bg-white hover:bg-gray-200">
                            <td class="px-6 py-4 whitespace-nowrap">${caseItem.date}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${caseItem.rescue_team}</td>
                            <td class="px-6 py-4 whitespace-nowrap capitalize text-wrap">${caseItem.place_of_incident}, Brgy. ${caseItem.barangay}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${caseItem.no_of_patients}</td>
                            <td class="px-6 py-4 whitespace-nowrap capitalize text-wrap">${caseItem.chief_complaints}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${caseItem.facility_name}</td>
                        </tr>
                    `).join('');
                        // Scroll to footer
                        document.getElementById('pageFooter').scrollIntoView({
                            behavior: 'smooth'
                        });
                    }

                    // Attach renderPaginationControls to the window object for global access
                    window.renderPaginationControls = function(data, barangay) {
                        const paginationContainer = document.getElementById('paginationControls');
                        paginationContainer.innerHTML = '';

                        // Display range information (e.g., "Showing 1 to 5 of 23 results")
                        const start = (data.current_page - 1) * data.per_page + 1;
                        const end = Math.min(data.total, data.current_page * data.per_page);
                        const rangeInfo = document.createElement('div');
                        rangeInfo.classList.add('text-gray-700', 'text-sm', 'mb-2');
                        rangeInfo.textContent = `Showing ${start} to ${end} of ${data.total} results`;
                        paginationContainer.appendChild(rangeInfo);

                        // Create pagination controls container
                        const paginationControls = document.createElement('div');
                        paginationControls.classList.add('flex', 'justify-center', 'space-x-2', 'border', 'rounded-lg', 'px-3', 'py-1');

                        // "Previous" button
                        if (data.prev_page_url) {
                            const prevButton = document.createElement('button');
                            prevButton.classList.add('px-3', 'py-1', 'border', 'rounded-l-lg', 'hover:bg-gray-100');
                            prevButton.textContent = '«';
                            prevButton.onclick = () => loadBarangayDetails(barangay, data.current_page - 1);
                            paginationControls.appendChild(prevButton);
                        }

                        // Page numbers
                        for (let i = 1; i <= data.last_page; i++) {
                            const pageButton = document.createElement('button');
                            pageButton.classList.add('px-3', 'py-1', 'border', 'hover:bg-gray-100');
                            if (i === data.current_page) {
                                pageButton.classList.add('border-2', 'border-[#EB8317]'); // Current page styling
                            }
                            pageButton.textContent = i;
                            pageButton.onclick = () => loadBarangayDetails(barangay, i);
                            paginationControls.appendChild(pageButton);
                        }

                        // "Next" button
                        if (data.next_page_url) {
                            const nextButton = document.createElement('button');
                            nextButton.classList.add('px-3', 'py-1', 'border', 'rounded-r-lg', 'hover:bg-gray-100');
                            nextButton.textContent = '»';
                            nextButton.onclick = () => loadBarangayDetails(barangay, data.current_page + 1);
                            paginationControls.appendChild(nextButton);
                        }

                        // Append pagination controls to the container
                        paginationContainer.appendChild(paginationControls);
                    };


                    // Render the Horizontal Bar Chart for ranking
                    renderBarChart(sortedForRanking);

                    function renderBarChart(sortedForRanking) {
                        const rankingLabels = sortedForRanking.map(item => item.barangay);
                        const rankingValues = sortedForRanking.map(item => item.total_cases);
                        const ctxBar = document.getElementById('barangayRankingChart').getContext('2d');

                        new Chart(ctxBar, {
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
                                        const barangay = rankingLabels[index];
                                        loadBarangayDetails(barangay, 1); // Start from page 1
                                    }
                                }
                            }
                        });
                    }

                    // Show suggestion for barangay with the highest cases
                    const highestCaseBarangay = sortedForRanking[0].barangay;
                    const highestCaseCount = sortedForRanking[0].total_cases;
                    const suggestionBox = document.getElementById('suggestionBox');
                    const focusBarangay = document.getElementById('focusBarangay');
                    focusBarangay.innerText = `${highestCaseBarangay} (${highestCaseCount} cases)`;
                    if (highestCaseCount > 1) {
                        suggestionBox.classList.remove('hidden');
                    }
                });
        });

        function searchBarangayCases() {
            // Get the search term from the input field
            const searchTerm = document.getElementById('searchBar').value.toLowerCase();

            // Get all the rows in the table body
            const tableBody = document.getElementById('barangayTableBody');
            const rows = tableBody.getElementsByTagName('tr');

            // Loop through each row and toggle its visibility based on the search term
            for (let row of rows) {
                // Get all cells in the current row
                const cells = row.getElementsByTagName('td');

                // Combine the text content of all cells for search comparison
                const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');

                // Check if the row text contains the search term
                if (rowText.includes(searchTerm)) {
                    row.style.display = ''; // Show row
                } else {
                    row.style.display = 'none'; // Hide row
                }
            }
        }


        //Print function for DetailedBarangay Table only
        function printBarangayCases() {
            // Get the table's HTML
            const table = document.querySelector('#barangayTableBody').parentElement.outerHTML;

            // Create a new window
            const printWindow = window.open('', '', 'height=600,width=800');

            // Write the necessary HTML to the new window
            printWindow.document.write(`
        <html>
            <head>
                <title>Print Barangay Cases</title>
                <style>
                    /* Add any styles you want for the printed page */
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    th, td {
                        border: 1px solid #ddd;
                        padding: 8px;
                        text-align: left;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                    .header {
                        text-align: center;
                        margin-bottom: 20px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }
                    .header img {
                        width: 100px;
                        max-width: 100px;
                        height: auto;
                    }
                    .header p {
                        margin: 0;
                        line-height: 1.4;
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <img src="{{ asset('images/escaLogo.jpg') }}" alt="Esca Logo">
                    <div class="mx-4">
                        <p class="font-bold">
                            REPUBLIC OF THE PHILIPPINES<br>PROVINCE OF NEGROS OCCIDENTAL<br> ESCALANTE CITY
                        </p>
                        <p class="text-blue-900 font-bold">Disaster Risk Reduction & Management Office</p>
                        <p class="text-blue-900 font-bold">Gomez Street, Brgy. Balintawak, Escalante City, Neg. Occ.</p>
                        <p class="text-red-600">09152627121 | 09089376724</p>
                    </div>
                    <img src="{{ asset('images/logo.png') }}" alt="Logo">
                </div>
                <h2>Medical Cases Per Barangay</h2>
                ${table}
            </body>
        </html>
    `);

            // Close the document to ensure all resources are loaded
            printWindow.document.close();

            // Wait for the content to load before printing
            printWindow.onload = function() {
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            };
        }
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