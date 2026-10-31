<?php
/**
 * Image Upload & Gallery Manager
 * Upload and display images for sales-portal.kuwaitcabinhotel.com
 * Access: https://sales-portal.kuwaitcabinhotel.com/imagelink.php
 * 
 * STANDALONE PHP FILE - NO LARAVEL DEPENDENCIES
 */

// Prevent any Laravel/framework loading
define('STANDALONE_PHP', true);

// Configuration
$UPLOAD_DIR = __DIR__ . '/public/uploads/';
$ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB

// Create uploads directory if it doesn't exist
if (!is_dir($UPLOAD_DIR)) {
    @mkdir($UPLOAD_DIR, 0755, true);
}

$message = '';
$error = '';
$images = [];

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    try {
        $file = $_FILES['image'];
        
        // Validate file
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Upload error: ' . getUploadErrorMessage($file['error']));
        }
        
        if ($file['size'] > $MAX_FILE_SIZE) {
            throw new Exception('File size exceeds maximum limit of 5MB');
        }
        
        // Get file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $ALLOWED_EXTENSIONS)) {
            throw new Exception('Invalid file type. Allowed: ' . implode(', ', $ALLOWED_EXTENSIONS));
        }
        
        // Validate MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($mime, $allowed_mimes)) {
            throw new Exception('Invalid MIME type: ' . $mime);
        }
        
        // Generate unique filename
        $timestamp = time();
        $random = bin2hex(random_bytes(8));
        $filename = $timestamp . '_' . $random . '.' . $extension;
        $filepath = $UPLOAD_DIR . $filename;
        
        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            throw new Exception('Failed to move uploaded file');
        }
        
        // Set proper permissions
        chmod($filepath, 0644);
        
        // Get protocol
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $image_url = $protocol . '://' . $host . '/uploads/' . $filename;
        
        $message = '✅ Image uploaded successfully!<br>';
        $message .= '<strong>Image URL:</strong><br>';
        $message .= '<input type="text" value="' . $image_url . '" readonly style="width: 100%; padding: 8px; margin-top: 5px;" onclick="this.select()">';
        
    } catch (Exception $e) {
        $error = '❌ Error: ' . $e->getMessage();
    }
}

// Handle image deletion
if (isset($_GET['delete']) && $_GET['delete'] !== '') {
    $filename = basename($_GET['delete']);
    $filepath = $UPLOAD_DIR . $filename;
    
    // Security check
    if (realpath($filepath) && strpos(realpath($filepath), realpath($UPLOAD_DIR)) === 0 && file_exists($filepath)) {
        if (unlink($filepath)) {
            $message = '✅ Image deleted successfully!';
        } else {
            $error = '❌ Failed to delete image';
        }
    } else {
        $error = '❌ Invalid file path';
    }
}

// Get all uploaded images
if (is_dir($UPLOAD_DIR)) {
    $files = @array_diff(@scandir($UPLOAD_DIR), ['.', '..']);
    if ($files === false) {
        $files = [];
    }
    
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    
    foreach ($files as $file) {
        $filepath = $UPLOAD_DIR . $file;
        if (is_file($filepath) && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $ALLOWED_EXTENSIONS)) {
            $images[] = [
                'filename' => $file,
                'url' => $protocol . '://' . $host . '/public/uploads/' . $file,
                'size' => @filesize($filepath),
                'uploaded_at' => date('Y-m-d H:i:s', @filemtime($filepath))
            ];
        }
    }
}

