<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight print-hidden">
            {{ __('Yearly Report') }}
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
                                    <tr>
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
                                    <tr>
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
                    <div class="grid place-items-end">
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
                                        <tr>
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

                                if (datasetLabel === 'Vehicular Accidents') {
                                    medicalReportBlock.classList.add('hidden');
                                    accidentReportBlock.classList.remove('hidden');
                                    totalRespondedReportBlock.classList.add('hidden'); // Hide Total Responded
                                } else if (datasetLabel === 'Medical Cases') {
                                    accidentReportBlock.classList.add('hidden');
                                    medicalReportBlock.classList.remove('hidden');
                                    totalRespondedReportBlock.classList.add('hidden'); // Hide Total Responded
                                } else if (datasetLabel === 'Total Responded') {
                                    medicalReportBlock.classList.add('hidden');
                                    accidentReportBlock.classList.add('hidden');
                                    totalRespondedReportBlock.classList.remove('hidden'); // Show Total Responded
                                }
                            }

                        }
                    });
                })
                .catch(error => console.error('Error fetching data:', error));
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