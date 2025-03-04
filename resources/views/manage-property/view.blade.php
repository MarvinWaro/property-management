<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('View Property') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Container -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">

                <!-- Top Section: Carousel/Images & Assigned User Info -->
                <!-- Using a 12-column grid; left side spans 8 columns, right side 4 columns (on md and above). -->
                <div class="grid grid-cols-12 gap-6 mb-6">
                    <!-- Left Column (8 of 12): Carousel or Single Image -->
                    <div class="col-span-12 md:col-span-8 bg-gray-100 dark:bg-gray-700 p-4 rounded">
                        @if($property->images->count() > 1)
                            <!-- Carousel for multiple images -->
                            <div id="propertyCarousel" class="relative" data-carousel="slide">
                                <!-- Carousel wrapper; set custom height (e.g., h-80) -->
                                <div class="overflow-hidden relative h-[30rem] rounded-md">
                                    @foreach($property->images as $key => $image)
                                        <div
                                            class="duration-700 ease-in-out {{ $key == 0 ? '' : 'hidden' }}"
                                            data-carousel-item
                                        >
                                            <img src="{{ asset('storage/' . $image->file_path) }}"
                                                 alt="Property Image {{ $key + 1 }}"
                                                 class="block w-full h-full object-cover rounded-md">
                                        </div>
                                    @endforeach
                                </div>
                                <!-- Slider controls -->
                                <button type="button"
                                        class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer"
                                        data-carousel-prev
                                >
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30">
                                        &larr;
                                    </span>
                                </button>
                                <button type="button"
                                        class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer"
                                        data-carousel-next
                                >
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30">
                                        &rarr;
                                    </span>
                                </button>
                            </div>
                        @elseif($property->images->count() == 1)
                            <!-- Single image display -->
                            <div class="overflow-hidden relative h-[30rem] rounded-md">
                                <img src="{{ asset('storage/' . $property->images->first()->file_path) }}"
                                     alt="Property Image"
                                     class="block w-full h-full object-cover rounded-md">
                            </div>
                        @else
                            <!-- Default image (CHEd logo) -->
                            <div class="overflow-hidden relative h-[30rem] rounded-md">
                                <img src="{{ asset('img/no-image.png') }}"
                                     alt="Default CHEd Logo"
                                     class="block w-full h-full object-cover rounded-md">
                            </div>
                        @endif
                    </div>

                    <!-- Right Column (4 of 12): Assigned User Info -->
                    <div class="col-span-12 md:col-span-4 flex flex-col items-center justify-center bg-gray-100 dark:bg-gray-700 p-6 rounded">
                        <img class="w-32 h-32 rounded-full object-cover"
                             src="{{ $property->endUser && $property->endUser->picture
                                    ? asset('storage/' . $property->endUser->picture)
                                    : asset('images/user-placeholder.png') }}"
                             alt="End User Photo" />
                        <div class="mt-4 text-center">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                                {{ $property->endUser->name ?? 'No Assigned User' }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ $property->endUser->department ?? 'No Department' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Middle Section: Property Details -->
                <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded mb-6">
                    <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">
                        Property Details
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left sub-column of property details -->
                        <div>
                            <p class="text-gray-700 dark:text-gray-300">
                                <strong>Property Number:</strong> {{ $property->property_number }}
                            </p>
                            <p class="text-gray-700 dark:text-gray-300">
                                <strong>Item Name:</strong> {{ $property->item_name }}
                            </p>
                            <p class="text-gray-700 dark:text-gray-300">
                                <strong>Serial Number:</strong> {{ $property->serial_no ?? 'N/A' }}
                            </p>
                            <p class="text-gray-700 dark:text-gray-300">
                                <strong>Model Number:</strong> {{ $property->model_no ?? 'N/A' }}
                            </p>
                            <p class="text-gray-700 dark:text-gray-300">
                                <strong>Acquisition Date:</strong>
                                {{ $property->acquisition_date ? $property->acquisition_date->format('F j, Y') : 'N/A' }}
                            </p>
                            <p class="text-gray-700 dark:text-gray-300">
                                <strong>Acquisition Cost:</strong>
                                {{ $property->acquisition_cost ? '$' . number_format($property->acquisition_cost, 2) : 'N/A' }}
                            </p>
                        </div>

                        <!-- Right sub-column of property details -->
                        <div>
                            <p class="text-gray-700 dark:text-gray-300">
                                <strong>Fund:</strong> {{ $property->fund ?? 'N/A' }}
                            </p>
                            <p class="text-gray-700 dark:text-gray-300">
                                <strong>Location:</strong> {{ $property->location->location_name ?? 'N/A' }}
                            </p>
                            <p class="text-gray-700 dark:text-gray-300">
                                <strong>Condition:</strong> {{ $property->condition }}
                            </p>
                            <p class="text-gray-700 dark:text-gray-300">
                                <strong>Description:</strong>
                                {{ $property->item_description ?? 'No description available.' }}
                            </p>
                            <p class="text-gray-700 dark:text-gray-300">
                                <strong>Remarks:</strong> {{ $property->remarks ?? 'No remarks.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Bottom Section: Other Properties Owned -->
                @if($property->endUser && $property->endUser->properties->where('id', '!=', $property->id)->count() > 0)
                    <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded">
                        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">
                            Other Properties Owned by {{ $property->endUser->name }}
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-4">
                            @foreach($property->endUser->properties as $prop)
                                @if($prop->id != $property->id)
                                    <div class="bg-white dark:bg-gray-800 rounded shadow p-4">
                                        <h3 class="font-semibold text-gray-800 dark:text-gray-100">
                                            {{ $prop->item_name }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            {{ $prop->property_number }}
                                        </p>
                                        @if($prop->images->isNotEmpty())
                                            <img src="{{ asset('storage/' . $prop->images->first()->file_path) }}"
                                                 alt="Property Image"
                                                 class="w-full h-32 object-cover object-center mt-2 rounded">
                                        @else
                                            <img src="{{ asset('img/no-image.png') }}"
                                                 alt="Default CHEd Logo"
                                                 class="w-full h-32 object-cover object-center mt-2 rounded">
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Footer / Copyright -->
            <div class="mt-6 border-t pt-4 text-center text-sm text-gray-500">
                Copyright © CHED Property Management System
            </div>
        </div>
    </div>
</x-app-layout>
