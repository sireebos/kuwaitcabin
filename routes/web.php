<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FolioController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\RestaurantTableController;
use App\Http\Controllers\RestaurantReservationController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InventoryTypeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\RatePlanController;
use App\Http\Controllers\RoomInventoryController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\BookingHistoryController;
use App\Http\Controllers\RoomAvailabilityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BillSplitController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\DailySummaryController;
use App\Http\Controllers\BarOrderController;
use App\Http\Controllers\StockSessionController;
use App\Http\Controllers\InstallationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SystemInfoController;

// Installation Wizard Routes (No Auth Required)
Route::prefix('install')->group(function () {
    Route::get('/', [InstallationController::class, 'welcome'])->name('installation.welcome');
    Route::get('/database', [InstallationController::class, 'database'])->name('installation.database');
    Route::get('/environment', [InstallationController::class, 'environment'])->name('installation.environment');
    Route::get('/migrations', [InstallationController::class, 'migrations'])->name('installation.migrations');
    Route::get('/admin', [InstallationController::class, 'admin'])->name('installation.admin');
    Route::get('/complete', [InstallationController::class, 'complete'])->name('installation.complete');

    Route::post('/test-connection', [InstallationController::class, 'testConnection'])->name('installation.testConnection');
    Route::post('/run-migrations', [InstallationController::class, 'runMigrations'])->name('installation.runMigrations');
    Route::post('/create-admin', [InstallationController::class, 'createAdmin'])->name('installation.createAdmin');
});

Route::get('/', function () {
    if (auth()->check()) {
        $property = auth()->user()->property_id ?? 1;
        return redirect()->route('dashboard', ['property' => $property]);
    }
    $property = \App\Models\Property::first();
    return view('welcome', compact('property'));
});

// Public booking routes (no auth required)
Route::get('/booking/{roomType}', [BookingController::class, 'publicCreate'])->name('booking.public-create');
Route::post('/booking', [BookingController::class, 'publicStore'])->name('booking.public-store');
Route::get('/booking-confirmation/{booking}', [BookingController::class, 'confirmation'])->name('booking.confirmation');

