<?php

// ⚠️ DELETE THIS FILE IMMEDIATELY AFTER SUCCESS ⚠️

// Simple protection
$secret = 'RUN_MIGRATE_2026';

if (!isset($_GET['key']) || $_GET['key'] !== $secret) {
    http_response_code(403);
    exit('Access denied');
}

// Load Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';

// Run migrations
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->call('migrate', [
    '--force' => true, // REQUIRED for production
]);

// Output result
echo '<pre>' . $kernel->output() . '</pre>';
