<?php
session_start();

echo "<h2>🧹 Xóa Session Admin</h2>";
echo "<hr>";

// Hiển thị session hiện tại
echo "<h4>Session hiện tại:</h4>";
echo "Session ID: " . session_id() . "<br>";
echo "Admin logged in: " . (isset($_SESSION['admin_logged_in']) ? 'YES' : 'NO') . "<br>";
echo "Admin username: " . ($_SESSION['admin_username'] ?? 'NOT SET') . "<br>";
echo "Admin ID: " . ($_SESSION['admin_id'] ?? 'NOT SET') . "<br>";

echo "<br>";

// Xóa session admin
if (isset($_SESSION['admin_logged_in'])) {
    unset($_SESSION['admin_logged_in']);
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
    
    echo "<h4>✅ Đã xóa session admin!</h4>";
    echo "<p>Bây giờ bạn có thể đăng nhập lại.</p>";
} else {
    echo "<h4>ℹ️ Không có session admin để xóa</h4>";
}

echo "<br><hr>";
echo "<h3>🔗 Liên kết:</h3>";
echo "<a href='index.php' style='background: #2196f3; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>🔐 Đăng nhập Admin</a>";
echo "<a href='../index.php' style='background: #ff9800; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🏠 Về trang chủ</a>";

// Redirect sau 3 giây
echo "<br><br><p>⏰ Tự động chuyển hướng sau 3 giây...</p>";
echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 3000);</script>";
?>
