<?php
/**
 * Fix Storage Link Script
 * Upload this file to your server root and run it via browser
 * URL: https://sales-portal.kuwaitcabinhotel.com/fix_storage_link.php
 */

echo "<h2>Laravel Storage Link Fix</h2>";

// Get the paths
$publicStoragePath = __DIR__ . '/public/storage';
$storageAppPublicPath = __DIR__ . '/storage/app/public';

echo "<p><strong>Public storage path:</strong> " . $publicStoragePath . "</p>";
echo "<p><strong>Storage app public path:</strong> " . $storageAppPublicPath . "</p>";

// Check if storage/app/public exists
if (!is_dir($storageAppPublicPath)) {
    echo "<p style='color: red;'>❌ Error: storage/app/public directory does not exist!</p>";
    exit;
}

// Remove existing public/storage if it exists
if (file_exists($publicStoragePath)) {
    if (is_link($publicStoragePath)) {
        unlink($publicStoragePath);
        echo "<p style='color: orange;'>🔗 Removed existing symlink</p>";
    } elseif (is_dir($publicStoragePath)) {
        // Remove directory and its contents
        function removeDirectory($dir) {
            if (is_dir($dir)) {
                $objects = scandir($dir);
                foreach ($objects as $object) {
                    if ($object != "." && $object != "..") {
                        if (is_dir($dir . "/" . $object)) {
                            removeDirectory($dir . "/" . $object);
                        } else {
                            unlink($dir . "/" . $object);
                        }
                    }
                }
                rmdir($dir);
            }
        }
        removeDirectory($publicStoragePath);
        echo "<p style='color: orange;'>📁 Removed existing directory</p>";
    }
}

// Create the symlink
if (symlink($storageAppPublicPath, $publicStoragePath)) {
    echo "<p style='color: green;'>✅ Storage link created successfully!</p>";
    
    // Test the link
    if (is_link($publicStoragePath) && is_dir($publicStoragePath)) {
        echo "<p style='color: green;'>✅ Storage link is working correctly!</p>";
        
        // Test access to the specific file
        $testFile = $publicStoragePath . '/logos/1K0RInRXtmp8zbo2JyKHAJfgRKbYWio5RVX1Uwbd.png';
        if (file_exists($testFile)) {
            echo "<p style='color: green;'>✅ Your logo file is now accessible!</p>";
            echo "<p><strong>File URL:</strong> <a href='/storage/logos/1K0RInRXtmp8zbo2JyKHAJfgRKbYWio5RVX1Uwbd.png' target='_blank'>https://sales-portal.kuwaitcabinhotel.com/storage/logos/1K0RInRXtmp8zbo2JyKHAJfgRKbYWio5RVX1Uwbd.png</a></p>";
        } else {
            echo "<p style='color: orange;'>⚠️ Logo file not found, but storage link is working</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Storage link created but not working properly</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Failed to create storage link. Error: " . error_get_last()['message'] . "</p>";
    
    // Alternative: Copy files instead of symlink
    echo "<h3>Alternative: Copying files instead of symlink</h3>";
    
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
    
    try {
        copyDirectory($storageAppPublicPath, $publicStoragePath);
        echo "<p style='color: green;'>✅ Files copied successfully as fallback!</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Failed to copy files: " . $e->getMessage() . "</p>";
    }
}

echo "<hr>";
echo "<p><strong>Next steps:</strong></p>";
echo "<ol>";
echo "<li>Delete this file (fix_storage_link.php) for security</li>";
echo "<li>Test your image URL: <a href='/storage/logos/1K0RInRXtmp8zbo2JyKHAJfgRKbYWio5RVX1Uwbd.png' target='_blank'>https://sales-portal.kuwaitcabinhotel.com/storage/logos/1K0RInRXtmp8zbo2JyKHAJfgRKbYWio5RVX1Uwbd.png</a></li>";
echo "</ol>";
?>
