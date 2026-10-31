<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use ZipArchive;

class SystemInfoController extends Controller
{
    /**
     * Current system version
     */
    const VERSION = '1.0.0';
    const VERSION_DATE = '2026-01-26';

    /**
     * Display system information
     */
    public function index()
    {
        $systemInfo = [
            'app_name' => config('app.name'),
            'app_version' => self::VERSION,
            'version_date' => self::VERSION_DATE,
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'server_os' => PHP_OS,
            'database_driver' => config('database.default'),
            'database_version' => $this->getDatabaseVersion(),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'debug_mode' => config('app.debug') ? 'Enabled' : 'Disabled',
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_driver' => config('queue.default'),
            'mail_driver' => config('mail.default'),
        ];

        $diskInfo = [
            'total_space' => $this->formatBytes(disk_total_space(base_path())),
            'free_space' => $this->formatBytes(disk_free_space(base_path())),
            'used_space' => $this->formatBytes(disk_total_space(base_path()) - disk_free_space(base_path())),
            'storage_path' => storage_path(),
            'storage_writable' => is_writable(storage_path()),
            'cache_path' => storage_path('framework/cache'),
            'cache_writable' => is_writable(storage_path('framework/cache')),
        ];

        $databaseInfo = [
            'total_tables' => $this->getTableCount(),
            'total_users' => \App\Models\User::count(),
            'total_properties' => \App\Models\Property::count(),
            'total_bookings' => \App\Models\Booking::count(),
            'total_guests' => \App\Models\Guest::count(),
        ];

        $pendingMigrations = $this->getPendingMigrations();
        $updateHistory = $this->getUpdateHistory();

        return view('system.info', compact('systemInfo', 'diskInfo', 'databaseInfo', 'pendingMigrations', 'updateHistory'));
    }

