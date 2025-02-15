<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Location') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="section-container p-5">
                    <!-- Increased max width to allow a wider form -->
                    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6">
                        <!-- Heading -->
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
                            Create New Location
                        </h2>

                        <!-- Validation Errors (optional) -->
                        @if ($errors->any())
                            <div class="mb-4 p-4 text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('location.store') }}" method="POST" onsubmit="showLoader()">
                            @csrf

                            <!-- Location Name Field -->
                            <label for="location_name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Location Name
                            </label>
                            <div class="mb-4">
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                        <!-- Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-map-pin">
                                            <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799
                                                        a1 1 0 0 1-1.202 0C9.539 20.193
                                                        4 14.993 4 10a8 8 0 0 1 16 0Z" />
                                            <circle cx="12" cy="10" r="3" />
                                        </svg>
                                    </div>
                                    <!-- w-full makes the input stretch across the container -->
                                    <input type="text" id="location_name" name="location_name"
                                        value="{{ old('location_name') }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                @error('location_name') border-red-500 @enderror"
                                        placeholder="Enter location name..." />
                                </div>
                                @error('location_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Buttons -->
                            <div class="flex justify-end space-x-2 mt-4">
                                <a href="{{ route('location.index') }}"
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
</x-app-layout>
