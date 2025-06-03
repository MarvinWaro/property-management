<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="section-container p-5">
                    <!-- Button and Search Bar Container -->
                    <div class="flex items-center justify-between mb-4 mt-2 space-x-2 w-full">
                        <!-- Search Bar Container -->
                        <form method="GET" action="{{ route('categories.index') }}"
                            class="w-full max-w-sm flex items-center space-x-2">
                            <div class="relative flex-grow">
                                <input type="text" name="search" id="search-input"
                                    value="{{ request()->get('search') }}" oninput="toggleClearButton()"
                                    placeholder="Search..."
                                    class="px-4 py-2 w-full border text-sm font-medium border-gray-300 rounded-lg
                                        focus:ring-1 focus:ring-orange-500 focus:border-orange-500
                                        dark:bg-gray-800 dark:border-gray-700 dark:text-white
                                        dark:focus:ring-orange-500 dark:focus:border-orange-500" />

                                <!-- The 'X' Button (hidden by default) -->
                                <button type="button" id="clearButton" onclick="clearSearch()" style="display: none;"
                                    class="absolute inset-y-0 right-2 flex items-center text-gray-500 hover:text-red-500 focus:outline-none">
                                    <!-- X Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x">
                                        <line x1="18" x2="6" y1="6" y2="18" />
                                        <line x1="6" x2="18" y1="6" y2="18" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Separate Search Button -->
                            <button type="submit"
                                class="px-3 py-2 text-sm text-white bg-orange-600 rounded-lg
                                    hover:bg-orange-800 focus:ring-1 focus:outline-none
                                    focus:ring-orange-300 dark:bg-orange-600 dark:hover:bg-orange-700
                                    dark:focus:ring-orange-800 flex items-center">
                                <!-- Search Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="size-5">
                                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11
                                        5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1
                                        12.452 4.391l3.328 3.329a.75.75
                                        0 1 1-1.06 1.06l-3.329-3.328A7
                                        7 0 0 1 2 9Z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </form>

                        <!-- Changed to match supplier button style -->
                        <button data-modal-target="createCategoryModal" data-modal-toggle="createCategoryModal"
                            type="button"
                            class="py-2 px-3 text-white bg-orange-600 hover:bg-orange-700 hover:shadow-lg transform hover:scale-105 rounded-lg text-sm font-medium focus:ring-4 focus:outline-none focus:ring-orange-300 dark:focus:ring-orange-800 transition-all duration-200 ml-2 inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="hidden sm:inline-block">Add Category</span>
                        </button>
                    </div>

                    <!-- Create Category Modal -->
                    <div id="createCategoryModal" tabindex="-1" aria-hidden="true" class="hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-md max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <!-- Modal header -->
                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 bg-gradient-to-r from-blue-600 to-blue-800">
                                    <h3 class="text-xl font-semibold text-white">
                                        Create New Category
                                    </h3>
                                    <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600" data-modal-hide="createCategoryModal">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"></path></svg>
                                    </button>
                                </div>

                                <!-- Modal body -->
                                <form id="createCategoryForm" action="{{ route('categories.store') }}" method="POST" class="p-4 md:p-5">
                                    @csrf
                                    <div class="grid gap-4 mb-4">
                                        <div class="col-span-2">
                                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category Name</label>
                                            <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter category name" required>
                                        </div>
                                        <div class="col-span-2">
                                            <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                                            <textarea id="description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter category description"></textarea>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end space-x-4">
                                        <button type="button" data-modal-hide="createCategoryModal" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</button>
                                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Create Category</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Category Modal (similar structure) -->
                    <div id="editCategoryModal" tabindex="-1" aria-hidden="true" class="hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-md max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <!-- Modal header -->
                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 bg-gradient-to-r from-blue-600 to-blue-800">
                                    <h3 class="text-xl font-semibold text-white">
                                        Edit Category
                                    </h3>
                                    <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600" data-modal-hide="editCategoryModal">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"></path></svg>
                                    </button>
                                </div>

                                <!-- Modal body -->
                                <form id="editCategoryForm" method="POST" class="p-4 md:p-5">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" id="edit_category_id" name="category_id">

                                    <div class="grid gap-4 mb-4">
                                        <div class="col-span-2">
                                            <label for="edit_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category Name</label>
                                            <input type="text" name="name" id="edit_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter category name" required>
                                        </div>
                                        <div class="col-span-2">
                                            <label for="edit_description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                                            <textarea id="edit_description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter category description"></textarea>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end space-x-4">
                                        <button type="button" data-modal-hide="editCategoryModal" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</button>
                                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Update Category</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Category Modals -->
                    @foreach($categories as $category)
                        <div id="deleteCategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative p-4 w-full max-w-md max-h-full">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 bg-gradient-to-r from-red-500 to-red-700">
                                        <h3 class="text-lg font-semibold text-white flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                            Delete Category
                                        </h3>
                                        <button type="button" class="text-white bg-transparent hover:bg-red-800 hover:text-white rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="deleteCategoryModal{{ $category->id }}">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>

                                    <!-- Modal body -->
                                    <div class="p-6">
                                        <div class="mb-5 text-center">
                                            <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                            </div>
                                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mt-3">Confirm Deletion</h3>
                                            <div class="mt-2 text-gray-600 dark:text-gray-400">
                                                <p>Are you sure you want to delete category:</p>
                                                <p class="font-semibold text-gray-800 dark:text-white mt-1">"{{ $category->name }}"</p>
                                            </div>
                                            <p class="mt-3 text-sm text-red-500">This action cannot be undone.</p>
                                        </div>

                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="mt-6">
                                            @csrf
                                            @method('DELETE')
                                            <div class="flex items-center justify-center space-x-4">
                                                <button data-modal-hide="deleteCategoryModal{{ $category->id }}" type="button" class="py-2.5 px-5 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="py-2.5 px-5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 inline-flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                                        <path d="M3 6h18"></path>
                                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                    </svg>
                                                    Delete Permanently
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Flash Messages -->
                    @if (session()->has('success'))
                        <div id="flashMessage"
                            class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                            role="alert">
                            <span class="font-medium">Success!</span> {{ session('success') }}
                        </div>
                        <script>
                            setTimeout(() => {
                                document.getElementById('flashMessage').style.display = 'none';
                            }, 3000);
                        </script>
                    @endif

                    <!-- Table Description Caption -->
                    <div class="p-4 mb-4 text-sm text-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-gray-300">
                        <h3 class="text-lg font-semibold mb-1 text-gray-900 dark:text-white">CHED Supplies Category</h3>
                        <p>
                            This section displays categorized items with their names and descriptions,
                            facilitating organized inventory management and streamlined asset
                            tracking within CHED.
                        </p>
                    </div>

                    <!-- Categories Table - Enhanced to match supplier table -->
                    <div class="overflow-hidden shadow-md sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="overflow-x-auto">
                            <div class="overflow-y-auto max-h-[500px]">
                                <table class="w-full text-sm text-left">
                                    <thead
                                        class="text-xs uppercase bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">ID</th>
                                            <th scope="col" class="px-6 py-3">Name</th>
                                            <th scope="col" class="px-6 py-3">Description</th>
                                            <th scope="col" class="px-6 py-3 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($categories as $category)
                                            <tr
                                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <th scope="row"
                                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {{ $category->id }}
                                                </th>
                                                <td class="px-6 py-4 font-medium">
                                                    <div class="text-gray-900 dark:text-white">{{ $category->name }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-gray-900 dark:text-white">
                                                    {{ $category->description ?? 'No description provided' }}</td>
                                                <td class="px-2 py-4">
                                                    <div class="flex items-center justify-center space-x-2">
                                                        <!-- Edit Button -->
                                                        <button type="button"
                                                            data-modal-target="editCategoryModal"
                                                            data-modal-toggle="editCategoryModal"
                                                            data-category-id="{{ $category->id }}"
                                                            data-category-name="{{ $category->name }}"
                                                            data-category-description="{{ $category->description }}"
                                                            class="edit-category-btn p-2 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-yellow-300 dark:bg-yellow-900 dark:text-yellow-300 dark:hover:bg-yellow-800 transition-all duration-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path
                                                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                                <path
                                                                    d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z" />
                                                            </svg>
                                                        </button>

                                                        <!-- Delete Button -->
                                                        <button type="button"
                                                            data-modal-target="deleteCategoryModal{{ $category->id }}"
                                                            data-modal-toggle="deleteCategoryModal{{ $category->id }}"
                                                            class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-300 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800 transition-all duration-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M3 6h18" />
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                                <line x1="10" x2="10" y1="11"
                                                                    y2="17" />
                                                                <line x1="14" x2="14" y1="11"
                                                                    y2="17" />
                                        orange    </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-8 text-center">
                                                    <!-- Empty state content -->
                                                    <div class="flex flex-col items-center justify-center">
                                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M18 6h-4V2a1 1 0 00-1-1H7a1 1 0 00-1 1v4H2a1 1 0 00-1 1v11a1 1 0 001 1h16a1 1 0 001-1V7a1 1 0 00-1-1z">
                                                            </path>
                                                        </svg>
                                                        <p
                                                            class="text-lg font-medium text-gray-500 dark:text-gray-400">
                                                            No categories found</p>
                                                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Get
                                                            started by adding a new category</p>
                                                        <button type="button" data-modal-target="createCategoryModal"
                                                            data-modal-toggle="createCategoryModal"
                                                            class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg transition-colors shadow-sm focus:ring-4 focus:ring-blue-300">
                                                            <svg class="w-4 h-4 mr-2" fill="currentColor"
                                                                viewBox="0 0 20 20"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                            Add Category
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <nav class="flex items-center justify-between pt-4 mb-3" aria-label="Table navigation">
                        <!-- On the left side (optional) -->
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            @if ($categories->count() > 0)
                                Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of
                                {{ $categories->total() }} categories
                            @endif
                        </div>

                        <!-- On the right side -->
                        <div class="mt-2 sm:mt-0">
                            {{ $categories->links('pagination::tailwind') }}
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Search Input -->
    <script>
        function toggleClearButton() {
            const input = document.getElementById('search-input');
            const clearBtn = document.getElementById('clearButton');
            clearBtn.style.display = input.value.trim().length > 0 ? 'flex' : 'none';
        }

        function clearSearch() {
            const input = document.getElementById('search-input');
            input.value = '';
            document.getElementById('clearButton').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', () => {
            toggleClearButton();
        });
    </script>

