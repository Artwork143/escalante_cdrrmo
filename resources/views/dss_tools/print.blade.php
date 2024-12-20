<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight print-hidden">
            {{ __('Disasters DSS') }}
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

                <form method="GET" action="{{ route('dss_tools.disaster') }}" class="mb-4">
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
                    <h3 class="text-lg font-semibold mb-4 mt-2">{{ __("Ranking of Disasters (Most to Least)") }}</h3>
                    <canvas id="disasterRankingChart"></canvas>

                    <!-- Suggestion Box -->
                    <div id="suggestionBox" class="mt-6 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 hidden">
                        <p class="font-semibold">Focus on Disaster Type: <span id="focusDisaster"></span></p>
                        <p>This disaster type has the highest number of casualties and might require more attention and resources.</p>
                    </div>
                </div>
            </div>

            <div id="disasterDetails" class="hidden bg-white shadow-md sm:rounded-lg p-6 mt-10 print-hidden">
                <div class="mb-4 flex justify-between">
                    <h3 id="disasterTitle" class="text-lg font-semibold mb-2"></h3>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barangay</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Affected Infrastructure</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Casualties</th>
                        </tr>
                    </thead>
                    <tbody id="disasterTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Rows will be dynamically added here -->
                    </tbody>
                </table>

                <div class="hidden flex-col mt-1 p-1 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700" id="suggest">
                    <p id="suggestionBox2" class="font-semibold">
                    </p>
                    <p>This barangay has the highest number of cases and might require more attention and resources.</p>
                </div>

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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch disaster data from the backend
            fetch(`/get-disaster-data?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}`)
                .then(response => response.json())
                .then(data => {
                    const sortedForChart = [...data].sort((a, b) => a.total_casualties - b.total_casualties);
                    const sortedForRanking = [...data].sort((a, b) => b.total_casualties - a.total_casualties);

                    const labels = sortedForChart.map(item => item.type);
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
                    function loadDisasterDetails(disasterType, page = 1) {
                        fetch(`/get-disaster-details/${disasterType}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}&page=${page}`)
                            .then(response => response.json())
                            .then(data => {
                                displayDisasterDetails(disasterType, data.data);
                                renderPaginationControls(data, disasterType);
                            })
                            .catch(error => console.error('Error fetching disaster details:', error));
                    }

                    // Attach renderPaginationControls to the window object for global access
                    window.renderPaginationControls = function(data, disasterType) {
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
                            prevButton.onclick = () => loadDisasterDetails(disasterType, data.current_page - 1);
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
                            pageButton.onclick = () => loadDisasterDetails(disasterType, i);
                            paginationControls.appendChild(pageButton);
                        }

                        // "Next" button
                        if (data.next_page_url) {
                            const nextButton = document.createElement('button');
                            nextButton.classList.add('px-3', 'py-1', 'border', 'rounded-r-lg', 'hover:bg-gray-100');
                            nextButton.textContent = '»';
                            nextButton.onclick = () => loadDisasterDetails(barangay, data.current_page + 1);
                            paginationControls.appendChild(nextButton);
                        }

                        // Append pagination controls to the container
                        paginationContainer.appendChild(paginationControls);
                    };

                    function displayDisasterDetails(disasterType, details) {
                        const disasterDetails = document.getElementById('disasterDetails');
                        const disasterTitle = document.getElementById('disasterTitle');
                        const tableBody = document.getElementById('disasterTableBody');
                        const suggestionBox2 = document.getElementById('suggestionBox2');

                        // Update title
                        disasterTitle.innerText = `Details for ${disasterType}`;

                        // Find duplicate barangays
                        const barangayCount = {};
                        details.forEach(detail => {
                            if (barangayCount[detail.barangay]) {
                                barangayCount[detail.barangay]++;
                            } else {
                                barangayCount[detail.barangay] = 1;
                            }
                        });

                        // Get list of barangays that appear more than once
                        const duplicateBarangays = Object.keys(barangayCount).filter(barangay => barangayCount[barangay] > 1);

                        // Display duplicate barangays in suggestion box
                        if (duplicateBarangays.length > 0) {
                            suggestionBox2.innerHTML = `Focus on this Barangay: ${duplicateBarangays.join(', ')}`;
                            document.getElementById('suggest').classList.add('flex');
                            document.getElementById('suggest').classList.remove('hidden');
                        }

                        // Populate table rows
                        tableBody.innerHTML = details.map(detail => `
        <tr class="even:bg-gray-50 odd:bg-white hover:bg-gray-200">
            <td class="px-6 py-4 whitespace-nowrap">${detail.date}</td>
            <td class="px-6 py-4 whitespace-nowrap">${detail.rescue_team}</td>
            <td class="px-6 py-4 whitespace-nowrap">${detail.barangay}</td>
            <td class="px-6 py-4 whitespace-nowrap">${detail.affected_infrastructure}</td>
            <td class="px-6 py-4 whitespace-nowrap">${detail.casualties}</td>
        </tr>
    `).join('');

                        // Ensure Print Button Passes disasterType
                        const printButton = disasterDetails.querySelector('button');
                        printButton.setAttribute('onclick', `printBarangayCases('${disasterType}')`);

                        // Show the table
                        disasterDetails.classList.remove('hidden');

                        // Scroll to the details
                        disasterDetails.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }


                    // Modify the onClick in both chart render functions
                    function renderPieChart(labels, values, backgroundColors) {
                        const ctxPie = document.getElementById('disasterPieChart').getContext('2d');
                        new Chart(ctxPie, {
                            type: 'pie',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Cases',
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
                                        loadDisasterDetails(disasterType); // Load details for the clicked type
                                    }
                                }
                            }
                        });
                    }

                    function renderBarChart(sortedForRanking) {
                        const rankingLabels = sortedForRanking.map(item => item.type);
                        const rankingValues = sortedForRanking.map(item => item.total_casualties);
                        const ctxBar = document.getElementById('disasterRankingChart').getContext('2d');

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
                                        const disasterType = rankingLabels[index];
                                        loadDisasterDetails(disasterType); // Load details for the clicked type
                                    }
                                }
                            }
                        });
                    }

                    // Show suggestion for disaster type with highest casualties
                    const highestCaseDisaster = sortedForRanking[0].type;
                    const highestCaseCount = sortedForRanking[0].total_casualties;
                    const suggestionBox = document.getElementById('suggestionBox');
                    const focusDisaster = document.getElementById('focusDisaster');
                    focusDisaster.innerText = `${highestCaseDisaster} (${highestCaseCount} casualties)`;
                    if (highestCaseCount > 1) {
                        suggestionBox.classList.remove('hidden');
                    }
                });
        });

        function searchBarangayCases() {
            // Get the search term from the input field
            const searchTerm = document.getElementById('searchBar').value.toLowerCase();

            // Get all the rows in the table body
            const tableBody = document.getElementById('disasterTableBody');
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


        function printBarangayCases(selectedCalamity) {
            // Get the table's HTML
            const table = document.querySelector('#disasterTableBody').parentElement.outerHTML;

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
                <h2>Details for ${selectedCalamity}</h2>
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


        document.addEventListener("DOMContentLoaded", function() {
            const selectElement = document.getElementById("type");

            // Fetch disaster types from the server
            fetch('/api/disaster-types')
                .then(response => response.json())
                .then(data => {
                    data.forEach(disaster => {
                        const option = document.createElement("option");
                        option.value = disaster.type_name;
                        option.textContent = disaster.type_name;
                        selectElement.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching disaster types:', error));
        });
    </script>
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