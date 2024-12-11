<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NO</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rescue Team</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Place of Incident</th>

                <!-- Conditional Columns Based on Disaster Type -->
                @if (request('type') === 'Flood')
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Water Level</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Water Trend</th>
                @elseif (request('type') === 'Earthquake')
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intensity Level</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aftershocks</th>
                @elseif (request('type') === 'Volcanic Eruption')
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Eruption Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Eruption Intensity</th>
                @elseif (request('type') === 'Rebel Encounter')
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Involved Parties</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Triggering Event</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nature of Encounter</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                @endif

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Affected Infrastructure</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Casualties</th>
                @if(auth()->user()->role === 0)
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @php
            // Sort disaster case: pending first, then approved
            $sortedCases = $disasters->sortByDesc(function ($case) {
            return !$case->is_approved; // Pending cases (false) come before approved (true)
            });
            @endphp

            @foreach ($sortedCases as $case)
            <tr class="{{ $case->is_approved ? 'even:bg-gray-50 odd:bg-white hover:bg-gray-200' : 'bg-yellow-100' }} {{ $case->is_approved ? '' : 'print-hidden' }}">
                <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $case->date }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $case->rescue_team }}</td>
                <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $case->place_of_incident }}, Brgy. {{ $case->barangay }}</td>

                <!-- Conditional Columns -->
                @if (request('type') === 'Flood')
                <td class="px-6 py-4 whitespace-nowrap">{{ $case->current_water_level }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $case->water_level_trend }}</td>
                @elseif (request('type') === 'Earthquake')
                <td class="px-6 py-4 whitespace-nowrap">{{ $case->intensity_level }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $case->aftershocks }}</td>
                @elseif (request('type') === 'Volcanic Eruption')
                <td class="px-6 py-4 whitespace-nowrap">{{ $case->eruption_type }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $case->eruption_intensity }}</td>
                @elseif (request('type') === 'Rebel Encounter')
                <td class="px-6 py-4 whitespace-nowrap">{{ $case->involved_parties }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $case->triggering_event }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $case->nature_of_encounter }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $case->duration }}</td>
                @endif

                <td class="px-6 py-4 whitespace-nowrap">{{ $case->affected_infrastructure }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $case->casualties }}</td>
                @if(auth()->user()->role === 0)
                <td class="px-6 py-4 whitespace-nowrap">{{ $case->is_approved ? 'Approved' : 'Pending' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($case->is_approved)
                    <a href="{{ route('disasters.edit', $case->id) }}" class="px-4 py-[10.5px] bg-yellow-500 text-white rounded-lg hover:bg-yellow-700 mr-2">
                        {{ __('Edit') }}
                    </a>
                    @endif

                    @if (!$case->is_approved)
                    <form action="{{ route('disasters.approve', $case->id) }}" method="POST" class="inline-block">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-700" onclick="return confirm('Are you sure you want to approve this disaster case?')">
                            {{ __('Approve') }}
                        </button>
                    </form>
                    @endif

                    <form action="{{ route('disasters.destroy', $case->id) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-700" onclick="return confirm('Are you sure you want to delete this disaster case?')">
                            {{ __('Delete') }}
                        </button>
                    </form>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>

   <!-- Bottom Content: Total count, Print Button, and Pagination -->
@if (request('type') && request('start_date') && request('end_date') && $disasters->count() > 0)
    <p class="text-lg font-medium mt-4 border-t-2 border-t-gray-300 pt-2">
        {{ __("Total Disasters for this date range: ") }} {{ $disasters->count() }}
    </p>

    <!-- Print Button -->
    <div class="grid place-items-end mt-2">
        <button onclick="window.print()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-700 {{ auth()->user()->role === 0 ? '' : 'hidden' }}">
            {{ __('Print') }}
        </button>
    </div>

@elseif (request('start_date') && request('end_date') && $disasters->isEmpty())
    <p>{{ __("No disasters found for this date range.") }}</p>
@endif

<!-- Pagination Links -->
<div class="mt-4">
    {{ $disasters->links() }}
</div>

@if (!request('start_date') && !request('end_date') && !request('type'))
    <p class="border-t-2 mt-4 border-t-gray-300 pt-2">{{ __("Please select a date range and type to filter the disasters you want to print.") }}</p>
@endif
</div>