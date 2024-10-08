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
                        <span class=" font-bold text-2xl">Vehicular Accident</span> <br> Response summary from year <span class=" text-red-600 font-bold">2020 to Present</span>
                    </p>

                    <h3 class="text-lg font-semibold mb-4">{{ __("Yearly Report of Vehicular Accidents") }}</h3>

                    @if ($yearlyAccidents)
                    <!-- Scrollable table container with fixed header -->
                    <div class="overflow-x-auto">
                        <div class="relative max-h-96 2xl:max-h-[30rem] overflow-y-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barangay</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cause of Incident</th>
                                        @foreach(range(2020, now()->year) as $year)
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $year }}</th>
                                        @endforeach
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($yearlyAccidents as $barangay => $accidents)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $barangay }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $accidents['causes_of_incident'] }}</td>

                                        @php
                                        $total = 0;
                                        @endphp

                                        @foreach(range(2020, now()->year) as $year)
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $accidents[$year] ?? 0 }}
                                            @php
                                            $total += $accidents[$year] ?? 0;
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
                    <p>{{ __("No vehicular accident data found.") }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

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