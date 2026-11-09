<?php

/**
 * KAN-12 fase 2: finestre mail:renew (prima hardcodate in SendRenewals).
 * Invite = prima email; reminder = solleciti a giorni esatti dalla scadenza.
 */
return [
    'invite_days_before' => (int) env('RENEWAL_INVITE_DAYS_BEFORE', 30),
    'invite_lookback_days' => (int) env('RENEWAL_INVITE_LOOKBACK_DAYS', 60),
    /** match mail:renew switch (log “7 days” usa 6) */
    'reminder_days_before' => (int) env('RENEWAL_REMINDER_DAYS_BEFORE', 6),
    'reminder_days_after' => [7, 14, 26],
];