Route::middleware(['auth', 'verified'])->group(function () {
    // Search (public routes)
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::post('/search', [SearchController::class, 'search'])->name('search.search');
    Route::get('/search/availability', [SearchController::class, 'availability'])->name('search.availability');

    // Dashboard
    Route::get('/dashboard/{property}', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports/sales/{property}', [DashboardController::class, 'salesReport'])->name('reports.sales');
    Route::get('/reports/occupancy/{property}', [DashboardController::class, 'occupancyReport'])->name('reports.occupancy');

    // Admin Only Routes
    Route::middleware('admin')->group(function () {
        // Properties
        Route::resource('properties', PropertyController::class);

        // Room Types (nested under properties)
        Route::resource('properties.room-types', RoomTypeController::class);

        // Rate Plans (nested under properties)
        Route::resource('properties.rate-plans', RatePlanController::class);

        // Room Inventory (nested under properties)
        Route::resource('properties.room-inventory', RoomInventoryController::class);

        // Rooms
        Route::resource('properties.rooms', RoomController::class);
        Route::post('/properties/{property}/rooms/bulk-delete', [RoomController::class, 'bulkDelete'])->name('properties.rooms.bulk-delete');

        // Booking Deletion (Admin Only)
        Route::delete('/properties/{property}/bookings/{booking}', [BookingController::class, 'destroy'])->name('properties.bookings.destroy');
        Route::post('/properties/{property}/bookings/delete-multiple', [BookingController::class, 'deleteMultiple'])->name('bookings.deleteMultiple');

        // Settings
        Route::get('/properties/{property}/settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::patch('/properties/{property}/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::get('/properties/{property}/branding', [SettingsController::class, 'branding'])->name('settings.branding');
        Route::get('/properties/{property}/setup', [SettingsController::class, 'setup'])->name('settings.setup');
        Route::put('/properties/{property}/setup', [SettingsController::class, 'updateSetup'])->name('settings.updateSetup');
        Route::get('/properties/{property}/reset-database', [SettingsController::class, 'resetDatabase'])->name('settings.resetDatabase');
        Route::post('/properties/{property}/reset-database', [SettingsController::class, 'confirmReset'])->name('settings.confirmReset');
        Route::get('/properties/{property}/api-documentation', [SettingsController::class, 'apiDocumentation'])->name('settings.api-documentation');
        
        // Email Settings
        Route::get('/properties/{property}/email-settings', [\App\Http\Controllers\EmailSettingsController::class, 'edit'])->name('settings.email');
        Route::put('/properties/{property}/email-settings', [\App\Http\Controllers\EmailSettingsController::class, 'update'])->name('settings.email.update');
        Route::post('/properties/{property}/email-settings/test', [\App\Http\Controllers\EmailSettingsController::class, 'test'])->name('settings.email.test');

        // User Management
        Route::resource('properties.users', UserController::class);

        // Reports
        Route::get('/properties/{property}/reports/sales', [ReportController::class, 'sales'])->name('reports.daily-sales');
        Route::get('/properties/{property}/reports/bookings', [ReportController::class, 'bookings'])->name('reports.daily-bookings');
        Route::get('/properties/{property}/reports/occupancy', [ReportController::class, 'occupancy'])->name('reports.occupancy');
        Route::get('/properties/{property}/reports/food-cost', [ReportController::class, 'foodCost'])->name('reports.food-cost');
        Route::get('/properties/{property}/reports/stock-usage', [ReportController::class, 'stockUsage'])->name('reports.stock-usage');
        Route::get('/properties/{property}/reports/sales/pdf', [ReportController::class, 'salesPdf'])->name('reports.daily-sales-pdf');
        Route::get('/properties/{property}/reports/bookings/pdf', [ReportController::class, 'bookingsPdf'])->name('reports.daily-bookings-pdf');

        // System Info & Updates
        Route::get('/system', [SystemInfoController::class, 'index'])->name('system.info');
        Route::post('/system/update', [SystemInfoController::class, 'update'])->name('system.update');
        Route::post('/system/migrations', [SystemInfoController::class, 'runMigrations'])->name('system.run-migrations');
        Route::post('/system/clear-cache', [SystemInfoController::class, 'clearCache'])->name('system.clear-cache');
    });

    // Bookings (Receptionist Only)
    Route::middleware('receptionist')->group(function () {
        Route::resource('properties.bookings', BookingController::class, ['except' => ['destroy']]);
        Route::post('/properties/{property}/bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');

        // Room Availability
        Route::post('/properties/{property}/availability/check', [RoomAvailabilityController::class, 'checkAvailability'])->name('availability.check');
        Route::post('/properties/{property}/availability/available-rooms', [RoomAvailabilityController::class, 'getAvailableRooms'])->name('availability.available-rooms');

        // Check-in/Check-out
        Route::get('/properties/{property}/checkin', [CheckinController::class, 'index'])->name('checkin.index');
        Route::get('/properties/{property}/bookings/{booking}/checkin', [CheckinController::class, 'create'])->name('checkin.create');
        Route::post('/properties/{property}/bookings/{booking}/checkin', [CheckinController::class, 'store'])->name('checkin.store');

        Route::get('/properties/{property}/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::get('/properties/{property}/bookings/{booking}/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
        Route::post('/properties/{property}/bookings/{booking}/checkout', [CheckoutController::class, 'checkout'])->name('checkout.checkout');

        // Booking History
        Route::get('/properties/{property}/booking-history', [BookingHistoryController::class, 'index'])->name('booking-history.index');
        Route::get('/properties/{property}/booking-history/{bookingHistory}', [BookingHistoryController::class, 'show'])->name('booking-history.show');
        Route::get('/properties/{property}/booking-history/{bookingHistory}/export', [BookingHistoryController::class, 'export'])->name('booking-history.export');

        // Folio
        Route::get('/properties/{property}/bookings/{booking}/folio', [FolioController::class, 'show'])->name('folio.show');
        Route::post('/properties/{property}/bookings/{booking}/folio/charge', [FolioController::class, 'addCharge'])->name('folio.addCharge');
        Route::post('/properties/{property}/bookings/{booking}/folio/payment', [FolioController::class, 'recordPayment'])->name('folio.recordPayment');
        Route::get('/properties/{property}/bookings/{booking}/folio/print', [FolioController::class, 'print'])->name('folio.print');
    });

    // Orders (Restaurant Staff Only)
    Route::middleware('restaurant_staff')->group(function () {
        Route::get('/properties/{property}/orders/pos', [OrderController::class, 'pos'])->name('orders.pos');
        Route::post('/properties/{property}/orders/create-with-items', [OrderController::class, 'createWithItems'])->name('orders.createWithItems');
        Route::post('/properties/{property}/orders/bulk-delete', [OrderController::class, 'bulkDelete'])->name('properties.orders.bulk-delete');
        Route::resource('properties.orders', OrderController::class, ['except' => ['store']]);
        Route::post('/properties/{property}/orders', [OrderController::class, 'store'])->name('properties.orders.store');
        Route::post('/properties/{property}/orders/{order}/items', [OrderController::class, 'addItem'])->name('orders.addItem');
        Route::delete('/properties/{property}/orders/{order}/items/{item}', [OrderController::class, 'removeItem'])->name('orders.removeItem');
        Route::patch('/properties/{property}/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::post('/properties/{property}/orders/{order}/charge-to-room', [OrderController::class, 'chargeToRoom'])->name('orders.chargeToRoom');
        Route::get('/properties/{property}/orders/{order}/receipt', [OrderController::class, 'receipt'])->name('properties.orders.receipt');
        Route::get('/properties/{property}/kitchen', [OrderController::class, 'kitchen'])->name('properties.kitchen');

        // Bill Splits
        Route::get('/properties/{property}/orders/{order}/bill-split', [BillSplitController::class, 'index'])->name('bill-splits.index');
        Route::post('/properties/{property}/orders/{order}/bill-split', [BillSplitController::class, 'store'])->name('bill-splits.store');
        Route::delete('/properties/{property}/orders/{order}/bill-split/{billSplit}', [BillSplitController::class, 'destroy'])->name('bill-splits.destroy');
        Route::patch('/properties/{property}/orders/{order}/bill-split/{billSplit}/status', [BillSplitController::class, 'updateStatus'])->name('bill-splits.updateStatus');
    });

    // Bar Orders (Bar Staff Only)
    Route::middleware('bar_staff')->group(function () {
        Route::get('/properties/{property}/bar-orders', [BarOrderController::class, 'index'])->name('properties.bar-orders.index');
        Route::get('/properties/{property}/bar-orders/pos', [BarOrderController::class, 'pos'])->name('properties.bar-orders.pos');
        Route::post('/properties/{property}/bar-orders/create-with-items', [BarOrderController::class, 'createWithItems'])->name('properties.bar-orders.createWithItems');
        Route::post('/properties/{property}/bar-orders/{order}/add-items', [BarOrderController::class, 'addItems'])->name('properties.bar-orders.addItems');
        Route::delete('/properties/{property}/bar-orders/{order}/remove-item/{inventoryItem}', [BarOrderController::class, 'removeItem'])->name('properties.bar-orders.removeItem');
        Route::post('/properties/{property}/bar-orders/bulk-delete', [BarOrderController::class, 'bulkDelete'])->name('properties.bar-orders.bulk-delete');
        Route::get('/properties/{property}/bar-orders/unpaid', [BarOrderController::class, 'getUnpaidOrders'])->name('properties.bar-orders.unpaid');
        Route::get('/properties/{property}/bar-orders/{order}/reopen', [BarOrderController::class, 'reopenOrder'])->name('properties.bar-orders.reopen');
        Route::get('/properties/{property}/bar-orders/{order}/continue', [BarOrderController::class, 'continueOrder'])->name('properties.bar-orders.continue');
        Route::get('/properties/{property}/bar-orders/{order}/receipt', [BarOrderController::class, 'receipt'])->name('properties.bar-orders.receipt');
        Route::get('/properties/{property}/bar-orders/{order}', [BarOrderController::class, 'show'])->name('properties.bar-orders.show');
        Route::patch('/properties/{property}/bar-orders/{order}/status', [BarOrderController::class, 'updateStatus'])->name('properties.bar-orders.updateStatus');
        Route::delete('/properties/{property}/bar-orders/{order}', [BarOrderController::class, 'destroy'])->name('properties.bar-orders.destroy');
    });

    // Promotions
    Route::resource('properties.promotions', PromotionController::class);
    Route::post('/properties/{property}/promotions/validate-code', [PromotionController::class, 'validateCode'])->name('promotions.validateCode');

    // Charge Order to Room
    Route::post('/properties/{property}/orders/{order}/charge-to-room', [OrderController::class, 'chargeToRoom'])->name('orders.chargeToRoom');

    // Menus (Restaurant Staff Only)
    Route::middleware('restaurant_staff')->group(function () {
        Route::resource('properties.menus', MenuController::class);
        Route::resource('properties.menus.categories', MenuCategoryController::class);
        Route::resource('properties.menus.categories.items', MenuItemController::class);

        // Restaurant Tables
        Route::resource('properties.restaurant-tables', RestaurantTableController::class);
        Route::get('/properties/{property}/restaurant-tables/layout/view', [RestaurantTableController::class, 'layout'])->name('properties.restaurant-tables.layout');
        Route::post('/properties/{property}/restaurant-tables/bulk-create', [RestaurantTableController::class, 'bulkCreate'])->name('properties.restaurant-tables.bulk-create');

        // Restaurant Reservations
        Route::resource('properties.restaurant-reservations', RestaurantReservationController::class);
        Route::patch('/properties/{property}/restaurant-reservations/{reservation}/status', [RestaurantReservationController::class, 'updateStatus'])->name('properties.restaurant-reservations.updateStatus');
    });

    // Inventory
    Route::resource('properties.inventory', InventoryController::class, ['parameters' => ['inventory' => 'item']]);
    Route::resource('properties.inventory-types', InventoryTypeController::class);
    Route::post('/properties/{property}/inventory/bulk-delete', [InventoryController::class, 'bulkDelete'])->name('properties.inventory.bulk-delete');
    Route::post('/properties/{property}/inventory/{item}/usage', [InventoryController::class, 'recordUsage'])->name('inventory.usage');
    Route::post('/properties/{property}/inventory/{item}/adjustment', [InventoryController::class, 'recordAdjustment'])->name('inventory.adjustment');
    Route::get('/properties/{property}/inventory/{item}/logs', [InventoryController::class, 'logs'])->name('inventory.logs');

    // Stock Sessions
    Route::resource('properties.stock-sessions', StockSessionController::class);
    Route::get('/properties/{property}/stock-sessions/{session}/report', [StockSessionController::class, 'report'])->name('properties.stock-sessions.report');
    Route::post('/properties/{property}/stock-sessions/{session}/adjustment', [StockSessionController::class, 'recordAdjustment'])->name('properties.stock-sessions.adjustment');

    // Guests
    Route::resource('properties.guests', GuestController::class);
    Route::post('/properties/{property}/guests/quick-create', [GuestController::class, 'quickCreate'])->name('guests.quickCreate');

    // Custom Fields
    Route::get('/properties/{property}/custom-fields/{entityType}', [CustomFieldController::class, 'index'])->name('properties.custom-fields.index');
    Route::get('/properties/{property}/custom-fields/{entityType}/create', [CustomFieldController::class, 'create'])->name('properties.custom-fields.create');
    Route::post('/properties/{property}/custom-fields/{entityType}', [CustomFieldController::class, 'store'])->name('properties.custom-fields.store');
    Route::get('/properties/{property}/custom-fields/{customField}/edit', [CustomFieldController::class, 'edit'])->name('properties.custom-fields.edit');
    Route::patch('/properties/{property}/custom-fields/{customField}', [CustomFieldController::class, 'update'])->name('properties.custom-fields.update');
    Route::delete('/properties/{property}/custom-fields/{customField}', [CustomFieldController::class, 'destroy'])->name('properties.custom-fields.destroy');

    // Daily Summary
    Route::get('/properties/{property}/daily-summary', [DailySummaryController::class, 'show'])->name('admin.daily-summary');
    Route::get('/properties/{property}/daily-sales-summary', [DashboardController::class, 'dailySalesSummary'])->name('dashboard.daily-sales-summary');

    // Notifications
    Route::get('/properties/{property}/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/properties/{property}/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::post('/properties/{property}/notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/properties/{property}/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/properties/{property}/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/properties/{property}/bookings/{booking}/notify-email', [NotificationController::class, 'sendEmailNotification'])->name('bookings.notify-email');
    Route::get('/properties/{property}/bookings/{booking}/notify-whatsapp', [NotificationController::class, 'sendWhatsApp'])->name('bookings.notify-whatsapp');
    Route::get('/properties/{property}/bookings/{booking}/whatsapp-url', [NotificationController::class, 'getWhatsAppUrl'])->name('bookings.whatsapp-url');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
