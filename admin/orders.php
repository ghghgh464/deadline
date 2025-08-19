<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

require_once '../Model/Database.php';
require_once '../config/database.php';

try {
    $db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);
    $conn = $db->getConnection();
    
    // Check if orders table exists, if not create it
    $conn->exec("CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        product_name VARCHAR(255) NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Get orders count
    $stmt = $conn->query("SELECT COUNT(*) as total FROM orders");
    $totalOrders = $stmt->fetch()['total'];
    
    // Get orders with pagination
    $itemsPerPage = 10;
    $currentPage = $_GET['page'] ?? 1;
    $offset = ($currentPage - 1) * $itemsPerPage;
    
    // Fetch orders with pagination
    $stmt = $conn->prepare("SELECT o.*, u.username FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $orders = $stmt->fetchAll();
    
    // Get total pages
    $totalPages = ceil($totalOrders / $itemsPerPage);
    
    // Get revenue statistics
    $stmt = $conn->query("SELECT SUM(amount) as total_revenue FROM orders WHERE status = 'completed'");
    $totalRevenue = $stmt->fetch()['total_revenue'] ?? 0;
    
    // Get status counts
    $stmt = $conn->query("SELECT status, COUNT(*) as count FROM orders GROUP BY status");
    $statusCounts = $stmt->fetchAll();
    
} catch (Exception $e) {
    $error = "Database error: " . $e->getMessage();
    $totalOrders = 0;
    $orders = [];
    $totalPages = 1;
    $totalRevenue = 0;
    $statusCounts = [];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin-style.css">
</head>
<body>
    <?php include 'views/header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'views/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="main-content">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="page-title">
                            <i class="fas fa-shopping-cart me-2"></i>Quản lý đơn hàng
                        </h1>
                    </div>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card dashboard-card text-white" style="background: linear-gradient(135deg, #007bff, #0056b3);">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title">Tổng đơn hàng</h5>
                                            <p class="card-text display-6"><?php echo $totalOrders; ?></p>
                                        </div>
                                        <div>
                                            <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card dashboard-card text-white" style="background: linear-gradient(135deg, #28a745, #1e7e34);">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title">Doanh thu</h5>
                                            <p class="card-text display-6">$<?php echo number_format($totalRevenue, 2); ?></p>
                                        </div>
                                        <div>
                                            <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card dashboard-card text-white" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title">Đang xử lý</h5>
                                            <p class="card-text display-6">
                                                <?php 
                                                $pendingCount = 0;
                                                foreach ($statusCounts as $status) {
                                                    if ($status['status'] === 'pending') {
                                                        $pendingCount = $status['count'];
                                                        break;
                                                    }
                                                }
                                                echo $pendingCount;
                                                ?>
                                            </p>
                                        </div>
                                        <div>
                                            <i class="fas fa-clock fa-2x opacity-75"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card dashboard-card text-white" style="background: linear-gradient(135deg, #17a2b8, #117a8b);">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title">Hoàn thành</h5>
                                            <p class="card-text display-6">
                                                <?php 
                                                $completedCount = 0;
                                                foreach ($statusCounts as $status) {
                                                    if ($status['status'] === 'completed') {
                                                        $completedCount = $status['count'];
                                                        break;
                                                    }
                                                }
                                                echo $completedCount;
                                                ?>
                                            </p>
                                        </div>
                                        <div>
                                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Table -->
                    <div class="card dashboard-card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Danh sách đơn hàng</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($orders)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Chưa có đơn hàng nào</h5>
                                    <p class="text-muted">Đơn hàng sẽ xuất hiện ở đây khi có người mua!</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Khách hàng</th>
                                                <th>Sản phẩm</th>
                                                <th>Giá</th>
                                                <th>Trạng thái</th>
                                                <th>Ngày tạo</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($orders as $order): ?>
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-secondary">#<?php echo $order['id']; ?></span>
                                                    </td>
                                                    <td>
                                                        <?php if ($order['username']): ?>
                                                            <strong><?php echo htmlspecialchars($order['username']); ?></strong>
                                                        <?php else: ?>
                                                            <span class="text-muted">Khách vãng lai</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($order['product_name']); ?></strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success fs-6">$<?php echo number_format($order['amount'], 2); ?></span>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $statusClass = '';
                                                        $statusText = '';
                                                        switch ($order['status']) {
                                                            case 'completed':
                                                                $statusClass = 'badge bg-success';
                                                                $statusText = 'Hoàn thành';
                                                                break;
                                                            case 'pending':
                                                                $statusClass = 'badge bg-warning';
                                                                $statusText = 'Đang xử lý';
                                                                break;
                                                            case 'cancelled':
                                                                $statusClass = 'badge bg-danger';
                                                                $statusText = 'Đã hủy';
                                                                break;
                                                        }
                                                        ?>
                                                        <span class="<?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">
                                                            <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                                    onclick="updateStatus(<?php echo $order['id']; ?>, 'completed')" 
                                                                    title="Đánh dấu hoàn thành">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-outline-warning" 
                                                                    onclick="updateStatus(<?php echo $order['id']; ?>, 'pending')" 
                                                                    title="Đánh dấu đang xử lý">
                                                                <i class="fas fa-clock"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                                    onclick="updateStatus(<?php echo $order['id']; ?>, 'cancelled')" 
                                                                    title="Đánh dấu đã hủy">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                                    onclick="viewOrder(<?php echo $order['id']; ?>)" 
                                                                    title="Xem chi tiết">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <?php if ($totalPages > 1): ?>
                                    <nav aria-label="Orders pagination" class="mt-4">
                                        <ul class="pagination justify-content-center">
                                            <?php if ($currentPage > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>">
                                                        <i class="fas fa-chevron-left"></i>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            
                                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                                <li class="page-item <?php echo $i == $currentPage ? 'active' : ''; ?>">
                                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                                </li>
                                            <?php endfor; ?>
                                            
                                            <?php if ($currentPage < $totalPages): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>">
                                                        <i class="fas fa-chevron-right"></i>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function updateStatus(orderId, status) {
        const statusText = {
            'completed': 'hoàn thành',
            'pending': 'đang xử lý',
            'cancelled': 'đã hủy'
        };
        
        if (confirm(`Bạn có chắc chắn muốn đánh dấu đơn hàng #${orderId} là ${statusText[status]}?`)) {
            // Implement status update functionality
            alert(`Chức năng cập nhật trạng thái sẽ được thêm sau!`);
        }
    }
    
    function viewOrder(orderId) {
        // Implement view order functionality
        alert(`Chức năng xem chi tiết đơn hàng sẽ được thêm sau!`);
    }
    </script>
</body>
</html>
