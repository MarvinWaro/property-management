<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('View Property') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Carousel Section (Top) -->
                <div class="mb-6">
                    @if($property->images->count() > 1)
                        <!-- Carousel for multiple images -->
                        <div id="propertyCarousel" class="relative" data-carousel="slide">
                            <!-- Carousel wrapper -->
                            <div class="overflow-hidden relative h-64 rounded-lg">
                                @foreach($property->images as $key => $image)
                                    <div class="duration-700 ease-in-out {{ $key == 0 ? '' : 'hidden' }}" data-carousel-item>
                                        <img src="{{ asset('storage/' . $image->file_path) }}"
                                             alt="Property Image {{ $key + 1 }}"
                                             class="block w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                            <!-- Slider controls -->
                            <button type="button" class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer" data-carousel-prev>
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30">
                                    &larr;
                                </span>
                            </button>
                            <button type="button" class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer" data-carousel-next>
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30">
                                    &rarr;
                                </span>
                            </button>
                        </div>
                    @elseif($property->images->count() == 1)
                        <!-- Single image display (no carousel controls) -->
                        <div class="overflow-hidden relative h-64 rounded-lg">
                            <img src="{{ asset('storage/' . $property->images->first()->file_path) }}"
                                 alt="Property Image"
                                 class="block w-full h-full object-cover">
                        </div>
                    @else
                        <!-- Default image when no images are available (CHEd logo) -->
                        <div class="overflow-hidden relative h-64 rounded-lg">
                            <img src="{{ asset('img/ched-logo.png') }}"
                                 alt="Default CHEd Logo"
                                 class="block w-full h-full object-cover">
                        </div>
                    @endif
                </div>

                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">
                    Property Details
                </h2>

                <!-- Property Details Section (Middle) -->
                <div class="mb-6 bg-white dark:bg-gray-700 p-4 rounded-md">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column: End User Info -->

                        <div class="text-gray-600 dark:text-gray-300">
                            <p><strong>Property Number:</strong> {{ $property->property_number }}</p>
                            <p><strong>Item Name:</strong> {{ $property->item_name }}</p>
                            <p><strong>Serial Number:</strong> {{ $property->serial_no ?? 'N/A' }}</p>
                            <p><strong>Model Number:</strong> {{ $property->model_no ?? 'N/A' }}</p>
                            <p><strong>Acquisition Date:</strong> {{ $property->acquisition_date ? $property->acquisition_date->format('F j, Y') : 'N/A' }}</p>
                            <p><strong>Acquisition Cost:</strong> {{ $property->acquisition_cost ? '$' . number_format($property->acquisition_cost, 2) : 'N/A' }}</p>
                            <p><strong>Fund:</strong> {{ $property->fund ?? 'N/A' }}</p>
                            <p><strong>Location:</strong> {{ $property->location->location_name ?? 'N/A' }}</p>
                            <p><strong>Condition:</strong> {{ $property->condition }}</p>
                            <div class="mt-4">
                                <p><strong>Description:</strong> {{ $property->item_description ?? 'No description available.' }}</p>
                                <p><strong>Remarks:</strong> {{ $property->remarks ?? 'No remarks.' }}</p>
                            </div>
                        </div>
                        <!-- Right Column: Property Details & Description -->
                        <div class="flex flex-col items-center justify-center">
                            <img class="w-40 h-40 rounded-full object-cover"
                                 src="{{ $property->endUser && $property->endUser->picture ? asset('storage/' . $property->endUser->picture) : asset('images/user-placeholder.png') }}"
                                 alt="End User Photo" />
                            <div class="mt-4 text-center">
                                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                                    {{ $property->endUser->name ?? 'No Assigned User' }}
                                </h3>
                                <p class="text-lg text-gray-600 dark:text-gray-300">
                                    {{ $property->endUser->department ?? 'No Department' }}
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Other Properties Owned Section (Bottom) -->
                @if($property->endUser && $property->endUser->properties->where('id', '!=', $property->id)->count() > 0)
                    <div class="mt-6">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                            Other Properties Owned by {{ $property->endUser->name }}
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            @foreach($property->endUser->properties as $prop)
                                @if($prop->id != $property->id)
                                    <div class="bg-white dark:bg-gray-700 p-4 rounded-md shadow">
                                        <h3 class="font-bold text-lg">{{ $prop->item_name }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $prop->property_number }}</p>
                                        @if($prop->images->isNotEmpty())
                                            <img src="{{ asset('storage/' . $prop->images->first()->file_path) }}"
                                                 alt="Property Image"
                                                 class="w-full h-32 object-cover object-center mt-2 rounded">
                                        @else
                                            <img src="{{ asset('img/ched-logo.png') }}"
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
