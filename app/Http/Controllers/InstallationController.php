<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class InstallationController extends Controller
{
    /**
     * Check if installation is already complete
     */
    private function isInstallationComplete(): bool
    {
        return file_exists(storage_path('app/installation_complete.flag'));
    }

    /**
     * Mark installation as complete
     */
    private function markInstallationComplete(): void
    {
        \Illuminate\Support\Facades\Storage::disk('local')->put('installation_complete.flag', 'Installation completed at ' . now());
    }

    /**
     * Show installation wizard step 1 - Welcome
     */
    public function welcome()
    {
        if ($this->isInstallationComplete()) {
            return redirect('/login')->with('info', 'Installation is already complete. Please log in.');
        }
        return view('installation.welcome');
    }

    /**
     * Show installation wizard step 2 - Database Configuration
     */
    public function database()
    {
        if ($this->isInstallationComplete()) {
            return redirect('/login')->with('info', 'Installation is already complete. Please log in.');
        }
        return view('installation.database');
    }

    /**
     * Show installation wizard step 3 - Environment Setup
     */
    public function environment()
    {
        if ($this->isInstallationComplete()) {
            return redirect('/login')->with('info', 'Installation is already complete. Please log in.');
        }
        return view('installation.environment');
    }

    /**
     * Show installation wizard step 4 - Run Migrations
     */
    public function migrations()
    {
        if ($this->isInstallationComplete()) {
            return redirect('/login')->with('info', 'Installation is already complete. Please log in.');
        }
        return view('installation.migrations');
    }

    /**
     * Show installation wizard step 5 - Create Admin User
     */
    public function admin()
    {
        if ($this->isInstallationComplete()) {
            return redirect('/login')->with('info', 'Installation is already complete. Please log in.');
        }
        return view('installation.admin');
    }

    /**
     * Show installation wizard step 6 - Complete
     */
    public function complete()
    {
        if ($this->isInstallationComplete()) {
            return redirect('/login')->with('info', 'Installation is already complete. Please log in.');
        }
        return view('installation.complete');
    }

    /**
     * Test database connection
     */
    public function testConnection(Request $request)
    {
        try {
            $driver = $request->input('driver', 'mysql');
            
            if ($driver === 'mysql') {
                $connection = [
                    'driver' => 'mysql',
                    'host' => $request->input('host', 'localhost'),
                    'port' => $request->input('port', 3306),
                    'database' => $request->input('database'),
                    'username' => $request->input('username'),
                    'password' => $request->input('password'),
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                ];
            } else {
                $connection = [
                    'driver' => 'sqlite',
                    'database' => $request->input('database'),
                ];
            }

            DB::setDefaultConnection('test');
            config(['database.connections.test' => $connection]);
            DB::connection('test')->getPdo();

            return response()->json(['success' => true, 'message' => 'Database connection successful!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Connection failed: ' . $e->getMessage()], 400);
        }
    }

    /**
     * Run migrations
     */
    public function runMigrations(Request $request)
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            return response()->json(['success' => true, 'message' => 'Migrations completed successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Migration failed: ' . $e->getMessage()], 400);
        }
    }

    /**
     * Create admin user
     */
    public function createAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            \App\Models\User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'is_admin' => true,
            ]);

            // Mark installation as complete
            $this->markInstallationComplete();

            return response()->json(['success' => true, 'message' => 'Admin user created successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error creating admin: ' . $e->getMessage()], 400);
        }
    }
}

