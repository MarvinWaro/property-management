<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add new Supplier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="section-container p-5">
                    <!-- Increased max width to allow a wider form -->
                    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6">
                        <!-- Heading -->
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">
                            Add new Suplier
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

                        <form action="{{ route('supplier.store') }}" method="POST" onsubmit="showLoader()">
                            @csrf

                            <div class="flex flex-col md:flex-row md:space-x-4">
                                <!-- Supplier's Name -->
                                <div class="flex-1 mb-4">
                                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Supplier's Name <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mb-4">
                                        <div class="relative">
                                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                                <!-- Icon for Name -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                    stroke="#a6a6a6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-user">
                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                                    <circle cx="12" cy="7" r="4" />
                                                </svg>
                                            </div>
                                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                    @error('name') border-red-500 @enderror"
                                                placeholder="Enter Supplier's name..." />
                                        </div>
                                        <!-- Error message for 'name' moved outside the relative div -->
                                        @error('name')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="flex-1 mb-4">
                                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Email
                                    </label>
                                    <div class="mb-4">
                                        <div class="relative">
                                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                                <!-- Icon for Email -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                    stroke="#a6a6a6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-mail">
                                                    <path d="M22 4H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h20a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Z" />
                                                    <polyline points="22,6 12,13 2,6" />
                                                </svg>
                                            </div>
                                            <input type="text" id="email" name="email" value="{{ old('email') }}"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                    @error('email') border-red-500 @enderror" placeholder="eg. juan@gmail.com" />
                                        </div>
                                        <!-- Error message for 'email' moved outside the relative div -->
                                        @error('email')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Contact Number -->
                                <div class="flex-1 mb-4">
                                    <label for="contact_no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Contact no. <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mb-4">
                                        <div class="relative">
                                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                                <!-- Icon for Phone -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                    stroke="#a6a6a6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-phone">
                                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.1A19.72 19.72 0 0 1 3.1 10.81 19.86 19.86 0 0 1 0 2.18 2 2 0 0 1 2 0h3a2 2 0 0 1 2 1.72c.13 1.12.32 2.22.57 3.29a2 2 0 0 1-.45 1.85L5.41 8.59a16 16 0 0 0 6 6l1.73-1.73a2 2 0 0 1 1.85-.45c1.07.25 2.17.44 3.29.57A2 2 0 0 1 22 16.92Z" />
                                                </svg>
                                            </div>
                                            <input type="text" id="contact_no" name="contact_no" value="{{ old('contact_no') }}"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5
                                                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                    @error('contact_no') border-red-500 @enderror" placeholder="eg. 09261337822" />
                                        </div>
                                        <!-- Error message for 'contact_no' moved outside the relative div -->
                                        @error('contact_no')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="flex justify-end space-x-2 mt-4">
                                <a href="{{ route('supplier.index') }}"
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
