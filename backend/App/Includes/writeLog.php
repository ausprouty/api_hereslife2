<?php

/**
 * Write a log entry to a file.
 *
 * This function writes the provided content to a log file. The logging behavior is
 * controlled by the `LOG_MODE` constant. It supports two modes:
 * - `write_log`: Writes the log without a timestamp in the filename.
 * - `write_time_log`: Adds a timestamp to the filename for each log entry.
 *
 * @param string $filename The name of the log file (without extension).
 * @param mixed  $content  The content to log. It will be converted to a string using var_dump.
 * 
 * @return void
 */
function writeLog($filename, $content) {
    if (LOG_MODE !== 'write_log' && LOG_MODE !== 'write_time_log') {
        return;
    }
    $filename = validateFilename($filename);
    if (LOG_MODE == 'write_time_log') {
        $filename = time() . '-' . $filename;
    }
    $text = var_dump_ret($content); // Convert the content to a string
    ensureLogDirectoryExists(); // Ensure the log directory exists
    $filePath = ROOT_LOG . $filename . '.txt';
    if (file_put_contents($filePath, $text) === false) {
        error_log("Failed to write log to $filePath");
    }
}

/**
 * Append content to an existing log file.
 *
 * This function appends the provided content to an existing log file.
 * The log entry is added with a prefix of 'APPEND-' in the filename.
 *
 * @param string $filename The name of the log file (without extension).
 * @param mixed  $content  The content to append. It will be converted to a string using var_dump.
 * 
 * @return void
 */
function writeLogAppend($filename, $content) {
    $filename = validateFilename($filename);
    $text = var_dump_ret($content); // Convert the content to a string
    ensureLogDirectoryExists(); // Ensure the log directory exists
    $filePath = ROOT_LOG . 'APPEND-' . $filename . '.txt';
    if (file_put_contents($filePath, $text, FILE_APPEND | LOCK_EX) === false) {
        error_log("Failed to append log to $filePath");
    }
}

/**
 * Write a debug log entry to a file.
 *
 * This function writes debug content to a log file with the prefix 'DEBUG-' in the filename.
 * 
 * @param string $filename The name of the log file (without extension).
 * @param mixed  $content  The debug content to log.
 * 
 * @return void
 */
function writeLogDebug($filename, $content) {
    $filename = validateFilename($filename);
    $text = var_dump_ret($content); // Convert the content to a string
    ensureLogDirectoryExists(); // Ensure the log directory exists
    $filePath = ROOT_LOG . 'DEBUG-' . $filename . '.txt';
    if (file_put_contents($filePath, $text) === false) {
        error_log("Failed to write debug log to $filePath");
    }
}

/**
 * Write an error log entry to a file.
 *
 * This function writes error content to a log file with the prefix 'ERROR-' in the filename.
 * 
 * @param string $filename The name of the log file (without extension).
 * @param mixed  $content  The error content to log.
 * 
 * @return void
 */
function writeLogError($filename, $content) {
    $filename = validateFilename($filename);
    $text = var_dump_ret($content); // Convert the content to a string
    ensureLogDirectoryExists(); // Ensure the log directory exists
    $filePath = ROOT_LOG . 'ERROR-' . $filename . '.txt';
    if (file_put_contents($filePath, $text) === false) {
        error_log("Failed to write error log to $filePath");
    }
}

/**
 * Convert a variable to a string using var_dump.
 *
 * This helper function captures the output of var_dump and returns it as a string.
 *
 * @param mixed $mixed The variable to dump.
 * 
 * @return string The var_dump output as a string.
 */
function var_dump_ret($mixed = null) {
    ob_start(); // Start output buffering
    var_dump($mixed); // Dump the variable
    $content = ob_get_contents(); // Get the buffered output
    ob_end_clean(); // End buffering and clean it
    return $content;
}

/**
 * Ensure that the log directory exists.
 *
 * This function checks whether the log directory (defined by ROOT_LOG) exists. If it does not,
 * it attempts to create it with the appropriate permissions.
 *
 * @return void
 * 
 * @throws \RuntimeException if the log directory cannot be created.
 */
function ensureLogDirectoryExists() {
    if (!file_exists(ROOT_LOG)) {
        if (!mkdir(ROOT_LOG, 0755, true) && !is_dir(ROOT_LOG)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', ROOT_LOG));
        }
    }
}

/**
 * Validate and sanitize a log filename.
 *
 * This function ensures the provided filename is safe for use by stripping any unwanted characters.
 * If no filename is provided, it generates one using the current timestamp.
 *
 * @param string $filename The filename to validate.
 * 
 * @return string The sanitized filename.
 */
function validateFilename($filename) {
    if (empty($filename)) {
        $filename = 'log-' . time();
    }
    // Sanitize the filename by removing unsafe characters
    return preg_replace('/[^A-Za-z0-9_\-]/', '_', $filename);
}
