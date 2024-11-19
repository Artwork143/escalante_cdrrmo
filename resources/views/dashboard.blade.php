@php
use Carbon\Carbon;
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div id="pageContent">
        <div class="py-12 flex justify-center items-center">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex-grow">
                <div class="bg-gray-50 shadow-lg rounded-lg overflow-hidden">
                    <div class="p-2 text-gray-900">
                        <div id="map" style="width: 100%; height: 500px;">
                            <div class="bg-white shadow-md rounded-lg p-6 absolute top-4 left-4 z-10" style="max-width: 400px;">
                                <h3 class="font-semibold text-xl text-gray-800">{{ Carbon::now()->format('F') }} Cases per Barangay</h3>
                                <div id="accident-info" class="text-lg text-gray-700 mt-2">Click on a barangay to see details...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emergency Hotline Table -->
        <div class="pb-12 flex">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex-grow">
                <div class="bg-white shadow-md sm:rounded-lg">
                    <!-- Title with color background -->
                    <div class="bg-[#295F98] text-white p-4 sm:rounded-t-lg">
                        <h3 class="font-bold text-2xl">
                            Emergency Hotline Numbers
                        </h3>
                    </div>

                    <div class="p-6 text-gray-900">
                        <table class="w-full table-auto border-collapse shadow-sm border border-gray-200 rounded-lg overflow-hidden">
                            <thead>
                                <tr class="bg-gray-600 text-white">
                                    <th class="border border-gray-300 px-4 py-2">Service</th>
                                    <th class="border border-gray-300 px-4 py-2">Hotline Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-gray-50 hover:bg-gray-100 transition ease-in-out">
                                    <td class="border border-gray-300 px-4 py-2">
                                        <i class="fas fa-ambulance text-gray-600 mr-2"></i> CDRRMO
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">09058400958</td>
                                </tr>
                                <tr class="bg-white hover:bg-gray-100 transition ease-in-out">
                                    <td class="border border-gray-300 px-4 py-2">
                                        <i class="fas fa-hands-helping text-gray-600 mr-2"></i> KABALIKAT
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">09292803024 | 09173799598</td>
                                </tr>
                                <tr class="bg-gray-50 hover:bg-gray-100 transition ease-in-out">
                                    <td class="border border-gray-300 px-4 py-2">
                                        <i class="fas fa-shield-alt text-gray-600 mr-2"></i> PNP
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">09985987421</td>
                                </tr>
                                <tr class="bg-white hover:bg-gray-100 transition ease-in-out">
                                    <td class="border border-gray-300 px-4 py-2">
                                        <i class="fas fa-fire-extinguisher text-gray-600 mr-2"></i> BFP
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">09216803965</td>
                                </tr>
                                <tr class="bg-gray-50 hover:bg-gray-100 transition ease-in-out">
                                    <td class="border border-gray-300 px-4 py-2">
                                        <i class="fas fa-bolt text-gray-600 mr-2"></i> NONECO
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">09178729032</td>
                                </tr>
                                <tr class="bg-white hover:bg-gray-100 transition ease-in-out">
                                    <td class="border border-gray-300 px-4 py-2">
                                        <i class="fas fa-anchor text-gray-600 mr-2"></i> COASTGUARD
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">09178428359</td>
                                </tr>
                                <tr class="bg-gray-50 hover:bg-gray-100 transition ease-in-out">
                                    <td class="border border-gray-300 px-4 py-2">
                                        <i class="fas fa-broadcast-tower text-gray-600 mr-2"></i> LOCAL RADIO FREQUENCY
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">145.400 Callsign: Opcen Base</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div id="barangayModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center" style="display: none;">
        <div id="modalContent" class="bg-white rounded-lg p-6 w-[430px] shadow-lg relative z-50 animate-bounce-in">
            <button onclick="closeModal()" class="bg-red-400 px-2 py-1 hover:scale-90 transition duration-150 hover:bg-red-800 text-white rounded absolute top-2 right-2">
                <i class="fa fa-times-circle" aria-hidden="true"></i>
            </button>
            <h2 id="modalBarangayName" class="text-xl font-semibold mb-4"></h2>
            <p id="modalDetails" class="text-gray-700 mb-4">Details will be shown here.</p>
        </div>
    </div>

