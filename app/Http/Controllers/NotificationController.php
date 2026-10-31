<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Booking;
use App\Notifications\NewBookingAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    /**
     * Display notifications for the property
     */
    public function index(Property $property)
    {
        $user = auth()->user();
        
        // Get unread notifications
        $unreadNotifications = $user->unreadNotifications()
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get all notifications (paginated)
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('property', 'notifications', 'unreadNotifications'));
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(Property $property, $notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        
        if ($notification) {
            $notification->markAsRead();
        }

        return redirect()->back()->with('success', 'Notification marked as read');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Property $property)
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'All notifications marked as read');
    }

    /**
     * Delete a notification
     */
    public function destroy(Property $property, $notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        
        if ($notification) {
            $notification->delete();
        }

        return redirect()->back()->with('success', 'Notification deleted');
    }

    /**
     * Get unread notification count (API endpoint)
     */
    public function unreadCount(Property $property)
    {
        return response()->json([
            'count' => auth()->user()->unreadNotifications()->count()
        ]);
    }

    /**
     * Send email notification for a booking
     */
    public function sendEmailNotification(Property $property, Booking $booking)
    {
        $booking->load(['guest', 'room.roomType', 'property']);
        
        // Check if email notifications are enabled
        if (!$property->email_notifications_enabled) {
            return redirect()->back()->with('error', 'Email notifications are disabled. Enable them in Settings → Email Settings.');
        }
        
        // Configure mailer with property settings
        if ($property->smtp_host) {
            \App\Http\Controllers\EmailSettingsController::configureMailer($property);
        }
        
        try {
            $user = auth()->user();
            $user->notify(new NewBookingAlert($booking));
            
            return redirect()->back()->with('success', 'Email notification sent successfully to ' . $user->email);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * Generate WhatsApp URL for a booking
     */
    public function getWhatsAppUrl(Property $property, Booking $booking, Request $request)
    {
        $booking->load(['guest', 'room.roomType', 'property']);
        
        // Use guest's phone number from the booking
        $phone = $booking->guest->phone ?? null;
        
        if (!$phone) {
            return response()->json([
                'success' => false,
                'message' => 'No phone number available for this guest.'
            ], 400);
        }
        
        $url = NewBookingAlert::getWhatsAppUrl($phone, $booking);
        
        return response()->json([
            'success' => true,
            'url' => $url,
            'message' => NewBookingAlert::getWhatsAppMessage($booking)
        ]);
    }

    /**
     * Redirect to WhatsApp with booking message
     */
    public function sendWhatsApp(Property $property, Booking $booking, Request $request)
    {
        $booking->load(['guest', 'room.roomType', 'property']);
        
        // Use guest's phone number from the booking
        $phone = $booking->guest->phone ?? null;
        
        if (!$phone) {
            return redirect()->back()->with('error', 'No phone number available for this guest.');
        }
        
        $url = NewBookingAlert::getWhatsAppUrl($phone, $booking);
        
        return redirect()->away($url);
    }
}
