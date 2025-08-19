<?php
header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');

require_once "config/database.php";

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8mb4");
    
    $page = $_GET['page'] ?? 'home';
    
    // Chỉ include header nếu không phải trang login/register
    if ($page !== 'login' && $page !== 'register') {
        include "views/header.php";
    }

    switch ($page) {
        case 'login':
            include "views/login.php";
            break;
        case 'register':
            include "views/register.php";
            break;
        case 'product':
            // Lấy sản phẩm từ database mmo
            $itemsPerPage = 10; 
            $currentPage = $_GET['page_num'] ?? 1;
            $offset = ($currentPage - 1) * $itemsPerPage;
            
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM products");
            $stmt->execute();
            $totalProducts = $stmt->fetch()['total'];
            $totalPages = ceil($totalProducts / $itemsPerPage);
            
            $stmt = $pdo->prepare("SELECT * FROM products ORDER BY created_at DESC LIMIT ? OFFSET ?");
            $stmt->execute([$itemsPerPage, $offset]);
            $products = $stmt->fetchAll();
            
            include "views/product.php";
            break;
        case 'cart':
            include "views/cart.php";
            break;
        case 'checkout':
            include "views/checkout.php";
            break;
        case 'search':
            $searchTerm = $_GET['term'] ?? '';
            $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ? OR category LIKE ?");
            $searchPattern = "%$searchTerm%";
            $stmt->execute([$searchPattern, $searchPattern, $searchPattern]);
            $products = $stmt->fetchAll();
            
            $totalProducts = null;
            $totalPages = null;
            include "views/product.php";
            break;
        default:
            // Trang chủ - hiển thị tất cả sản phẩm
            $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
            $products = $stmt->fetchAll();
            
            $totalProducts = null;
            $totalPages = null;
            include "views/home.php";
            break;
    }

    // Chỉ include footer nếu không phải trang login/register
    if ($page !== 'login' && $page !== 'register') {
        include "views/footer.php";
    }
    
} catch (PDOException $e) {
    echo "<h2>❌ Lỗi kết nối database!</h2>";
    echo "<p><strong>Lỗi:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Hãy kiểm tra:</p>";
    echo "<ul>";
    echo "<li>XAMPP có đang chạy không?</li>";
    echo "<li>Database 'mmo' đã được tạo chưa?</li>";
    echo "<li>Bảng 'products' đã được tạo chưa?</li>";
    echo "</ul>";
    echo "<p><a href='setup_admin.php' style='background: #1db954; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔧 Chạy Setup Admin</a></p>";
}
?>
