<?php
/**
 * Database Check & Admin User Creation Script
 * Upload to server and run: https://sales-portal.kuwaitcabinhotel.com/check_db.php
 * DELETE THIS FILE AFTER USE!
 */

$dbHost = '127.0.0.1';
$dbName = 'sales-protal_DB88';
$dbUser = 'sales-protal_DB88';
$dbPass = 'sales-protal_DB88';

echo "<html><head><title>DB Check</title>";
echo "<style>body{font-family:Arial;padding:20px;}table{border-collapse:collapse;margin:10px 0;}td,th{border:1px solid #ddd;padding:8px;text-align:left;}.success{color:green;}.error{color:red;}</style></head><body>";
echo "<h1>🔍 Database Check</h1>";

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p class='success'>✅ Database connected!</p>";

    // Check tables
    echo "<h2>📊 Tables in Database</h2>";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<p>Found <strong>" . count($tables) . "</strong> tables</p>";
    if (count($tables) == 0) {
        echo "<p class='error'>❌ No tables found! Run mysql_migrate.php first.</p>";
    } else {
        echo "<details><summary>View tables</summary><ul>";
        foreach ($tables as $t) echo "<li>$t</li>";
        echo "</ul></details>";
    }

    // Check users table
    echo "<h2>👥 Users Table</h2>";
    if (in_array('users', $tables)) {
        $users = $pdo->query("SELECT id, name, email, role_id, status FROM users")->fetchAll(PDO::FETCH_ASSOC);
        echo "<p>Found <strong>" . count($users) . "</strong> users</p>";
        
        if (count($users) > 0) {
            echo "<table><tr><th>ID</th><th>Name</th><th>Email</th><th>Role ID</th><th>Status</th></tr>";
            foreach ($users as $u) {
                echo "<tr><td>{$u['id']}</td><td>{$u['name']}</td><td>{$u['email']}</td><td>{$u['role_id']}</td><td>{$u['status']}</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='error'>❌ No users found!</p>";
        }
    } else {
        echo "<p class='error'>❌ Users table not found!</p>";
    }

    // Check roles table
    echo "<h2>🎭 Roles Table</h2>";
    if (in_array('roles', $tables)) {
        $roles = $pdo->query("SELECT id, name, display_name FROM roles")->fetchAll(PDO::FETCH_ASSOC);
        echo "<p>Found <strong>" . count($roles) . "</strong> roles</p>";
        if (count($roles) > 0) {
            echo "<table><tr><th>ID</th><th>Name</th><th>Display Name</th></tr>";
            foreach ($roles as $r) {
                echo "<tr><td>{$r['id']}</td><td>{$r['name']}</td><td>{$r['display_name']}</td></tr>";
            }
            echo "</table>";
        }
    }

    // Check properties table
    echo "<h2>🏨 Properties Table</h2>";
    if (in_array('properties', $tables)) {
        $props = $pdo->query("SELECT id, name, display_name FROM properties")->fetchAll(PDO::FETCH_ASSOC);
        echo "<p>Found <strong>" . count($props) . "</strong> properties</p>";
        if (count($props) > 0) {
            echo "<table><tr><th>ID</th><th>Name</th><th>Display Name</th></tr>";
            foreach ($props as $p) {
                echo "<tr><td>{$p['id']}</td><td>{$p['name']}</td><td>{$p['display_name']}</td></tr>";
            }
            echo "</table>";
        }
    }

    // Create admin user form
    echo "<hr><h2>➕ Create Admin User</h2>";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_admin'])) {
        $email = $_POST['email'];
        $name = $_POST['name'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        
        // Get admin role id
        $adminRole = $pdo->query("SELECT id FROM roles WHERE name = 'admin' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        $roleId = $adminRole ? $adminRole['id'] : 1;
        
        // Get first property
        $property = $pdo->query("SELECT id FROM properties LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        $propertyId = $property ? $property['id'] : 1;
        
        try {
            $stmt = $pdo->prepare("INSERT INTO users (property_id, name, email, password, role_id, status, email_verified_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, 'active', NOW(), NOW(), NOW())");
            $stmt->execute([$propertyId, $name, $email, $password, $roleId]);
            echo "<p class='success'>✅ Admin user created! Email: <strong>$email</strong></p>";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate') !== false) {
                // Update existing user
                $stmt = $pdo->prepare("UPDATE users SET password = ?, role_id = ?, status = 'active' WHERE email = ?");
                $stmt->execute([$password, $roleId, $email]);
                echo "<p class='success'>✅ User updated with new password!</p>";
            } else {
                echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
            }
        }
    }
    
    echo "<form method='POST'>
        <input type='hidden' name='create_admin' value='1'>
        <p><label>Name: <input type='text' name='name' value='Admin' required></label></p>
        <p><label>Email: <input type='email' name='email' value='admin@hotel.test' required></label></p>
        <p><label>Password: <input type='text' name='password' value='password123' required></label></p>
        <p><button type='submit' style='background:#22c55e;color:white;padding:10px 20px;border:none;cursor:pointer;'>Create/Update Admin User</button></p>
    </form>";

} catch (PDOException $e) {
    echo "<p class='error'>❌ Database Error: " . $e->getMessage() . "</p>";
}

echo "<hr><p><strong>⚠️ DELETE this file after use!</strong></p>";
echo "</body></html>";

