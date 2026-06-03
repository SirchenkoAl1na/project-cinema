<?php
require_once __DIR__ . '/App/Data.php';
require_once __DIR__ . '/App/DB.php';
use App\DB;

try {
    DB::query("ALTER TABLE `tickets` 
        ADD COLUMN `qr_token` VARCHAR(64) NULL,
        ADD COLUMN `qr_status` VARCHAR(16) NOT NULL DEFAULT 'pending',
        ADD COLUMN `scanned_at` DATETIME NULL,
        ADD COLUMN `scanned_by_name` VARCHAR(128) NULL,
        ADD UNIQUE INDEX (`qr_token`);");
    echo "Success!";
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Already exists.";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