<script>
    // When edit button is clicked, populate the modal with category data
    document.addEventListener('DOMContentLoaded', function() {
        // Handle edit button clicks
        const editButtons = document.querySelectorAll('.edit-category-btn');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-category-id');
                const name = this.getAttribute('data-category-name');
                const description = this.getAttribute('data-category-description');

                // Populate form fields
                document.getElementById('edit_category_id').value = id;
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_description').value = description;

                // Update form action URL
                const form = document.getElementById('editCategoryForm');
                form.action = `/categories/${id}`;
            });
        });

        // Clear search functionality
        function toggleClearButton() {
            const searchInput = document.getElementById('search-input');
            const clearButton = document.getElementById('clearButton');

            if (searchInput.value.length > 0) {
                clearButton.style.display = 'flex';
            } else {
                clearButton.style.display = 'none';
            }
        }

        function clearSearch() {
            document.getElementById('search-input').value = '';
            toggleClearButton();
            window.location.href = window.location.pathname;
        }

        // Initialize state on page load
        toggleClearButton();

        // Make these functions globally available
        window.toggleClearButton = toggleClearButton;
        window.clearSearch = clearSearch;
    });

    // Delete confirmation function
    function confirmDelete(formId, categoryName) {
        if (confirm(`Are you sure you want to delete "${categoryName}"? This action cannot be undone.`)) {
            document.getElementById(formId).submit();
        }
    }
</script>
</x-app-layout>
