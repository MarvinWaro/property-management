<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Change Password') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="section-container p-5">

                    <div class="max-w-md mx-auto mt-8">
                        <h2 class="text-xl font-bold mb-4">Change Your Password</h2>

                        @if ($errors->any())
                            <div class="text-red-600 mb-4">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('user.force-change-password.update') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="password" class="block mb-1 font-medium">New Password</label>
                                <input type="password" name="password" class="w-full border border-gray-300 rounded p-2" required>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="block mb-1 font-medium">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="w-full border border-gray-300 rounded p-2" required>
                            </div>

                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Update Password
                            </button>
                        </form>
                    </div>

                </div><!-- End .section-container -->
            </div>
        </div>
    </div>
</x-app-layout>
