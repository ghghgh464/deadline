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
    
    // Check if products table exists, if not create it
    $conn->exec("CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        category VARCHAR(100),
        stock INT DEFAULT 0,
        image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Get categories count
    $stmt = $conn->query("SELECT COUNT(DISTINCT category) as total FROM products");
    $totalCategories = $stmt->fetch()['total'];
    
    // Get categories with product counts
    $stmt = $conn->query("SELECT category, COUNT(*) as product_count, AVG(price) as avg_price FROM products GROUP BY category ORDER BY product_count DESC");
    $categories = $stmt->fetchAll();
    
} catch (Exception $e) {
    $error = "Database error: " . $e->getMessage();
    $totalCategories = 0;
    $categories = [];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý danh mục - Admin Panel</title>
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
                            <i class="fas fa-folder me-2"></i>Quản lý danh mục
                        </h1>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            <i class="fas fa-plus me-2"></i>Thêm danh mục mới
                        </button>
                    </div>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card dashboard-card text-white" style="background: linear-gradient(135deg, #17a2b8, #117a8b);">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title">Tổng danh mục</h5>
                                            <p class="card-text display-6"><?php echo $totalCategories; ?></p>
                                        </div>
                                        <div>
                                            <i class="fas fa-folder fa-2x opacity-75"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card dashboard-card text-white" style="background: linear-gradient(135deg, #28a745, #1e7e34);">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title">Danh mục phổ biến</h5>
                                            <p class="card-text display-6">
                                                <?php 
                                                $popularCategory = '';
                                                $maxProducts = 0;
                                                foreach ($categories as $cat) {
                                                    if ($cat['product_count'] > $maxProducts) {
                                                        $maxProducts = $cat['product_count'];
                                                        $popularCategory = $cat['category'];
                                                    }
                                                }
                                                echo $popularCategory ?: 'N/A';
                                                ?>
                                            </p>
                                        </div>
                                        <div>
                                            <i class="fas fa-star fa-2x opacity-75"></i>
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
                                            <h5 class="card-title">Sản phẩm trung bình</h5>
                                            <p class="card-text display-6">
                                                <?php 
                                                $avgProducts = $totalCategories > 0 ? round(array_sum(array_column($categories, 'product_count')) / $totalCategories, 1) : 0;
                                                echo $avgProducts;
                                                ?>
                                            </p>
                                        </div>
                                        <div>
                                            <i class="fas fa-chart-line fa-2x opacity-75"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Categories Table -->
                    <div class="card dashboard-card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Danh sách danh mục</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($categories)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-folder fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Chưa có danh mục nào</h5>
                                    <p class="text-muted">Danh mục sẽ xuất hiện ở đây khi có sản phẩm!</p>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                        <i class="fas fa-plus me-2"></i>Thêm danh mục
                                    </button>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Danh mục</th>
                                                <th>Số sản phẩm</th>
                                                <th>Giá trung bình</th>
                                                <th>Trạng thái</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($categories as $category): ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-folder text-warning me-2"></i>
                                                            <strong><?php echo htmlspecialchars($category['category']); ?></strong>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary fs-6"><?php echo $category['product_count']; ?></span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success fs-6"><?php echo number_format($category['avg_price'], 0, ',', '.'); ?>đ</span>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $statusClass = $category['product_count'] > 5 ? 'bg-success' : ($category['product_count'] > 0 ? 'bg-warning' : 'bg-danger');
                                                        $statusText = $category['product_count'] > 5 ? 'Phổ biến' : ($category['product_count'] > 0 ? 'Trung bình' : 'Ít sản phẩm');
                                                        ?>
                                                        <span class="badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                                    onclick="editCategory('<?php echo htmlspecialchars($category['category']); ?>')" 
                                                                    title="Sửa">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                                    onclick="viewCategoryProducts('<?php echo htmlspecialchars($category['category']); ?>')" 
                                                                    title="Xem sản phẩm">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                                    onclick="deleteCategory('<?php echo htmlspecialchars($category['category']); ?>')" 
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
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">
                        <i class="fas fa-plus me-2"></i>Thêm danh mục mới
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addCategoryForm">
                        <div class="mb-3">
                            <label for="categoryName" class="form-label">Tên danh mục</label>
                            <input type="text" class="form-control" id="categoryName" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoryDescription" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="categoryDescription" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="categoryIcon" class="form-label">Icon</label>
                            <select class="form-select" id="categoryIcon">
                                <option value="fas fa-box">📦 Sản phẩm</option>
                                <option value="fas fa-music">🎵 Âm nhạc</option>
                                <option value="fas fa-gamepad">🎮 Game</option>
                                <option value="fas fa-tv">📺 Streaming</option>
                                <option value="fas fa-tools">🔧 Công cụ</option>
                                <option value="fas fa-graduation-cap">🎓 Giáo dục</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" onclick="saveCategory()">
                        <i class="fas fa-save me-2"></i>Lưu
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function editCategory(categoryName) {
        alert(`Chức năng sửa danh mục "${categoryName}" sẽ được thêm sau!`);
    }
    
    function viewCategoryProducts(categoryName) {
        alert(`Chức năng xem sản phẩm danh mục "${categoryName}" sẽ được thêm sau!`);
    }
    
    function deleteCategory(categoryName) {
        if (confirm(`Bạn có chắc chắn muốn xóa danh mục "${categoryName}"? Hành động này sẽ ảnh hưởng đến tất cả sản phẩm trong danh mục!`)) {
            alert(`Chức năng xóa danh mục sẽ được thêm sau!`);
        }
    }
    
    function saveCategory() {
        const categoryName = document.getElementById('categoryName').value.trim();
        const categoryDescription = document.getElementById('categoryDescription').value.trim();
        const categoryIcon = document.getElementById('categoryIcon').value;
        
        if (!categoryName) {
            alert('Vui lòng nhập tên danh mục!');
            return;
        }
        
        // Implement save category functionality
        alert(`Chức năng lưu danh mục "${categoryName}" sẽ được thêm sau!`);
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
        modal.hide();
        
        // Reset form
        document.getElementById('addCategoryForm').reset();
    }
    </script>
</body>
</html>
