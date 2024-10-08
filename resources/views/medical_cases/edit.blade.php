<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Medical Case') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">{{ __("Edit Medical Case") }}</h3>

                    <!-- Error Handling -->
                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="text-red-500">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Edit Form -->
                    <form action="{{ route('medical_cases.update', $medicalCase->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700">{{ __('Date') }}</label>
                            <input type="date" name="date" id="date" value="{{ old('date', $medicalCase->date) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                        </div>

                        <!-- Rescue Team (Dropdown) -->
                        <div class="mb-4">
                            <label for="rescue_team" class="block text-sm font-medium text-gray-700">{{ __('Rescue Team') }}</label>
                            <select name="rescue_team" id="rescue_team" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value=""></option>
                                <option value="Alpha" {{ old('rescue_team', $medicalCase->rescue_team) == 'Alpha' ? 'selected' : '' }}>Alpha</option>
                                <option value="Bravo" {{ old('rescue_team', $medicalCase->rescue_team) == 'Bravo' ? 'selected' : '' }}>Bravo</option>
                                <option value="Charlie" {{ old('rescue_team', $medicalCase->rescue_team) == 'Charlie' ? 'selected' : '' }}>Charlie</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="barangay" class="block text-sm font-medium text-gray-700">{{ __('Barangay') }}</label>
                            <select name="barangay" id="barangay" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value=""></option>
                                <option value="Alimango" {{ old('barangay', $medicalCase->barangay) == 'Alimango' ? 'selected' : '' }}>Alimango</option>
                                <option value="Balintawak" {{ old('barangay', $medicalCase->barangay) == 'Balintawak' ? 'selected' : '' }}>Balintawak</option>
                                <option value="Binaguiohan" {{ old('barangay', $medicalCase->barangay) == 'Binaguiohan' ? 'selected' : '' }}>Binaguiohan</option>
                                <option value="Buenavista" {{ old('barangay', $medicalCase->barangay) == 'Buenavista' ? 'selected' : '' }}>Buenavista</option>
                                <option value="Cervantes" {{ old('barangay', $medicalCase->barangay) == 'Cervantes' ? 'selected' : '' }}>Cervantes</option>
                                <option value="Dian-ay" {{ old('barangay', $medicalCase->barangay) == 'Dian-ay' ? 'selected' : '' }}>Dian-ay</option>
                                <option value="Hacienda Fe" {{ old('barangay', $medicalCase->barangay) == 'Hacienda Fe' ? 'selected' : '' }}>Hacienda Fe</option>
                                <option value="Japitan" {{ old('barangay', $medicalCase->barangay) == 'Japitan' ? 'selected' : '' }}>Japitan</option>
                                <option value="Jonob-jonob" {{ old('barangay', $medicalCase->barangay) == 'Jonob-jonob' ? 'selected' : '' }}>Jonob-jonob</option>
                                <option value="Langub" {{ old('barangay', $medicalCase->barangay) == 'Langub' ? 'selected' : '' }}>Langub</option>
                                <option value="Libertad" {{ old('barangay', $medicalCase->barangay) == 'Libertad' ? 'selected' : '' }}>Libertad</option>
                                <option value="Mabini" {{ old('barangay', $medicalCase->barangay) == 'Mabini' ? 'selected' : '' }}>Mabini</option>
                                <option value="Magsaysay" {{ old('barangay', $medicalCase->barangay) == 'Magsaysay' ? 'selected' : '' }}>Magsaysay</option>
                                <option value="Malasibog" {{ old('barangay', $medicalCase->barangay) == 'Malasibog' ? 'selected' : '' }}>Malasibog</option>
                                <option value="Old Poblacion" {{ old('barangay', $medicalCase->barangay) == 'Old Poblacion' ? 'selected' : '' }}>Old Poblacion</option>
                                <option value="Paitan" {{ old('barangay', $medicalCase->barangay) == 'Paitan' ? 'selected' : '' }}>Paitan</option>
                                <option value="Pinagpugasan" {{ old('barangay', $medicalCase->barangay) == 'Pinagpugasan' ? 'selected' : '' }}>Pinagpugasan</option>
                                <option value="Rizal" {{ old('barangay', $medicalCase->barangay) == 'Rizal' ? 'selected' : '' }}>Rizal</option>
                                <option value="Tamlang" {{ old('barangay', $medicalCase->barangay) == 'Tamlang' ? 'selected' : '' }}>Tamlang</option>
                                <option value="Udtongan" {{ old('barangay', $medicalCase->barangay) == 'Udtongan' ? 'selected' : '' }}>Udtongan</option>
                                <option value="Washington" {{ old('barangay', $medicalCase->barangay) == 'Washington' ? 'selected' : '' }}>Washington</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="place_of_incident" class="block text-sm font-medium text-gray-700">{{ __('Place of Incident') }}</label>
                            <input type="text" name="place_of_incident" id="place_of_incident" value="{{ old('place_of_incident', $medicalCase->place_of_incident) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                        </div>

                        <div class="mb-4">
                            <label for="no_of_patients" class="block text-sm font-medium text-gray-700">{{ __('No. of Patients') }}</label>
                            <input type="number" name="no_of_patients" id="no_of_patients" value="{{ old('no_of_patients', $medicalCase->no_of_patients) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                        </div>

                        <div class="mb-4">
                            <label for="chief_complaints" class="block text-sm font-medium text-gray-700">{{ __('Chief Complaints') }}</label>
                            <input type="text" name="chief_complaints" id="chief_complaints" value="{{ old('chief_complaints', $medicalCase->chief_complaints) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                        </div>

                        <div class="mb-4">
                            <label for="facility_name" class="block text-sm font-medium text-gray-700">{{ __('Facility Name') }}</label>
                            <input type="text" name="facility_name" id="facility_name" value="{{ old('facility_name', $medicalCase->facility_name) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                        </div>

                        <!-- <div class="mb-4">
                            <label for="is_approved" class="block text-sm font-medium text-gray-700">{{ __('Status') }}</label>
                            <select name="is_approved" id="is_approved" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                                <option value="0" {{ old('is_approved', $medicalCase->is_approved) == 0 ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                <option value="1" {{ old('is_approved', $medicalCase->is_approved) == 1 ? 'selected' : '' }}>{{ __('Approved') }}</option>
                            </select>
                        </div> -->

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <a href="{{ route('medical_cases.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-700 mr-2">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
                                {{ __('Update Case') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
