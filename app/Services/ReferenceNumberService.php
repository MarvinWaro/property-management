<?php

namespace App\Services;

use App\Models\RisSlip;
use App\Models\SupplyTransaction;
use Carbon\Carbon;

class ReferenceNumberService
{
    /**
     * Generate an IAR reference number for stock receipts
     * Format: IAR YYYY-MM-NNN where NNN is a sequential number per month
     */
    public function generateIarNumber(int $supply_id): string
    {
        $today = Carbon::now();
        $year = $today->format('Y');
        $month = $today->format('m');

        // Find the last IAR number for the current month/year
        $lastIar = SupplyTransaction::where('transaction_type', 'receipt')
            ->where('reference_no', 'like', "IAR {$year}-{$month}-%")
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
     * Generate a unique RIS number in the format "RIS YYYY-MM-NNN"
     * where NNN is sequential per month
     */
    public function generateRisNumber(): string
    {
        $today = Carbon::now();
        $yearMonth = $today->format('Y-m');
        $prefix = "RIS {$yearMonth}-";

        // Always get the highest existing number for this month
        $lastRisSlip = RisSlip::where('ris_no', 'like', "$prefix%")
            ->orderByRaw("CAST(SUBSTRING(ris_no, LENGTH('$prefix') + 1) AS UNSIGNED) DESC")
            ->first();

        if ($lastRisSlip) {
            // Parse last number
            $lastNumber = intval(substr($lastRisSlip->ris_no, strlen($prefix)));
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }

        return $prefix . $nextNumber;
    }

    /**
     * Check if an RIS number already exists, if so return it.
     * If not, generate a new one.
     */
    public function getOrCreateRisNumber(int $risId = null): string
    {
        if ($risId) {
            $existingRis = RisSlip::find($risId);
            if ($existingRis && $existingRis->ris_no) {
                return $existingRis->ris_no;
            }
        }

        return $this->generateRisNumber();
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
