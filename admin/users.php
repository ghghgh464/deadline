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
    
    // Check if users table exists, if not create it
    $conn->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Get users count
    $stmt = $conn->query("SELECT COUNT(*) as total FROM users");
    $totalUsers = $stmt->fetch()['total'];
    
    // Get users with pagination
    $itemsPerPage = 10;
    $currentPage = $_GET['page'] ?? 1;
    $offset = ($currentPage - 1) * $itemsPerPage;
    
    // Fetch users with pagination
    $stmt = $conn->prepare("SELECT * FROM users ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $users = $stmt->fetchAll();
    
    // Get total pages
    $totalPages = ceil($totalUsers / $itemsPerPage);
    
    // Get recent registrations count
    $stmt = $conn->query("SELECT COUNT(*) as recent FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    $recentUsers = $stmt->fetch()['recent'];
    
} catch (Exception $e) {
    $error = "Database error: " . $e->getMessage();
    $totalUsers = 0;
    $users = [];
    $totalPages = 1;
    $recentUsers = 0;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng - Admin Panel</title>
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
                            <i class="fas fa-users me-2"></i>Quản lý người dùng
                        </h1>
                        <a href="user-add.php" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Thêm người dùng mới
                        </a>
                    </div>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card dashboard-card text-white" style="background: linear-gradient(135deg, #28a745, #1e7e34);">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title">Tổng người dùng</h5>
                                            <p class="card-text display-6"><?php echo $totalUsers; ?></p>
                                        </div>
                                        <div>
                                            <i class="fas fa-users fa-2x opacity-75"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card dashboard-card text-white" style="background: linear-gradient(135deg, #17a2b8, #117a8b);">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title">Đăng ký tuần này</h5>
                                            <p class="card-text display-6"><?php echo $recentUsers; ?></p>
                                        </div>
                                        <div>
                                            <i class="fas fa-user-clock fa-2x opacity-75"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card dashboard-card text-white" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title">Người dùng mới</h5>
                                            <p class="card-text display-6"><?php echo $recentUsers; ?></p>
                                        </div>
                                        <div>
                                            <i class="fas fa-user-plus fa-2x opacity-75"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Users Table -->
                    <div class="card dashboard-card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Danh sách người dùng</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($users)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Chưa có người dùng nào</h5>
                                    <p class="text-muted">Người dùng sẽ xuất hiện ở đây khi họ đăng ký!</p>
                                    <a href="user-add.php" class="btn btn-primary">
                                        <i class="fas fa-user-plus me-2"></i>Thêm người dùng
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Tên đăng nhập</th>
                                                <th>Email</th>
                                                <th>Ngày đăng ký</th>
                                                <th>Trạng thái</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($users as $user): ?>
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-secondary">#<?php echo $user['id']; ?></span>
                                                    </td>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted"><?php echo htmlspecialchars($user['email']); ?></span>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">
                                                            <?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success">Hoạt động</span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="user-edit.php?id=<?php echo $user['id']; ?>" 
                                                               class="btn btn-sm btn-outline-primary" title="Sửa">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <a href="user-view.php?id=<?php echo $user['id']; ?>" 
                                                               class="btn btn-sm btn-outline-info" title="Xem">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-sm btn-outline-warning" 
                                                                    onclick="toggleUserStatus(<?php echo $user['id']; ?>)" 
                                                                    title="Khóa/Mở khóa">
                                                                <i class="fas fa-lock"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                                    onclick="deleteUser(<?php echo $user['id']; ?>)" 
                                                                    title="Xóa">
                                                                <i class="fas fa-trash"></i>
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
                                    <nav aria-label="Users pagination" class="mt-4">
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
    function toggleUserStatus(userId) {
        if (confirm('Bạn có chắc chắn muốn thay đổi trạng thái người dùng này?')) {
            // Implement user status toggle functionality
            alert('Chức năng khóa/mở khóa người dùng sẽ được thêm sau!');
        }
    }
    
    function deleteUser(userId) {
        if (confirm('Bạn có chắc chắn muốn xóa người dùng này? Hành động này không thể hoàn tác!')) {
            // Implement delete user functionality
            alert('Chức năng xóa người dùng sẽ được thêm sau!');
        }
    }
    </script>
</body>
</html>
