<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Disaster Table</title>
    @vite('resources/css/app.css') <!-- Assuming you use Vite for Tailwind -->
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="max-w-4xl mx-auto mt-10">
        <h1 class="text-2xl font-bold text-center mb-6">Disaster Data</h1>

        <!-- Create Disaster Case Button -->
        <div class="mb-6 text-right">
            <a href="{{ route('disasters.create') }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
                Create New Disaster Case
            </a>
        </div>

        <!-- Dropdown for selecting disaster -->
        <div class="mb-4">
            <label for="disaster-select" class="block font-medium text-gray-700">Select Disaster Type:</label>
            <select id="disaster-select" class="w-full mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="" disabled selected>Select a disaster</option>
                <option value="Flood">Flood</option>
                <option value="Earthquake">Earthquake</option>
                <option value="Volcanic Eruption">Volcanic Eruption</option>
                <option value="Rebel Encounter">Rebel Encounter</option>
            </select>
        </div>


        <!-- Dynamic Table -->
        <div class="overflow-x-auto">
            <table id="disaster-table" class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-50">
                    <tr id="table-header">
                        <!-- Headers will be dynamically populated -->
                    </tr>
                </thead>
                <tbody id="table-body" class="bg-white">
                    <!-- Rows will be dynamically populated -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById("disaster-select").addEventListener("change", (e) => {
            const selectedDisaster = e.target.value;
            if (selectedDisaster) {
                // Ensure the correct URL is generated with the selected type
                updateTable(selectedDisaster);
            }
        });

        // Fetch and update table data based on the selected disaster type
        async function updateTable(disasterType) {
            try {
                const response = await fetch(`/disaster-data/${disasterType}`); // Corrected URL with disaster type
                const {
                    headers,
                    rows
                } = await response.json();

                const tableHeader = document.getElementById("table-header");
                const tableBody = document.getElementById("table-body");

                // Clear existing table content
                tableHeader.innerHTML = "";
                tableBody.innerHTML = "";

                // Populate table headers
                headers.forEach(header => {
                    const th = document.createElement("th");
                    th.className = "border border-gray-300 px-4 py-2 text-left";
                    th.textContent = header;
                    tableHeader.appendChild(th);
                });

                // Populate table rows
                rows.forEach(row => {
                    const tr = document.createElement("tr");
                    row.forEach(cell => {
                        const td = document.createElement("td");
                        td.className = "border border-gray-300 px-4 py-2";
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
</body>

</html>