<?php

namespace App\Services;

use App\Models\SupplyTransaction;
use App\Models\RisSlip;
use Carbon\Carbon;

class ReferenceNumberService
{
    /**
     * Generate an IAR reference number for stock receipts
     * Format: IAR YYYY-MM-NNN where NNN is a sequential number per supply
     */
    public function generateIarNumber(int $supply_id): string
    {
        $today = Carbon::now();
        $year = $today->format('Y');
        $month = $today->format('m');

        // Find the last IAR number for this specific supply (without year/month filter)
        $lastIar = SupplyTransaction::where('supply_id', $supply_id)
            ->where('reference_no', 'like', "IAR %")
            ->orderBy('created_at', 'desc')
            ->first();

        $nextNumber = 1;

        if ($lastIar) {
            // Extract the number part from the last IAR
            $parts = explode('-', $lastIar->reference_no);
            if (count($parts) >= 3) {
                $lastNumber = intval($parts[2]);
                $nextNumber = $lastNumber + 1;
            }
        }

        // Format with leading zeros (001, 002, etc.)
        return "IAR {$year}-{$month}-" . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate a RIS reference number with continuous numbering across years
     * Format: RIS-YYYYMM-NNNN where NNNN is a continuous sequence
     */
    public function generateRisNumber(): string
    {
        $today = Carbon::now();
        $year = $today->format('Y');
        $month = $today->format('m');

        // Find the last RIS without filtering by year/month
        $latestRis = RisSlip::where('ris_no', 'like', 'RIS-%')
                           ->orderBy('ris_id', 'desc')
                           ->first();

        $nextNumber = 1;

        if ($latestRis) {
            // Extract the number part from the last RIS
            $parts = explode('-', $latestRis->ris_no);
            if (count($parts) >= 3) {
                $lastNumber = intval($parts[2]);
                $nextNumber = $lastNumber + 1;
            }
        }

        // Format with leading zeros (0001, 0002, etc.)
        return 'RIS-' . $year . $month . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Check if we're at the beginning of a new year
     */
    public function isNewYear(): bool
    {
        $today = Carbon::now();
        return $today->month == 1 && $today->day <= 15;
    }
}
