<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class EmailSettingsController extends Controller
{
    /**
     * Show the email settings form
     */
    public function edit(Property $property)
    {
        return view('settings.email', compact('property'));
    }

    /**
     * Update the email settings
     */
    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_encryption' => 'nullable|in:tls,ssl,none',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|string|max:255',
            'email_notifications_enabled' => 'boolean',
        ]);

        // Handle encryption value
        if (isset($validated['smtp_encryption']) && $validated['smtp_encryption'] === 'none') {
            $validated['smtp_encryption'] = null;
        }

        // Encrypt password if provided
        if (!empty($validated['smtp_password'])) {
            $validated['smtp_password'] = Crypt::encryptString($validated['smtp_password']);
        } else {
            // Keep existing password if not provided
            unset($validated['smtp_password']);
        }

        // Handle checkbox
        $validated['email_notifications_enabled'] = $request->has('email_notifications_enabled');

        $property->update($validated);

        return redirect()->route('settings.email', $property)
            ->with('success', 'Email settings updated successfully!');
    }

    /**
     * Test email configuration
     */
    public function test(Request $request, Property $property)
    {
        $request->validate([
            'test_email' => 'required|email'
        ]);

        try {
            // Configure mail dynamically
            $this->configureMailer($property);

            // Send test email
            Mail::raw('This is a test email from ' . $property->name . ' Hotel Management System. If you receive this, your email settings are configured correctly!', function ($message) use ($request, $property) {
                $message->to($request->test_email)
                    ->subject('Test Email from ' . $property->name);
            });

            return redirect()->route('settings.email', $property)
                ->with('success', 'Test email sent successfully to ' . $request->test_email);
        } catch (\Exception $e) {
            return redirect()->route('settings.email', $property)
                ->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    /**
     * Configure the mailer dynamically based on property settings
     */
    public static function configureMailer(Property $property)
    {
        if (!$property->smtp_host) {
            return false;
        }

        $password = $property->smtp_password;
        if ($password) {
            try {
                $password = Crypt::decryptString($password);
            } catch (\Exception $e) {
                // Password might not be encrypted (old data)
            }
        }

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => $property->smtp_host,
            'mail.mailers.smtp.port' => $property->smtp_port ?? 587,
            'mail.mailers.smtp.username' => $property->smtp_username,
            'mail.mailers.smtp.password' => $password,
            'mail.mailers.smtp.encryption' => $property->smtp_encryption,
            'mail.from.address' => $property->mail_from_address ?? $property->email,
            'mail.from.name' => $property->mail_from_name ?? $property->name,
        ]);

        return true;
    }
}
