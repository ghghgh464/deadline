<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../index.php');
    exit;
}

require_once '../config/database.php';

echo "<h2>🔍 Dashboard Debug - Kiểm tra dữ liệu</h2>";
echo "<hr>";

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8mb4");
    
    echo "<h3>✅ Kết nối database thành công!</h3>";
    
    // Test 1: Kiểm tra bảng users
    echo "<h4>1. Test bảng users:</h4>";
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
        $totalUsers = $stmt->fetch()['total'];
        echo "📊 Tổng users: $totalUsers<br>";
        
        if ($totalUsers > 0) {
            $stmt = $pdo->query("SELECT username, email, created_at FROM users ORDER BY created_at DESC LIMIT 5");
            $recentUsers = $stmt->fetchAll();
            echo "👥 Recent users:<br>";
            foreach ($recentUsers as $user) {
                echo "- {$user['username']} ({$user['email']}) - {$user['created_at']}<br>";
            }
        }
    } catch (Exception $e) {
        echo "❌ Lỗi users: " . $e->getMessage() . "<br>";
    }
    
    echo "<br>";
    
    // Test 2: Kiểm tra bảng products
    echo "<h4>2. Test bảng products:</h4>";
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM products");
        $totalProducts = $stmt->fetch()['total'];
        echo "📦 Tổng products: $totalProducts<br>";
        
        if ($totalProducts > 0) {
            $stmt = $pdo->query("SELECT id, name, price, category, stock FROM products ORDER BY created_at DESC LIMIT 5");
            $recentProducts = $stmt->fetchAll();
            echo "🛍️ Recent products:<br>";
            foreach ($recentProducts as $product) {
                echo "- ID: {$product['id']} - {$product['name']} - " . number_format($product['price']) . " VND - {$product['category']}<br>";
            }
        }
    } catch (Exception $e) {
        echo "❌ Lỗi products: " . $e->getMessage() . "<br>";
    }
    
    echo "<br>";
    
    // Test 3: Kiểm tra session
    echo "<h4>3. Test session:</h4>";
    echo "Session ID: " . session_id() . "<br>";
    echo "Admin logged in: " . ($_SESSION['admin_logged_in'] ? 'YES' : 'NO') . "<br>";
    echo "Admin username: " . ($_SESSION['admin_username'] ?? 'NOT SET') . "<br>";
    
    echo "<br><hr>";
    
    // Test 4: Hiển thị dashboard thật
    echo "<h3>🎯 Test hiển thị dashboard:</h3>";
    
    // Statistics Cards
    echo "<div style='display: flex; gap: 20px; margin: 20px 0;'>";
    echo "<div style='background: #e3f2fd; padding: 20px; border-radius: 10px; border: 1px solid #2196f3;'>";
    echo "<h4>👥 Tổng người dùng</h4>";
    echo "<h2 style='color: #2196f3; margin: 0;'>" . number_format($totalUsers) . "</h2>";
    echo "</div>";
    
    echo "<div style='background: #e8f5e8; padding: 20px; border-radius: 10px; border: 1px solid #4caf50;'>";
    echo "<h4>📦 Tổng sản phẩm</h4>";
    echo "<h2 style='color: #4caf50; margin: 0;'>" . number_format($totalProducts) . "</h2>";
    echo "</div>";
    echo "</div>";
    
    // Recent Users Table
    if (!empty($recentUsers)) {
        echo "<h4>👥 Người dùng gần đây:</h4>";
        echo "<table style='width: 100%; border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr style='background: #f5f5f5;'><th style='padding: 10px; border: 1px solid #ddd;'>Username</th><th style='padding: 10px; border: 1px solid #ddd;'>Email</th><th style='padding: 10px; border: 1px solid #ddd;'>Ngày tạo</th></tr>";
        foreach ($recentUsers as $user) {
            echo "<tr>";
            echo "<td style='padding: 10px; border: 1px solid #ddd;'><strong>{$user['username']}</strong></td>";
            echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$user['email']}</td>";
            echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . date('d/m/Y', strtotime($user['created_at'])) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>❌ Không có dữ liệu users để hiển thị</p>";
    }
    
    // Recent Products Table
    if (!empty($recentProducts)) {
        echo "<h4>📦 Sản phẩm gần đây:</h4>";
        echo "<table style='width: 100%; border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr style='background: #f5f5f5;'><th style='padding: 10px; border: 1px solid #ddd;'>Tên</th><th style='padding: 10px; border: 1px solid #ddd;'>Giá</th><th style='padding: 10px; border: 1px solid #ddd;'>Danh mục</th><th style='padding: 10px; border: 1px solid #ddd;'>Thao tác</th></tr>";
        foreach ($recentProducts as $product) {
            echo "<tr>";
            echo "<td style='padding: 10px; border: 1px solid #ddd;'><strong>{$product['name']}</strong></td>";
            echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . number_format($product['price']) . " VND</td>";
            echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$product['category']}</td>";
            echo "<td style='padding: 10px; border: 1px solid #ddd;'>";
            echo "<a href='product-edit.php?id={$product['id']}' style='color: #2196f3; margin-right: 10px;'>✏️</a>";
            echo "<a href='product-delete.php?id={$product['id']}' style='color: #f44336;' onclick='return confirm(\"Xóa?\")'>🗑️</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>❌ Không có dữ liệu products để hiển thị</p>";
    }
    
    echo "<br><hr>";
    echo "<h3>🔗 Liên kết:</h3>";
    echo "<a href='dashboard.php' style='background: #1db954; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>📊 Dashboard chính</a>";
    echo "<a href='../index.php' style='background: #2196f3; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🏠 Về trang chủ</a>";
    
} catch (PDOException $e) {
    echo "<h3>❌ Lỗi kết nối database!</h3>";
    echo "<p><strong>Lỗi:</strong> " . $e->getMessage() . "</p>";
}
?>
