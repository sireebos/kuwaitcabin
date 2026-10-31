<?php
/**
 * Standalone Migration Runner
 * Delete this file immediately after use!
 */

// Prevent any error pages from loading
ini_set('display_errors', 0);
error_reporting(0);

header('Content-Type: text/plain; charset=utf-8');

echo "=== Kuwait Cabin Hotel - Migration Runner ===\n\n";

try {
    // Change to app root directory
    chdir(__DIR__ . '/..');
    
    // Execute artisan migrate via command line
    $output = [];
    $returnCode = 0;
    
    exec('php artisan migrate --force 2>&1', $output, $returnCode);
    
    echo implode("\n", $output);
    echo "\n\n";
    
    if ($returnCode === 0) {
        echo "✅ Migrations completed successfully!\n";
    } else {
        echo "⚠️ Migration returned code: {$returnCode}\n";
    }
    
} catch (Throwable $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n⚠️ DELETE THIS FILE NOW!\n";