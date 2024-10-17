@php
use Carbon\Carbon;
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-12 flex">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex-grow">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-2 text-gray-900">
                    <div id="map" style="width: 100%; height: 500px;">
                        <div class="sm:rounded-lg p-4 ml-1" style="width: 400px;">
                            <h3 class="font-semibold text-xl">Total Cases for {{ Carbon::now()->format('F') }} of Brgy. </h3>
                            <div id="accident-info" class="text-lg">Click on a barangay to see details...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Emergency Hotline Table -->
    <div class="pb-12 flex">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex-grow">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-xl">Emergency Hotline Numbers</h3>
                    <table class="w-full table-auto border-collapse border border-gray-300 mt-4">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border border-gray-300 px-4 py-2">Service</th>
                                <th class="border border-gray-300 px-4 py-2">Hotline Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">CDRRMO</td>
                                <td class="border border-gray-300 px-4 py-2">09058400958</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">KABALIKAT</td>
                                <td class="border border-gray-300 px-4 py-2">09292803024 | 09173799598</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">PNP</td>
                                <td class="border border-gray-300 px-4 py-2">09985987421</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">BFP</td>
                                <td class="border border-gray-300 px-4 py-2">09216803965</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">NONECO</td>
                                <td class="border border-gray-300 px-4 py-2">09178729032</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">COASTGUARD</td>
                                <td class="border border-gray-300 px-4 py-2">09178428359</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">LOCAL RADIO FREQUENCY</td>
                                <td class="border border-gray-300 px-4 py-2">145.400 Callsign: Opcen Base</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    var selectedLayer = null; // To track the currently selected barangay layer

    // Initialize map with disabled zoom controls
    var map = L.map('map', {
        zoomControl: false,
        scrollWheelZoom: false, // Disable zoom by scrolling
        doubleClickZoom: false, // Disable zoom by double-clicking
        dragging: false, // Disable dragging the map
        touchZoom: false, // Disable zoom for touch devices (pinch)
        keyboard: false // Disable zoom by keyboard shortcuts
    }).setView([10.1234, 122.1234], 12); // Set initial map view


    // Load GeoJSON data for the barangay polygons
    fetch('/geojson/Escalante.geojson')
        .then(response => {
            if (!response.ok) {
                throw new Error('GeoJSON file could not be loaded.');
            }
            return response.json();
        })
        .then(geojson => {
            // Add GeoJSON layer to the map with hover and click interactions
            var polygonLayer = L.geoJSON(geojson, {
                style: {
                    color: '#024CAA',
                    fillColor: '#347928',
                    fillOpacity: 0.5
                },
                onEachFeature: function(feature, layer) {
                    if (feature.properties && feature.properties.barangay) {
                        // Bind tooltip with barangay name
                        layer.bindTooltip(feature.properties.barangay, {
                            permanent: false,
                            direction: "top"
                        });

                        // Hover interaction to change fill opacity
                        layer.on('mouseover', function(e) {
                            this.setStyle({
                                fillOpacity: 0.7
                            });
                        });

                        layer.on('mouseout', function(e) {
                            if (selectedLayer !== this) {
                                this.setStyle({
                                    fillOpacity: 0.5
                                });
                            }
                        });

                        // Click interaction to change color and fetch accident data
                        layer.on('click', function() {
                            const barangayName = feature.properties.barangay;

                            // Reset style of previously selected barangay
                            if (selectedLayer) {
                                selectedLayer.setStyle({
                                    color: 'blue',
                                    fillColor: '#f03',
                                    fillOpacity: 0.5
                                });
                            }

                            // Set new style for the clicked barangay
                            this.setStyle({
                                fillColor: '#FF6600',
                                fillOpacity: 0.7
                            });

                            // Track the currently selected layer
                            selectedLayer = this;

                            // Get the current month (1 for January, 2 for February, etc.)
                            const currentMonth = new Date().getMonth() + 1; // JavaScript months are 0-based

                            // Fetch data including the current month in the API call
                            fetch(`/api/cases?barangay=${encodeURIComponent(barangayName)}&month=${currentMonth}`)
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Failed to fetch data.');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    // Extract the relevant data from the response
                                    const accidents = data.accidents_count || 0;
                                    const medicalCases = data.medicals_count || 0;
                                    const punongBarangay = data.punong_barangay || 'Unknown';
                                    const contactNumber = data.contact_number || 'N/A';

                                    // Update the accident-info div to display the details
                                    document.getElementById('accident-info').innerHTML =
                                        `<strong>${barangayName}</strong><br>
            Vehicular Accidents: ${accidents}<br>
            Medical Cases: ${medicalCases}<br>
            <strong>Punong Barangay:<br></strong> ${punongBarangay}<br>
            <strong>Contact Number:</strong> ${contactNumber}`;
                                })
                                .catch(error => {
                                    console.error('Error fetching data:', error);
                                    document.getElementById('accident-info').innerHTML =
                                        `<strong>${barangayName}</strong><br>Error loading data.`;
                                });
                        });
                    }
                }
            }).addTo(map);

            // Fit map bounds to the GeoJSON data
            map.fitBounds(polygonLayer.getBounds());
        })
        .catch(error => {
            console.error('Error loading the GeoJSON file:', error);
            document.getElementById('accident-info').innerHTML = 'Error loading map data.';
        });

    // Enable scroll zoom only when the map is focused
    map.on('focus', function() {
        map.scrollWheelZoom.enable();
    });

    map.on('blur', function() {
        map.scrollWheelZoom.disable();
    });
</script>