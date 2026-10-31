<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\RoomApiController;

Route::prefix('v1')->group(function () {
    // ============================================
    // PUBLIC ROOM & BOOKING API (for main website)
    // ============================================
    
    // Properties (Hotels)
    Route::get('/properties', [RoomApiController::class, 'properties']);
    Route::get('/properties/{property}', [RoomApiController::class, 'property']);
    
    // Room Types
    Route::get('/properties/{property}/room-types', [RoomApiController::class, 'roomTypes']);
    Route::get('/properties/{property}/room-types/{roomType}', [RoomApiController::class, 'roomType']);
    
    // Rooms
    Route::get('/properties/{property}/rooms', [RoomApiController::class, 'rooms']);
    Route::get('/rooms/{room}', [RoomApiController::class, 'room']);
    
    // Room Availability Search
    Route::post('/rooms/availability', [RoomApiController::class, 'checkAvailability']);
    
    // Website Booking
    Route::post('/rooms/book', [RoomApiController::class, 'createBooking']);
    
    // Booking Status & Cancellation (for guests)
    Route::post('/bookings/status', [RoomApiController::class, 'bookingStatus']);
    Route::post('/bookings/cancel', [RoomApiController::class, 'cancelBooking']);

    // ============================================
    // INTERNAL BOOKING API
    // ============================================
    Route::post('/bookings/search', [BookingApiController::class, 'search']);
    Route::post('/bookings', [BookingApiController::class, 'store']);
    Route::get('/bookings/{booking}', [BookingApiController::class, 'show']);
    Route::post('/bookings/{booking}/confirm', [BookingApiController::class, 'confirm']);

    // ============================================
    // ORDER API
    // ============================================
    Route::post('/orders', [OrderApiController::class, 'store']);
    Route::post('/orders/{order}/items', [OrderApiController::class, 'addItem']);
    Route::get('/orders/{order}', [OrderApiController::class, 'show']);
    Route::patch('/orders/{order}/status', [OrderApiController::class, 'updateStatus']);
});

