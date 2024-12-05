@php
use Carbon\Carbon;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight print-hidden">
            {{ __('Disasters Management') }}
        </h2>
    </x-slot>

    <div class="py-12 print-adjust">
        <div class="{{ auth()->user()->role === 0 ? 'xl:max-w-[90rem]' : 'max-w-7xl' }} mx-auto sm:px-6 lg:px-8 relative">
            <!-- Dropdown for selecting disaster -->
            <div class="mb-4">
                <form method="GET" action="{{ route('disasters.index') }}" id="disaster-filter-form">
                    <label for="disaster-select" class="block font-medium text-gray-700">Select Disaster Type:</label>
                    <select
                        id="disaster-select"
                        name="type"
                        onchange="document.getElementById('disaster-filter-form').submit()"
                        class="w-1/6 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="" disabled {{ is_null(request('type')) ? 'selected' : '' }}>Select a disaster</option>
                        <option value="Flood" {{ request('type') === 'Flood' ? 'selected' : '' }}>Flood</option>
                        <option value="Earthquake" {{ request('type') === 'Earthquake' ? 'selected' : '' }}>Earthquake</option>
                        <option value="Volcanic Eruption" {{ request('type') === 'Volcanic Eruption' ? 'selected' : '' }}>Volcanic Eruption</option>
                        <option value="Rebel Encounter" {{ request('type') === 'Rebel Encounter' ? 'selected' : '' }}>Rebel Encounter</option>
                    </select>
                </form>
            </div>

            <div class="bg-white shadow-md sm:rounded-lg mb-1">
                <div class="p-5 bg-[#295F98] print-hidden">
                    <div class="flex justify-between items-center mb-6">
                    @if(request('type'))
                        <h3 class="text-xl font-semibold text-white">{{ request('type') }} Cases</h3>
                    @else
                    <h3 class="text-xl font-semibold text-white">List of Disasters</h3>
                    @endif


                        <!-- Create button visible to both admins and non-admins -->
                        <a href="{{ route('disasters.create') }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
                            Create New Case
                        </a>
                    </div>

                    <form method="GET" action="{{ route('medical_cases.index') }}" class="mb-4">
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

                <div class="p-6 text-gray-900 print-header">
                    <!-- Tables for different disasters -->
                    @if(request('type'))
                    @include('partials.disaster-table', ['disasterType' => request('type')])
                    @else
                    <p id="warning" class="text-gray-500 text-center">Please select a disaster type to manage.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>