<?php
// File thiết lập admin cho MMO Services
require_once 'config/database.php';

try {
    // Kết nối database
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8mb4");
    
    echo "<h2>🔧 Thiết lập Database MMO Services</h2>";
    echo "<hr>";
    
    // 1. Tạo bảng admin_users
    echo "<h3>1. Tạo bảng admin_users...</h3>";
    $sql = "CREATE TABLE IF NOT EXISTS admin_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100),
        full_name VARCHAR(100),
        is_active TINYINT(1) DEFAULT 1,
        role ENUM('admin', 'moderator') DEFAULT 'admin',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    echo "✅ Bảng admin_users đã được tạo thành công!<br><br>";
    
    // 2. Tạo bảng users
    echo "<h3>2. Tạo bảng users...</h3>";
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        full_name VARCHAR(100),
        phone VARCHAR(20),
        address TEXT,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    echo "✅ Bảng users đã được tạo thành công!<br><br>";
    
    // 3. Tạo bảng products
    echo "<h3>3. Tạo bảng products...</h3>";
    $sql = "CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        image VARCHAR(255),
        category VARCHAR(100),
        stock INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    echo "✅ Bảng products đã được tạo thành công!<br><br>";
    
    // 4. Tạo tài khoản admin
    echo "<h3>4. Tạo tài khoản admin...</h3>";
    
    // Kiểm tra xem admin đã tồn tại chưa
    $stmt = $pdo->prepare("SELECT id FROM admin_users WHERE username = ?");
    $stmt->execute(['admin']);
    $existingAdmin = $stmt->fetch();
    
    if (!$existingAdmin) {
        // Tạo password hash cho admin1234
        $adminPassword = password_hash('admin1234', PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO admin_users (username, password, email, full_name, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['admin', $adminPassword, 'admin@mmoservices.com', 'Administrator', 'admin']);
        
        echo "✅ Tài khoản admin đã được tạo thành công!<br>";
        echo "👤 Username: <strong>admin</strong><br>";
        echo "🔑 Password: <strong>admin1234</strong><br><br>";
    } else {
        echo "ℹ️ Tài khoản admin đã tồn tại!<br>";
        echo "👤 Username: <strong>admin</strong><br>";
        echo "🔑 Password: <strong>admin1234</strong><br><br>";
    }
    
    // 5. Tạo tài khoản user mẫu
    echo "<h3>5. Tạo tài khoản user mẫu...</h3>";
    
    $userPasswords = [
        'user1' => 'password123',
        'user2' => 'password123'
    ];
    
    foreach ($userPasswords as $username => $password) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $existingUser = $stmt->fetch();
        
        if (!$existingUser) {
            $userPasswordHash = password_hash($password, PASSWORD_DEFAULT);
            $email = $username . '@example.com';
            $fullName = $username === 'user1' ? 'Nguyễn Văn A' : 'Trần Thị B';
            $phone = $username === 'user1' ? '0123456789' : '0987654321';
            
            $sql = "INSERT INTO users (username, password, email, full_name, phone) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $userPasswordHash, $email, $fullName, $phone]);
            
            echo "✅ User <strong>$username</strong> đã được tạo (Password: <strong>$password</strong>)<br>";
        } else {
            echo "ℹ️ User <strong>$username</strong> đã tồn tại<br>";
        }
    }
    
    echo "<br>";
    
    // 6. Tạo sản phẩm mẫu
    echo "<h3>6. Tạo sản phẩm mẫu...</h3>";
    
    $sampleProducts = [
        [
            'name' => 'Netflix Premium',
            'description' => 'Tài khoản Netflix Premium 4K, không giới hạn thiết bị, xem phim chất lượng cao',
            'price' => 150000,
            'image' => 'netflix.png',
            'category' => 'Streaming',
            'stock' => 50
        ],
        [
            'name' => 'Spotify Premium',
            'description' => 'Tài khoản Spotify Premium không quảng cáo, nghe nhạc chất lượng cao',
            'price' => 120000,
            'image' => 'spotify premium.png',
            'category' => 'Music',
            'stock' => 30
        ],
        [
            'name' => 'Microsoft 365',
            'description' => 'Gói Microsoft 365 Family với Word, Excel, PowerPoint, OneDrive 1TB',
            'price' => 800000,
            'image' => 'microsoft 365.png',
            'category' => 'Office',
            'stock' => 20
        ],
        [
            'name' => 'Canva Pro',
            'description' => 'Tài khoản Canva Pro cho thiết kế đồ họa chuyên nghiệp',
            'price' => 200000,
            'image' => 'canva pro.png',
            'category' => 'Design',
            'stock' => 25
        ],
        [
            'name' => 'CapCut Pro',
            'description' => 'Tài khoản CapCut Pro cho chỉnh sửa video chất lượng cao',
            'price' => 180000,
            'image' => 'capcut pro.png',
            'category' => 'Video',
            'stock' => 15
        ],
        [
            'name' => 'Xbox Game Pass Ultimate',
            'description' => 'Tài khoản Xbox Game Pass Ultimate 3 tháng, chơi game không giới hạn',
            'price' => 450000,
            'image' => 'xbox gamepass ultimate.png',
            'category' => 'Gaming',
            'stock' => 40
        ],
        [
            'name' => 'Google Drive',
            'description' => 'Google Drive 2TB lưu trữ đám mây an toàn',
            'price' => 300000,
            'image' => 'google drive.png',
            'category' => 'Storage',
            'stock' => 35
        ],
        [
            'name' => 'MB Bank',
            'description' => 'Tài khoản MB Bank với các tiện ích ngân hàng số',
            'price' => 100000,
            'image' => 'mb bank.png',
            'category' => 'Banking',
            'stock' => 10
        ],
        [
            'name' => 'MoMo',
            'description' => 'Tài khoản MoMo với ví điện tử và thanh toán online',
            'price' => 80000,
            'image' => 'momo.png',
            'category' => 'Payment',
            'stock' => 60
        ],
        [
            'name' => 'Tích Xanh',
            'description' => 'Tài khoản Tích Xanh cho doanh nghiệp và khởi nghiệp',
            'price' => 250000,
            'image' => 'tichxanh.png',
            'category' => 'Business',
            'stock' => 12
        ]
    ];
    
    foreach ($sampleProducts as $product) {
        $stmt = $pdo->prepare("SELECT id FROM products WHERE name = ?");
        $stmt->execute([$product['name']]);
        $existingProduct = $stmt->fetch();
        
        if (!$existingProduct) {
            $sql = "INSERT INTO products (name, description, price, image, category, stock) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $product['name'],
                $product['description'],
                $product['price'],
                $product['image'],
                $product['category'],
                $product['stock']
            ]);
            
            echo "✅ Sản phẩm <strong>{$product['name']}</strong> đã được tạo<br>";
        } else {
            echo "ℹ️ Sản phẩm <strong>{$product['name']}</strong> đã tồn tại<br>";
        }
    }
    
    echo "<br>";
    
    // 7. Hiển thị thông tin tài khoản
    echo "<h3>7. Thông tin tài khoản đã tạo:</h3>";
    
    echo "<h4>🔐 Admin Accounts:</h4>";
    $stmt = $pdo->query("SELECT username, email, role, created_at FROM admin_users");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($admins) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background: #f0f0f0;'><th>Username</th><th>Email</th><th>Role</th><th>Ngày tạo</th></tr>";
        foreach ($admins as $admin) {
            echo "<tr>";
            echo "<td><strong>{$admin['username']}</strong></td>";
            echo "<td>{$admin['email']}</td>";
            echo "<td>{$admin['role']}</td>";
            echo "<td>{$admin['created_at']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    echo "<br><h4>👥 User Accounts:</h4>";
    $stmt = $pdo->query("SELECT username, email, full_name, created_at FROM users LIMIT 5");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($users) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background: #f0f0f0;'><th>Username</th><th>Email</th><th>Họ tên</th><th>Ngày tạo</th></tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td><strong>{$user['username']}</strong></td>";
            echo "<td>{$user['email']}</td>";
            echo "<td>{$user['full_name']}</td>";
            echo "<td>{$user['created_at']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    echo "<br><h4>📦 Products Created:</h4>";
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM products");
    $totalProducts = $stmt->fetch()['total'];
    echo "✅ Tổng cộng <strong>$totalProducts</strong> sản phẩm đã được tạo!<br>";
    
    echo "<br><hr>";
    echo "<h3>🎉 Thiết lập hoàn tất!</h3>";
    echo "<p><strong>Bây giờ bạn có thể:</strong></p>";
    echo "<ul>";
    echo "<li>👤 <strong>Đăng nhập Admin:</strong> username: <code>admin</code>, password: <code>admin1234</code></li>";
    echo "<li>👤 <strong>Đăng nhập User:</strong> username: <code>user1</code>, password: <code>password123</code></li>";
    echo "<li>🏠 <strong>Xem trang chủ:</strong> <a href='index.php'>http://localhost/tf/index.php</a></li>";
    echo "<li>📱 <strong>Xem sản phẩm:</strong> <a href='index.php?page=product'>http://localhost/tf/index.php?page=product</a></li>";
    echo "<li>⚙️ <strong>Admin Panel:</strong> <a href='admin/dashboard.php'>http://localhost/tf/admin/dashboard.php</a></li>";
    echo "</ul>";
    
    echo "<p><a href='index.php' style='background: #1db954; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>🏠 Về trang chủ</a>";
    echo "<a href='admin/dashboard.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>⚙️ Admin Panel</a></p>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Lỗi kết nối database!</h2>";
    echo "<p><strong>Lỗi:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Hãy kiểm tra:</p>";
    echo "<ul>";
    echo "<li>XAMPP có đang chạy không?</li>";
    echo "<li>Database 'mmo' đã được tạo chưa?</li>";
    echo "<li>Thông tin kết nối trong config/database.php có đúng không?</li>";
    echo "</ul>";
}
?>
