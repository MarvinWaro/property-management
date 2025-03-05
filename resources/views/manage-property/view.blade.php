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
                    <div
                        class="col-span-12 md:col-span-4 flex flex-col items-center justify-center p-6 rounded text-white"
                        style="
                            background: linear-gradient(
                                rgba(37, 100, 237, 0.6),   /* a Tailwind-like 'bg-blue-600' in RGBA form */
                                rgba(0, 34, 133, 1)   /* a Tailwind-like 'bg-indigo-900' in RGBA form */
                            ),
                            url('{{ asset('img/ched-building.jpg') }}');
                            background-size: cover;
                            background-position: center;
                            background-repeat: no-repeat;
                        "
                        >
                        <img
                            class="w-32 h-32 rounded-full object-cover border-4 border-white"
                            src="{{ $property->endUser && $property->endUser->picture
                                    ? asset('storage/' . $property->endUser->picture)
                                    : asset('img/ched-logo.png') }}"
                            alt="End User Photo"
                        />
                        <div class="mt-4 text-center">
                            <h3 class="text-xl font-bold">
                                {{ $property->endUser->name ?? 'No Assigned User' }}
                            </h3>
                            <p>
                                {{ $property->endUser->department ?? 'No Department' }}
                            </p>
                        </div>
                    </div>

                </div>

                <!-- More prominent heading: larger, bolder, darker text. -->
                <h2 class="text-xl font-extrabold text-gray-900 dark:text-white mb-6">
                    Property Details
                </h2>

                <!-- Middle Section: Property Details -->
                <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded mb-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left sub-column of property details -->
                        <div>
                            <!-- Each row pairs a bold label with lighter data -->
                            <p class="mb-2">
                                <span class="font-semibold text-gray-900 dark:text-gray-200">
                                    Property Number:
                                </span>
                                <span class="ml-2 text-gray-600 dark:text-gray-300">
                                    {{ $property->property_number }}
                                </span>
                            </p>
                            <p class="mb-2">
                                <span class="font-semibold text-gray-900 dark:text-gray-200">
                                    Item Name:
                                </span>
                                <span class="ml-2 text-[#4169E1] font-bold">
                                    {{ $property->item_name }}
                                </span>
                            </p>
                            <p class="mb-2">
                                <span class="font-semibold text-gray-900 dark:text-gray-200">
                                    Serial Number:
                                </span>
                                <span class="ml-2 text-gray-600 dark:text-gray-300">
                                    {{ $property->serial_no ?? 'N/A' }}
                                </span>
                            </p>
                            <p class="mb-2">
                                <span class="font-semibold text-gray-900 dark:text-gray-200">
                                    Model Number:
                                </span>
                                <span class="ml-2 text-gray-600 dark:text-gray-300">
                                    {{ $property->model_no ?? 'N/A' }}
                                </span>
                            </p>
                            <p class="mb-2">
                                <span class="font-semibold text-gray-900 dark:text-gray-200">
                                    Acquisition Date:
                                </span>
                                <span class="ml-2 text-gray-600 dark:text-gray-300">
                                    {{ $property->acquisition_date ? $property->acquisition_date->format('F j, Y') : 'N/A' }}
                                </span>
                            </p>
                            <p class="mb-2">
                                <span class="font-semibold text-gray-900 dark:text-gray-200">
                                    Acquisition Cost:
                                </span>
                                <span class="ml-2 text-gray-600 dark:text-gray-300">
                                    {{ $property->acquisition_cost
                                        ? '$' . number_format($property->acquisition_cost, 2)
                                        : 'N/A' }}
                                </span>
                            </p>
                        </div>

                        <!-- Right sub-column of property details -->
                        <div>
                            <p class="mb-2">
                                <span class="font-semibold text-gray-900 dark:text-gray-200">
                                    Fund:
                                </span>
                                <span class="ml-2 text-gray-600 dark:text-gray-300">
                                    {{ $property->fund ?? 'N/A' }}
                                </span>
                            </p>
                            <p class="mb-2">
                                <span class="font-semibold text-gray-900 dark:text-gray-200">
                                    Location:
                                </span>
                                <span class="ml-2 text-gray-600 dark:text-gray-300">
                                    {{ $property->location->location_name ?? 'N/A' }}
                                </span>
                            </p>
                            <p class="mb-2">
                                <span class="font-semibold text-gray-900 dark:text-gray-200">
                                    Condition:
                                </span>
                                <span class="ml-2 text-gray-600 dark:text-gray-300">
                                    {{ $property->condition }}
                                </span>
                            </p>
                            <p class="mb-2">
                                <span class="font-semibold text-gray-900 dark:text-gray-200">
                                    Description:
                                </span>
                                <span class="ml-2 text-[#4169E1] font-bold">
                                    {{ $property->item_description ?? 'No description available.' }}
                                </span>
                            </p>
                            <p class="mb-2">
                                <span class="font-semibold text-gray-900 dark:text-gray-200">
                                    Remarks:
                                </span>
                                <span class="ml-2 text-gray-600 dark:text-gray-300">
                                    {{ $property->remarks ?? 'No remarks.' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <h2 class="text-xl font-extrabold text-gray-900 dark:text-white mb-6">
                    Other Properties Owned by {{ $property->endUser->name }}
                </h2>

                <!-- Bottom Section: Other Properties Owned -->
                @if($property->endUser && $property->endUser->properties->where('id', '!=', $property->id)->count() > 0)
                    <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded">


                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($property->endUser->properties as $prop)
                                @if($prop->id != $property->id)
                                    <!-- Card Container -->
                                    <div
                                        class="relative w-full h-48 md:h-64 rounded-[15px] shadow-lg overflow-hidden
                                            bg-white dark:bg-gray-800
                                            transform-gpu will-change-transform
                                            transition-transform duration-300 ease-out
                                            hover:scale-105 group"
                                    >
                                        <!-- The Image (absolute) -->
                                        @if($prop->images->isNotEmpty())
                                            <img
                                                src="{{ asset('storage/' . $prop->images->first()->file_path) }}"
                                                alt="Property Image"
                                                class="absolute inset-0 w-full h-full object-cover"
                                            />
                                        @else
                                            <img
                                                src="{{ asset('img/no-image.png') }}"
                                                alt="Default Property Image"
                                                class="absolute inset-0 w-full h-full object-cover"
                                            />
                                        @endif

                                        <!-- Dark Overlay (fades in on hover) -->
                                        <div
                                            class="absolute inset-0 bg-black
                                                transition-opacity duration-300 ease-out
                                                opacity-0 group-hover:opacity-60"
                                        ></div>

                                        <!-- Info & Link pinned to bottom, slides in on hover -->
                                        <div
                                            class="absolute bottom-0 left-0 right-0 text-white p-4
                                                transform translate-y-full
                                                transition-transform duration-300 ease-out
                                                group-hover:translate-y-0
                                                z-10"
                                        >
                                            <h3 class="text-xl font-semibold">
                                                {{ $prop->item_name }}
                                            </h3>
                                            <p class="text-sm">
                                                {{ $prop->property_number }}
                                            </p>
                                            <!-- Optional short snippet -->
                                            <p class="mt-2 text-sm">
                                                {{ \Illuminate\Support\Str::limit($prop->item_description ?? 'No description', 60) }}
                                            </p>

                                            <!-- "Read More" link, pointing to property.view route -->
                                            <a
                                                href="{{ route('property.view', $prop->id) }}"
                                                class="mt-4 inline-block px-3 py-1.5 text-sm rounded-md bg-blue-600 text-white
                                                    hover:bg-blue-700 transition-colors duration-300"
                                                >
                                                    Read More
                                            </a>

                                        </div>
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
