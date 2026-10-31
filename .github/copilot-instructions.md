# Hotel Management System - AI Agent Instructions

## Architecture Overview

This is a **Laravel 12 hospitality management platform** with multi-tenant properties, combining hotel operations (bookings, check-in/out, room inventory) with restaurant/bar operations (orders, inventory, reservations).

### Core Domain Areas

**Bookings & Guests** (`BookingController`, `BookingService`, `Booking` model)
- Lifecycle: pending → confirmed → checked_in → checked_out (or cancelled)
- Creates a `Folio` (guest account) tracking room charges + additional charges
- Manages `RoomInventory` to block availability during stay
- Emits `BookingCreated` event → triggers email confirmation

**Orders & F&B Operations** (`OrderController`, `BarOrderController`, `OrderService`)
- Multiple order types: `dine_in` (restaurant tables), `takeaway`, `room_service`, `bar`
- Can be linked to bookings or standalone; charged to room via `Folio` if `charged_to_room=true`
- Tracks inventory consumption through `InventoryLog` for menu items with ingredients

**Inventory** (`InventoryController`, `InventoryService`)
- Tracks ingredients, beverages, supplies with SKU/quantity/reorder levels
- Records usage via `InventoryLog` with transaction safety (DB::transaction)
- Low stock triggers `LowStockAlert` notifications to property admins

**Property-Scoped Multi-Tenancy**
- All resources (bookings, orders, rooms, inventory) filtered by `property_id`
- Controllers receive `Property` as route parameter and enforce isolation

## Repository & Service Patterns

### Data Access: Repository Layer
All repositories extend `BaseRepository` and follow a consistent interface:

```php
// app/Repositories/{Entity}Repository extends BaseRepository
public function getModel(): string { return Entity::class; }
// Custom query methods (getByProperty, getOrdersByPropertyAndDate, etc.)
```

**Key repositories**: `BookingRepository`, `OrderRepository`, `InventoryRepository`
- Use `->paginate(15)` for list endpoints (default page size)
- Eager-load relationships: `->with(['guest', 'room', 'folio.items'])`
- Filter by property first in all queries

### Business Logic: Service Layer
Services orchestrate repositories and handle transactions:

```php
// app/Services/{Entity}Service
private {Entity}Repository $repository;
// Methods trigger DB::transaction for multi-step operations
// Dispatch events after state changes (BookingCreated, OrderStatusUpdated)
```

**Critical patterns**:
- Use `DB::transaction()` when modifying related models (booking reserves inventory + creates folio)
- Call `repository->create()` inside services, not directly in controllers
- Notify inventory low-stock in `InventoryService::recordUsage()`

## Controllers & Routing

### Web Routes Structure
- Property context required: `routes/properties/{property}/resource/{id}`
- Dashboard → Property selection → Resource management
- Inline forms (create/edit in same view) typical; redirects to list after save

### API Endpoints (`/api/v1`)
- Booking: `POST /bookings/search`, `POST /bookings`, `GET /bookings/{id}`, `POST /bookings/{id}/confirm`
- Orders: `POST /orders`, `POST /orders/{id}/items`, `PATCH /orders/{id}/status`
- JSON responses with `success: true/false`, `message`, and data payload
- Validation errors return 400 with message; exceptions caught in try-catch

## Event-Driven Patterns

**EventServiceProvider** maps events to listeners:

```php
BookingCreated::class => [SendBookingConfirmationEmail::class]
OrderStatusUpdated::class => [LogOrderStatusChange::class]
```

**Fire events from services:**
```php
// In BookingService::createBooking()
event(new BookingCreated($booking));
```

Listeners handle email, logging, and notifications—never inline in controllers.

## Database Conventions

### Decimal Fields
Use `'decimal:2'` casts in models for currency:
```php
'subtotal' => 'decimal:2', 'tax' => 'decimal:2', 'total_amount' => 'decimal:2'
```

### Date Fields
Use native `'date'` or `'datetime'` casts; Carbon objects are automatic:
```php
'check_in_date' => 'date', 'check_out_date' => 'date', 'paid_at' => 'datetime'
```

### Fillable Arrays
Define all mutable attributes; include foreign keys (`property_id`, `user_id`, `guest_id`).

