<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create users') }}
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
                            Create New End User
                        </h2>

                        <form action="{{ route('end_users.store') }}" method="POST" enctype="multipart/form-data"
                            onsubmit="showLoader()">
                            @csrf

                            <!-- Profile Photo Upload (Left-aligned) -->
                            <div x-data="{ photoName: null, photoPreview: null }" class="mb-4">
                                <!-- Photo File Input -->
                                <input type="file" name="picture" class="hidden" x-ref="photo"
                                    x-on:change="
                                           photoName = $refs.photo.files[0].name;
                                           const reader = new FileReader();
                                           reader.onload = (e) => {
                                               photoPreview = e.target.result;
                                           };
                                           reader.readAsDataURL($refs.photo.files[0]);
                                       ">
                                <div>
                                    <!-- Default/Current Photo (if no preview available) -->
                                    <div class="mt-2" x-show="!photoPreview">
                                        <img src="{{ asset('img/ched-logo.png') }}"
                                            class="w-40 h-40 rounded-full shadow" alt="Default Profile Photo">
                                    </div>

                                    <!-- New Photo Preview -->
                                    <div class="mt-2" x-show="photoPreview" style="display: none;">
                                        <span class="block w-40 h-40 rounded-full shadow"
                                            x-bind:style="'background-size: cover; background-repeat: no-repeat; background-position: center; background-image: url(' +
                                            photoPreview + ');'">
                                        </span>
                                    </div>
                                    <button type="button"
                                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300
                                                   rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest
                                                   shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-400
                                                   focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50
                                                   transition ease-in-out duration-150 mt-2"
                                        x-on:click.prevent="$refs.photo.click()">
                                        Select New Photo
                                    </button>
                                </div>
                                @error('picture')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror

                            </div>

                            <!-- Input Fields in Two Columns -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Name Field -->
                                <div>
                                    <label for="name"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Your Name
                                    </label>
                                    <div class="mb-4 relative">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-user">
                                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                                <circle cx="12" cy="7" r="4" />
                                            </svg>
                                        </div>
                                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                      focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                      dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                      dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                      @error('name') border-red-500 @enderror"
                                            placeholder="Enter your full name">
                                    </div>
                                    @error('name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email Field -->
                                <div>
                                    <label for="email"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Your Email
                                    </label>
                                    <div class="mb-4 relative">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-at-sign">
                                                <circle cx="12" cy="12" r="4" />
                                                <path d="M16 8v5a3 3 0 0 0 6 0v-1
                                                         a10 10 0 1 0-4 8" />
                                            </svg>
                                        </div>
                                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                      focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                      dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                      dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                      @error('email') border-red-500 @enderror"
                                            placeholder="youremail@gmail.com">
                                    </div>
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Department Dropdown -->
                                <div>
                                    <label for="department"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Select Department
                                    </label>
                                    <div class="mb-4 relative">
                                        <select id="department" name="department"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                       focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                                                       dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                       dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                       @error('department') border-red-500 @enderror">
                                            <option value="" disabled selected>Choose Department</option>
                                            <option value="Admin Department">Admin Department</option>
                                            <option value="Technical Department">Technical Department</option>
                                            <option value="UNIFAST">UNIFAST</option>
                                        </select>
                                    </div>
                                    @error('department')
                                        <p class="text-red-500 text-xs mt-1">Please choose from the dropdown.</p>
                                    @enderror
                                </div>

                                <!-- Designation Dropdown -->
                                <div>
                                    <label for="designation"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Select Designation
                                    </label>
                                    <div class="mb-4 relative">
                                        <select id="designation" name="designation"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                       focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                                                       dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                       dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                       @error('designation') border-red-500 @enderror">
                                            <option value="" disabled selected>Choose Designation</option>
                                            <option value="PTS 1">PTS 1</option>
                                            <option value="PTS 2">PTS 2</option>
                                            <option value="PTS 3">PTS 3</option>
                                        </select>
                                    </div>
                                    @error('designation')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Form Buttons -->
                            <div class="flex justify-end space-x-2 mt-4">
                                <a href="{{ route('end_users.index') }}"
                                    class="text-sm px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                                    Back
                                </a>
                                <button type="submit"
                                    class="text-sm px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                    Submit
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
