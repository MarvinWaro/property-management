<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Property') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="section-container p-5">
                    <!-- Increase max width for two columns -->
                    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6">
                        <!-- Heading -->
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
                            Edit Property
                        </h2>

                        <!-- Display Validation Errors -->
                        @if ($errors->any())
                            <div class="mb-4 p-4 text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('property.update', $property->id) }}" method="POST" onsubmit="return confirmUpdate()" enctype="multipart/form-data">

                            @csrf
                            @method('PUT')

                            <!-- Use grid to create responsive two-column layout -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <!-- Dropzone for Property Pictures (Spans 2 columns) -->
                                <div class="md:col-span-2 relative">
                                    <label for="dropzone-file" id="dropzone-container"
                                        class="relative flex items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600 overflow-hidden">
                                        <!-- Default instructions (hidden if images exist) -->
                                        <div id="default-content"
                                            class="flex flex-col items-center justify-center {{ $property->images->isNotEmpty() ? 'hidden' : 'flex' }}">
                                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                                                    class="font-semibold">Click to upload</span> or drag and drop</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF
                                                (MAX. 800x400px)</p>
                                        </div>

                                        <!-- Preview container: existing images are shown if available -->
                                        <div id="preview-container"
                                            class="absolute inset-0 grid gap-2 w-full h-full
                                            @if ($property->images->isNotEmpty()) grid-cols-{{ $property->images->count() }} @endif">
                                            @if ($property->images->isNotEmpty())
                                                @foreach ($property->images as $image)
                                                    <img src="{{ asset('storage/' . $image->file_path) }}"
                                                        class="w-full h-full object-cover object-center">
                                                @endforeach
                                            @endif
                                        </div>

                                        <!-- File input -->
                                        <input id="dropzone-file" type="file" name="images[]" class="hidden" multiple
                                            accept="image/*">
                                    </label>

                                    <!-- Remove Images button: only display if images exist -->
                                    @if ($property->images->isNotEmpty())
                                        <button type="button" id="clear-images"
                                            class="mt-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                            Remove Images
                                        </button>
                                        <!-- Hidden input to flag removal for backend processing -->
                                        <input type="hidden" name="remove_existing_images" id="remove_existing_images"
                                            value="0">
                                    @endif
                                </div>

                                <!-- 1. Item Name -->
                                <div>
                                    <label for="item_name"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Item Name <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mb-4 relative">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-package">
                                                <path d="M7.5 4.27V8L4 10m7.5-5.73L15 8l3.5-2m-7 0L4 10l8 5 8-5-8-5Z" />
                                                <path d="M4 15l8 5 8-5M4 10v5m16-5v5" />
                                            </svg>
                                        </div>
                                        <input type="text" id="item_name" name="item_name"
                                            value="{{ old('item_name', $property->item_name) }}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                   focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                   dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                   @error('item_name') border-red-500 @enderror"
                                            placeholder="Enter item name...">
                                    </div>
                                    @error('item_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- New: Property Number -->
                                <div>
                                    <label for="item_name"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Product Number <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mb-4 relative">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <!-- Optional SVG Icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-hash">
                                                <line x1="4" y1="12" x2="20" y2="12">
                                                </line>
                                                <line x1="12" y1="4" x2="12" y2="20">
                                                </line>
                                            </svg>
                                        </div>
                                        <input type="text" id="property_number" name="property_number"
                                            value="{{ old('property_number', $property->property_number) }}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                   focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                   dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                   @error('property_number') border-red-500 @enderror"
                                            placeholder="Enter property number...">
                                    </div>
                                    @error('property_number')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- 2. Serial No -->
                                <div>
                                    <label for="serial_no"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Serial No
                                    </label>
                                    <div class="mb-4 relative">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-barcode">
                                                <path
                                                    d="M3 5v14m18-14v14M8 5v14m8-14v14M5 5v14m14-14v14M11 5v14m2-14v14" />
                                            </svg>
                                        </div>
                                        <input type="text" id="serial_no" name="serial_no"
                                            value="{{ old('serial_no', $property->serial_no) }}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                   focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                   dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                   @error('serial_no') border-red-500 @enderror"
                                            placeholder="Enter serial number...">
                                    </div>
                                    @error('serial_no')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- 3. Model No -->
                                <div>
                                    <label for="model_no"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Model No
                                    </label>
                                    <div class="mb-4 relative">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-monitor">
                                                <rect x="2" y="3" width="20" height="14" rx="2"
                                                    ry="2" />
                                                <path d="M12 17v4M8 21h8" />
                                            </svg>
                                        </div>
                                        <input type="text" id="model_no" name="model_no"
                                            value="{{ old('model_no', $property->model_no) }}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                   focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                   dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                   @error('model_no') border-red-500 @enderror"
                                            placeholder="Enter model number...">
                                    </div>
                                    @error('model_no')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- 4. Acquisition Date -->
                                <div>
                                    <label for="acquisition_date"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Acquisition Date
                                    </label>
                                    <div class="mb-4 relative">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-calendar">
                                                <rect width="18" height="18" x="3" y="4" rx="2"
                                                    ry="2" />
                                                <line x1="16" x2="16" y1="2" y2="6" />
                                                <line x1="8" x2="8" y1="2" y2="6" />
                                                <line x1="3" x2="21" y1="10" y2="10" />
                                            </svg>
                                        </div>
                                        <input type="date" id="acquisition_date" name="acquisition_date"
                                            value="{{ old('acquisition_date', $property->acquisition_date) }}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                   focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                   dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                   @error('acquisition_date') border-red-500 @enderror">
                                    </div>
                                    @error('acquisition_date')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- 5. Acquisition Cost -->
                                <div>
                                    <label for="acquisition_cost"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Acquisition Cost
                                    </label>
                                    <div class="mb-4 relative">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-credit-card">
                                                <rect x="2" y="5" width="20" height="14" rx="2"
                                                    ry="2" />
                                                <line x1="2" x2="22" y1="10" y2="10" />
                                            </svg>
                                        </div>
                                        <!-- Use type="text" so we can comma-format in JavaScript -->
                                        <input type="text" id="acquisition_cost" name="acquisition_cost"
                                            {{-- number_format() ensures the user sees commas for the saved DB value, e.g. 49,000.00 --}}
                                            value="{{ old('acquisition_cost', number_format($property->acquisition_cost, 2)) }}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                @error('acquisition_cost') border-red-500 @enderror"
                                            placeholder="0.00">
                                    </div>
                                    @error('acquisition_cost')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- 6. Unit of Measure -->
                                <div>
                                    <label for="unit_of_measure"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Unit of Measure
                                    </label>
                                    <div class="mb-4 relative">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-scale">
                                                <path d="M7 20h10M6 6l6-3 6 3M12 3v17" />
                                                <path d="M6 6c0 3.5-2 6-2 6s2 2.5 2 6m12-12c0 3.5 2 6 2 6s-2 2.5-2 6" />
                                            </svg>
                                        </div>
                                        <input type="text" id="unit_of_measure" name="unit_of_measure"
                                            value="{{ old('unit_of_measure', $property->unit_of_measure) }}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                   focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                   dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                   @error('unit_of_measure') border-red-500 @enderror"
                                            placeholder="e.g., 'piece' or 'box'">
                                    </div>
                                    @error('unit_of_measure')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- 7. Quantity (Physical Count) -->
                                <div>
                                    <label for="quantity_per_physical_count"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Quantity (Physical Count)
                                    </label>
                                    <div class="mb-4 relative">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-hash">
                                                <line x1="4" y1="9" x2="20" y2="9" />
                                                <line x1="4" y1="15" x2="20" y2="15" />
                                                <line x1="10" y1="3" x2="8" y2="21" />
                                                <line x1="16" y1="3" x2="14" y2="21" />
                                            </svg>
                                        </div>
                                        <input type="number" id="quantity_per_physical_count"
                                            name="quantity_per_physical_count"
                                            value="{{ old('quantity_per_physical_count', $property->quantity_per_physical_count) }}"
                                            min="1"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                   focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                   dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                   @error('quantity_per_physical_count') border-red-500 @enderror"
                                            placeholder="1">
                                    </div>
                                    @error('quantity_per_physical_count')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- 8. Fund -->
                                <div>
                                    <label for="fund"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Fund
                                    </label>
                                    <div class="mb-4 relative">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-banknote">
                                                <rect width="18" height="12" x="3" y="6" rx="2"
                                                    ry="2" />
                                                <circle cx="8" cy="12" r="2" />
                                                <path d="M12 12h4" />
                                            </svg>
                                        </div>
                                        <select id="fund" name="fund"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                   focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                   dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                   @error('fund') border-red-500 @enderror">
                                            <option value="" disabled>
                                                -- Select Fund --
                                            </option>
                                            <option value="Fund 101"
                                                {{ old('fund', $property->fund) === 'Fund 101' ? 'selected' : '' }}>
                                                Fund 101
                                            </option>
                                            <option value="Fund 151"
                                                {{ old('fund', $property->fund) === 'Fund 151' ? 'selected' : '' }}>
                                                Fund 151
                                            </option>
                                        </select>
                                    </div>
                                    @error('fund')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- 9. Location -->
                                <div>
                                    <label for="location_id"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Location / Whereabouts <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mb-4 relative">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-map-pin">
                                                <path
                                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0Z" />
                                                <circle cx="12" cy="10" r="3" />
                                            </svg>
                                        </div>

                                        <!-- Hidden actual select element for form submission -->
                                        <select id="location_id" name="location_id" class="hidden">
                                            <option value="" disabled>
                                                -- Select Location --
                                            </option>
                                            @foreach ($locations as $loc)
                                                <option value="{{ $loc->id }}"
                                                    {{ old('location_id', $property->location_id) == $loc->id ? 'selected' : '' }}>
                                                    {{ $loc->location_name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <!-- Custom dropdown button -->
                                        <button type="button" id="location-dropdown-button"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                @error('location_id') border-red-500 @enderror flex items-center justify-between">
                                            <span id="selected-location-text">
                                                @if(old('location_id', $property->location_id))
                                                    {{ $locations->where('id', old('location_id', $property->location_id))->first()->location_name ?? '-- Select Location --' }}
                                                @else
                                                    -- Select Location --
                                                @endif
                                            </span>
                                            <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>

                                        <!-- Dropdown menu -->
                                        <div id="location-dropdown-menu"
                                            class="hidden absolute z-10 w-full bg-white rounded-lg shadow-lg dark:bg-gray-700 mt-1">
                                            <!-- Search input -->
                                            <div class="p-2">
                                                <div class="relative">
                                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                        </svg>
                                                    </div>
                                                    <input type="text" id="location-search"
                                                        class="block w-full p-2 pl-10 text-sm border border-gray-300 rounded-lg bg-gray-50
                                                            focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500
                                                            dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                        placeholder="Search location...">
                                                </div>
                                            </div>

                                            <!-- Dropdown items container with max height and scrollbar -->
                                            <ul id="location-options" class="py-1 text-sm text-gray-700 dark:text-gray-200 max-h-60 overflow-y-auto">
                                                @foreach ($locations as $loc)
                                                    <li>
                                                        <a href="#" data-value="{{ $loc->id }}"
                                                        class="location-option block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white
                                                                {{ old('location_id', $property->location_id) == $loc->id ? 'bg-gray-100 dark:bg-gray-600' : '' }}">
                                                            {{ $loc->location_name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>

                                            <!-- No results message -->
                                            <p id="location-no-results" class="hidden p-4 text-sm text-gray-500 dark:text-gray-400">
                                                No locations found.
                                            </p>
                                        </div>
                                    </div>
                                    @error('location_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- 10. For End-Users --}}
                                @php
                                    // Filter out admin users and separate by status (active/inactive)
                                    $activeUsers = $users->filter(function ($user) {
                                        return $user->status && $user->role !== 'admin';
                                    });
                                    $inactiveUsers = $users->filter(function ($user) {
                                        return !$user->status && $user->role !== 'admin';
                                    });
                                    // Merge both collections for easy retrieval of selected user details
                                    $allUsers = $activeUsers->merge($inactiveUsers);

                                    // Use 'user_id' from the property (fallback to old input on validation error)
                                    $selectedUser = old('user_id', $property->user_id);
                                @endphp

                                <div>
                                    <label for="user_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Assigned User <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mb-4 relative">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <!-- SVG Icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-user">
                                                <path d="M20 21c0-2.667-4-4-8-4s-8 1.333-8 4" />
                                                <circle cx="12" cy="7" r="4" />
                                            </svg>
                                        </div>

                                        <!-- Hidden select element for form submission -->
                                        <select id="user_id" name="user_id" class="hidden" onchange="removeHiddenEndUser()">
                                            <option value="" disabled {{ !$selectedUser ? 'selected' : '' }}>
                                                -- Select User --
                                            </option>

                                            {{-- Display active users first --}}
                                            @foreach ($activeUsers as $user)
                                                <option value="{{ $user->id }}" {{ $selectedUser == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ optional($user->department)->name }})
                                                </option>
                                            @endforeach

                                            {{-- Display inactive users last (disabled) --}}
                                            @foreach ($inactiveUsers as $user)
                                                <option value="{{ $user->id }}" {{ $selectedUser == $user->id ? 'selected' : '' }} disabled>
                                                    {{ $user->name }} ({{ optional($user->department)->name }}) (Inactive)
                                                </option>
                                            @endforeach
                                        </select>

                                        <!-- Custom dropdown button -->
                                        <button type="button" id="user-dropdown-button"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                @error('user_id') border-red-500 @enderror flex items-center justify-between">
                                            <span id="selected-user-text">
                                                @if($selectedUser && $allUsers->where('id', $selectedUser)->first())
                                                    {{ $allUsers->where('id', $selectedUser)->first()->name }}
                                                    <span class="text-blue-500 italic">({{ optional($allUsers->where('id', $selectedUser)->first()->department)->name }})</span>
                                                    @if(!$allUsers->where('id', $selectedUser)->first()->status)
                                                        (Inactive)
                                                    @endif
                                                @else
                                                    -- Select User --
                                                @endif
                                            </span>
                                            <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>

                                        <!-- Dropdown menu -->
                                        <div id="user-dropdown-menu"
                                            class="hidden absolute z-10 w-full bg-white rounded-lg shadow-lg dark:bg-gray-700 mt-1">
                                            <!-- Search input -->
                                            <div class="p-2">
                                                <div class="relative">
                                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                        </svg>
                                                    </div>
                                                    <input type="text" id="user-search"
                                                        class="block w-full p-2 pl-10 text-sm border border-gray-300 rounded-lg bg-gray-50
                                                            focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500
                                                            dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                        placeholder="Search user...">
                                                </div>
                                            </div>

                                            <!-- Dropdown items container with max height and scrollbar -->
                                            <ul id="user-options" class="py-1 text-sm text-gray-700 dark:text-gray-200 max-h-60 overflow-y-auto">
                                                <!-- Active Users Header -->
                                                @if($activeUsers->count() > 0)
                                                    <li class="px-3 py-2 uppercase text-xs font-semibold bg-gray-100 dark:bg-gray-600">
                                                        Active Users
                                                    </li>
                                                    @foreach ($activeUsers as $user)
                                                        <li>
                                                            <a href="#" data-value="{{ $user->id }}" data-is-inactive="0"
                                                            class="user-option block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white
                                                                    {{ $selectedUser == $user->id ? 'bg-gray-100 dark:bg-gray-600' : '' }}">
                                                                {{ $user->name }} <span class="text-blue-500 italic">({{ optional($user->department)->name }})</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                @endif

                                                <!-- Inactive Users Header -->
                                                @if($inactiveUsers->count() > 0)
                                                    <li class="px-3 py-2 uppercase text-xs font-semibold bg-gray-100 dark:bg-gray-600">
                                                        Inactive Users
                                                    </li>
                                                    @foreach ($inactiveUsers as $user)
                                                        <li>
                                                            <a href="#" data-value="{{ $user->id }}" data-is-inactive="1"
                                                            class="user-option block px-4 py-2 text-gray-400 cursor-not-allowed
                                                                    {{ $selectedUser == $user->id ? 'bg-gray-100 dark:bg-gray-600' : '' }}">
                                                                {{ $user->name }} <span class="text-blue-500 italic">({{ optional($user->department)->name }})</span> (Inactive)
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>

                                            <!-- No results message -->
                                            <p id="user-no-results" class="hidden p-4 text-sm text-gray-500 dark:text-gray-400">
                                                No users found.
                                            </p>
                                        </div>
                                    </div>

                                    @error('user_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror

                                    {{-- If the selected user is inactive, add a hidden input to submit its value --}}
                                    @if ($selectedUser && !$allUsers->where('id', $selectedUser)->first()->status)
                                        <input type="hidden" id="hidden_user_id" name="user_id" value="{{ $selectedUser }}">
                                    @endif
                                </div>


                                <!-- 11. Condition -->
                                <div>
                                    <label for="item_name"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Condition <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mb-4 relative">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-activity">
                                                <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                                            </svg>
                                        </div>
                                        <select id="condition" name="condition"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                   focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                   dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                   @error('condition') border-red-500 @enderror">
                                            <option value="" disabled>
                                                -- Select Condition --
                                            </option>
                                            <option value="Serviceable"
                                                {{ old('condition', $property->condition) === 'Serviceable' ? 'selected' : '' }}>
                                                Serviceable
                                            </option>
                                            <option value="Unserviceable"
                                                {{ old('condition', $property->condition) === 'Unserviceable' ? 'selected' : '' }}>
                                                Unserviceable
                                            </option>
                                        </select>
                                    </div>
                                    @error('condition')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- 12. Item Description (Spans 2 columns) -->
                                <div class="md:col-span-2">
                                    <label for="item_name"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Item description <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mb-4 relative">
                                        <div
                                            class="absolute top-2 start-0 ms-2 mt-1.5 pointer-events-none text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-file-text">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                                <path d="M14 2v6h6" />
                                                <path d="M16 13H8" />
                                                <path d="M16 17H8" />
                                                <path d="M10 9H8" />
                                            </svg>
                                        </div>
                                        <textarea id="item_description" name="item_description" rows="3"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                   focus:ring-blue-500 focus:border-blue-500 block w-full pt-2.5 ps-8 pe-2.5
                                                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                   dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                   @error('item_description') border-red-500 @enderror"
                                            placeholder="Short description of the item...">{{ old('item_description', $property->item_description) }}</textarea>
                                    </div>
                                    @error('item_description')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- 13. Remarks (Spans 2 columns) -->
                                <div class="md:col-span-2">
                                    <label for="remarks"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Remarks (optional)
                                    </label>
                                    <div class="mb-4 relative">
                                        <div
                                            class="absolute top-2 start-0 ms-2 mt-1.5 pointer-events-none text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-file-text">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                                <path d="M14 2v6h6" />
                                                <path d="M16 13H8" />
                                                <path d="M16 17H8" />
                                                <path d="M10 9H8" />
                                            </svg>
                                        </div>
                                        <textarea id="remarks" name="remarks" rows="3"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                   focus:ring-blue-500 focus:border-blue-500 block w-full pt-2.5 ps-8 pe-2.5
                                                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                   dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                   @error('remarks') border-red-500 @enderror"
                                            placeholder="Any additional notes...">{{ old('remarks', $property->remarks) }}</textarea>
                                    </div>
                                    @error('remarks')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div> <!-- End of grid -->

                            <!-- Buttons -->
                            <div class="flex justify-end space-x-2 mt-4">
                                <a href="{{ route('property.index') }}"
                                    class="text-sm px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                                    Back
                                </a>
                                <button type="submit"
                                    class="text-sm px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                    Update
                                </button>
                            </div>
                        </form>

                    </div><!-- End .max-w-4xl -->
                </div><!-- End .section-container -->
            </div>
        </div>
    </div>

    {{-- Image Dropzone File --}}
    <script>
        const fileInput = document.getElementById('dropzone-file');
        const previewContainer = document.getElementById('preview-container');
        const defaultContent = document.getElementById('default-content');
        const clearButton = document.getElementById('clear-images');
        const removedImagesInput = document.getElementById('remove_existing_images');

        fileInput.addEventListener('change', function(event) {
            // Clear previous previews
            previewContainer.innerHTML = '';

            const files = event.target.files;
            const numFiles = files.length;

            // If no files are selected, display default instructions and hide clear button
            if (numFiles === 0) {
                defaultContent.style.display = 'flex';
                if (clearButton) {
                    clearButton.style.display = 'none';
                }
                return;
            } else {
                defaultContent.style.display = 'none';
                if (clearButton) {
                    clearButton.style.display = 'block';
                }
            }

            // Limit file count to 4
            if (numFiles > 4) {
                alert('You can upload a maximum of 4 images.');
                fileInput.value = ''; // Reset the input
                previewContainer.innerHTML = '';
                defaultContent.style.display = 'flex';
                if (clearButton) {
                    clearButton.style.display = 'none';
                }
                return;
            }

            // Set grid columns based on the number of files selected
            let gridColsClass = '';
            if (numFiles === 1) {
                gridColsClass = 'grid-cols-1';
            } else if (numFiles === 2) {
                gridColsClass = 'grid-cols-2';
            } else if (numFiles === 3) {
                gridColsClass = 'grid-cols-3';
            } else if (numFiles === 4) {
                gridColsClass = 'grid-cols-4';
            }
            // Update preview container classes
            previewContainer.className = `absolute inset-0 grid ${gridColsClass} gap-2 w-full h-full`;

            // Process each file
            Array.from(files).forEach(file => {
                // Check file size (25MB = 25 * 1024 * 1024 bytes)
                if (file.size > 25 * 1024 * 1024) {
                    alert(`File "${file.name}" exceeds the 25MB size limit.`);
                    fileInput.value = '';
                    previewContainer.innerHTML = '';
                    defaultContent.style.display = 'flex';
                    if (clearButton) {
                        clearButton.style.display = 'none';
                    }
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-full object-cover';
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });

        if (clearButton) {
            clearButton.addEventListener('click', function() {
                // Clear the preview container
                previewContainer.innerHTML = '';
                // Reset the file input
                fileInput.value = '';
                // Show default instructions and hide clear button
                defaultContent.style.display = 'flex';
                clearButton.style.display = 'none';
                // Mark the hidden input for removal so the backend can process it
                if (removedImagesInput) {
                    removedImagesInput.value = '1';
                }
            });
        }
    </script>

    {{-- For the comma of cost --}}
    <script>
        // Attach the event listener to reformat the cost as the user types
        const acqCostInput = document.getElementById('acquisition_cost');

        acqCostInput.addEventListener('input', function() {
            // Remove any non-digit character
            let digits = this.value.replace(/\D/g, '');
            if (digits === '') {
                digits = '0';
            }

            // Convert digits to integer, treat as cents, then divide by 100
            let intValue = parseInt(digits, 10);
            let amount = intValue / 100;

            // Format with commas plus two decimal places
            this.value = amount.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        });
    </script>

    <!-- SCRIPT FOR EDIT.BLADE.PHP -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Location Dropdown
            const locationButton = document.getElementById('location-dropdown-button');
            const locationMenu = document.getElementById('location-dropdown-menu');
            const locationOptions = document.querySelectorAll('.location-option');
            const locationSearch = document.getElementById('location-search');
            const locationSelect = document.getElementById('location_id');
            const selectedLocationText = document.getElementById('selected-location-text');
            const locationNoResults = document.getElementById('location-no-results');

            // User Dropdown
            const userButton = document.getElementById('user-dropdown-button');
            const userMenu = document.getElementById('user-dropdown-menu');
            const userOptions = document.querySelectorAll('.user-option');
            const userSearch = document.getElementById('user-search');
            const userSelect = document.getElementById('user_id');
            const selectedUserText = document.getElementById('selected-user-text');
            const userNoResults = document.getElementById('user-no-results');

            // =============== LOCATION DROPDOWN FUNCTIONS ===============

            // Toggle location dropdown
            if (locationButton) {
                locationButton.addEventListener('click', function() {
                    locationMenu.classList.toggle('hidden');
                    if (!locationMenu.classList.contains('hidden')) {
                        locationSearch.focus();
                        // Close user dropdown if open
                        if (userMenu) userMenu.classList.add('hidden');
                    }
                });
            }

            // Close location dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (locationMenu && !locationMenu.classList.contains('hidden') &&
                    locationButton && !locationButton.contains(event.target) &&
                    !locationMenu.contains(event.target)) {
                    locationMenu.classList.add('hidden');
                }
            });

            // Handle location option selection
            if (locationOptions) {
                locationOptions.forEach(option => {
                    option.addEventListener('click', function(e) {
                        e.preventDefault();
                        const value = this.getAttribute('data-value');
                        const text = this.textContent.trim();

                        // Update hidden select
                        locationSelect.value = value;

                        // Update button text
                        selectedLocationText.textContent = text;

                        // Close dropdown
                        locationMenu.classList.add('hidden');

                        // Trigger change event on select
                        const event = new Event('change', { bubbles: true });
                        locationSelect.dispatchEvent(event);
                    });
                });
            }

            // Filter location options on search
            if (locationSearch) {
                locationSearch.addEventListener('input', function() {
                    const searchValue = this.value.toLowerCase().trim();
                    let hasVisibleOptions = false;

                    locationOptions.forEach(option => {
                        const text = option.textContent.toLowerCase();
                        const parent = option.parentElement;

                        if (text.includes(searchValue)) {
                            parent.classList.remove('hidden');
                            hasVisibleOptions = true;
                        } else {
                            parent.classList.add('hidden');
                        }
                    });

                    // Show/hide no results message
                    if (locationNoResults) {
                        if (hasVisibleOptions) {
                            locationNoResults.classList.add('hidden');
                        } else {
                            locationNoResults.classList.remove('hidden');
                        }
                    }
                });
            }

            // =============== USER DROPDOWN FUNCTIONS ===============

            // Toggle user dropdown
            if (userButton) {
                userButton.addEventListener('click', function() {
                    userMenu.classList.toggle('hidden');
                    if (!userMenu.classList.contains('hidden')) {
                        userSearch.focus();
                        userSearch.value = ''; // Clear search on open

                        // Reset visibility of all user options
                        const userItems = document.querySelectorAll('#user-options li:not(.uppercase)');
                        userItems.forEach(item => {
                            item.classList.remove('hidden');
                        });

                        // Show all category headers
                        const sectionHeaders = document.querySelectorAll('#user-options li.uppercase');
                        sectionHeaders.forEach(header => header.classList.remove('hidden'));

                        // Hide no results message
                        if (userNoResults) userNoResults.classList.add('hidden');

                        // Close location dropdown if open
                        if (locationMenu) locationMenu.classList.add('hidden');
                    }
                });
            }

            // Close user dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (userMenu && !userMenu.classList.contains('hidden') &&
                    userButton && !userButton.contains(event.target) &&
                    !userMenu.contains(event.target)) {
                    userMenu.classList.add('hidden');
                }
            });

            // Handle user option selection
            if (userOptions) {
                userOptions.forEach(option => {
                    option.addEventListener('click', function(e) {
                        e.preventDefault();
                        const isInactive = this.getAttribute('data-is-inactive') === '1';
                        const isExcluded = this.getAttribute('data-is-excluded') === '1';

                        // Skip action for inactive or excluded users
                        if (isInactive || isExcluded) {
                            return;
                        }

                        const value = this.getAttribute('data-value');
                        const text = this.textContent.trim();

                        // Update hidden select
                        userSelect.value = value;

                        // Update button text
                        selectedUserText.textContent = text;

                        // Remove hidden input for active/non-excluded users
                        removeHiddenEndUser();

                        // Close dropdown
                        userMenu.classList.add('hidden');

                        // Trigger change event on select
                        const event = new Event('change', { bubbles: true });
                        userSelect.dispatchEvent(event);
                    });
                });
            }

            // Filter user options on search
            if (userSearch) {
                userSearch.addEventListener('input', function() {
                    const searchValue = this.value.toLowerCase().trim();
                    let hasVisibleOptions = false;
                    let visibleActiveUsers = 0;
                    let visibleInactiveOrExcludedUsers = 0;

                    // Get all user list items (skip the section headers)
                    const userItems = document.querySelectorAll('#user-options li:not(.uppercase)');

                    // Check each option
                    userItems.forEach(item => {
                        const option = item.querySelector('a');
                        if (!option) return;

                        const text = option.textContent.toLowerCase();
                        const isInactive = option.getAttribute('data-is-inactive') === '1';
                        const isExcluded = option.getAttribute('data-is-excluded') === '1';

                        if (text.includes(searchValue)) {
                            item.classList.remove('hidden');
                            hasVisibleOptions = true;

                            // Count visible users in each section
                            if (isInactive || isExcluded) {
                                visibleInactiveOrExcludedUsers++;
                            } else {
                                visibleActiveUsers++;
                            }
                        } else {
                            item.classList.add('hidden');
                        }
                    });

                    // Hide/show section headers based on search results
                    const sectionHeaders = document.querySelectorAll('#user-options li.uppercase');
                    if (sectionHeaders.length >= 2) {
                        // Active users header
                        if (visibleActiveUsers === 0) {
                            sectionHeaders[0].classList.add('hidden');
                        } else {
                            sectionHeaders[0].classList.remove('hidden');
                        }

                        // Inactive/Excluded users header
                        if (visibleInactiveOrExcludedUsers === 0) {
                            sectionHeaders[1].classList.add('hidden');
                        } else {
                            sectionHeaders[1].classList.remove('hidden');
                        }
                    }

                    // Show/hide no results message
                    if (userNoResults) {
                        if (hasVisibleOptions) {
                            userNoResults.classList.add('hidden');
                        } else {
                            userNoResults.classList.remove('hidden');
                        }
                    }
                });
            }
        });

        // Function to remove hidden user input
        function removeHiddenEndUser() {
            const hiddenInput = document.getElementById('hidden_user_id');
            if (hiddenInput) {
                hiddenInput.remove();
            }
        }
    </script>

    <script>
        // Function to show the loader/spinner.
        function showLoader() {
            const loader = document.getElementById('loader');
            if (loader) {
                loader.classList.remove('hidden');
            } else {
                console.error("Loader element with ID 'loader' not found.");
            }
        }

        // Function to hide the loader/spinner.
        function hideLoader() {
            const loader = document.getElementById('loader');
            if (loader) {
                loader.classList.add('hidden');
            }
        }

        // Confirmation function used in the form submission.
        function confirmUpdate() {
            const message = "Are you sure you want to update this property? This might cause the QR code to regenerate.";
            if (confirm(message)) {
                // Only show the spinner if confirmed.
                showLoader();
                return true; // Allow submission.
            } else {
                // If cancelled, ensure that the loader is hidden.
                hideLoader();
                return false; // Block submission.
            }
        }

        // Option 1: Using the form's onsubmit attribute.
        // Ensure your form tag includes: onsubmit="return confirmUpdate()"
        // Example:
        // <form action="{{ route('property.update', $property->id) }}" method="POST" onsubmit="return confirmUpdate()" enctype="multipart/form-data">

        // Option 2: If you prefer to attach the event on DOMContentLoaded:
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("form");
            if (form) {
                form.addEventListener('submit', function(event){
                    // Call the confirmation function and if it returns false, prevent submission.
                    if (!confirmUpdate()) {
                        event.preventDefault();
                        // Optionally, you could use your own logic here to reset form state if needed.
                    }
                });
            }
        });
    </script>



</x-app-layout>
