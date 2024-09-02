<?php

function writeLog($filename, $content) {
    if (LOG_MODE !== 'write_log' && LOG_MODE !== 'write_time_log') {
        return;
    }
    $filename = validateFilename($filename);
    if (LOG_MODE == 'write_time_log') {
        $filename = time() . '-' . $filename;
    }
    $text = var_dump_ret($content);
    ensureLogDirectoryExists();
    $filePath = ROOT_LOG . $filename . '.txt';
    if (file_put_contents($filePath, $text) === false) {
        error_log("Failed to write log to $filePath");
    }
}

function writeLogAppend($filename, $content) {
    $filename = validateFilename($filename);
    $text = var_dump_ret($content);
    ensureLogDirectoryExists();
    $filePath = ROOT_LOG . 'APPEND-' . $filename . '.txt';
    if (file_put_contents($filePath, $text, FILE_APPEND | LOCK_EX) === false) {
        error_log("Failed to append log to $filePath");
    }
}

function writeLogDebug($filename, $content) {
    $filename = validateFilename($filename);
    $text = var_dump_ret($content);
    ensureLogDirectoryExists();
    $filePath = ROOT_LOG . 'DEBUG-' . $filename . '.txt';
    if (file_put_contents($filePath, $text) === false) {
        error_log("Failed to write debug log to $filePath");
    }
}

function writeLogError($filename, $content) {
    $filename = validateFilename($filename);
    $text = var_dump_ret($content);
    ensureLogDirectoryExists();
    $filePath = ROOT_LOG . 'ERROR-' . $filename . '.txt';
    if (file_put_contents($filePath, $text) === false) {
        error_log("Failed to write error log to $filePath");
    }
}

function var_dump_ret($mixed = null) {
    ob_start();
    var_dump($mixed);
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

function ensureLogDirectoryExists() {
    if (!file_exists(ROOT_LOG)) {
        if (!mkdir(ROOT_LOG, 0755, true) && !is_dir(ROOT_LOG)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', ROOT_LOG));
        }
    }
}

function validateFilename($filename) {
    if (empty($filename)) {
        $filename = 'log-' . time();
    }
    return preg_replace('/[^A-Za-z0-9_\-]/', '_', $filename); // Sanitize filename
}