</x-app-layout>

<script>
    var selectedLayer = null;

    var map = L.map('map', {
        zoomControl: false,
        scrollWheelZoom: false,
        doubleClickZoom: false,
        dragging: false,
        touchZoom: false,
        keyboard: false
    }).setView([10.1234, 122.1234], 12);

    // Load GeoJSON data
    fetch('/geojson/Escalante.geojson')
        .then(response => {
            if (!response.ok) {
                throw new Error('GeoJSON file could not be loaded.');
            }
            return response.json();
        })
        .then(geojson => {
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

                        layer.on('click', function() {
                            const barangayName = feature.properties.barangay;

                            // Fetch data for the clicked barangay
                            fetch(`/api/cases?barangay=${encodeURIComponent(barangayName)}&month=${new Date().getMonth() + 1}`)
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Failed to fetch data.');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    const accidents = data.accidents_count || 0;
                                    const medicalCases = data.medicals_count || 0;
                                    const punongBarangay = data.punong_barangay || 'Unknown';
                                    const contactNumber = data.contact_number || 'N/A';

                                    // Combine medical and accident counts for each rescue team
                                    const alphaCount = (data.alpha.medicals_count || 0) + (data.alpha.accidents_count || 0);
                                    const bravoCount = (data.bravo.medicals_count || 0) + (data.bravo.accidents_count || 0);
                                    const charlieCount = (data.charlie.medicals_count || 0) + (data.charlie.accidents_count || 0);

                                    // Prepare modal content
                                    const details = `
                                        Vehicular Accidents: ${accidents}<br>
                                        Medical Cases: ${medicalCases}<br>
                                        Punong Barangay: ${punongBarangay}<br>
                                        Contact Number: ${contactNumber}<br>
                                        Alpha: ${alphaCount} | Bravo: ${bravoCount} | Charlie: ${charlieCount}
                                    `;

                                    // Open modal with data
                                    openModal(barangayName, details);
                                })
                                .catch(error => {
                                    console.error('Error fetching data:', error);
                                    openModal(barangayName, 'Error loading data.');
                                });
                        });
                    }
                }
            }).addTo(map);

            map.fitBounds(polygonLayer.getBounds());
        })
        .catch(error => {
            console.error('Error loading the GeoJSON file:', error);
            document.getElementById('accident-info').innerHTML = 'Error loading map data.';
        });


    map.on('focus', function() {
        map.scrollWheelZoom.enable();
    });
    map.on('blur', function() {
        map.scrollWheelZoom.disable();
    });

    function openModal(barangayName, details) {
        document.getElementById('modalBarangayName').innerText = barangayName;
        document.getElementById('modalDetails').innerHTML = details;
        document.getElementById('barangayModal').style.display = 'flex'; // Show modal
        document.getElementById('modalContent').classList.remove('animate-bounce-out');
        document.getElementById('modalContent').classList.add('animate-bounce-in');
        document.getElementById('pageContent').classList.add('blur');
    }

    function closeModal() {
        const modalContent = document.getElementById('modalContent');
        modalContent.classList.remove('animate-bounce-in');
        modalContent.classList.add('animate-bounce-out');

        // Hide modal after closing animation
        modalContent.addEventListener('animationend', function handleAnimationEnd() {
            document.getElementById('barangayModal').style.display = 'none';
            document.getElementById('pageContent').classList.remove('blur');
            modalContent.removeEventListener('animationend', handleAnimationEnd);
        });
    }
</script>

<style>
    .blur {
        filter: blur(5px);
    }

    /* Define keyframes for modal animations */
    @keyframes bounce-in {

        0%,
        100% {
            transform: scale(1);
            opacity: 1;
        }

        30% {
            transform: scale(1.1);
        }

        50% {
            transform: scale(0.9);
        }

        70% {
            transform: scale(1.05);
        }
    }

    @keyframes bounce-out {
        0% {
            transform: scale(1);
            opacity: 1;
        }

        100% {
            transform: scale(0.9);
            opacity: 0;
        }
    }

    /* Classes to apply animations */
    .animate-bounce-in {
        animation: bounce-in 0.6s ease-in-out forwards;
    }

    .animate-bounce-out {
        animation: bounce-out 0.2s ease-in-out forwards;
    }
</style>