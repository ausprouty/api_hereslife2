<?php

/*
TimeService

Purpose: Provides time-related utilities to manage and format time calculations consistently across the application.
Responsibilities:
    Calculate time thresholds (e.g., 30 minutes ago, 7 days ago).
    Handle timezone settings.

*/

class TimeService {
    public function __construct() {
        date_default_timezone_set('UTC');
    }

    public function getTimeThreshold($minutesAgo) {
        return strtotime("- $minutesAgo minutes");
    }

    public function getDaysAgo($days) {
        return strtotime("- $days days");
    }
}