<?php
/**
 * Storage Link Setup
 * This file MUST be in /public/ folder
 * Visit: https://sales-portal.kuwaitcabinhotel.com/fix-storage.php
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Storage Link Setup</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        p { line-height: 1.8; }
        code { background: #f0f0f0; padding: 4px 8px; border-radius: 3px; font-family: monospace; }
        .success { color: #27ae60; font-weight: bold; }
        .error { color: #e74c3c; font-weight: bold; }
        .warning { color: #f39c12; font-weight: bold; }
        hr { margin: 20px 0; border: none; border-top: 1px solid #ddd; }
        ol { margin: 15px 0; padding-left: 20px; }
        li { margin: 8px 0; }
    </style>
</head>
<body>
<div class="container">

<h2>🔗 Storage Link Setup</h2>

<?php
// Paths relative to this file (/public/fix-storage.php)
$publicDir = __DIR__;  // /public
$baseDir = dirname($publicDir);  // /
$publicStoragePath = $publicDir . '/storage';
$storageAppPublicPath = $baseDir . '/storage/app/public';

echo "<p><strong>Configuration:</strong></p>";
echo "<p>Base: <code>" . $baseDir . "</code></p>";
echo "<p>Public: <code>" . $publicDir . "</code></p>";
echo "<p>Target: <code>" . $storageAppPublicPath . "</code></p>";

// Check source exists
if (!is_dir($storageAppPublicPath)) {
    echo "<p class='error'>❌ FAILED: storage/app/public not found at " . $storageAppPublicPath . "</p>";
    echo "</div></body></html>";
    exit;
}

echo "<p class='success'>✅ Source directory exists</p>";

// Remove existing
if (file_exists($publicStoragePath)) {
    if (is_link($publicStoragePath)) {
        unlink($publicStoragePath);
        echo "<p class='warning'>🔗 Removed old symlink</p>";
    } elseif (is_dir($publicStoragePath)) {
        // Recursive delete
        $it = new RecursiveDirectoryIterator($publicStoragePath, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($publicStoragePath);
        echo "<p class='warning'>📁 Removed old directory</p>";
    }
}

// Try symlink
$success = false;
if (@symlink($storageAppPublicPath, $publicStoragePath)) {
    echo "<p class='success'>✅ Symlink created successfully!</p>";
    $success = true;
} else {
    // Fallback: copy files
    echo "<p class='warning'>⚠️ Symlink failed, copying files...</p>";
    
    @mkdir($publicStoragePath, 0755, true);
    $it = new RecursiveDirectoryIterator($storageAppPublicPath, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::SELF_FIRST);
    
    foreach ($files as $file) {
        $rel = substr($file->getRealPath(), strlen($storageAppPublicPath) + 1);
        if ($file->isDir()) {
            @mkdir($publicStoragePath . '/' . $rel, 0755, true);
        } else {
            @copy($file->getRealPath(), $publicStoragePath . '/' . $rel);
        }
    }
    
    if (is_dir($publicStoragePath)) {
        echo "<p class='success'>✅ Files copied successfully!</p>";
        $success = true;
    }
}

// Verify
if ($success) {
    echo "<p class='success'><strong>✅ SUCCESS!</strong></p>";
    echo "<p>Images are now accessible at:</p>";
    echo "<ul>";
    echo "<li><code>/storage/uploads/filename.jpg</code></li>";
    echo "<li><code>/storage/logos/filename.png</code></li>";
    echo "</ul>";
} else {
    echo "<p class='error'><strong>❌ Failed to create storage link</strong></p>";
}

?>

<hr>

<p><strong>Now:</strong></p>
<ol>
    <li>Test your images load at <code>/storage/uploads/</code></li>
    <li><strong>Delete this file</strong> (fix-storage.php) for security</li>
    <li>Visit <code>/imagelink.php</code> to upload images</li>
</ol>

</div>
</body>
</html>
