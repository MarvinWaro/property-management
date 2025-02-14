<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Property') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-4 p-4 text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('property.store') }}" method="POST">
                    @csrf

                    <!-- Item Name -->
                    <div class="mb-4">
                        <label for="item_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Item Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="item_name"
                            name="item_name"
                            class="mt-1 block w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white"
                            value="{{ old('item_name') }}"
                            required
                        />
                    </div>

                    <!-- Item Description -->
                    <div class="mb-4">
                        <label for="item_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Item Description
                        </label>
                        <textarea
                            id="item_description"
                            name="item_description"
                            rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white"
                        >{{ old('item_description') }}</textarea>
                    </div>

                    <!-- Serial No -->
                    <div class="mb-4">
                        <label for="serial_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Serial No
                        </label>
                        <input
                            type="text"
                            id="serial_no"
                            name="serial_no"
                            class="mt-1 block w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white"
                            value="{{ old('serial_no') }}"
                        />
                    </div>

                    <!-- Model No -->
                    <div class="mb-4">
                        <label for="model_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Model No
                        </label>
                        <input
                            type="text"
                            id="model_no"
                            name="model_no"
                            class="mt-1 block w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white"
                            value="{{ old('model_no') }}"
                        />
                    </div>

                    <!-- Acquisition Date -->
                    <div class="mb-4">
                        <label for="acquisition_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Acquisition Date
                        </label>
                        <input
                            type="date"
                            id="acquisition_date"
                            name="acquisition_date"
                            class="mt-1 block w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white"
                            value="{{ old('acquisition_date') }}"
                        />
                    </div>

                    <!-- Acquisition Cost -->
                    <div class="mb-4">
                        <label for="acquisition_cost" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Acquisition Cost (PHP)
                        </label>
                        <input
                            type="number"
                            step="0.01"
                            id="acquisition_cost"
                            name="acquisition_cost"
                            class="mt-1 block w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white"
                            value="{{ old('acquisition_cost') }}"
                        />
                    </div>

                    <!-- Unit of Measure -->
                    <div class="mb-4">
                        <label for="unit_of_measure" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Unit of Measure
                        </label>
                        <input
                            type="text"
                            id="unit_of_measure"
                            name="unit_of_measure"
                            class="mt-1 block w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white"
                            value="{{ old('unit_of_measure') }}"
                        />
                    </div>

                    <!-- Quantity -->
                    <div class="mb-4">
                        <label for="quantity_per_physical_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Quantity (Physical Count) <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            id="quantity_per_physical_count"
                            name="quantity_per_physical_count"
                            class="mt-1 block w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white"
                            value="{{ old('quantity_per_physical_count', 1) }}"
                            min="1"
                            required
                        />
                    </div>

                    <!-- Fund -->
                    <div class="mb-4">
                        <label for="fund" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Fund <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="fund"
                            name="fund"
                            class="mt-1 block w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white"
                            required
                        >
                            <option value="" disabled {{ !old('fund') ? 'selected' : '' }}>-- Choose a Fund --</option>
                            <option value="General Fund" {{ old('fund') === 'General Fund' ? 'selected' : '' }}>General Fund</option>
                            <option value="Special Fund" {{ old('fund') === 'Special Fund' ? 'selected' : '' }}>Special Fund</option>
                            <!-- Add more funds if needed -->
                        </select>
                    </div>

                    <!-- Location -->
                    <div class="mb-4">
                        <label for="location_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Location <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="location_id"
                            name="location_id"
                            class="mt-1 block w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white"
                            required
                        >
                            <option value="" disabled {{ !old('location_id') ? 'selected' : '' }}>-- Select Location --</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>
                                    {{ $loc->location_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- End User -->
                    <div class="mb-4">
                        <label for="end_user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            End User <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="end_user_id"
                            name="end_user_id"
                            class="mt-1 block w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white"
                            required
                        >
                            <option value="" disabled {{ !old('end_user_id') ? 'selected' : '' }}>-- Select User --</option>
                            @foreach($endUsers as $user)
                                <option value="{{ $user->id }}" {{ old('end_user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->department }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Condition -->
                    <div class="mb-4">
                        <label for="condition" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Condition <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="condition"
                            name="condition"
                            class="mt-1 block w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white"
                            required
                        >
                            <option value="" disabled {{ !old('condition') ? 'selected' : '' }}>-- Select Condition --</option>
                            <option value="Serviceable" {{ old('condition') === 'Serviceable' ? 'selected' : '' }}>Serviceable</option>
                            <option value="Unserviceable" {{ old('condition') === 'Unserviceable' ? 'selected' : '' }}>Unserviceable</option>
                        </select>
                    </div>

                    <!-- Remarks -->
                    <div class="mb-6">
                        <label for="remarks" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Remarks (optional)
                        </label>
                        <textarea
                            id="remarks"
                            name="remarks"
                            rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white"
                        >{{ old('remarks') }}</textarea>
                    </div>

                    <!-- Submit & Cancel -->
                    <div class="flex items-center space-x-2">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        >
                            Save Property
                        </button>
                        <a
                            href="{{ route('property.index') }}"
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 focus:ring-2 focus:ring-gray-200 focus:outline-none"
                        >
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