// Sort by most recent first
usort($images, function($a, $b) {
    return strtotime($b['uploaded_at']) - strtotime($a['uploaded_at']);
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload & Gallery - Kuwait Cabin Hotel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px 20px;
        }
        
        .upload-section {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 40px;
            border: 2px dashed #667eea;
        }
        
        .upload-section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.5em;
        }
        
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }
        
        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }
        
        .file-label {
            display: block;
            padding: 20px;
            background: white;
            border: 2px solid #667eea;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
            color: #667eea;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .file-label:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }
        
        .file-input-wrapper input[type=file]:focus + .file-label {
            outline: 2px solid #667eea;
            outline-offset: 2px;
        }
        
        .submit-btn {
            width: 100%;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .submit-btn:active {
            transform: translateY(0);
        }
        
        .message {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .gallery-section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.5em;
        }
        
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .gallery-item {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }
        
        .gallery-item-info {
            padding: 15px;
        }
        
        .gallery-item-url {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
            word-break: break-all;
            font-size: 0.85em;
            color: #667eea;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .gallery-item-url:hover {
            background: #e9ecef;
        }
        
        .gallery-item-meta {
            font-size: 0.85em;
            color: #666;
            margin-bottom: 10px;
        }
        
        .gallery-item-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-small {
            flex: 1;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
            font-weight: 600;
            transition: all 0.2s;
        }
        
        .btn-copy {
            background: #667eea;
            color: white;
        }
        
        .btn-copy:hover {
            background: #5568d3;
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        
        .btn-delete:hover {
            background: #c82333;
        }
        
        .empty-gallery {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }
        
        .empty-gallery svg {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .info-box strong {
            color: #667eea;
        }
        
        @media (max-width: 768px) {
            .gallery {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 1.8em;
            }
            
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🖼️ Image Gallery Manager</h1>
            <p>Upload and manage images for Kuwait Cabin Hotel</p>
        </div>
        
        <div class="content">
            <!-- Messages -->
            <?php if ($message): ?>
                <div class="message success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <!-- Upload Section -->
            <div class="upload-section">
                <h2>📤 Upload New Image</h2>
                
                <div class="info-box">
                    <strong>ℹ️ Allowed formats:</strong> JPG, JPEG, PNG, GIF, WebP (Max 5MB)
                </div>
                
                <form method="POST" enctype="multipart/form-data">
                    <div class="file-input-wrapper">
                        <input type="file" id="imageInput" name="image" accept="image/*" required>
                        <label for="imageInput" class="file-label">
                            📁 Click to select image or drag & drop
                        </label>
                    </div>
                    <button type="submit" class="submit-btn">🚀 Upload Image</button>
                </form>
            </div>
            
            <!-- Gallery Section -->
            <div class="gallery-section">
                <h2>📸 Image Gallery (<?php echo count($images); ?> images)</h2>
                
                <?php if (count($images) > 0): ?>
                    <div class="gallery">
                        <?php foreach ($images as $image): ?>
                            <div class="gallery-item">
                                <img src="<?php echo htmlspecialchars($image['url']); ?>" alt="Gallery image" loading="lazy">
                                <div class="gallery-item-info">
                                    <div class="gallery-item-url" title="Click to copy" onclick="copyToClipboard('<?php echo htmlspecialchars($image['url']); ?>')">
                                        <?php echo htmlspecialchars($image['url']); ?>
                                    </div>
                                    <div class="gallery-item-meta">
                                        📅 <?php echo $image['uploaded_at']; ?><br>
                                        💾 <?php echo formatBytes($image['size']); ?>
                                    </div>
                                    <div class="gallery-item-actions">
                                        <button class="btn-small btn-copy" onclick="copyToClipboard('<?php echo htmlspecialchars($image['url']); ?>')">
                                            📋 Copy
                                        </button>
                                        <a href="?delete=<?php echo urlencode($image['filename']); ?>" class="btn-small btn-delete" onclick="return confirm('Delete this image?')">
                                            🗑️ Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-gallery">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                        <h3>No images yet</h3>
                        <p>Upload an image to get started!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('✅ Image URL copied to clipboard!');
            }, function(err) {
                console.error('Failed to copy:', err);
            });
        }
        
        // Drag and drop support
        const fileInput = document.getElementById('imageInput');
        const fileLabel = document.querySelector('.file-label');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileLabel.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            fileLabel.addEventListener(eventName, () => {
                fileLabel.style.background = '#667eea';
                fileLabel.style.color = 'white';
            });
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            fileLabel.addEventListener(eventName, () => {
                fileLabel.style.background = 'white';
                fileLabel.style.color = '#667eea';
            });
        });
        
        fileLabel.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
        });
    </script>
</body>
</html>

<?php
/**
 * Helper Functions
 */
function getUploadErrorMessage($error_code) {
    $errors = [
        UPLOAD_ERR_OK => 'No error',
        UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
        UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
        UPLOAD_ERR_PARTIAL => 'File partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file',
        UPLOAD_ERR_EXTENSION => 'File upload blocked by extension'
    ];
    return $errors[$error_code] ?? 'Unknown error';
}

function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    return round($bytes, $precision) . ' ' . $units[$pow];
}
?>
