<?php

namespace App\Support;

/**
 * Validator for surat status transitions.
 *
 * Allowed flow:
 *   draft > diajukan  (pegawai submit)
 *   diajukan > diproses  (admin process)
 *   diproses > disetujui | ditolak  (pimpinan approve/reject)
 */
class SuratStatusTransition
{
    /**
     * Map of current status > allowed next statuses.
     */
    const TRANSITIONS = [
        'draft' => ['diajukan'],
        'diajukan' => ['diproses'],
        'diproses' => ['disetujui', 'ditolak'],
    ];

    /**
     * Check if a transition from $from to $to is allowed.
     */
    public static function canTransition(string $from, string $to): bool
    {
        return isset(self::TRANSITIONS[$from])
            && in_array($to, self::TRANSITIONS[$from], true);
    }

    /**
     * Get the list of allowed next statuses from a given status.
     * Returns empty array if no transitions are allowed (terminal state).
     *
     * @return string[]
     */
    public static function allowed(string $from): array
    {
        return self::TRANSITIONS[$from] ?? [];
    }

    /**
     * Assert that a transition is valid, throwing an exception if not.
     *
     * @throws \InvalidArgumentException
     */
    public static function assertValid(string $from, string $to): void
    {
        if (!self::canTransition($from, $to)) {
            throw new \InvalidArgumentException(
                "Transisi status tidak valid: '{$from}' > '{$to}'. " .
                "Status yang diizinkan dari '{$from}': [" . implode(', ', self::allowed($from)) . "]"
            );
        }
    }
}
