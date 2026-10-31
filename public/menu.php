<?php
/**
 * Hotel Menu & Rooms Display
 * Standalone PHP - Place in /public folder
 * URL: https://kuwaitcabinhotel.com/menu.php
 */

// Database connection
$host = '127.0.0.1';
$dbname = 'sales-protal_DB88';
$username = 'sales-protal_DB88';
$password = 'sales-protal_DB88';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get property ID and view
$propertyId = isset($_GET['property']) ? (int)$_GET['property'] : 1;
$view = isset($_GET['view']) ? $_GET['view'] : 'home';

// Fetch Drinks from inventory_items (beverages) - include out of stock
$drinksQuery = $pdo->prepare("
    SELECT id, name, NULL as description, unit_cost as price, 'Beverages' as category_name,
           CASE WHEN quantity > 0 THEN 1 ELSE 0 END as is_available
    FROM inventory_items
    WHERE property_id = ? AND type = 'beverage' AND is_active = 1
    ORDER BY (quantity > 0) DESC, name
");
$drinksQuery->execute([$propertyId]);
$drinks = $drinksQuery->fetchAll(PDO::FETCH_ASSOC);

// Fetch Food
$foodQuery = $pdo->prepare("
    SELECT mi.id, mi.name, mi.description, mi.price, mc.name as category_name
    FROM menu_items mi
    LEFT JOIN menu_categories mc ON mi.menu_category_id = mc.id
    LEFT JOIN menus m ON mc.menu_id = m.id
    WHERE m.property_id = ? AND mi.is_available = 1
    AND (LOWER(mc.name) NOT LIKE '%drink%' AND LOWER(mc.name) NOT LIKE '%beverage%' AND LOWER(mc.name) NOT LIKE '%bar%')
    AND (LOWER(m.name) NOT LIKE '%bar%')
    ORDER BY mc.name, mi.name
");
$foodQuery->execute([$propertyId]);
$food = $foodQuery->fetchAll(PDO::FETCH_ASSOC);

// Fetch Rooms
$roomsQuery = $pdo->prepare("
    SELECT r.id, r.room_number, r.floor, rt.name as room_type, rt.base_price, rt.description, rt.capacity, rt.beds
    FROM rooms r
    LEFT JOIN room_types rt ON r.room_type_id = rt.id
    WHERE r.property_id = ? AND r.status = 'available'
    ORDER BY rt.name, r.room_number
");
$roomsQuery->execute([$propertyId]);
$rooms = $roomsQuery->fetchAll(PDO::FETCH_ASSOC);

// Get property info
$propertyQuery = $pdo->prepare("SELECT name, currency, logo_path, light_logo_path, phone, email, address, city, country, website, description FROM properties WHERE id = ?");
$propertyQuery->execute([$propertyId]);
$property = $propertyQuery->fetch(PDO::FETCH_ASSOC);
$currency = $property['currency'] ?? 'KWD';
$propertyName = $property['name'] ?? 'Our Hotel';
$phone = $property['phone'] ?? null;
$email = $property['email'] ?? null;
$address = $property['address'] ?? null;
$city = $property['city'] ?? null;
$country = $property['country'] ?? null;
$website = 'kuwaitcabinhotel.com';
$description = $property['description'] ?? null;

// Use external logo URL
$logoUrl = 'https://sales-portal.kuwaitcabinhotel.com/storage/logos/fMAXTHtNy0FspZixqCWJWgc5yT5I1HvOnyGXnF3f.png';

// Group items by category
function groupByCategory($items) {
    $grouped = [];
    foreach ($items as $item) {
        $cat = $item['category_name'] ?? 'Other';
        if (!isset($grouped[$cat])) {
            $grouped[$cat] = [];
        }
        $grouped[$cat][] = $item;
    }
    return $grouped;
}

$drinksByCategory = groupByCategory($drinks);
$foodByCategory = groupByCategory($food);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($propertyName) ?> - Menu & Rooms</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #ffffff;
            min-height: 100vh;
            color: #555;
        }
        
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        
        /* Header with Logo */
        header {
            text-align: center;
            padding: 40px 20px;
            background: #f8f9fa;
            border-radius: 20px;
            margin-bottom: 40px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }
        
        .logo {
            max-width: 180px;
            max-height: 120px;
            margin-bottom: 20px;
            border-radius: 10px;
        }
        
        .logo-placeholder {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #333, #666);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            margin: 0 auto 15px;
        }
        
        header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #444;
        }
        
        .tagline {
            color: #666;
            font-size: 1rem;
            margin-bottom: 25px;
            font-style: italic;
        }
        
        .contact-info {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #555;
            font-size: 0.9rem;
            transition: color 0.3s;
        }
        
        .contact-item:hover { color: #333; }
        
        .contact-item a {
            color: inherit;
            text-decoration: none;
        }
        
        .contact-item .icon { font-size: 1.2rem; }
        
        /* Navigation Buttons */
        .nav-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .nav-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px 30px;
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-radius: 50px;
            color: #555;
            font-size: 1.1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .nav-btn:hover {
            background: #907e7eff;
            border-color: #666;
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .nav-btn .icon { font-size: 1.5rem; }
        .nav-btn .count {
            background: rgba(0,0,0,0.1);
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        
        .nav-btn:hover .count {
            background: rgba(255,255,255,0.2);
        }
        
        /* Back Button */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #f0f0f0;
            border-radius: 30px;
            color: #555;
            text-decoration: none;
            margin-bottom: 25px;
            transition: all 0.3s;
        }
        
        .back-btn:hover {
            background: #e0e0e0;
        }
        
        /* Section Title */
        .section-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
            color: #444;
        }
        
        .section-title .icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #666, #888);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }
        
        .section-subtitle {
            color: #777;
            margin-bottom: 30px;
            font-size: 1rem;
        }
        
        /* QR Code Styles */
        .qr-container {
            display: flex;
            justify-content: center;
            padding: 20px;
        }
        
        .qr-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 5px 30px rgba(0,0,0,0.1);
            border: 1px solid #e8e8e8;
        }
        
        .qr-image {
            width: 300px;
            height: 300px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .qr-url {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 25px;
            word-break: break-all;
        }
        
        .qr-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .qr-download-btn {
            display: inline-block;
            padding: 12px 25px;
            background: #555;
            color: #fff;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .qr-download-btn:hover {
            background: #333;
            transform: translateY(-2px);
        }
        
        /* Category Section */
        .category-section {
            margin-bottom: 35px;
        }
        
        .category-title {
            font-size: 1.3rem;
            color: #666;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ddd;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        /* Items Grid */
        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .item-card {
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            border: 1px solid #e8e8e8;
            transition: all 0.3s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .item-card:hover {
            transform: translateY(-5px);
            border-color: #ccc;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .item-card.unavailable {
            opacity: 0.5;
            background: #f5f5f5;
        }
        
        .item-card.unavailable:hover {
            transform: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .unavailable-badge {
            display: inline-block;
            background: #e0e0e0;
            color: #888;
            font-size: 0.75rem;
            padding: 3px 10px;
            border-radius: 12px;
            margin-top: 8px;
        }
        
        .item-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: #444;
        }
        
        .item-desc {
            color: #777;
            font-size: 0.9rem;
            margin-bottom: 12px;
            line-height: 1.5;
        }
        
        .item-price {
            font-size: 1.4rem;
            font-weight: 500;
            color: #b75d44ff;
        }
        
        /* Room Card */
        .room-card {
            background: #fff;
            border-radius: 15px;
            padding: 25px;
            border: 1px solid #e8e8e8;
            transition: all 0.3s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .room-card:hover {
            transform: translateY(-5px);
            border-color: #ccc;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .room-number {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: #444;
        }
        
        .room-type {
            color: #555;
            font-size: 1rem;
            margin-bottom: 12px;
        }
        
        .room-badges {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }
        
        .badge {
            background: #f0f0f0;
            color: #555;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .room-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: #e53935;
        }
        
        .room-price span {
            font-size: 0.9rem;
            color: #888;
            font-weight: 400;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #888;
        }
        
        .empty-state .icon { font-size: 4rem; margin-bottom: 20px; }
        
        /* Footer */
        footer {
            text-align: center;
            padding: 30px;
            color: #999;
            font-size: 0.9rem;
            margin-top: 40px;
        }
        
        footer a { color: #555; text-decoration: none; }
        
        /* Responsive */
        @media (max-width: 768px) {
            .nav-buttons { flex-direction: column; }
            .nav-btn { justify-content: center; }
            .section-title { font-size: 1.5rem; }
            .items-grid { grid-template-columns: 1fr; }
            .contact-info { flex-direction: column; align-items: center; gap: 15px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header with Logo -->
        <header>
            <img src="<?= $logoUrl ?>" alt="<?= htmlspecialchars($propertyName) ?>" class="logo">
            <p class="tagline">
                <?php if ($description): ?>
                    <?= htmlspecialchars($description) ?>
                <?php else: ?>


                <?php endif; ?>
                                  <br>  Start your stay with a refreshing complimentary drinks served At our launch. Choose from our selection of fresh juices, cocktails, or premium beverages.

            </p>
            
            <div class="contact-info">
                <?php if ($phone): ?>
                    <div class="contact-item">
                        <span class="icon">📞</span>
                        <a href="tel:<?= htmlspecialchars($phone) ?>"><?= htmlspecialchars($phone) ?></a>
                    </div>
                <?php endif; ?>
                
                <?php if ($email): ?>
                    <div class="contact-item">
                        <span class="icon">✉️</span>
                        <a href="mailto:<?= htmlspecialchars($email) ?>"><?= htmlspecialchars($email) ?></a>
                    </div>
                <?php endif; ?>
                
                <?php if ($address || $city): ?>
                    <div class="contact-item">
                        <span class="icon">📍</span>
                        <span>
                            <?= htmlspecialchars($address) ?>
                            <?php if ($city): ?>, <?= htmlspecialchars($city) ?><?php endif; ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <div class="contact-item">
                    <span class="icon">🌐</span>
                    <a href="https://<?= htmlspecialchars($website) ?>" target="_blank"><?= htmlspecialchars($website) ?></a>
                </div>
            </div>
        </header>
        
        <?php if ($view === 'home'): ?>
        <!-- Home View - Category Buttons -->
        <div class="nav-buttons">
            <a href="?view=drinks&property=<?= $propertyId ?>" class="nav-btn">
                <span class="icon">🍹</span>
                <span>Drinks</span>
                <span class="count"><?= count($drinks) ?></span>
            </a>
            <a href="?view=food&property=<?= $propertyId ?>" class="nav-btn">
                <span class="icon">🍽️</span>
                <span>Food</span>
                <span class="count"><?= count($food) ?></span>
            </a>
            <a href="?view=rooms&property=<?= $propertyId ?>" class="nav-btn">
                <span class="icon">🛏️</span>
                <span>Rooms</span>
                <span class="count"><?= count($rooms) ?></span>
            </a>
            <a href="?view=qrcode&property=<?= $propertyId ?>" class="nav-btn">
                <span class="icon">📱</span>
                <span>QR Code</span>
            </a>
        </div>
        
        <?php elseif ($view === 'drinks'): ?>
        <!-- Drinks View -->
        <a href="?view=home&property=<?= $propertyId ?>" class="back-btn">← Back to Menu</a>
        
        <h2 class="section-title">
            <span class="icon">🍹</span>
            <span>Drinks Menu</span>
        </h2>
        <p class="section-subtitle">Refreshing beverages for every taste</p>
        
        <?php if (empty($drinks)): ?>
            <div class="empty-state">
                <div class="icon">🍹</div>
                <p>No drinks available at the moment</p>
            </div>
        <?php else: ?>
            <?php foreach ($drinksByCategory as $category => $items): ?>
                <div class="category-section">
                    <h3 class="category-title"><?= htmlspecialchars($category) ?></h3>
                    <div class="items-grid">
                        <?php foreach ($items as $item): ?>
                            <div class="item-card <?= empty($item['is_available']) ? 'unavailable' : '' ?>">
                                <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
                                <?php if ($item['description']): ?>
                                    <div class="item-desc"><?= htmlspecialchars($item['description']) ?></div>
                                <?php endif; ?>
                                <div class="item-price"><?= $currency ?> <?= number_format($item['price'], 2) ?></div>
                                <?php if (empty($item['is_available'])): ?>
                                    <span class="unavailable-badge">Not Available</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <?php elseif ($view === 'food'): ?>
        <!-- Food View -->
        <a href="?view=home&property=<?= $propertyId ?>" class="back-btn">← Back to Menu</a>
        
        <h2 class="section-title">
            <span class="icon">🍽️</span>
            <span>Food Menu</span>
        </h2>
        <p class="section-subtitle">Culinary delights prepared with passion</p>
        
        <?php if (empty($food)): ?>
            <div class="empty-state">
                <div class="icon">🍽️</div>
                <p>No food items available at the moment</p>
            </div>
        <?php else: ?>
            <?php foreach ($foodByCategory as $category => $items): ?>
                <div class="category-section">
                    <h3 class="category-title"><?= htmlspecialchars($category) ?></h3>
                    <div class="items-grid">
                        <?php foreach ($items as $item): ?>
                            <div class="item-card">
                                <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
                                <?php if ($item['description']): ?>
                                    <div class="item-desc"><?= htmlspecialchars($item['description']) ?></div>
                                <?php endif; ?>
                                <div class="item-price"><?= $currency ?> <?= number_format($item['price'], 2) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <?php elseif ($view === 'rooms'): ?>
        <!-- Rooms View -->
        <a href="?view=home&property=<?= $propertyId ?>" class="back-btn">← Back to Menu</a>
        
        <h2 class="section-title">
            <span class="icon">🛏️</span>
            <span>Available Rooms</span>
        </h2>
        <p class="section-subtitle">Comfortable accommodations for your stay</p>
        
        <?php if (empty($rooms)): ?>
            <div class="empty-state">
                <div class="icon">🛏️</div>
                <p>No rooms available at the moment</p>
            </div>
        <?php else: ?>
            <div class="items-grid">
                <?php foreach ($rooms as $room): ?>
                    <div class="room-card">
                        <div class="room-number">Room <?= htmlspecialchars($room['room_number']) ?></div>
                        <div class="room-type"><?= htmlspecialchars($room['room_type']) ?></div>
                        <div class="room-badges">
                            <?php if ($room['floor']): ?>
                                <span class="badge">Floor <?= $room['floor'] ?></span>
                            <?php endif; ?>
                            <?php if ($room['capacity']): ?>
                                <span class="badge">👥 <?= $room['capacity'] ?> guests</span>
                            <?php endif; ?>
                            <?php if ($room['beds']): ?>
                                <span class="badge">🛏️ <?= $room['beds'] ?> beds</span>
                            <?php endif; ?>
                        </div>
                        <div class="room-price">
                            <?= $currency ?> <?= number_format($room['base_price'], 2) ?>
                            <span>/ night</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php elseif ($view === 'qrcode'): ?>
        <!-- QR Code View -->
        <a href="?view=home&property=<?= $propertyId ?>" class="back-btn">← Back to Menu</a>
        
        <h2 class="section-title">
            <span class="icon">📱</span>
            <span>Scan QR Code</span>
        </h2>
        <p class="section-subtitle">Scan this QR code to access our menu on your phone</p>
        
        <?php
        $menuUrl = 'https://sales-portal.kuwaitcabinhotel.com/menu.php?property=' . $propertyId;
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($menuUrl);
        ?>
        
        <div class="qr-container">
            <div class="qr-card">
                <img src="<?= $qrCodeUrl ?>" alt="QR Code" class="qr-image">
                <p class="qr-url"><?= htmlspecialchars($menuUrl) ?></p>
                <div class="qr-actions">
                    <a href="<?= $qrCodeUrl ?>&format=png" download="menu-qrcode.png" class="qr-download-btn">
                        📥 Download QR Code
                    </a>
                    <a href="https://api.qrserver.com/v1/create-qr-code/?size=600x600&data=<?= urlencode($menuUrl) ?>&format=png" download="menu-qrcode-large.png" class="qr-download-btn">
                        📥 Download Large (Print)
                    </a>
                </div>
            </div>
        </div>
        
        <?php endif; ?>
        
        <footer>
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($propertyName) ?> &middot; <a href="https://<?= htmlspecialchars($website) ?>"><?= htmlspecialchars($website) ?></a></p>
        </footer>
    </div>
</body>
</html>

