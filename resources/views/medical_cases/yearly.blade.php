<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight print-hidden">
            {{ __('Yearly Report') }}
        </h2>

        <div class="hidden print-show text-center">
            <div class="flex">
                <img src="{{ asset('images/escaLogo.jpg') }}" style="width: 17%; max-width: 800px;">
                <div>
                    <p class="font-bold">
                        REPUBLIC OF THE PHILIPPINES<br>PROVINCE OF NEGROS OCCIDENTAL<br> ESCALANTE CITY
                    </p>
                    <p class=" text-blue-900 font-bold">Disaster Risk Reduction & Management Office</p>
                    <p class="text-blue-900 font-bold">Gomez Street, Brgy. Balintawak, Escalante City, Neg. Occ.</p>
                    <p class=" text-red-600">09152627121 | 09089376724</p>
                </div>
                <img src="{{ asset('images/logo.png') }}" style="width: 17%; max-width: 800px;">
            </div>
        </div>

    </x-slot>

    <div class="py-12 print-adjust">
        <div class="max-w-7xl mx-auto sm:px-6 xl:px-0 2xl:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 text-gray-900 print-header">

                    <p class="hidden print-show mt-1 pt-1 mb-1 pb-1 border-b-2 border-b-gray-300 text-center">
                        <span class=" font-bold text-2xl">Medical Case</span> <br> Response summary from year <span class=" text-red-600 font-bold">2020 to Present</span>
                    </p>

                    <h3 class="text-lg font-semibold mb-4">{{ __("Yearly Report of Medical Cases") }}</h3>

                    @if ($yearlyMedicals)
                    <!-- Scrollable table container with fixed header -->
                    <div class="overflow-x-auto">
                        <div class="relative max-h-96 2xl:max-h-[30rem] overflow-y-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barangay</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chief Complaints</th>
                                        @foreach(range(2020, now()->year) as $year)
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $year }}</th>
                                        @endforeach
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($yearlyMedicals as $barangay => $cases)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $barangay }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-wrap capitalize">{{ $cases['chief_complaints'] }}</td>

                                        @php
                                        $total = 0;
                                        @endphp

                                        @foreach(range(2020, now()->year) as $year)
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $cases[$year] ?? 0 }}
                                            @php
                                            $total += $cases[$year] ?? 0;
                                            @endphp
                                        </td>
                                        @endforeach

                                        <td class="px-6 py-4 whitespace-nowrap font-semibold">{{ $total }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Print Button -->
                    <div class="grid place-items-end">
                        <button onclick="window.print()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-700 {{ auth()->user()->role === 0 ? '' : 'hidden' }}">
                            {{ __('Print') }}
                        </button>
                    </div>

                    @else
                    <p>{{ __("No medical case data found.") }}</p>
                    @endif
                </div>
            </div>
            <div class="{{ auth()->user()->role === 0 ? 'flex' : 'hidden' }} print-hidden gap-3 mt-10">
                <div class="bg-white overflow-hidden w-full shadow-md sm:rounded-lg">
                    <div class="p-6">
                        <!-- <h3 class="text-lg font-semibold mb-4 mt-2">{{ __("Line Chart") }}</h3> -->
                        <!-- Bar Chart Container -->
                        <canvas id="medicalCasesChart" class="p-6"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this script tag to load Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/yearly-medicals')
                .then(response => response.json())
                .then(data => {
                    const yearlyMedicals = data.yearlyMedicals;

                    // Extract years, total medical cases, vehicular accidents, and the sum of both
                    const years = yearlyMedicals.map(item => item.year);
                    const totalCases = yearlyMedicals.map(item => item.total_medicals);
                    const vehicularAccidents = yearlyMedicals.map(item => item.vehicular_accidents);
                    const totalSum = yearlyMedicals.map(item => item.total_sum);

                    // Create the chart with multiple datasets
                    const ctx = document.getElementById('medicalCasesChart').getContext('2d');
                    const myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: years, // X-axis labels (years)
                            datasets: [{
                                    label: 'Medical Cases',
                                    data: totalCases, // Y-axis data (total medical cases)
                                    borderColor: 'rgb(75, 192, 192)',
                                    fill: false,
                                    tension: 0.1
                                },
                                {
                                    label: 'Vehicular Accidents',
                                    data: vehicularAccidents, // Y-axis data (vehicular accidents)
                                    borderColor: 'rgb(255, 99, 132)',
                                    fill: false,
                                    tension: 0.1
                                },
                                {
                                    label: 'Total Responded',
                                    data: totalSum, // Y-axis data (sum of both)
                                    borderColor: 'rgb(54, 162, 235)',
                                    fill: false,
                                    tension: 0.1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: true
                                },
                                // title: {
                                //     display: true,
                                //     text: 'Total Medical Cases, Vehicular Accidents, and Combined Per Year'
                                // }
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
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