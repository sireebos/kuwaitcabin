<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Show the settings page
     */
    public function edit(Property $property) 
    {
        return view('settings.edit', compact('property'));
    } 

    /**
     * Show the branding page
     */
    public function branding(Property $property)
    {
        return view('settings.branding', compact('property'));
    }

    /**
     * Update hotel settings
     */
    public function update(Request $request, Property $property)
    {
        // Check if this is a branding upload request
        $isBrandingUpdate = $request->hasFile('logo') || $request->hasFile('light_logo') || $request->hasFile('favicon') || $request->hasFile('background');

        if ($isBrandingUpdate) {
            // Branding upload validation
            $validated = $request->validate([
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'light_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico|max:1024',
                'background' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            ]);
        } else {
            // Hotel settings validation
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'display_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'tax_rate' => 'required|numeric|min:0|max:100',
            ]);
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($property->logo_path && file_exists(public_path($property->logo_path))) {
                unlink(public_path($property->logo_path));
            }

            // Store new logo
            $logoPath = $request->file('logo')->store('logos', 'public');
            $validated['logo_path'] = 'storage/' . $logoPath;
        }

        // Handle light logo upload
        if ($request->hasFile('light_logo')) {
            // Delete old light logo if exists
            if ($property->light_logo_path && file_exists(public_path($property->light_logo_path))) {
                unlink(public_path($property->light_logo_path));
            }

            // Store new light logo
            $lightLogoPath = $request->file('light_logo')->store('logos', 'public');
            $validated['light_logo_path'] = 'storage/' . $lightLogoPath;
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            // Delete old favicon if exists
            if ($property->favicon_path && file_exists(public_path($property->favicon_path))) {
                unlink(public_path($property->favicon_path));
            }

            // Store new favicon
            $faviconPath = $request->file('favicon')->store('favicons', 'public');
            $validated['favicon_path'] = 'storage/' . $faviconPath;
        }

        // Handle background upload
        if ($request->hasFile('background')) {
            // Delete old background if exists
            if ($property->background_path && file_exists(public_path($property->background_path))) {
                unlink(public_path($property->background_path));
            }

            // Store new background
            $backgroundPath = $request->file('background')->store('backgrounds', 'public');
            $validated['background_path'] = 'storage/' . $backgroundPath;
        }

        $property->update($validated);

        $redirectRoute = $isBrandingUpdate ? 'settings.branding' : 'settings.edit';
        $message = $isBrandingUpdate ? 'Branding assets uploaded successfully!' : 'Hotel settings updated successfully!';

        return redirect()->route($redirectRoute, $property)
            ->with('success', $message);
    }

    /**
     * Show the setup page for home page configuration
     */
    public function setup(Property $property)
    {
        return view('settings.setup', compact('property'));
    }

    /**
     * Update home page configuration
     */
    public function updateSetup(Request $request, Property $property)
    {
        $validated = $request->validate([
            'home_title' => 'required|string|max:255',
            'home_description' => 'required|string|max:1000',
            'home_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'home_image_link' => 'nullable|url|max:500',
            'features_enabled' => 'nullable|array',
        ]);

        // Handle image upload
        if ($request->hasFile('home_image')) {
            $path = $request->file('home_image')->store('home', 'public');
            $validated['home_image_path'] = $path;
        }

        $property->update($validated);

        return redirect()->route('settings.setup', $property)
            ->with('success', 'Home page configuration updated successfully!');
    }

    /**
     * Show the API documentation page
     */
    public function apiDocumentation(Property $property)
    {
        return view('settings.api-documentation', compact('property'));
    }

    /**
     * Show the reset database page (Admin only)
     */
    public function resetDatabase(Property $property)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        return view('settings.reset-database', compact('property'));
    }

    /**
     * Confirm and reset the database (Admin only)
     */
    public function confirmReset(Request $request, Property $property)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'password' => 'required|string',
            'confirmation' => 'required|in:yes',
        ]);

        // Verify password
        if (!\Hash::check($validated['password'], auth()->user()->password)) {
            return back()->withErrors(['password' => 'Invalid password']);
        }

        // Clear all data from tables
        try {
            \DB::table('order_items')->truncate();
            \DB::table('orders')->truncate();
            \DB::table('payments')->truncate();
            \DB::table('folios')->truncate();
            \DB::table('bookings')->truncate();
            \DB::table('room_inventory')->truncate();
            \DB::table('inventory_items')->truncate();
            \DB::table('restaurant_tables')->truncate();
            \DB::table('rooms')->truncate();
            \DB::table('guests')->truncate();

            return redirect()->route('settings.edit', $property)
                ->with('success', 'Database has been reset successfully! All data has been cleared.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error resetting database: ' . $e->getMessage()]);
        }
    }
}

