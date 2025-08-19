<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../index.php');
    exit;
}

require_once '../config/database.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8mb4");

    // Get data
    $totalUsers = 0;
    $totalProducts = 0;
    $recentUsers = [];
    $recentProducts = [];
    
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
        $totalUsers = $stmt->fetch()['total'];
        
        $stmt = $pdo->query("SELECT username, email, created_at FROM users ORDER BY created_at DESC LIMIT 5");
        $recentUsers = $stmt->fetchAll();
    } catch (Exception $e) {
        // Table doesn't exist yet
    }
    
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM products");
        $totalProducts = $stmt->fetch()['total'];
        
        $stmt = $pdo->query("SELECT id, name, price, category, stock FROM products ORDER BY created_at DESC LIMIT 5");
        $recentProducts = $stmt->fetchAll();
    } catch (Exception $e) {
        // Table doesn't exist yet
    }

} catch (Exception $e) {
    $error = "Database connection error: " . $e->getMessage();
    error_log("Dashboard Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MMO Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: bold; color: #1db954 !important; }
        .sidebar { 
            min-height: 100vh; 
            background: linear-gradient(135deg, #1db954, #1ed760); 
            color: white; 
        }
        .sidebar .nav-link { 
            color: rgba(255,255,255,0.8); 
            padding: 12px 20px; 
            margin: 2px 0; 
            border-radius: 8px; 
            transition: all 0.3s ease; 
        }
        .sidebar .nav-link:hover { 
            color: white; 
            background: rgba(255,255,255,0.1); 
            transform: translateX(5px); 
        }
        .sidebar .nav-link.active { 
            background: rgba(255,255,255,0.2); 
            color: white; 
        }
        .card { 
            border: none; 
            border-radius: 15px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.1); 
            transition: transform 0.3s ease; 
        }
        .card:hover { transform: translateY(-5px); }
        .stat-card { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            color: white; 
        }
        .stat-card.users { background: linear-gradient(135deg, #2196f3, #21cbf3); }
        .stat-card.products { background: linear-gradient(135deg, #4caf50, #45a049); }
        .stat-card.revenue { background: linear-gradient(135deg, #ff9800, #f57c00); }
        .stat-card.orders { background: linear-gradient(135deg, #9c27b0, #7b1fa2); }
        .stat-label { font-size: 0.9rem; opacity: 0.9; margin-bottom: 5px; }
        .stat-number { font-size: 2rem; font-weight: bold; margin: 0; }
        .stat-icon { font-size: 2.5rem; opacity: 0.8; }
        .page-title { color: #333; margin-bottom: 30px; font-weight: 600; }
        .recent-section { margin-top: 30px; }
        .btn-admin { 
            background: linear-gradient(135deg, #1db954, #1ed760); 
            border: none; 
            color: white; 
            padding: 12px 20px; 
            border-radius: 8px; 
            transition: all 0.3s ease; 
        }
        .btn-admin:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 5px 15px rgba(29, 185, 84, 0.3); 
            color: white; 
        }
        .btn-edit, .btn-delete { 
            padding: 5px 10px; 
            border-radius: 5px; 
            text-decoration: none; 
            margin: 0 2px; 
        }
        .btn-edit { background: #2196f3; color: white; }
        .btn-delete { background: #f44336; color: white; }
        .price-vnd { font-weight: bold; color: #4caf50; }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-gamepad me-2"></i>MMO Services Admin
            </a>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-2"></i><?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../index.php"><i class="fas fa-home me-2"></i>Về trang chủ</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="products.php">
                                <i class="fas fa-box me-2"></i>Quản lý sản phẩm
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="categories.php">
                                <i class="fas fa-tags me-2"></i>Danh mục
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="users.php">
                                <i class="fas fa-users me-2"></i>Người dùng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="orders.php">
                                <i class="fas fa-shopping-cart me-2"></i>Đơn hàng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="settings.php">
                                <i class="fas fa-cog me-2"></i>Cài đặt
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="main-content">
                    <h1 class="page-title">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </h1>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Debug Info -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Debug Info:</strong><br>
                        Total Users: <?php echo $totalUsers; ?><br>
                        Total Products: <?php echo $totalProducts; ?><br>
                        Recent Users Count: <?php echo count($recentUsers); ?><br>
                        Recent Products Count: <?php echo count($recentProducts); ?><br>
                        Session Admin: <?php echo isset($_SESSION['admin_logged_in']) ? 'YES' : 'NO'; ?>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card users h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="stat-label">Tổng người dùng</div>
                                            <div class="stat-number"><?php echo number_format($totalUsers); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users stat-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card products h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="stat-label">Tổng sản phẩm</div>
                                            <div class="stat-number"><?php echo number_format($totalProducts); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-box stat-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Users -->
                    <div class="row recent-section">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Người dùng gần đây</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($recentUsers)): ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Username</th>
                                                        <th>Email</th>
                                                        <th>Ngày tạo</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($recentUsers as $user): ?>
                                                        <tr>
                                                            <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                            <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted">Chưa có người dùng nào.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Sản phẩm gần đây</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($recentProducts)): ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Tên sản phẩm</th>
                                                        <th>Giá</th>
                                                        <th>Danh mục</th>
                                                        <th>Thao tác</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($recentProducts as $product): ?>
                                                        <tr>
                                                            <td><strong><?php echo htmlspecialchars($product['name']); ?></strong></td>
                                                            <td class="price-vnd"><?php echo number_format($product['price']); ?> VND</td>
                                                            <td><?php echo htmlspecialchars($product['category']); ?></td>
                                                            <td>
                                                                <a href="product-edit.php?id=<?php echo $product['id']; ?>" class="btn-edit" title="Chỉnh sửa">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <a href="product-delete.php?id=<?php echo $product['id']; ?>" class="btn-edit" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted">Chưa có sản phẩm nào.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Thao tác nhanh</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <a href="product-add.php" class="btn btn-admin w-100">
                                                <i class="fas fa-plus me-2"></i>Thêm sản phẩm
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <a href="products.php" class="btn btn-admin w-100">
                                                <i class="fas fa-box me-2"></i>Quản lý sản phẩm
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <a href="users.php" class="btn btn-admin w-100">
                                                <i class="fas fa-user-plus me-2"></i>Quản lý người dùng
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <a href="orders.php" class="btn btn-admin w-100">
                                                <i class="fas fa-list me-2"></i>Xem đơn hàng
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

