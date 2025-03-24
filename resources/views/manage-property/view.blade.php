<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('View Property') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Container -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">

                <!-- Top Section: Property Info Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $property->item_name }}</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Property #: {{ $property->property_number }}</p>
                    </div>

                    <!-- Small QR Code Section -->
                    <div class="mt-4 md:mt-0 flex items-center">
                        <div class="w-20 h-20">
                            <img src="{{ $qrCodeImage }}" alt="QR Code" class="w-full h-full">
                        </div>
                        <div class="ml-3">
                            <!-- Change the onclick handler from window.open to openModal() -->
                            <button onclick="openModal()" class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                Enlarge QR
                            </button>
                        </div>
                    </div>

                    <!-- Modal for Enlarged QR Code -->
                    <div id="qrModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50" onclick="closeModal()">
                        <div class="bg-white p-4 rounded-lg relative" onclick="event.stopPropagation()">
                            <!-- Close Button -->
                            <button onclick="closeModal()" class="absolute top-0 right-0 p-2 text-xl font-bold">&times;</button>
                            <!-- Enlarged QR Code Image -->
                            <img src="{{ $qrCodeImage }}" alt="QR Code" class="w-96 h-96">
                        </div>
                    </div>
                </div>

                <!-- Carousel and User Info Side by Side -->
                <div class="grid grid-cols-12 gap-6 mb-6">
                    <!-- Left Column (8 of 12): Image Carousel -->
                    <div class="col-span-12 lg:col-span-8">
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden h-full">
                            @if($property->images->count() > 1)
                                <!-- Carousel for multiple images -->
                                <div id="propertyCarousel" class="relative h-full" data-carousel="slide">
                                    <div class="overflow-hidden relative h-[28rem] lg:h-full rounded-lg">
                                        @foreach($property->images as $key => $image)
                                            <div class="duration-700 ease-in-out absolute inset-0 transition-opacity {{ $key == 0 ? 'opacity-100' : 'opacity-0' }}" data-carousel-item="{{ $key == 0 ? 'active' : '' }}">
                                                <img src="{{ asset('storage/' . $image->file_path) }}" alt="Property Image {{ $key + 1 }}" class="block w-full h-full object-cover">
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Carousel Controls -->
                                    <button type="button" class="absolute top-1/2 -translate-y-1/2 left-4 z-30 flex items-center justify-center w-10 h-10 rounded-full bg-white/70 dark:bg-gray-800/70 shadow hover:bg-white/90 dark:hover:bg-gray-800/90" data-carousel-prev>
                                        <span class="inline-flex items-center justify-center w-8 h-8 text-gray-800 dark:text-white">&larr;</span>
                                    </button>
                                    <button type="button" class="absolute top-1/2 -translate-y-1/2 right-4 z-30 flex items-center justify-center w-10 h-10 rounded-full bg-white/70 dark:bg-gray-800/70 shadow hover:bg-white/90 dark:hover:bg-gray-800/90" data-carousel-next>
                                        <span class="inline-flex items-center justify-center w-8 h-8 text-gray-800 dark:text-white">&rarr;</span>
                                    </button>

                                    <!-- Indicators -->
                                    <div class="absolute z-30 flex space-x-2 -translate-x-1/2 bottom-4 left-1/2">
                                        @foreach($property->images as $key => $image)
                                            <button type="button" class="w-2 h-2 rounded-full {{ $key == 0 ? 'bg-white' : 'bg-white/50' }}" aria-current="{{ $key == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $key + 1 }}" data-carousel-slide-to="{{ $key }}"></button>
                                        @endforeach
                                    </div>
                                </div>
                            @elseif($property->images->count() == 1)
                                <!-- Single image display -->
                                <div class="overflow-hidden relative h-[28rem] lg:h-full rounded-lg">
                                    <img src="{{ asset('storage/' . $property->images->first()->file_path) }}" alt="Property Image" class="block w-full h-full object-cover">
                                </div>
                            @else
                                <!-- Default image -->
                                <div class="overflow-hidden relative h-[28rem] lg:h-full rounded-lg flex items-center justify-center bg-gray-200 dark:bg-gray-600">
                                    <img src="{{ asset('img/default.png') }}" alt="Default Image" class="max-h-full max-w-full object-contain p-4">
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right Column (4 of 12): Assigned User Info -->
                    <div class="col-span-12 lg:col-span-4">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-900 dark:from-blue-700 dark:to-blue-900 rounded-lg shadow h-full flex flex-col">
                            <div class="p-6 flex flex-col items-center justify-center flex-grow">
                                <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-lg">
                                    <img class="w-full h-full object-cover"
                                        src="{{ $property->endUser && $property->endUser->picture ? asset('storage/' . $property->endUser->picture) : asset('img/ched-logo.png') }}"
                                        alt="End User Photo" />
                                </div>
                                <div class="mt-4 text-center text-white">
                                    <h3 class="text-xl font-bold">{{ $property->endUser->name ?? 'No Assigned User' }}</h3>
                                    <p class="text-blue-100">
                                        @if($property->endUser->designation && $property->endUser->department)
                                            {{ $property->endUser->designation }} | {{ $property->endUser->department }}
                                        @elseif($property->endUser->designation)
                                            {{ $property->endUser->designation }}
                                        @else
                                            {{ $property->endUser->department ?? 'No Department' }}
                                        @endif
                                    </p>
                                </div>


                                @if($property->endUser)
                                <div class="mt-6 w-full">
                                    <div class="flex items-center justify-between text-sm text-white mb-2">
                                        <span>Assigned Properties</span>
                                        <span class="bg-white text-blue-800 rounded-full px-2 py-0.5">
                                            {{ $property->endUser->properties->count() }}
                                        </span>
                                    </div>
                                    <div class="bg-blue-600/30 rounded p-3 text-sm text-white">
                                        <p class="mb-1">Last Assignment:</p>
                                        <p class="font-semibold truncate">
                                            {{ $property->endUser->properties->sortByDesc('created_at')->first()->item_name ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Property Details Section - Redesigned -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Property Details
                    </h2>

                    <!-- Modern Card Layout for Details -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Basic Info Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 border-t-4 border-blue-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Basic Information</h3>

                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Item Name</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $property->item_name }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Property Number</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $property->property_number }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Serial Number</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $property->serial_no ?? 'N/A' }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Model Number</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $property->model_no ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Financial Info Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 border-t-4 border-green-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Financial Details</h3>

                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Acquisition Date</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $property->acquisition_date ? $property->acquisition_date->format('F j, Y') : 'N/A' }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Acquisition Cost</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $property->acquisition_cost ? '$' . number_format($property->acquisition_cost, 2) : 'N/A' }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Fund</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $property->fund ?? 'N/A' }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Condition</p>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $property->condition == 'Good' ? 'bg-green-100 text-green-800' : ($property->condition == 'Fair' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $property->condition }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Location & Description Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 border-t-4 border-purple-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Location & Details</h3>

                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Location</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $property->location->location_name ?? 'N/A' }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Description</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $property->item_description ?? 'No description available.' }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Remarks</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $property->remarks ?? 'No remarks.' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Other Properties Section -->
                @if($property->endUser && $property->endUser->properties->where('id', '!=', $property->id)->count() > 0)
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Other Properties Owned by {{ $property->endUser->name }}
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($property->endUser->properties->where('id', '!=', $property->id) as $prop)
                            <a href="{{ route('property.view', $prop->id) }}" class="block group">
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden transform transition-all duration-300 group-hover:shadow-lg group-hover:-translate-y-1">
                                    <div class="h-48 bg-gray-200 dark:bg-gray-700 relative">
                                        @if($prop->images->isNotEmpty())
                                            <img src="{{ asset('storage/' . $prop->images->first()->file_path) }}" alt="Property Image" class="w-full h-full object-cover" />
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-300 dark:bg-gray-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="absolute top-2 right-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-600 text-white">
                                                {{ $prop->property_number }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white truncate">{{ $prop->item_name }}</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                                            {{ \Illuminate\Support\Str::limit($prop->item_description ?? 'No description', 60) }}
                                        </p>
                                        <div class="flex items-center justify-between mt-3">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $prop->acquisition_date ? $prop->acquisition_date->format('M Y') : 'Unknown date' }}
                                            </span>
                                            <span class="text-blue-600 dark:text-blue-400 text-sm font-medium group-hover:underline">View Details</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            <!-- Footer / Copyright -->
            <div class="mt-6 text-center text-sm text-gray-500">
                <p>Copyright © {{ date('Y') }} CHED Property Management System</p>
            </div>
        </div>
    </div>
</x-app-layout>


<script>
    // Function to open the modal
    function openModal() {
        document.getElementById('qrModal').classList.remove('hidden');
    }
    // Function to close the modal
    function closeModal() {
        document.getElementById('qrModal').classList.add('hidden');
    }
</script>


