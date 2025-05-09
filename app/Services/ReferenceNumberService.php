<?php

namespace App\Services;

use App\Models\SupplyTransaction;
use App\Models\RisSlip;
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
     * Generate a RIS reference number for a specific month/year
     * Format: RIS YYYY-MM-NNN where NNN is sequential per month
     */
    public function generateRisNumber(): string
    {
        $today = Carbon::now();
        $year = $today->format('Y');
        $month = $today->format('m');

        // First check for RIS transactions in this format (RIS YYYY-MM-NNN)
        $latestRis = SupplyTransaction::where('transaction_type', 'issue')
            ->where('reference_no', 'like', "RIS {$year}-{$month}-%")
            ->orderBy('created_at', 'desc')
            ->first();

        // If no transactions found, check RIS slips
        if (!$latestRis) {
            $latestRisSlip = RisSlip::where('ris_no', 'like', "RIS {$year}-{$month}-%")
                ->orderBy('created_at', 'desc')
                ->first();

            if ($latestRisSlip) {
                $parts = explode('-', $latestRisSlip->ris_no);
                if (count($parts) >= 3) {
                    $nextNumber = intval($parts[2]) + 1;
                } else {
                    $nextNumber = 1;
                }
            } else {
                $nextNumber = 1;
            }
        } else {
            // Extract the number part from the last RIS transaction
            $parts = explode('-', $latestRis->reference_no);
            if (count($parts) >= 3) {
                $nextNumber = intval($parts[2]) + 1;
            } else {
                $nextNumber = 1;
            }
        }

        // Format with leading zeros (001, 002, etc.)
        return "RIS {$year}-{$month}-" . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Check if an RIS number already exists, if so return it.
     * If not, generate a new one.
     * This helps prevent duplicate RIS numbers for the same RIS slip.
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
