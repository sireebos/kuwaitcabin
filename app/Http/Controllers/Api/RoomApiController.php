<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Guest;
use App\Models\User;
use App\Notifications\NewBookingAlert;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class RoomApiController extends Controller
{
    public function __construct(private BookingService $bookingService)
    {
    }

    /**
     * Get all properties (hotels) available for booking
     */
    public function properties(): JsonResponse
    {
        $properties = Property::where('is_active', true)
            ->select([
                'id', 'name', 'slug', 'description', 'address', 'city', 'state',
                'country', 'phone', 'email', 'website', 'total_rooms',
                'check_in_time', 'check_out_time', 'currency', 'logo_path',
                'home_title', 'home_description', 'home_image_path', 'tax_rate'
            ])
            ->get();

        return response()->json([
            'success' => true,
            'properties' => $properties,
            'count' => $properties->count(),
        ]);
    }

    /**
     * Get a single property details
     */
    public function property(Property $property): JsonResponse
    {
        if (!$property->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Property not found or inactive',
            ], 404);
        }

        $property->load(['roomTypes' => function ($query) {
            $query->where('is_active', true);
        }]);

        return response()->json([
            'success' => true,
            'property' => [
                'id' => $property->id,
                'name' => $property->name,
                'slug' => $property->slug,
                'description' => $property->description,
                'address' => $property->address,
                'city' => $property->city,
                'state' => $property->state,
                'country' => $property->country,
                'phone' => $property->phone,
                'email' => $property->email,
                'website' => $property->website,
                'total_rooms' => $property->total_rooms,
                'check_in_time' => $property->check_in_time,
                'check_out_time' => $property->check_out_time,
                'currency' => $property->currency,
                'tax_rate' => $property->tax_rate,
                'logo_path' => $property->logo_path,
                'home_title' => $property->home_title,
                'home_description' => $property->home_description,
                'home_image_path' => $property->home_image_path,
                'room_types' => $property->roomTypes,
            ],
        ]);
    }

    /**
     * Get all room types for a property
     */
    public function roomTypes(Property $property): JsonResponse
    {
        $roomTypes = RoomType::where('property_id', $property->id)
            ->where('is_active', true)
            ->withCount(['rooms' => function ($query) {
                $query->where('status', 'available');
            }])
            ->get()
            ->map(function ($roomType) {
                return [
                    'id' => $roomType->id,
                    'name' => $roomType->name,
                    'slug' => $roomType->slug,
                    'description' => $roomType->description,
                    'capacity' => $roomType->capacity,
                    'beds' => $roomType->beds,
                    'base_price' => $roomType->base_price,
                    'amenities' => $roomType->amenities,
                    'total_rooms' => $roomType->total_rooms,
                    'available_rooms_count' => $roomType->rooms_count,
                ];
            });

        return response()->json([
            'success' => true,
            'room_types' => $roomTypes,
            'count' => $roomTypes->count(),
            'currency' => $property->currency,
        ]);
    }

    /**
     * Get a single room type for a property
     */
    public function roomType(Property $property, RoomType $roomType): JsonResponse
    {
        // Ensure the room type belongs to this property
        if ($roomType->property_id !== $property->id) {
            return response()->json([
                'success' => false,
                'message' => 'Room type not found for this property',
            ], 404);
        }

        $roomType->loadCount(['rooms' => function ($query) {
            $query->where('status', 'available');
        }]);

        return response()->json([
            'success' => true,
            'room_type' => [
                'id' => $roomType->id,
                'name' => $roomType->name,
                'slug' => $roomType->slug,
                'description' => $roomType->description,
                'capacity' => $roomType->capacity,
                'max_occupancy' => $roomType->capacity,
                'beds' => $roomType->beds,
                'base_price' => $roomType->base_price,
                'amenities' => $roomType->amenities,
                'total_rooms' => $roomType->total_rooms,
                'available_rooms_count' => $roomType->rooms_count,
            ],
            'currency' => $property->currency,
        ]);
    }

    /**
     * Get all rooms for a property
     */
    public function rooms(Property $property): JsonResponse
    {
        $rooms = Room::where('property_id', $property->id)
            ->with('roomType:id,name,slug,description,capacity,beds,base_price,amenities')
            ->get()
            ->map(function ($room) {
                return [
                    'id' => $room->id,
                    'room_number' => $room->room_number,
                    'floor' => $room->floor,
                    'wing' => $room->wing,
                    'status' => $room->status,
                    'price' => $room->getEffectivePrice(),
                    'room_type' => $room->roomType,
                ];
            });

        return response()->json([
            'success' => true,
            'rooms' => $rooms,
            'count' => $rooms->count(),
        ]);
    }

    /**
     * Check room availability for given dates
     */
    public function checkAvailability(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guests' => 'nullable|integer|min:1',
            'room_type_id' => 'nullable|exists:room_types,id',
        ]);

        $property = Property::find($validated['property_id']);
        $checkInDate = Carbon::parse($validated['check_in_date']);
        $checkOutDate = Carbon::parse($validated['check_out_date']);
        $nights = $checkInDate->diffInDays($checkOutDate);

        // Build query for rooms
        $roomsQuery = Room::where('property_id', $property->id)
            ->with('roomType:id,name,slug,description,capacity,beds,base_price,amenities');

        // Filter by room type if specified
        if (!empty($validated['room_type_id'])) {
            $roomsQuery->where('room_type_id', $validated['room_type_id']);
        }

        // Filter by guest capacity if specified
        if (!empty($validated['guests'])) {
            $roomsQuery->whereHas('roomType', function ($query) use ($validated) {
                $query->where('capacity', '>=', $validated['guests']);
            });
        }

        $allRooms = $roomsQuery->get();

        // Check availability for each room
        $availableRooms = $allRooms->filter(function ($room) use ($checkInDate, $checkOutDate) {
            return $room->isAvailable($checkInDate, $checkOutDate);
        })->map(function ($room) use ($nights, $property) {
            $price = $room->getEffectivePrice();
            $subtotal = $price * $nights;
            $tax = $subtotal * (($property->tax_rate ?? 0) / 100);
            $total = $subtotal + $tax;

            return [
                'id' => $room->id,
                'room_number' => $room->room_number,
                'floor' => $room->floor,
                'wing' => $room->wing,
                'price_per_night' => $price,
                'nights' => $nights,
                'subtotal' => round($subtotal, 2),
                'tax' => round($tax, 2),
                'total_amount' => round($total, 2),
                'currency' => $property->currency ?? '₦',
                'room_type' => [
                    'id' => $room->roomType->id,
                    'name' => $room->roomType->name,
                    'slug' => $room->roomType->slug,
                    'description' => $room->roomType->description,
                    'capacity' => $room->roomType->capacity,
                    'beds' => $room->roomType->beds,
                    'amenities' => $room->roomType->amenities,
                ],
            ];
        })->values();

        return response()->json([
            'success' => true,
            'search_params' => [
                'property_id' => $property->id,
                'property_name' => $property->name,
                'check_in_date' => $checkInDate->format('Y-m-d'),
                'check_out_date' => $checkOutDate->format('Y-m-d'),
                'nights' => $nights,
                'guests' => $validated['guests'] ?? null,
                'room_type_id' => $validated['room_type_id'] ?? null,
            ],
            'available_rooms' => $availableRooms,
            'count' => $availableRooms->count(),
        ]);
    }

    /**
     * Get a single room details with availability status
     */
    public function room(Room $room, Request $request): JsonResponse
    {
        $room->load('roomType', 'property');

        $response = [
            'id' => $room->id,
            'room_number' => $room->room_number,
            'floor' => $room->floor,
            'wing' => $room->wing,
            'status' => $room->status,
            'price' => $room->getEffectivePrice(),
            'room_type' => $room->roomType,
            'property' => [
                'id' => $room->property->id,
                'name' => $room->property->name,
                'currency' => $room->property->currency,
                'tax_rate' => $room->property->tax_rate,
            ],
        ];

        // Check availability if dates provided
        if ($request->has('check_in_date') && $request->has('check_out_date')) {
            $checkInDate = Carbon::parse($request->check_in_date);
            $checkOutDate = Carbon::parse($request->check_out_date);
            $nights = $checkInDate->diffInDays($checkOutDate);
            $isAvailable = $room->isAvailable($checkInDate, $checkOutDate);

            $price = $room->getEffectivePrice();
            $subtotal = $price * $nights;
            $tax = $subtotal * (($room->property->tax_rate ?? 0) / 100);
            $total = $subtotal + $tax;

            $response['availability'] = [
                'check_in_date' => $checkInDate->format('Y-m-d'),
                'check_out_date' => $checkOutDate->format('Y-m-d'),
                'is_available' => $isAvailable,
                'nights' => $nights,
                'price_per_night' => $price,
                'subtotal' => round($subtotal, 2),
                'tax' => round($tax, 2),
                'total_amount' => round($total, 2),
            ];
        }

        return response()->json([
            'success' => true,
            'room' => $response,
        ]);
    }

    /**
     * Create a booking from the website (public API)
     */
    public function createBooking(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'number_of_guests' => 'required|integer|min:1',
            // Guest information
            'guest.first_name' => 'required|string|max:255',
            'guest.last_name' => 'required|string|max:255',
            'guest.email' => 'required|email|max:255',
            'guest.phone' => 'required|string|max:50',
            'guest.address' => 'nullable|string|max:500',
            'guest.city' => 'nullable|string|max:100',
            'guest.state' => 'nullable|string|max:100',
            'guest.country' => 'nullable|string|max:100',
            'guest.id_type' => 'nullable|string|max:50',
            'guest.id_number' => 'nullable|string|max:100',
            // Optional booking info
            'special_requests' => 'nullable|string|max:1000',
            'booking_source' => 'nullable|string|max:50',
        ]);

        $property = Property::find($validated['property_id']);
        $room = Room::with('roomType')->find($validated['room_id']);

        // Verify room belongs to property
        if ($room->property_id !== $property->id) {
            return response()->json([
                'success' => false,
                'message' => 'Room does not belong to the specified property',
            ], 400);
        }

        // Check room availability
        $checkInDate = Carbon::parse($validated['check_in_date']);
        $checkOutDate = Carbon::parse($validated['check_out_date']);

        if (!$room->isAvailable($checkInDate, $checkOutDate)) {
            return response()->json([
                'success' => false,
                'message' => 'Room is not available for the selected dates',
            ], 400);
        }

        // Check capacity
        if ($validated['number_of_guests'] > $room->roomType->capacity) {
            return response()->json([
                'success' => false,
                'message' => 'Number of guests exceeds room capacity of ' . $room->roomType->capacity,
            ], 400);
        }

        try {
            $booking = DB::transaction(function () use ($validated, $property, $room, $checkInDate, $checkOutDate) {
                // Create or find guest
                $guestData = $validated['guest'];
                $guestData['property_id'] = $property->id;

                $guest = Guest::firstOrCreate(
                    [
                        'property_id' => $property->id,
                        'email' => $guestData['email'],
                    ],
                    $guestData
                );

                // Calculate pricing
                $nights = $checkInDate->diffInDays($checkOutDate);
                $roomRate = $room->getEffectivePrice();
                $subtotal = $roomRate * $nights;
                $tax = $subtotal * (($property->tax_rate ?? 0) / 100);
                $totalAmount = $subtotal + $tax;

                // Create booking
                $bookingData = [
                    'property_id' => $property->id,
                    'guest_id' => $guest->id,
                    'room_id' => $room->id,
                    'check_in_date' => $checkInDate->format('Y-m-d'),
                    'check_out_date' => $checkOutDate->format('Y-m-d'),
                    'number_of_guests' => $validated['number_of_guests'],
                    'room_rate' => $roomRate,
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'total_amount' => $totalAmount,
                    'special_requests' => $validated['special_requests'] ?? null,
                    'booking_source' => $validated['booking_source'] ?? 'website',
                    'status' => 'pending',
                ];

                return $this->bookingService->createBooking($bookingData);
            });

            $booking->load(['guest', 'room.roomType', 'property']);

            // Send notification to all admin users of this property
            $this->sendBookingNotifications($booking);

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'booking' => [
                    'id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'status' => $booking->status,
                    'check_in_date' => $booking->check_in_date->format('Y-m-d'),
                    'check_out_date' => $booking->check_out_date->format('Y-m-d'),
                    'number_of_guests' => $booking->number_of_guests,
                    'number_of_nights' => $booking->number_of_nights,
                    'room_rate' => $booking->room_rate,
                    'subtotal' => $booking->subtotal,
                    'tax' => $booking->tax,
                    'total_amount' => $booking->total_amount,
                    'currency' => $booking->property->currency ?? '₦',
                    'special_requests' => $booking->special_requests,
                    'guest' => [
                        'id' => $booking->guest->id,
                        'name' => $booking->guest->full_name,
                        'email' => $booking->guest->email,
                        'phone' => $booking->guest->phone,
                    ],
                    'room' => [
                        'id' => $booking->room->id,
                        'room_number' => $booking->room->room_number,
                        'room_type' => $booking->room->roomType->name,
                    ],
                    'property' => [
                        'id' => $booking->property->id,
                        'name' => $booking->property->name,
                        'address' => $booking->property->address,
                        'phone' => $booking->property->phone,
                        'email' => $booking->property->email,
                    ],
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get booking status by reference number
     */
    public function bookingStatus(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'booking_reference' => 'required|string',
            'email' => 'required|email',
        ]);

        $booking = \App\Models\Booking::where('booking_reference', $validated['booking_reference'])
            ->whereHas('guest', function ($query) use ($validated) {
                $query->where('email', $validated['email']);
            })
            ->with(['guest', 'room.roomType', 'property', 'payments'])
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found or email does not match',
            ], 404);
        }

        $totalPaid = $booking->payments->sum('amount');

        return response()->json([
            'success' => true,
            'booking' => [
                'id' => $booking->id,
                'booking_reference' => $booking->booking_reference,
                'status' => $booking->status,
                'check_in_date' => $booking->check_in_date->format('Y-m-d'),
                'check_out_date' => $booking->check_out_date->format('Y-m-d'),
                'number_of_guests' => $booking->number_of_guests,
                'number_of_nights' => $booking->number_of_nights,
                'room_rate' => $booking->room_rate,
                'subtotal' => $booking->subtotal,
                'tax' => $booking->tax,
                'total_amount' => $booking->total_amount,
                'total_paid' => $totalPaid,
                'balance_due' => $booking->total_amount - $totalPaid,
                'currency' => $booking->property->currency ?? '₦',
                'special_requests' => $booking->special_requests,
                'guest' => [
                    'name' => $booking->guest->full_name,
                    'email' => $booking->guest->email,
                    'phone' => $booking->guest->phone,
                ],
                'room' => [
                    'room_number' => $booking->room->room_number,
                    'room_type' => $booking->room->roomType->name,
                ],
                'property' => [
                    'name' => $booking->property->name,
                    'address' => $booking->property->address,
                    'phone' => $booking->property->phone,
                    'email' => $booking->property->email,
                    'check_in_time' => $booking->property->check_in_time,
                    'check_out_time' => $booking->property->check_out_time,
                ],
            ],
        ]);
    }

    /**
     * Cancel a booking (only if status is pending or confirmed)
     */
    public function cancelBooking(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'booking_reference' => 'required|string',
            'email' => 'required|email',
            'reason' => 'nullable|string|max:500',
        ]);

        $booking = \App\Models\Booking::where('booking_reference', $validated['booking_reference'])
            ->whereHas('guest', function ($query) use ($validated) {
                $query->where('email', $validated['email']);
            })
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found or email does not match',
            ], 404);
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Booking cannot be cancelled. Current status: ' . $booking->status,
            ], 400);
        }

        try {
            DB::transaction(function () use ($booking, $validated) {
                // Release room inventory
                $this->bookingService->releaseRoomInventory($booking);

                // Update booking status
                $booking->update([
                    'status' => 'cancelled',
                    'special_requests' => $booking->special_requests 
                        ? $booking->special_requests . "\n\nCancellation reason: " . ($validated['reason'] ?? 'Not provided')
                        : "Cancellation reason: " . ($validated['reason'] ?? 'Not provided'),
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully',
                'booking_reference' => $booking->booking_reference,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel booking: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send notifications to all admin users of the property
     */
    protected function sendBookingNotifications(\App\Models\Booking $booking): void
    {
        try {
            $property = $booking->property;
            
            \Illuminate\Support\Facades\Log::info('Sending booking notification for: ' . $booking->booking_reference);
            
            // Check if email notifications are enabled
            if (!$property->email_notifications_enabled) {
                \Illuminate\Support\Facades\Log::info('Email notifications disabled for property: ' . $property->id);
                return;
            }
            
            // Configure mailer with property settings
            if ($property->smtp_host) {
                \App\Http\Controllers\EmailSettingsController::configureMailer($property);
            }
            
            // Get all admin users for this property
            $adminUsers = User::where('property_id', $booking->property_id)
                ->whereHas('role', function ($query) {
                    $query->where('name', 'admin');
                })
                ->get();

            \Illuminate\Support\Facades\Log::info('Found ' . $adminUsers->count() . ' admin users for property ' . $property->id);

            // If no admin users found, notify all users with manage_bookings permission
            if ($adminUsers->isEmpty()) {
                $adminUsers = User::where('property_id', $booking->property_id)
                    ->whereHas('role', function ($query) {
                        $query->where('permissions', 'like', '%manage_bookings%');
                    })
                    ->get();
                \Illuminate\Support\Facades\Log::info('Fallback: Found ' . $adminUsers->count() . ' users with manage_bookings permission');
            }

            if ($adminUsers->isNotEmpty()) {
                // Send notification to each admin
                Notification::send($adminUsers, new NewBookingAlert($booking));
                \Illuminate\Support\Facades\Log::info('Notifications sent successfully to ' . $adminUsers->count() . ' users');
            } else {
                \Illuminate\Support\Facades\Log::warning('No admin users found to send notification for booking: ' . $booking->booking_reference);
            }
        } catch (\Exception $e) {
            // Log the error but don't fail the booking creation
            \Illuminate\Support\Facades\Log::error('Failed to send booking notification: ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
        }
    }
}
