<div>
    @if (session('signature_success'))
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ session('signature_success') }}
        </div>
    @endif

    @if (session('signature_error'))
        <div class="mb-4 font-medium text-sm text-red-600 dark:text-red-400">
            {{ session('signature_error') }}
        </div>
    @endif

    <x-action-section>
        <x-slot name="title">
            {{ __('E-Signature Management') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Upload your signature image to be used on requisition forms and other documents.') }}
        </x-slot>

        <x-slot name="content">
            <!-- Current Signature -->
            <div class="mt-2">
                <x-label value="{{ __('Current Signature') }}" />
                <div class="mt-1 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    @if(Auth::user()->signature_path)
                        <div class="flex flex-col items-center">
                            <img src="{{ Storage::url(Auth::user()->signature_path) }}"
                                alt="Your signature"
                                class="max-h-24 mb-3">

                            <form action="{{ route('signature.delete') }}" method="POST" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <x-danger-button type="submit">
                                    {{ __('Remove Signature') }}
                                </x-danger-button>
                            </form>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-6">
                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                            <p class="text-sm text-gray-500 dark:text-gray-400">No signature uploaded yet</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Upload New Signature -->
            <div class="mt-5">
                <x-label for="new-signature" value="{{ __('Upload New Signature') }}" />

                <form action="{{ route('signature.upload') }}" method="POST" enctype="multipart/form-data" class="mt-1">
                    @csrf

                    <div class="flex items-center gap-4">
                        <div class="relative flex-grow">
                            <input type="file"
                                id="new-signature"
                                name="signature"
                                accept="image/*"
                                class="hidden"
                                required
                                onchange="updateFileName(this)"
                            />

                            <div class="flex items-center">
                                <x-secondary-button onclick="document.getElementById('new-signature').click(); return false;" type="button" class="mr-3">
                                    {{ __('Browse File') }}
                                </x-secondary-button>

                                <span id="file-chosen" class="text-sm text-gray-500 dark:text-gray-400">
                                    No file selected
                                </span>
                            </div>
                        </div>

                        <x-button>
                            {{ __('Upload') }}
                        </x-button>
                    </div>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Upload an image of your signature (JPEG, PNG, JPG, GIF). Maximum size: 1MB.
                    </p>

                    @error('signature')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </form>
            </div>

            <!-- Usage Information -->
            <div class="mt-5">
                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-2">How your signature is used:</h4>
                    <ul class="list-disc pl-5 text-sm text-blue-700 dark:text-blue-400 space-y-1">
                        <li>Your signature will appear on requisition forms you submit</li>
                        <li>It will be visible on printed documents and digital records</li>
                        <li>Upload a clear image with good contrast for best results</li>
                        <li>A transparent background is recommended but not required</li>
                    </ul>
                </div>
            </div>
        </x-slot>
    </x-action-section>
</div>

<script>
    function updateFileName(input) {
        const fileChosen = document.getElementById('file-chosen');
        if (input.files.length > 0) {
            fileChosen.textContent = input.files[0].name;
        } else {
            fileChosen.textContent = 'No file selected';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Check if URL contains the signature section anchor
        if (window.location.hash === '#signature-section') {
            // Scroll to the signature section
            document.getElementById('signature-section').scrollIntoView();
        }
    });
</script>
