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
    
    // Get system info with error handling
    try {
        $stmt = $conn->query("SELECT COUNT(*) as total FROM users");
        $totalUsers = $stmt->fetch()['total'];
    } catch (Exception $e) {
        $totalUsers = 0;
    }
    
    try {
        $stmt = $conn->query("SELECT COUNT(*) as total FROM orders");
        $totalOrders = $stmt->fetch()['total'];
    } catch (Exception $e) {
        $totalOrders = 0;
    }
    
    try {
        $stmt = $conn->query("SELECT COUNT(*) as total FROM products");
        $totalProducts = $stmt->fetch()['total'];
    } catch (Exception $e) {
        $totalProducts = 0;
    }
    
} catch (Exception $e) {
    $error = "Database error: " . $e->getMessage();
    $totalUsers = 0;
    $totalOrders = 0;
    $totalProducts = 0;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cài đặt hệ thống - Admin Panel</title>
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
                            <i class="fas fa-cog me-2"></i>Cài đặt hệ thống
                        </h1>
                    </div>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <!-- System Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card dashboard-card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin hệ thống</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="text-center mb-3">
                                                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                                <h6>Tổng người dùng</h6>
                                                <h4 class="text-primary"><?php echo $totalUsers; ?></h4>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center mb-3">
                                                <i class="fas fa-shopping-cart fa-2x text-success mb-2"></i>
                                                <h6>Tổng đơn hàng</h6>
                                                <h4 class="text-success"><?php echo $totalOrders; ?></h4>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center mb-3">
                                                <i class="fas fa-box fa-2x text-info mb-2"></i>
                                                <h6>Tổng sản phẩm</h6>
                                                <h4 class="text-info"><?php echo $totalProducts; ?></h4>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center mb-3">
                                                <i class="fas fa-server fa-2x text-warning mb-2"></i>
                                                <h6>Phiên bản</h6>
                                                <h4 class="text-warning">v1.0</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card dashboard-card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-database me-2"></i>Thông tin database</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Host:</strong> <?php echo DB_HOST; ?>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Database:</strong> <?php echo DB_NAME; ?>
                                    </div>
                                    <div class="mb-3">
                                        <strong>User:</strong> <?php echo DB_USER; ?>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Charset:</strong> UTF-8
                                    </div>
                                    <div class="mb-3">
                                        <strong>PHP Version:</strong> <?php echo PHP_VERSION; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Sections -->
                    <div class="row">
                        <!-- General Settings -->
                        <div class="col-md-6 mb-4">
                            <div class="card dashboard-card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-sliders-h me-2"></i>Cài đặt chung</h5>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="mb-3">
                                            <label for="siteName" class="form-label">Tên website</label>
                                            <input type="text" class="form-control" id="siteName" value="MMO Services" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="siteDescription" class="form-label">Mô tả website</label>
                                            <textarea class="form-control" id="siteDescription" rows="3" readonly>Dịch vụ MMO chất lượng cao</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="adminEmail" class="form-label">Email admin</label>
                                            <input type="email" class="form-control" id="adminEmail" value="admin@mmoservices.com" readonly>
                                        </div>
                                        <button type="button" class="btn btn-primary" onclick="saveGeneralSettings()">
                                            <i class="fas fa-save me-2"></i>Lưu cài đặt
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Security Settings -->
                        <div class="col-md-6 mb-4">
                            <div class="card dashboard-card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Cài đặt bảo mật</h5>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="mb-3">
                                            <label for="sessionTimeout" class="form-label">Thời gian session (phút)</label>
                                            <input type="number" class="form-control" id="sessionTimeout" value="60" min="15" max="480">
                                        </div>
                                        <div class="mb-3">
                                            <label for="maxLoginAttempts" class="form-label">Số lần đăng nhập tối đa</label>
                                            <input type="number" class="form-control" id="maxLoginAttempts" value="5" min="3" max="10">
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="enableTwoFactor" checked>
                                                <label class="form-check-label" for="enableTwoFactor">
                                                    Bật xác thực 2 yếu tố
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="enableCaptcha" checked>
                                                <label class="form-check-label" for="enableCaptcha">
                                                    Bật CAPTCHA cho đăng nhập
                                                </label>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-warning" onclick="saveSecuritySettings()">
                                            <i class="fas fa-save me-2"></i>Lưu cài đặt
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Backup Settings -->
                        <div class="col-md-6 mb-4">
                            <div class="card dashboard-card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-download me-2"></i>Sao lưu & Khôi phục</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-success w-100 mb-2" onclick="backupDatabase()">
                                            <i class="fas fa-download me-2"></i>Sao lưu database
                                        </button>
                                        <button type="button" class="btn btn-info w-100 mb-2" onclick="backupFiles()">
                                            <i class="fas fa-file-archive me-2"></i>Sao lưu files
                                        </button>
                                        <button type="button" class="btn btn-warning w-100" onclick="restoreDatabase()">
                                            <i class="fas fa-upload me-2"></i>Khôi phục database
                                        </button>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Sao lưu tự động mỗi ngày lúc 2:00 AM
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Maintenance Settings -->
                        <div class="col-md-6 mb-4">
                            <div class="card dashboard-card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Bảo trì hệ thống</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="maintenanceMode">
                                            <label class="form-check-label" for="maintenanceMode">
                                                Chế độ bảo trì
                                            </label>
                                        </div>
                                        <small class="text-muted">Chỉ admin mới có thể truy cập</small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="maintenanceMessage" class="form-label">Thông báo bảo trì</label>
                                        <textarea class="form-control" id="maintenanceMessage" rows="3" placeholder="Hệ thống đang bảo trì, vui lòng quay lại sau..."></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-danger" onclick="toggleMaintenance()">
                                            <i class="fas fa-power-off me-2"></i>Bật/Tắt bảo trì
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Actions -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card dashboard-card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Hành động hệ thống</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <button type="button" class="btn btn-outline-primary w-100" onclick="clearCache()">
                                                <i class="fas fa-broom me-2"></i>Xóa cache
                                            </button>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <button type="button" class="btn btn-outline-warning w-100" onclick="optimizeDatabase()">
                                                <i class="fas fa-database me-2"></i>Tối ưu database
                                            </button>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <button type="button" class="btn btn-outline-info w-100" onclick="generateReport()">
                                                <i class="fas fa-chart-bar me-2"></i>Tạo báo cáo
                                            </button>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <button type="button" class="btn btn-outline-danger w-100" onclick="systemRestart()">
                                                <i class="fas fa-redo me-2"></i>Khởi động lại
                                            </button>
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
    <script>
    function saveGeneralSettings() {
        alert('Chức năng lưu cài đặt chung sẽ được thêm sau!');
    }
    
    function saveSecuritySettings() {
        alert('Chức năng lưu cài đặt bảo mật sẽ được thêm sau!');
    }
    
    function backupDatabase() {
        alert('Chức năng sao lưu database sẽ được thêm sau!');
    }
    
    function backupFiles() {
        alert('Chức năng sao lưu files sẽ được thêm sau!');
    }
    
    function restoreDatabase() {
        alert('Chức năng khôi phục database sẽ được thêm sau!');
    }
    
    function toggleMaintenance() {
        const maintenanceMode = document.getElementById('maintenanceMode');
        const message = maintenanceMode.checked ? 'bật' : 'tắt';
        if (confirm(`Bạn có chắc chắn muốn ${message} chế độ bảo trì?`)) {
            alert(`Chức năng ${message} chế độ bảo trì sẽ được thêm sau!`);
        }
    }
    
    function clearCache() {
        if (confirm('Bạn có chắc chắn muốn xóa cache?')) {
            alert('Chức năng xóa cache sẽ được thêm sau!');
        }
    }
    
    function optimizeDatabase() {
        if (confirm('Bạn có chắc chắn muốn tối ưu database?')) {
            alert('Chức năng tối ưu database sẽ được thêm sau!');
        }
    }
    
    function generateReport() {
        alert('Chức năng tạo báo cáo sẽ được thêm sau!');
    }
    
    function systemRestart() {
        if (confirm('Bạn có chắc chắn muốn khởi động lại hệ thống? Hành động này sẽ làm gián đoạn dịch vụ!')) {
            alert('Chức năng khởi động lại hệ thống sẽ được thêm sau!');
        }
    }
    </script>
</body>
</html>
