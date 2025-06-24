<?php

namespace App\Constants;

class RisStatus
{
    public const DRAFT = 'draft';
    public const APPROVED = 'approved';
    public const POSTED = 'posted';
    public const DECLINED = 'declined';

    /**
     * Get all available statuses
     */
    public static function all(): array
    {
        return [
            self::DRAFT,
            self::APPROVED,
            self::POSTED,
            self::DECLINED,
        ];
    }

    /**
     * Get status labels for display
     */
    public static function labels(): array
    {
        return [
            self::DRAFT => 'Draft',
            self::APPROVED => 'Approved',
            self::POSTED => 'Posted/Issued',
            self::DECLINED => 'Declined',
        ];
    }

    /**
     * Get display label for a status
     */
    public static function getLabel(string $status): string
    {
        return self::labels()[$status] ?? ucfirst($status);
    }

    /**
     * Check if status can be approved
     */
    public static function canBeApproved(string $status): bool
    {
        return $status === self::DRAFT;
    }

    /**
     * Check if status can be declined
     */
    public static function canBeDeclined(string $status): bool
    {
        return $status === self::DRAFT;
    }

    /**
     * Check if status can be issued
     */
    public static function canBeIssued(string $status): bool
    {
        return $status === self::APPROVED;
    }

    /**
     * Check if RIS is completed
     */
    public static function isCompleted(string $status): bool
    {
        return $status === self::POSTED;
    }

    /**
     * Check if status is final (cannot be changed)
     */
    public static function isFinal(string $status): bool
    {
        return in_array($status, [self::POSTED, self::DECLINED]);
    }

    /**
     * Get CSS class for status badge
     */
    public static function getBadgeClass(string $status): string
    {
        return match($status) {
            self::DRAFT => 'bg-gray-200 text-gray-800',
            self::APPROVED => 'bg-blue-200 text-blue-800',
            self::POSTED => 'bg-green-200 text-green-800',
            self::DECLINED => 'bg-red-200 text-red-800',
            default => 'bg-gray-200 text-gray-800',
        };
    }
}
