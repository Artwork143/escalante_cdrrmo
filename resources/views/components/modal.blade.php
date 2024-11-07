<div x-show="isOpen" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50" x-cloak>
    <div @click.away="isOpen = false" class="bg-white w-11/12 md:max-w-md mx-auto rounded-lg shadow-lg py-4 px-6 text-gray-800">
        <h3 class="text-xl font-semibold mb-2">Total Cases for <span x-text="barangayName"></span> of Brgy.</h3>
        <p class="mb-2">Vehicular Accidents: <span x-text="accidents"></span></p>
        <p class="mb-2">Medical Cases: <span x-text="medicalCases"></span></p>
        <p class="font-semibold">Punong Barangay:</p>
        <p class="mb-2" x-text="punongBarangay"></p>
        <p class="font-semibold">Contact Number:</p>
        <p x-text="contactNumber"></p>
        <button @click="isOpen = false" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-lg">Close</button>
    </div>
</div>
