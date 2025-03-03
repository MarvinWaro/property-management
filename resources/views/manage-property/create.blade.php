<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Property') }}
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
                            Create New Property
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

                        <form action="{{ route('property.store') }}" method="POST" onsubmit="showLoader()" enctype="multipart/form-data">
                            @csrf

                            <!-- Use grid to create responsive two-column layout -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                {{-- Property Picture --}}
                                <div class="md:col-span-2 relative">
                                    <label for="dropzone-file" id="dropzone-container" class="relative flex items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600 overflow-hidden">
                                        <!-- Default instructions -->
                                        <div id="default-content" class="flex flex-col items-center justify-center">
                                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span></p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG (MAX. 7MB)</p>
                                        </div>

                                        <!-- Preview container -->
                                        <div id="preview-container" class="absolute inset-0 grid gap-2 w-full h-full"></div>

                                        <!-- File input -->
                                        <input id="dropzone-file" type="file" name="images[]" class="hidden" multiple accept="image/*" />
                                    </label>

                                    <!-- Remove Images button (hidden initially) -->
                                    <button type="button" id="clear-images" class="mt-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded hidden">
                                        Remove Images
                                    </button>
                                </div>

                                <!-- 1. Item Name -->
                                <div>
                                    <label for="item_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Item Name <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mb-4 relative">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <!-- SVG Icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-package">
                                                <path d="M7.5 4.27V8L4 10m7.5-5.73L15 8l3.5-2m-7 0L4 10l8 5 8-5-8-5Z" />
                                                <path d="M4 15l8 5 8-5M4 10v5m16-5v5" />
                                            </svg>
                                        </div>
                                        <input type="text" id="item_name" name="item_name"
                                            value="{{ old('item_name') }}"
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
                                    <label for="item_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Property Number <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mb-4 relative">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <!-- SVG Icon (optional) -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-hash">
                                                <line x1="4" y1="12" x2="20" y2="12"></line>
                                                <line x1="12" y1="4" x2="12" y2="20"></line>
                                            </svg>
                                        </div>
                                        <input type="text" id="property_number" name="property_number"
                                            value="{{ old('property_number') }}"
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
                                    <label for="serial_no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Serial No
                                    </label>
                                    <div class="mb-4 relative">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <!-- SVG Icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-barcode">
                                                <path d="M3 5v14m18-14v14M8 5v14m8-14v14M5 5v14m14-14v14M11 5v14m2-14v14" />
                                            </svg>
                                        </div>
                                        <input type="text" id="serial_no" name="serial_no"
                                            value="{{ old('serial_no') }}"
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
                                    <label for="model_no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Model No
                                    </label>
                                    <div class="mb-4 relative">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <!-- SVG Icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-monitor">
                                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
                                                <path d="M12 17v4M8 21h8" />
                                            </svg>
                                        </div>
                                        <input type="text" id="model_no" name="model_no"
                                            value="{{ old('model_no') }}"
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
                                    <label for="acquisition_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Acquisition Date
                                    </label>
                                    <div class="mb-4 relative">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <!-- SVG Icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-calendar">
                                                <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                                                <line x1="16" x2="16" y1="2" y2="6" />
                                                <line x1="8" x2="8" y1="2" y2="6" />
                                                <line x1="3" x2="21" y1="10" y2="10" />
                                            </svg>
                                        </div>
                                        <input type="date" id="acquisition_date" name="acquisition_date"
                                            value="{{ old('acquisition_date') }}"
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
                                    <label for="acquisition_cost" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Acquisition Cost
                                    </label>
                                    <div class="mb-4 relative">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <!-- Icon, optional -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-credit-card">
                                                <rect x="2" y="5" width="20" height="14" rx="2" ry="2" />
                                                <line x1="2" x2="22" y1="10" y2="10" />
                                            </svg>
                                        </div>
                                        <!-- Notice type="text" so we can format with commas -->
                                        <input
                                            type="text"
                                            id="acquisition_cost"
                                            name="acquisition_cost"
                                            value="{{ old('acquisition_cost') }}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                @error('acquisition_cost') border-red-500 @enderror"
                                            placeholder="0.00"
                                        >
                                    </div>
                                    @error('acquisition_cost')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- 6. Unit of Measure -->
                                <div>
                                    <label for="unit_of_measure" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Unit of Measure
                                    </label>
                                    <div class="mb-4 relative">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <!-- SVG Icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-scale">
                                                <path d="M7 20h10M6 6l6-3 6 3M12 3v17" />
                                                <path d="M6 6c0 3.5-2 6-2 6s2 2.5 2 6m12-12c0 3.5 2 6 2 6s-2 2.5-2 6" />
                                            </svg>
                                        </div>
                                        <input type="text" id="unit_of_measure" name="unit_of_measure"
                                            value="{{ old('unit_of_measure') }}"
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
                                    <label for="quantity_per_physical_count" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Quantity (Physical Count)
                                    </label>
                                    <div class="mb-4 relative">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <!-- SVG Icon -->
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
                                            value="{{ old('quantity_per_physical_count', 1) }}" min="1"
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
                                    <label for="fund" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Fund
                                    </label>
                                    <div class="mb-4 relative">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <!-- SVG Icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-banknote">
                                                <rect width="18" height="12" x="3" y="6" rx="2" ry="2" />
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
                                            <option value="" disabled {{ !old('fund') ? 'selected' : '' }}>
                                                -- Select Fund --
                                            </option>
                                            <option value="Fund 101" {{ old('fund') === 'Fund 101' ? 'selected' : '' }}>
                                                Fund 101
                                            </option>
                                            <option value="Fund 151" {{ old('fund') === 'Fund 151' ? 'selected' : '' }}>
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
                                    <label for="item_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Location / Whereabouts <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mb-4 relative">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <!-- SVG Icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-map-pin">
                                                <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0Z" />
                                                <circle cx="12" cy="10" r="3" />
                                            </svg>
                                        </div>
                                        <select id="location_id" name="location_id"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                @error('location_id') border-red-500 @enderror">
                                            <option value="" disabled {{ !old('location_id') ? 'selected' : '' }}>
                                                -- Select Location --
                                            </option>
                                            @foreach ($locations as $loc)
                                                <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>
                                                    {{ $loc->location_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('location_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- 10. End User in Create Form -->
                                <div>
                                    <label for="end_user_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        End-user <span class="text-red-500">*</span>
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
                                        <select id="end_user_id" name="end_user_id"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                @error('end_user_id') border-red-500 @enderror">
                                            <option value="" disabled {{ !old('end_user_id') ? 'selected' : '' }}>
                                                -- Select User --
                                            </option>
                                            @foreach ($endUsers as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('end_user_id') == $user->id ? 'selected' : '' }}
                                                    @if($user->excluded) disabled @endif>
                                                    {{ $user->name }} ({{ $user->department }})
                                                    @if($user->excluded)
                                                        (Excluded)
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('end_user_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- 11. Condition -->
                                <div>
                                    <label for="item_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Condition <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mb-4 relative">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <!-- SVG Icon -->
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
                                            <option value="" disabled {{ !old('condition') ? 'selected' : '' }}>
                                                -- Select Condition --
                                            </option>
                                            <option value="Serviceable" {{ old('condition') === 'Serviceable' ? 'selected' : '' }}>
                                                Serviceable
                                            </option>
                                            <option value="Unserviceable" {{ old('condition') === 'Unserviceable' ? 'selected' : '' }}>
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
                                    <label for="item_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Item Description <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mb-4 relative">
                                        <div class="absolute top-2 start-0 ms-2 mt-1.5 pointer-events-none text-gray-400">
                                            <!-- SVG Icon -->
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
                                            placeholder="Short description of the item...">{{ old('item_description') }}</textarea>
                                    </div>
                                    @error('item_description')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- 13. Remarks (Spans 2 columns) -->
                                <div class="md:col-span-2">
                                    <label for="remarks" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Remarks (optional)
                                    </label>
                                    <div class="mb-4 relative">
                                        <div class="absolute top-2 start-0 ms-2 mt-1.5 pointer-events-none text-gray-400">
                                            <!-- SVG Icon -->
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
                                            placeholder="Any additional notes...">{{ old('remarks') }}</textarea>
                                    </div>
                                    @error('remarks')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>
                            <!-- End of Grid -->

                            <!-- Buttons -->
                            <div class="flex justify-end space-x-2 mt-4">
                                <a href="{{ route('property.index') }}"
                                    class="text-sm px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                                    Back
                                </a>
                                <button type="submit"
                                    class="text-sm px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                    Submit
                                </button>
                            </div>
                        </form>

                    </div><!-- End .max-w-4xl -->
                </div><!-- End .section-container -->
            </div>
        </div>
    </div>

    {{-- Image Script --}}
    <script>
        const fileInput = document.getElementById('dropzone-file');
        const previewContainer = document.getElementById('preview-container');
        const defaultContent = document.getElementById('default-content');
        const clearButton = document.getElementById('clear-images');

        fileInput.addEventListener('change', function(event) {
            // Clear previous previews
            previewContainer.innerHTML = '';

            const files = event.target.files;
            const numFiles = files.length;

            // If no files are selected, display default instructions and hide clear button
            if (numFiles === 0) {
                defaultContent.style.display = 'flex';
                clearButton.classList.add('hidden');
                return;
            } else {
                defaultContent.style.display = 'none';
                clearButton.classList.remove('hidden');
            }

            // Limit file count to 3
            if (numFiles > 3) {
                alert('You can upload a maximum of 3 images.');
                fileInput.value = ''; // Reset the input
                previewContainer.innerHTML = '';
                defaultContent.style.display = 'flex';
                clearButton.classList.add('hidden');
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
            }
            // Update preview container classes
            previewContainer.className = `absolute inset-0 grid ${gridColsClass} gap-2 w-full h-full`;

            // Process each file
            Array.from(files).forEach(file => {
                // Check file size (7MB = 7 * 1024 * 1024 bytes)
                if (file.size > 7 * 1024 * 1024) {
                    alert(`File "${file.name}" exceeds the 7MB size limit.`);
                    fileInput.value = '';
                    previewContainer.innerHTML = '';
                    defaultContent.style.display = 'flex';
                    clearButton.classList.add('hidden');
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

        clearButton.addEventListener('click', function() {
            // Clear the preview container and reset file input
            previewContainer.innerHTML = '';
            fileInput.value = '';
            // Show default instructions
            defaultContent.style.display = 'flex';
            // Hide the clear button
            clearButton.classList.add('hidden');
        });
    </script>

    {{-- Comma on Acquisition Cost --}}
    <script>
        const acqCostInput = document.getElementById('acquisition_cost');

        acqCostInput.addEventListener('input', function () {
            // 1) Remove all non-digit characters
            let digits = this.value.replace(/\D/g, '');
            if (digits === '') {
                digits = '0';
            }

            // 2) Interpret as cents: parse integer, then divide by 100
            let intValue = parseInt(digits, 10);
            let amount   = intValue / 100;

            // 3) Format with commas + always 2 decimals
            this.value = amount.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        });
    </script>


</x-app-layout>
