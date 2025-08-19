<?php
session_start();
require_once '../config/database.php';

echo "<h2>🧪 Test Admin Login</h2>";
echo "<hr>";

// Test 1: Kiểm tra session hiện tại
echo "<h4>1. Session hiện tại:</h4>";
echo "Session ID: " . session_id() . "<br>";
echo "Admin logged in: " . (isset($_SESSION['admin_logged_in']) ? 'YES' : 'NO') . "<br>";
echo "Admin username: " . ($_SESSION['admin_username'] ?? 'NOT SET') . "<br>";
echo "<br>";

// Test 2: Kiểm tra database connection
echo "<h4>2. Test database connection:</h4>";
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8mb4");
    
    echo "✅ Kết nối database thành công!<br>";
    
    // Test 3: Kiểm tra bảng admin_users
    echo "<h4>3. Test bảng admin_users:</h4>";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM admin_users");
    $count = $stmt->fetch()['count'];
    echo "📊 Số lượng admin: $count<br>";
    
    if ($count > 0) {
        $stmt = $pdo->query("SELECT username, email FROM admin_users");
        $admins = $stmt->fetchAll();
        echo "👥 Danh sách admin:<br>";
        foreach ($admins as $admin) {
            echo "- {$admin['username']} ({$admin['email']})<br>";
        }
    }
    
    echo "<br>";
    
    // Test 4: Test đăng nhập
    echo "<h4>4. Test đăng nhập:</h4>";
    echo "<form method='POST' action='controllers/auth.php'>";
    echo "<input type='text' name='username' placeholder='Username' value='admin' style='padding: 5px; margin: 5px;'><br>";
    echo "<input type='password' name='password' placeholder='Password' value='admin1234' style='padding: 5px; margin: 5px;'><br>";
    echo "<button type='submit' style='background: #1db954; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin: 10px;'>🚀 Test Login</button>";
    echo "</form>";
    
    echo "<br><hr>";
    echo "<h3>🔗 Liên kết:</h3>";
    echo "<a href='index.php' style='background: #2196f3; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>🔐 Admin Login</a>";
    echo "<a href='dashboard_simple.php' style='background: #4caf50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>📊 Dashboard</a>";
    echo "<a href='../index.php' style='background: #ff9800; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🏠 Về trang chủ</a>";
    
} catch (PDOException $e) {
    echo "❌ Lỗi kết nối database: " . $e->getMessage() . "<br>";
}
?>
