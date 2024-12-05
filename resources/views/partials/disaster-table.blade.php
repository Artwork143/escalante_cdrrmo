<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NO</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rescue Team</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Place of Incident</th>
                <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">City</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barangay</th> -->
                <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th> -->

                <!-- Conditional Columns Based on Disaster Type -->
                @if ($disasterType === 'Flood')
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Water Level</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Water Trend</th>
                @elseif ($disasterType === 'Earthquake')
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intensity Level</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aftershocks</th>
                @elseif ($disasterType === 'Volcanic Eruption')
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Eruption Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Eruption Intensity</th>
                @elseif ($disasterType === 'Rebel Encounter')
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

            @foreach ($sortedCases->where('type', $disasterType) as $case)
            <tr class="{{ $case->is_approved ? 'even:bg-gray-50 odd:bg-white hover:bg-gray-200' : 'bg-yellow-100' }} {{ $case->is_approved ? '' : 'print-hidden' }}">
                <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $case->date }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $case->rescue_team }}</td>
                <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $case->place_of_incident }}, Brgy. {{ $case->barangay }}</td>
                <!-- <td class="px-6 py-4 whitespace-nowrap">{{ $case->city }}</td> -->
                <!-- <td class="px-6 py-4 whitespace-nowrap">{{ $case->barangay }}</td> -->
                <!-- <td class="px-6 py-4 whitespace-nowrap">{{ $case->type }}</td> -->

                <!-- Conditional Columns -->
                @if ($disasterType === 'Flood')
                <td class="px-6 py-4 whitespace-nowrap">{{ $case->current_water_level }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $case->water_level_trend }}</td>
                @elseif ($disasterType === 'Earthquake')
                <td class="px-6 py-4 whitespace-nowrap">{{ $case->intensity_level }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $case->aftershocks }}</td>
                @elseif ($disasterType === 'Volcanic Eruption')
                <td class="px-6 py-4 whitespace-nowrap">{{ $case->eruption_type }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $case->eruption_intensity }}</td>
                @elseif ($disasterType === 'Rebel Encounter')
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
</div>