<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Supply Ledger Card: {{ $supply->item_name }}
            </h2>
            <div class="flex space-x-2">
                <!-- PDF Export Button -->
                {{-- <a href="{{ route('supply-ledger-cards.export-pdf', $supply->supply_id) }}?fund_cluster={{ $fundCluster }}&year={{ $selectedYear }}"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                    <span class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span>Export as PDF</span>
                    </span>
                </a> --}}

                <!-- Replace your Excel Export Button with this direct link approach -->
                <a href="{{ route('supply-ledger-cards.export-excel', $supply->supply_id) }}?fund_cluster={{ $fundCluster }}&year={{ $selectedYear }}"
                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                    <span class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Export as Excel</span>
                    </span>
                </a>

                {{-- <!-- Test Button (remove this after testing) -->
                <button onclick="testExcelExport()" type="button"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <span>Test Excel</span>
                </button> --}}
            </div>
        </div>
    </x-slot>

    <!-- Rest of your existing content -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Your existing supply details and table content here -->
            <!-- Supply Details Card -->
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow mb-6">
                <!-- Ledger Card Header -->
                <div class="p-5 border-b border-[#e5e7eb] dark:border-[#374151] bg-[#a01b1a] dark:bg-[#a01b1a]">
                    <div class="flex flex-wrap justify-between items-center">
                        <div class="text-white">
                            <h3 class="text-xl font-bold">{{ $supply->item_name }}</h3>
                            <p class="text-[#f9fafb] opacity-70">Stock No: {{ $supply->stock_no }}</p>
                        </div>
                        <div class="mt-2 md:mt-0 flex space-x-2">
                            <!-- Year Selector -->
                            <form method="GET" action="{{ route('supply-ledger-cards.show', $supply->supply_id) }}"
                                class="flex space-x-2">
                                <input type="hidden" name="fund_cluster" value="{{ $fundCluster }}">
                                <input type="hidden" name="month" value="{{ $selectedMonth }}">
                                <select name="year" onchange="this.form.submit()"
                                    class="px-4 py-2 rounded-lg text-sm
                                        bg-white text-[#a01b1a] border border-[#a01b1a]
                                        focus:outline-none focus:ring-2 focus:ring-[#a01b1a]">
                                    @foreach ($availableYears as $year)
                                        <option value="{{ $year }}"
                                            {{ $selectedYear == $year ? 'selected' : '' }}>
                                            Year: {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>

                            <!-- Month Selector (NEW) -->
                            @if($availableMonths->isNotEmpty())
                                <form method="GET" action="{{ route('supply-ledger-cards.show', $supply->supply_id) }}"
                                    class="flex space-x-2">
                                    <input type="hidden" name="fund_cluster" value="{{ $fundCluster }}">
                                    <input type="hidden" name="year" value="{{ $selectedYear }}">
                                    <select name="month" onchange="this.form.submit()"
                                        class="px-4 py-2 rounded-lg text-sm
                                            bg-white text-[#a01b1a] border border-[#a01b1a]
                                            focus:outline-none focus:ring-2 focus:ring-[#a01b1a]">
                                        <option value="">All Months</option>
                                        @foreach ($availableMonths as $month)
                                            <option value="{{ $month['value'] }}"
                                                {{ $selectedMonth == $month['value'] ? 'selected' : '' }}>
                                                {{ $month['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            @endif

                            <!-- Fund Cluster Selector -->
                            <form method="GET" action="{{ route('supply-ledger-cards.show', $supply->supply_id) }}"
                                class="flex space-x-2">
                                <input type="hidden" name="year" value="{{ $selectedYear }}">
                                <input type="hidden" name="month" value="{{ $selectedMonth }}">
                                <select name="fund_cluster" onchange="this.form.submit()"
                                    class="px-4 py-2 rounded-lg text-sm
                                        bg-white text-[#a01b1a] border border-[#a01b1a]
                                        focus:outline-none focus:ring-2 focus:ring-[#a01b1a]">
                                    @foreach ($fundClusters as $fc)
                                        <option value="{{ $fc }}"
                                            {{ $fundCluster == $fc ? 'selected' : '' }}>
                                            Fund Cluster: {{ $fc }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="p-5">
                    <!-- resources/views/supply-ledger-cards/show.blade.php -->

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        <div class="bg-gray-200 dark:bg-gray-700 p-3 rounded-lg">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Unit</p>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $supply->unit_of_measurement }}</p>
                        </div>
                        <div class="bg-gray-200 dark:bg-gray-700 p-3 rounded-lg">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Category</p>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $supply->category->name ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-gray-200 dark:bg-gray-700 p-3 rounded-lg">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Current Balance</p>
                            <p class="font-medium text-gray-800 dark:text-white">{{ number_format($currentStock) }}</p>
                        </div>
                        <div class="bg-gray-200 dark:bg-gray-700 p-3 rounded-lg">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Moving Average Cost</p>
                            <p class="font-medium text-gray-800 dark:text-white">₱{{ number_format($movingAverageCost, 4) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Supply Ledger Card Table -->
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Supply Ledger Card -
                        {{ $selectedYear }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Entity Name: COMMISSION ON HIGHER EDUCATION REGIONAL OFFICE XII &nbsp;&nbsp;|&nbsp;&nbsp;
                        Fund Cluster: {{ $fundCluster }}
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead>
                            <!-- Main header row with column groups -->
                            <tr class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                <th scope="col"
                                    class="px-4 py-3 border-b border-r border-gray-200 dark:border-gray-600 text-center"
                                    rowspan="2">DATE</th>
                                <th scope="col"
                                    class="px-4 py-3 border-b border-r border-gray-200 dark:border-gray-600 text-center"
                                    rowspan="2">REFERENCE</th>
                                <th scope="col" colspan="3"
                                    class="px-4 py-3 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-green-50 dark:bg-green-900/20">
                                    RECEIPT
                                </th>
                                <th scope="col" colspan="3"
                                    class="px-4 py-3 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-red-50 dark:bg-red-900/20">
                                    ISSUE
                                </th>
                                <th scope="col" colspan="3"
                                    class="px-4 py-3 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-blue-50 dark:bg-blue-900/20">
                                    BALANCE
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 border-b border-gray-200 dark:border-gray-600 text-center"
                                    rowspan="2">
                                    DAYS TO<br />CONSUME
                                </th>
                            </tr>

                            <!-- Subheader for column details -->
                            <tr class="text-xs text-gray-700 bg-gray-50 dark:bg-gray-700/70 dark:text-gray-400">
                                <!-- Receipt columns -->
                                <th scope="col"
                                    class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-green-50/50 dark:bg-green-900/10">
                                    QTY.</th>
                                <th scope="col"
                                    class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-green-50/50 dark:bg-green-900/10">
                                    UNIT COST</th>
                                <th scope="col"
                                    class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-green-50/50 dark:bg-green-900/10">
                                    TOTAL COST</th>
                                <!-- Issue columns -->
                                <th scope="col"
                                    class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-red-50/50 dark:bg-red-900/10">
                                    QTY.</th>
                                <th scope="col"
                                    class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-red-50/50 dark:bg-red-900/10">
                                    UNIT COST</th>
                                <th scope="col"
                                    class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-red-50/50 dark:bg-red-900/10">
                                    TOTAL COST</th>
                                <!-- Balance columns -->
                                <th scope="col"
                                    class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-blue-50/50 dark:bg-blue-900/10">
                                    QTY.</th>
                                <th scope="col"
                                    class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-blue-50/50 dark:bg-blue-900/10">
                                    UNIT COST</th>
                                <th scope="col"
                                    class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-blue-50/50 dark:bg-blue-900/10">
                                    TOTAL COST</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ledgerCardEntries as $entry)
                                <tr
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td
                                        class="px-4 py-3 whitespace-nowrap border-r border-gray-100 dark:border-gray-700">
                                        {{ \Carbon\Carbon::parse($entry['date'])->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 py-3 border-r border-gray-100 dark:border-gray-700">
                                        @if ($entry['reference'] === 'Beginning Balance')
                                            <span class="font-medium text-gray-800 dark:text-white">
                                                {{ $entry['reference'] }}
                                            </span>
                                        @else
                                            {{ $entry['reference'] }}
                                        @endif
                                    </td>
                                    <!-- Receipt columns -->
                                    <td
                                        class="px-4 py-3 text-center bg-green-50/30 dark:bg-green-900/5 border-r border-gray-100 dark:border-gray-700">
                                        @if ($entry['receipt_qty'])
                                            <span class="font-medium text-green-600 dark:text-green-400">
                                                {{ number_format($entry['receipt_qty']) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center bg-green-50/30 dark:bg-green-900/5 border-r border-gray-100 dark:border-gray-700">
                                        @if ($entry['receipt_unit_cost'])
                                            <span class="font-medium text-green-600 dark:text-green-400">
                                                {{ number_format($entry['receipt_unit_cost'], 4) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center bg-green-50/30 dark:bg-green-900/5 border-r border-gray-100 dark:border-gray-700">
                                        @if ($entry['receipt_total_cost'])
                                            <span class="font-medium text-green-600 dark:text-green-400">
                                                ₱{{ number_format($entry['receipt_total_cost'], 4) }}
                                            </span>
                                        @endif
                                    </td>
                                    <!-- Issue columns -->
                                    <td
                                        class="px-4 py-3 text-center bg-red-50/30 dark:bg-red-900/5 border-r border-gray-100 dark:border-gray-700">
                                        @if ($entry['issue_qty'])
                                            <span class="font-medium text-red-600 dark:text-red-400">
                                                {{ number_format($entry['issue_qty']) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center bg-red-50/30 dark:bg-red-900/5 border-r border-gray-100 dark:border-gray-700">
                                        @if ($entry['issue_unit_cost'])
                                            <span class="font-medium text-red-600 dark:text-red-400">
                                                {{ number_format($entry['issue_unit_cost'], 4) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center bg-red-50/30 dark:bg-red-900/5 border-r border-gray-100 dark:border-gray-700">
                                        @if ($entry['issue_total_cost'])
                                            <span class="font-medium text-red-600 dark:text-red-400">
                                                ₱{{ number_format($entry['issue_total_cost'], 4) }}
                                            </span>
                                        @endif
                                    </td>
                                    <!-- Balance columns -->
                                    <td
                                        class="px-4 py-3 text-center font-medium text-gray-800 dark:text-white bg-blue-50/30 dark:bg-blue-900/5 border-r border-gray-100 dark:border-gray-700">
                                        {{ number_format($entry['balance_qty']) }}
                                    </td>
                                    <!-- Balance unit cost column -->
                                    <!-- Balance unit cost column -->
                                    <td
                                        class="px-4 py-3 text-center font-medium text-gray-800 dark:text-white bg-blue-50/30 dark:bg-blue-900/5 border-r border-gray-100 dark:border-gray-700">
                                        @if ($entry['balance_unit_cost'] !== null && $entry['balance_qty'] > 0)
                                            {{ number_format($entry['balance_unit_cost'], 4) }}
                                        @else
                                            0.0000
                                        @endif
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-medium text-gray-800 dark:text-white bg-blue-50/30 dark:bg-blue-900/5 border-r border-gray-100 dark:border-gray-700">
                                        ₱{{ number_format($entry['balance_total_cost'], 4) }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        {{ $entry['days_to_consume'] ?? 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
                                        No transactions found for this supply.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add SheetJS CDN and Excel Export Script -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script> --}}
    <script>
        // Test if SheetJS is loaded
        console.log('SheetJS loaded:', typeof XLSX !== 'undefined');

        function exportSupplyLedgerExcel() {
            console.log('Export function called');

            // Check if XLSX is available
            if (typeof XLSX === 'undefined') {
                alert('Excel library not loaded. Please refresh the page and try again.');
                return;
            }

            try {
            // Prepare supply data
            const supplyData = {
                item_name: @json($supply->item_name),
                stock_no: @json($supply->stock_no),
                description: @json($supply->description ?? ''),
                unit_of_measurement: @json($supply->unit_of_measurement),
                reorder_point: @json($supply->reorder_point ?? ''),
            };

            // Prepare ledger entries data
            const ledgerEntries = @json($ledgerCardEntries);
            const fundCluster = @json($fundCluster);
            const selectedYear = @json($selectedYear);

            // Create workbook and worksheet
            const wb = XLSX.utils.book_new();
            const wsData = [];

            // Row 1: Empty with Appendix 57 in last column
            wsData.push(['', '', '', '', '', '', '', '', '', '', '', 'Appendix 57']);

            // Row 2: Empty
            wsData.push(['', '', '', '', '', '', '', '', '', '', '', '']);

            // Row 3: Title - SUPPLIES LEDGER CARD (centered)
            wsData.push(['', '', '', '', 'SUPPLIES LEDGER CARD', '', '', '', '', '', '', '']);

            // Row 4: Empty
            wsData.push(['', '', '', '', '', '', '', '', '', '', '', '']);

            // Header table structure matching PDF exactly
            // Row 5: Entity Name (spans 3 cols) | Fund Cluster (spans 2 cols) | Empty cols
            wsData.push(['Entity Name: COMMISSION ON HIGHER EDUCATION REGIONAL OFFICE XII', '', '', 'Fund Cluster: ' + fundCluster, '', '', '', '', '', '', '', '']);

            // Row 6: Item info
            wsData.push(['Item:', supplyData.item_name, '', 'Item Code:', supplyData.stock_no, '', '', '', '', '', '', '']);

            // Row 7: Description info
            wsData.push(['Description:', supplyData.description, '', 'Re-order Point:', supplyData.reorder_point, '', '', '', '', '', '', '']);

            // Row 8: Unit of measurement
            wsData.push(['Unit of Measurement:', supplyData.unit_of_measurement, '', '', '', '', '', '', '', '', '', '']);

            // Row 9: Empty
            wsData.push(['', '', '', '', '', '', '', '', '', '', '', '']);

            // Row 10: Main headers
            wsData.push([
                'Date', 'Reference',
                'Receipt', '', '',
                'Issue', '', '',
                'Balance', '', '',
                'No. of Days to Consume'
            ]);

            // Row 11: Sub headers
            wsData.push([
                '', '',
                'Qty.', 'Unit Cost', 'Total Cost',
                'Qty.', 'Unit Cost', 'Total Cost',
                'Qty.', 'Unit Cost', 'Total Cost',
                ''
            ]);

            // Add ledger data
            ledgerEntries.forEach(entry => {
                wsData.push([
                    entry.date ? new Date(entry.date).toLocaleDateString('en-US') : '',
                    entry.reference || '',
                    entry.receipt_qty || '',
                    entry.receipt_unit_cost || '',
                    entry.receipt_total_cost || '',
                    entry.issue_qty || '',
                    entry.issue_unit_cost || '',
                    entry.issue_total_cost || '',
                    entry.balance_qty || '',
                    entry.balance_unit_cost || '',
                    entry.balance_total_cost || '',
                    entry.days_to_consume || ''
                ]);
            });

            // Add empty rows to match the format (minimum 15 data rows)
            const emptyRowsNeeded = Math.max(15 - ledgerEntries.length, 0);
            for (let i = 0; i < emptyRowsNeeded; i++) {
                wsData.push(['', '', '', '', '', '', '', '', '', '', '', '']);
            }

            // Create worksheet
            const ws = XLSX.utils.aoa_to_sheet(wsData);

            // IMPORTANT: Set worksheet range first
            if (!ws['!ref']) {
                const lastRow = wsData.length - 1;
                const lastCol = 11; // L column (0-indexed)
                ws['!ref'] = XLSX.utils.encode_range({s:{r:0,c:0}, e:{r:lastRow,c:lastCol}});
            }

            // Set column widths
            ws['!cols'] = [
                { width: 12 }, // Date
                { width: 20 }, // Reference
                { width: 8 },  // Receipt Qty
                { width: 10 }, // Receipt Unit Cost
                { width: 12 }, // Receipt Total Cost
                { width: 8 },  // Issue Qty
                { width: 10 }, // Issue Unit Cost
                { width: 12 }, // Issue Total Cost
                { width: 8 },  // Balance Qty
                { width: 10 }, // Balance Unit Cost
                { width: 12 }, // Balance Total Cost
                { width: 15 }  // Days to Consume
            ];

            // Merge cells for proper layout
            if (!ws['!merges']) ws['!merges'] = [];

            // Merge title (row 3, columns E to H)
            ws['!merges'].push({s:{r:2,c:4}, e:{r:2,c:7}});

            // Merge Entity Name (row 5, columns A to C)
            ws['!merges'].push({s:{r:4,c:0}, e:{r:4,c:2}});

            // Merge Fund Cluster (row 5, columns D to E)
            ws['!merges'].push({s:{r:4,c:3}, e:{r:4,c:4}});

            // Merge Item name (row 6, columns B to C)
            ws['!merges'].push({s:{r:5,c:1}, e:{r:5,c:2}});

            // Merge Description (row 7, columns B to C)
            ws['!merges'].push({s:{r:6,c:1}, e:{r:6,c:2}});

            // Merge Unit of Measurement (row 8, columns B to C)
            ws['!merges'].push({s:{r:7,c:1}, e:{r:7,c:2}});

            // Merge main header groups (row 10)
            const headerRow = 9; // 0-based index for row 10
            ws['!merges'].push({s:{r:headerRow,c:0}, e:{r:headerRow+1,c:0}}); // Date
            ws['!merges'].push({s:{r:headerRow,c:1}, e:{r:headerRow+1,c:1}}); // Reference
            ws['!merges'].push({s:{r:headerRow,c:2}, e:{r:headerRow,c:4}});   // Receipt
            ws['!merges'].push({s:{r:headerRow,c:5}, e:{r:headerRow,c:7}});   // Issue
            ws['!merges'].push({s:{r:headerRow,c:8}, e:{r:headerRow,c:10}});  // Balance
            ws['!merges'].push({s:{r:headerRow,c:11}, e:{r:headerRow+1,c:11}}); // Days to Consume

            // Helper function to add borders to a range - ENHANCED VERSION
            function addBordersToRange(worksheet, startRow, endRow, startCol, endCol) {
                const borderStyle = {
                    top: {style: "thin"},
                    bottom: {style: "thin"},
                    left: {style: "thin"},
                    right: {style: "thin"}
                };

                for (let r = startRow; r <= endRow; r++) {
                    for (let c = startCol; c <= endCol; c++) {
                        const cellAddress = XLSX.utils.encode_cell({r: r, c: c});

                        // Ensure cell exists with proper value
                        if (!worksheet[cellAddress]) {
                            worksheet[cellAddress] = {v: "", t: "s"};
                        }

                        // Ensure style object exists
                        if (!worksheet[cellAddress].s) {
                            worksheet[cellAddress].s = {};
                        }

                        // Apply border with full specification
                        worksheet[cellAddress].s.border = {
                            top: {style: "thin", color: {auto: 1}},
                            bottom: {style: "thin", color: {auto: 1}},
                            left: {style: "thin", color: {auto: 1}},
                            right: {style: "thin", color: {auto: 1}}
                        };
                    }
                }
            }

            // Apply borders to header info section (rows 5-8, columns A-E)
            addBordersToRange(ws, 4, 7, 0, 4);

            // Apply borders to ledger table (from row 10 to end, all columns)
            const dataRowStart = 9; // Row 10 (0-indexed)
            const dataRowEnd = Math.max(25, dataRowStart + 2 + ledgerEntries.length);
            addBordersToRange(ws, dataRowStart, dataRowEnd, 0, 11);

            // ALTERNATIVE BORDER METHOD - More aggressive approach
            // If the above doesn't work, this should force borders
            const maxRow = Math.max(25, 12 + ledgerEntries.length);

            // Force create all cells in header area with borders
            for (let r = 4; r <= 7; r++) {
                for (let c = 0; c <= 4; c++) {
                    const addr = XLSX.utils.encode_cell({r:r, c:c});
                    if (!ws[addr]) ws[addr] = {v:'', t:'s'};
                    ws[addr].s = ws[addr].s || {};
                    ws[addr].s.border = {
                        top: {style: "thin"},
                        bottom: {style: "thin"},
                        left: {style: "thin"},
                        right: {style: "thin"}
                    };
                }
            }

            // Force create all cells in data area with borders
            for (let r = 9; r <= maxRow; r++) {
                for (let c = 0; c <= 11; c++) {
                    const addr = XLSX.utils.encode_cell({r:r, c:c});
                    if (!ws[addr]) ws[addr] = {v:'', t:'s'};
                    ws[addr].s = ws[addr].s || {};
                    ws[addr].s.border = {
                        top: {style: "thin"},
                        bottom: {style: "thin"},
                        left: {style: "thin"},
                        right: {style: "thin"}
                    };
                }
            }

            // Center align headers and title
            // Title (row 3, column E)
            const titleCell = XLSX.utils.encode_cell({r:2, c:4});
            if (ws[titleCell]) {
                if (!ws[titleCell].s) ws[titleCell].s = {};
                ws[titleCell].s.alignment = {horizontal: 'center', vertical: 'center'};
                ws[titleCell].s.font = {bold: true, size: 14};
            }

            // Header cells formatting
            for (let C = 0; C <= 11; ++C) {
                const headerCell1 = XLSX.utils.encode_cell({r:headerRow, c:C});
                const headerCell2 = XLSX.utils.encode_cell({r:headerRow+1, c:C});

                if (ws[headerCell1]) {
                    if (!ws[headerCell1].s) ws[headerCell1].s = {};
                    ws[headerCell1].s.alignment = {horizontal: 'center', vertical: 'center'};
                    ws[headerCell1].s.font = {bold: true};
                }

                if (ws[headerCell2]) {
                    if (!ws[headerCell2].s) ws[headerCell2].s = {};
                    ws[headerCell2].s.alignment = {horizontal: 'center', vertical: 'center'};
                    ws[headerCell2].s.font = {bold: true};
                }
            }

            // Format header info section labels (make them bold and left-aligned)
            const headerLabels = [
                {r:4, c:0}, // Entity Name
                {r:4, c:3}, // Fund Cluster
                {r:5, c:0}, // Item
                {r:5, c:3}, // Item Code
                {r:6, c:0}, // Description
                {r:6, c:3}, // Re-order Point
                {r:7, c:0}  // Unit of Measurement
            ];

            headerLabels.forEach(pos => {
                const cell_address = XLSX.utils.encode_cell(pos);
                if (ws[cell_address]) {
                    if (!ws[cell_address].s) ws[cell_address].s = {};
                    ws[cell_address].s.font = {bold: true};
                    ws[cell_address].s.alignment = {horizontal: 'left', vertical: 'top'};
                }
            });

            // Format numeric cells
            const range = XLSX.utils.decode_range(ws['!ref']); // Define range here
            for (let R = headerRow + 2; R <= range.e.r; ++R) {
                // Quantity columns (C, F, I)
                [2, 5, 8].forEach(col => {
                    const cell_address = XLSX.utils.encode_cell({r:R, c:col});
                    if (ws[cell_address] && ws[cell_address].v) {
                        if (!ws[cell_address].s) ws[cell_address].s = {};
                        ws[cell_address].s.numFmt = '#,##0.0000';
                        ws[cell_address].s.alignment = {horizontal: 'center'};
                    }
                });

                // Cost columns (D, E, G, H, J, K)
                [3, 4, 6, 7, 9, 10].forEach(col => {
                    const cell_address = XLSX.utils.encode_cell({r:R, c:col});
                    if (ws[cell_address] && ws[cell_address].v) {
                        if (!ws[cell_address].s) ws[cell_address].s = {};
                        ws[cell_address].s.numFmt = '#,##0.0000';
                        ws[cell_address].s.alignment = {horizontal: 'center'};
                    }
                });

                // Date column (A) - center align
                const dateCell = XLSX.utils.encode_cell({r:R, c:0});
                if (ws[dateCell]) {
                    if (!ws[dateCell].s) ws[dateCell].s = {};
                    ws[dateCell].s.alignment = {horizontal: 'center'};
                }

                // Days to consume column (L) - center align
                const daysCell = XLSX.utils.encode_cell({r:R, c:11});
                if (ws[daysCell]) {
                    if (!ws[daysCell].s) ws[daysCell].s = {};
                    ws[daysCell].s.alignment = {horizontal: 'center'};
                }
            }

            // Add worksheet to workbook
            XLSX.utils.book_append_sheet(wb, ws, 'Supply Ledger Card');

            // Generate filename
            const filename = `Supply_Ledger_Card_${supplyData.item_name.replace(/[^a-zA-Z0-9]/g, '_')}_${selectedYear}.xlsx`;

            // Save file
            XLSX.writeFile(wb, filename);

            console.log('Excel file generated successfully');

            } catch (error) {
                console.error('Error generating Excel file:', error);
                alert('Error generating Excel file: ' + error.message);
            }
        }

        // Test function to verify everything is working
        function testExcelExport() {
            console.log('Testing Excel export...');
            if (typeof XLSX !== 'undefined') {
                console.log('✅ SheetJS is loaded and ready');
                alert('Excel export is ready to use!');
            } else {
                console.log('❌ SheetJS is not loaded');
                alert('Excel library is not loaded. Please refresh the page.');
            }
        }

        // Run test when page loads
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Page loaded, testing Excel functionality...');
            setTimeout(function() {
                if (typeof XLSX !== 'undefined') {
                    console.log('✅ Excel export ready');
                } else {
                    console.log('❌ Excel library failed to load');
                }
            }, 1000);
        });
    </script>
</x-app-layout>
