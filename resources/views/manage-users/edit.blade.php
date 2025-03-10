<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Update users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="section-container p-5">
                    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6">
                        <!-- Heading -->
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">Update End User</h2>
                        <form action="{{ route('end_users.update', $endUser->id) }}" method="POST"
                            enctype="multipart/form-data" onsubmit="showLoader()">
                            @csrf
                            @method('PUT')

                            <!-- Profile Photo Update -->
                            <div x-data="{
                                    // System default image (CHED logo)
                                    defaultImage: '{{ asset('img/ched-logo.png') }}',
                                    // Current photo: if user has a custom photo, use it; otherwise, use CHED logo.
                                    photoPreview: '{{ $endUser->picture ? asset('storage/' . $endUser->picture) : asset('img/ched-logo.png') }}',
                                    photoName: null,
                                    removePhoto: false
                                }" class="mb-4">

                                <!-- Hidden File Input -->
                                <input type="file" name="picture" class="hidden" x-ref="photo"
                                    x-on:change="
                                        photoName = $refs.photo.files[0].name;
                                        removePhoto = false;  // Reset removal flag if a new file is chosen
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            photoPreview = e.target.result;
                                        };
                                        reader.readAsDataURL($refs.photo.files[0]);
                                    ">

                                <!-- Hidden Input for Removal Flag -->
                                <input type="hidden" name="remove_photo" x-model="removePhoto">

                                <div>
                                    <!-- Preview Image -->
                                    <div class="mt-2">
                                        <img :src="photoPreview" class="w-40 h-40 rounded-full shadow object-cover" alt="Profile Photo">
                                    </div>
                                    <div class="flex gap-2 mt-2">
                                        <!-- Select New Photo Button -->
                                        <button type="button"
                                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-400 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150"
                                            x-on:click.prevent="$refs.photo.click()">
                                            Select New Photo
                                        </button>

                                        <!-- Remove Photo Button -->
                                        <button type="button"
                                            class="inline-flex items-center px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm transition ease-in-out duration-150"
                                            :class="{
                                                'hover:bg-red-600 focus:border-red-700 focus:shadow-outline-red active:bg-red-700': photoPreview !== defaultImage,
                                                'opacity-50 cursor-not-allowed': photoPreview === defaultImage
                                            }"
                                            x-bind:disabled="photoPreview === defaultImage"
                                            x-on:click.prevent="
                                                if (photoPreview !== defaultImage) {
                                                    // Reset back to the system default (CHED logo)
                                                    photoPreview = defaultImage;
                                                    photoName = null;
                                                    removePhoto = true;  // Set the flag so the controller knows to remove the picture
                                                    $refs.photo.value = '';
                                                }
                                            ">
                                            Remove Photo
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Name Field -->
                                <div>
                                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Your Name
                                    </label>
                                    <div class="mb-4">
                                        <div class="relative">
                                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user">
                                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                                    <circle cx="12" cy="7" r="4" />
                                                </svg>
                                            </div>
                                            <input type="text" id="name" name="name"
                                                value="{{ old('name', $endUser->name) }}"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                    focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                    @error('name') border-red-500 @enderror"
                                                placeholder="Enter your full name">
                                        </div>
                                        @error('name')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email Field -->
                                <div>
                                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Your Email
                                    </label>
                                    <div class="mb-4">
                                        <div class="relative">
                                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-at-sign">
                                                    <circle cx="12" cy="12" r="4" />
                                                    <path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8" />
                                                </svg>
                                            </div>
                                            <input type="email" id="email" name="email"
                                                value="{{ old('email', $endUser->email) }}"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                    focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                    @error('email') border-red-500 @enderror"
                                                placeholder="youremail@gmail.com">
                                        </div>
                                        @error('email')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Contact Number Field -->
                                <div>
                                    <label for="contact-number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Your Contact Number
                                    </label>
                                    <div class="mb-4">
                                        <div class="relative">
                                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="#a6a6a6" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone">
                                                    <path
                                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                                </svg>
                                            </div>
                                            <input type="text" id="contact-number" name="phone_number"
                                                value="{{ old('phone_number', $endUser->phone_number) }}"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                    focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                    @error('phone_number') border-red-500 @enderror"
                                                placeholder="09*********">
                                        </div>
                                        @error('phone_number')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Department Dropdown -->
                                <div>
                                    <label for="department" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Select Department
                                    </label>
                                    <div class="mb-4">
                                        <div class="relative">
                                            <select id="department" name="department"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                    focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                                                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                    @error('department') border-red-500 @enderror">
                                                <option value="" disabled>Select Department</option>
                                                <option value="Admin Department"
                                                    {{ old('department', $endUser->department) == 'Admin Department' ? 'selected' : '' }}>
                                                    Admin Department</option>
                                                <option value="Technical Department"
                                                    {{ old('department', $endUser->department) == 'Technical Department' ? 'selected' : '' }}>
                                                    Technical Department</option>
                                                <option value="UNIFAST"
                                                    {{ old('department', $endUser->department) == 'UNIFAST' ? 'selected' : '' }}>
                                                    UNIFAST</option>
                                            </select>
                                        </div>
                                        @error('department')
                                            <p class="text-red-500 text-xs mt-1">Please choose from the dropdown.</p>
                                        @enderror
                                    </div>
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
                                    Update
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
