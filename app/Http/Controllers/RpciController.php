<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\SupplyTransaction;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;

class RpciController extends Controller
{
    public function index(Request $request)
    {
        $year        = $request->get('year', now()->year);
        $semester    = $request->get('semester', 1);
        $fundCluster = $request->get('fund_cluster', '');

        // <<< Add this line to generate a list of years >>>
        $years       = range(now()->year, now()->year - 4);

        $fundClusters = ['101','151'];

        return view('rpci.index', compact(
            'year',
            'semester',
            'fundCluster',
            'fundClusters',
            'years'         // <<< And make sure it's in the compact >>>
        ));
    }

    public function exportExcel(Request $request)
    {
        $data = $request->validate([
            'year'        => 'required|integer',
            'semester'    => 'required|in:1,2',
            'fund_cluster'=> 'nullable|in:101,151',
        ]);

        // determine cutoff date
        $endMonth = $data['semester']==1 ? 6 : 12;
        $endDay   = $data['semester']==1 ? 30 : 31;
        $endDate  = Carbon::create($data['year'], $endMonth, $endDay)->endOfDay();

        // balance per card up to cutoff
        $balances = SupplyTransaction::selectRaw("
                supply_id,
                SUM(
                  CASE transaction_type WHEN 'receipt' THEN quantity ELSE -quantity END
                ) as balance_qty
            ")
            ->when($data['fund_cluster'], fn($q) => $q->where('fund_cluster',$data['fund_cluster']))
            ->where('transaction_date','<=',$endDate)
            ->groupBy('supply_id')
            ->pluck('balance_qty','supply_id');

        // load only the supplies that have a balance
        $supplies = Supply::whereIn('supply_id', $balances->keys())->get();

        // load our Excel template
        $template    = storage_path('app/templates/Appendix 66 - RPCI.xls');
        $spreadsheet = IOFactory::load($template);
        $sheet       = $spreadsheet->getActiveSheet();

        // header tweaks
        $sheet->setCellValue('H1', 'Appendix 66');
        $sheet->setCellValue('C3', 'REPORT ON THE PHYSICAL COUNT OF INVENTORIES');
        $sheet->setCellValue('E4', 'As at '.$endDate->format('F d, Y'));
        $sheet->setCellValue('B6', 'Fund Cluster: '.($data['fund_cluster']?:'All'));

        // fill rows starting at row 10
        $startRow = 10;
        foreach ($supplies as $i => $supply) {
            $r = $startRow + $i;
            $sheet->setCellValue("A{$r}", $i+1);                                      // Article #
            $sheet->setCellValue("B{$r}", $supply->item_name);                        // Description
            $sheet->setCellValue("C{$r}", $supply->stock_no);                         // Stock No
            $sheet->setCellValue("D{$r}", $supply->unit_of_measurement);              // Unit
            $sheet->setCellValue("E{$r}", $supply->unit_cost);                        // Unit Value
            $sheet->setCellValue("F{$r}", $balances[$supply->supply_id] ?? 0);        // Balance per card
            // G, H, I… left blank
        }

        // autosize columns A–I
        foreach (range('A','I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // stream download
        return response()->streamDownload(function() use($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
        }, "RPCI-{$data['year']}-S{$data['semester']}.xlsx", [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);
    }
}
