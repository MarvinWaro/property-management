<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('View Property') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Property Details</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">Item Name</td>
                            <td class="px-6 py-4">{{ $property->item_name ?: 'TBA/NA' }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">Description</td>
                            <td class="px-6 py-4">{{ $property->item_description ?: 'TBA/NA' }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">Serial No</td>
                            <td class="px-6 py-4">{{ $property->serial_no ?: 'TBA/NA' }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">Model No</td>
                            <td class="px-6 py-4">{{ $property->model_no ?: 'TBA/NA' }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">Acquisition Date</td>
                            <td class="px-6 py-4">{{ $property->acquisition_date ?: 'TBA/NA' }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">Acquisition Cost</td>
                            <td class="px-6 py-4">{{ $property->acquisition_cost ?: 'TBA/NA' }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">Unit of Measure</td>
                            <td class="px-6 py-4">{{ $property->unit_of_measure ?: 'TBA/NA' }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">Quantity per Physical Count</td>
                            <td class="px-6 py-4">{{ $property->quantity_per_physical_count ?: 'TBA/NA' }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">Fund</td>
                            <td class="px-6 py-4">{{ $property->fund ?: 'TBA/NA' }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">Location</td>
                            <td class="px-6 py-4">
                                {{ $property->location->location_name ?? 'TBA/NA' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">End User</td>
                            <td class="px-6 py-4">
                                {{ $property->endUser->name ?? 'TBA/NA' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">Condition</td>
                            <td class="px-6 py-4">{{ $property->condition ?: 'TBA/NA' }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">Remarks</td>
                            <td class="px-6 py-4">{{ $property->remarks ?: 'TBA/NA' }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">Active</td>
                            <td class="px-6 py-4">{{ $property->active ? 'Yes' : 'No' }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">Excluded</td>
                            <td class="px-6 py-4">{{ $property->excluded ? 'Yes' : 'No' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>