    /**
     * Handle system update upload
     */
    public function update(Request $request)
    {
        $request->validate([
            'update_file' => 'required|file|mimes:zip|max:102400', // Max 100MB
        ]);

        try {
            $file = $request->file('update_file');
            $updatePath = storage_path('app/updates');
            $extractPath = storage_path('app/updates/extracted');
            $backupPath = storage_path('app/backups/' . date('Y-m-d_H-i-s'));

            // Create directories if they don't exist
            File::ensureDirectoryExists($updatePath);
            File::ensureDirectoryExists($extractPath);
            File::ensureDirectoryExists($backupPath);

            // Save uploaded file
            $filename = 'update_' . date('Y-m-d_H-i-s') . '.zip';
            $file->move($updatePath, $filename);
            $zipPath = $updatePath . '/' . $filename;

            // Extract ZIP file
            $zip = new ZipArchive;
            if ($zip->open($zipPath) !== TRUE) {
                return back()->with('error', 'Failed to open update package.');
            }

            // Clean extract path
            File::deleteDirectory($extractPath);
            File::ensureDirectoryExists($extractPath);

            $zip->extractTo($extractPath);
            $zip->close();

            // Check for update manifest
            $manifestPath = $extractPath . '/update-manifest.json';
            $manifest = null;
            if (File::exists($manifestPath)) {
                $manifest = json_decode(File::get($manifestPath), true);
            }

            // Backup critical files before update
            $this->backupFiles($backupPath);

            // Apply file updates
            $updatedFiles = $this->applyFileUpdates($extractPath);

            // Run migrations if any
            $migrationOutput = '';
            if ($request->input('run_migrations', true)) {
                Artisan::call('migrate', ['--force' => true]);
                $migrationOutput = Artisan::output();
            }

            // Clear all caches
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            // Log the update
            $this->logUpdate($manifest, $updatedFiles, $migrationOutput);

            // Clean up
            File::deleteDirectory($extractPath);

            $message = 'System updated successfully!';
            if (!empty($updatedFiles)) {
                $message .= ' Updated ' . count($updatedFiles) . ' files.';
            }
            if (!empty($migrationOutput)) {
                $message .= ' Migrations executed.';
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    /**
     * Run pending migrations
     */
    public function runMigrations()
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            $output = Artisan::output();

            return back()->with('success', 'Migrations executed successfully. ' . $output);
        } catch (\Exception $e) {
            return back()->with('error', 'Migration failed: ' . $e->getMessage());
        }
    }

    /**
     * Clear all caches
     */
    public function clearCache()
    {
        try {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            Artisan::call('optimize:clear');

            return back()->with('success', 'All caches cleared successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Cache clear failed: ' . $e->getMessage());
        }
    }

    /**
     * Backup files before update
     */
    private function backupFiles($backupPath)
    {
        // Backup .env file
        if (File::exists(base_path('.env'))) {
            File::copy(base_path('.env'), $backupPath . '/.env');
        }

        // Backup database config
        if (File::exists(config_path('database.php'))) {
            File::ensureDirectoryExists($backupPath . '/config');
            File::copy(config_path('database.php'), $backupPath . '/config/database.php');
        }

        // Create backup manifest
        File::put($backupPath . '/backup-manifest.json', json_encode([
            'created_at' => now()->toISOString(),
            'version' => self::VERSION,
        ], JSON_PRETTY_PRINT));
    }

    /**
     * Apply file updates from extracted folder
     */
    private function applyFileUpdates($extractPath)
    {
        $updatedFiles = [];
        $excludedPaths = [
            '.env',
            'storage',
            'update-manifest.json',
            '.git',
            'vendor',
            'node_modules',
        ];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($extractPath, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $relativePath = str_replace($extractPath . DIRECTORY_SEPARATOR, '', $item->getPathname());
            
            // Skip excluded paths
            $skip = false;
            foreach ($excludedPaths as $excluded) {
                if (str_starts_with($relativePath, $excluded)) {
                    $skip = true;
                    break;
                }
            }
            if ($skip) continue;

            $targetPath = base_path($relativePath);

            if ($item->isDir()) {
                File::ensureDirectoryExists($targetPath);
            } else {
                // Copy file
                File::ensureDirectoryExists(dirname($targetPath));
                File::copy($item->getPathname(), $targetPath);
                $updatedFiles[] = $relativePath;
            }
        }

        return $updatedFiles;
    }

    /**
     * Log update to history
     */
    private function logUpdate($manifest, $updatedFiles, $migrationOutput)
    {
        $historyPath = storage_path('app/update-history.json');
        $history = [];

        if (File::exists($historyPath)) {
            $history = json_decode(File::get($historyPath), true) ?? [];
        }

        $history[] = [
            'date' => now()->toISOString(),
            'version' => $manifest['version'] ?? 'Unknown',
            'description' => $manifest['description'] ?? 'Manual update',
            'files_updated' => count($updatedFiles),
            'migrations_run' => !empty($migrationOutput),
            'user' => auth()->user()->name ?? 'System',
        ];

        File::put($historyPath, json_encode($history, JSON_PRETTY_PRINT));
    }

    /**
     * Get update history
     */
    private function getUpdateHistory()
    {
        $historyPath = storage_path('app/update-history.json');

        if (File::exists($historyPath)) {
            return array_reverse(json_decode(File::get($historyPath), true) ?? []);
        }

        return [];
    }

    /**
     * Get database version
     */
    private function getDatabaseVersion()
    {
        try {
            $driver = config('database.default');
            if ($driver === 'mysql') {
                return DB::select('SELECT VERSION() as version')[0]->version ?? 'Unknown';
            } elseif ($driver === 'sqlite') {
                return DB::select('SELECT sqlite_version() as version')[0]->version ?? 'Unknown';
            } elseif ($driver === 'pgsql') {
                return DB::select('SELECT version()')[0]->version ?? 'Unknown';
            }
        } catch (\Exception $e) {
            return 'Unknown';
        }
        return 'Unknown';
    }

    /**
     * Get table count
     */
    private function getTableCount()
    {
        try {
            $driver = config('database.default');
            if ($driver === 'mysql') {
                $database = config('database.connections.mysql.database');
                return DB::select("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = ?", [$database])[0]->count ?? 0;
            } elseif ($driver === 'sqlite') {
                return DB::select("SELECT COUNT(*) as count FROM sqlite_master WHERE type='table'")[0]->count ?? 0;
            }
        } catch (\Exception $e) {
            return 0;
        }
        return 0;
    }

    /**
     * Get pending migrations
     */
    private function getPendingMigrations()
    {
        try {
            $migrator = app('migrator');
            $files = $migrator->getMigrationFiles(database_path('migrations'));
            $ran = $migrator->getRepository()->getRan();
            
            $pending = [];
            foreach ($files as $file => $path) {
                if (!in_array($file, $ran)) {
                    $pending[] = $file;
                }
            }
            return $pending;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
