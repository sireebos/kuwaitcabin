<?php
/**
 * Restructure Laravel for Subdomain
 * This script moves public files to root and updates paths
 * Run this once: https://sales-portal.kuwaitcabinhotel.com/imagelink.php
 */

echo "<h2>Laravel Subdomain Restructure</h2>";

// Step 1: Move public files to root (except index.php)
$publicDir = __DIR__ . '/public';
$rootDir = __DIR__;

$filesToMove = [
    'crown-favicon.svg',
    'favicon.ico', 
    'manifest.json',
    'robots.txt'
];

$dirsToMove = [
    'css',
    'storage',
    'build'
];

echo "<h3>Step 1: Moving public files to root</h3>";

// Move individual files
foreach ($filesToMove as $file) {
    $source = $publicDir . '/' . $file;
    $dest = $rootDir . '/' . $file;
    
    if (file_exists($source)) {
        if (copy($source, $dest)) {
            echo "✅ Moved $file<br>";
        } else {
            echo "❌ Failed to move $file<br>";
        }
    }
}

// Move directories
function copyDirectory($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                copyDirectory($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

foreach ($dirsToMove as $dir) {
    $source = $publicDir . '/' . $dir;
    $dest = $rootDir . '/' . $dir;
    
    if (is_dir($source)) {
        copyDirectory($source, $dest);
        echo "✅ Moved directory $dir<br>";
    }
}

// Step 2: Create new index.php in root
echo "<h3>Step 2: Creating new index.php</h3>";

$newIndexContent = '<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define(\'LARAVEL_START\', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.\'/storage/framework/maintenance.php\')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.\'/vendor/autoload.php\';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.\'/bootstrap/app.php\';

$app->handleRequest(Request::capture());
';

if (file_put_contents($rootDir . '/index.php', $newIndexContent)) {
    echo "✅ Created new index.php in root<br>";
} else {
    echo "❌ Failed to create new index.php<br>";
}

// Step 3: Update .htaccess
echo "<h3>Step 3: Updating .htaccess</h3>";

$newHtaccessContent = 'RewriteEngine On

# Security: Block access to sensitive directories and files
RewriteRule ^(app|bootstrap|config|database|resources|routes|storage/app|storage/framework|storage/logs|tests|vendor)/ - [F,L]
RewriteRule ^(\.env|\.env\..*|composer\.json|composer\.lock|artisan)$ - [F,L]

# Allow access to storage/app/public (for uploaded files)
RewriteRule ^storage/app/public/ - [L]

# Handle Laravel routes
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [L]
';

if (file_put_contents($rootDir . '/.htaccess', $newHtaccessContent)) {
    echo "✅ Updated .htaccess<br>";
} else {
    echo "❌ Failed to update .htaccess<br>";
}

// Step 4: Test the setup
echo "<h3>Step 4: Testing Setup</h3>";

if (file_exists($rootDir . '/storage')) {
    echo "✅ Storage directory accessible<br>";
} else {
    echo "❌ Storage directory not found<br>";
}

if (file_exists($rootDir . '/css/volting-design-system.css')) {
    echo "✅ CSS files accessible<br>";
} else {
    echo "❌ CSS files not found<br>";
}

echo "<hr>";
echo "<h3>🎉 Restructure Complete!</h3>";
echo "<p><strong>Your URLs should now work without /public/:</strong></p>";
echo "<ul>";
echo "<li><a href=\"/\">Homepage: https://sales-portal.kuwaitcabinhotel.com/</a></li>";
echo "<li><a href=\"/storage/logos/1K0RInRXtmp8zbo2JyKHAJfgRKbYWio5RVX1Uwbd.png\">Logo: https://sales-portal.kuwaitcabinhotel.com/storage/logos/1K0RInRXtmp8zbo2JyKHAJfgRKbYWio5RVX1Uwbd.png</a></li>";
echo "<li><a href=\"/css/volting-design-system.css\">CSS: https://sales-portal.kuwaitcabinhotel.com/css/volting-design-system.css</a></li>";
echo "</ul>";

echo "<p><strong>Next steps:</strong></p>";
echo "<ol>";
echo "<li>Test all the links above</li>";
echo "<li>Delete this file (restructure_for_subdomain.php) for security</li>";
echo "<li>Delete the old /public directory if everything works</li>";
echo "</ol>";
?>