### Relationships
Standard Eloquent: `belongsTo()`, `hasMany()`, `hasOne()`. Eager-load in controllers to avoid N+1.

## Testing & Quality

**Test Structure**: `tests/Unit/` and `tests/Feature/`

**Run tests**:
```bash
composer test    # Clears config, runs phpunit
php artisan test # Direct phpunit wrapper
```

**Testing config** (`phpunit.xml`): SQLite in-memory DB, array mail/cache/session drivers for isolation.

**Linting**: Use Laravel Pint for formatting:
```bash
php artisan pint  # Auto-format PSR-12 compliance
```

## Development Workflow

### Local Setup
```bash
composer setup    # Install deps, generate key, run migrations, npm install, build assets
composer dev      # Runs concurrently: artisan serve + queue:listen + pail (logs) + npm dev
```

**Key tasks**:
- `php artisan serve` → HTTP server on 8000
- `php artisan queue:listen` → Process async jobs (emails, notifications)
- `php artisan pail` → Real-time log stream
- `npm run dev` → Vite hot-reload for Tailwind CSS + Alpine.js

### Build & Frontend
- Vite config (`vite.config.js`) watches `resources/css/app.css` and `resources/js/app.js`
- Tailwind CSS 3.1 + Alpine.js 3.4 for interactivity
- `npm run build` → Production assets in `public/build/`
- Laravel Vite plugin auto-injects assets; no manual `@vite` needed in most views

## File Organization

- **Models**: `app/Models/` (Booking, Order, Folio, InventoryItem, etc.)
- **Controllers**: `app/Http/Controllers/` + `Api/` subdirectory for REST endpoints
- **Services**: `app/Services/BookingService.php`, etc.
- **Repositories**: `app/Repositories/BaseRepository.php` (abstract), specific repos extend it
- **Views**: `resources/views/` (Blade templates, Tailwind-styled)
- **Routes**: `routes/web.php` (web), `routes/api.php` (v1 API)
- **Migrations**: `database/migrations/`
- **Mail**: `app/Mail/BookingConfirmation.php`
- **Events/Listeners**: `app/Events/`, `app/Listeners/`

## Common Development Patterns

### Adding a New Feature
1. Create migration + model with relationships
2. Extend `BaseRepository` for data access (custom query methods)
3. Create `Service` class with transaction-wrapped business logic
4. Build controller(s) using `__construct(private Service $service)`
5. Add routes in `web.php` or `api.php`
6. Fire events from service after state changes
7. Add Unit/Feature tests in `tests/`

### Modifying Bookings
- Always verify guest `check_in_date <= now() <= check_out_date` for active bookings
- Update `RoomInventory` records when dates change
- Recalculate `Folio` balances if rates/nights change
- Dispatch `BookingCreated` only on initial creation; use different events for updates

### Adding Charges to Guest Bills
- Create `FolioItem` record linked to booking's `Folio`
- Update `Folio->total_charges` and `balance` (balance = charges - payments)
- Reference order via polymorphic relation if from restaurant/bar order

### Managing Low Inventory
- `InventoryService::recordUsage()` auto-checks stock level after decrement
- Sends `LowStockAlert` notification only if `quantity <= reorder_level`
- Retrieve low-stock items: `$inventoryService->getLowStockItems($propertyId)`

## Dependencies & Versions

- **PHP**: 8.2+ (typed properties, match expressions)
- **Laravel**: 12.0 (latest stable; uses native routing, eloquent, queues)
- **Frontend**: Vite 7.0, Tailwind CSS 3.1, Alpine.js 3.4
- **PDF**: barryvdh/laravel-dompdf 3.1 (for folio/invoice exports)
- **Testing**: PHPUnit 11.5, Mockery, Faker

## Installation Wizard Flow

Auto-detected on first run if `.env` missing:
- `install/database` → test DB connection, run migrations
- `install/environment` → set up config
- `install/admin` → create initial admin user
- Controllers: `InstallationController` in `routes/web.php`

---

**For questions on architectural decisions**, refer to specific service/repository implementations—they document the "why" through transaction patterns, event mappings, and relationship design.
