<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MMO Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .user-dropdown {
            position: relative;
        }
        
        .user-dropdown .dropdown-toggle {
            background: linear-gradient(135deg, #1db954, #1ed760);
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .user-dropdown .dropdown-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(29, 185, 84, 0.3);
        }
        
        .user-dropdown .dropdown-menu {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            padding: 10px 0;
            min-width: 220px;
            margin-top: 10px;
        }
        
        .user-dropdown .dropdown-item {
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .user-dropdown .dropdown-item:hover {
            background: #f8f9fa;
            transform: translateX(5px);
        }
        
        .user-dropdown .dropdown-divider {
            margin: 8px 15px;
            border-color: #e9ecef;
        }
        
        .admin-badge {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 12px;
            margin-left: 8px;
        }
        
        .auth-buttons .btn {
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .auth-buttons .btn-outline-light:hover {
            background: #1db954;
            border-color: #1db954;
            transform: translateY(-2px);
        }
        
        .auth-buttons .btn-primary {
            background: linear-gradient(135deg, #1db954, #1ed760);
            border: none;
        }
        
        .auth-buttons .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(29, 185, 84, 0.3);
        }
        
        .cart-badge {
            position: relative;
            border-radius: 25px;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }
        
        .cart-badge:hover {
            transform: translateY(-2px);
        }
        
        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .theme-switch {
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.3s ease;
            color: #fff;
        }
        
        .theme-switch:hover {
            background: rgba(255,255,255,0.1);
            transform: rotate(180deg);
        }
    </style>
</head>
<body>
    <?php
    session_start();
    $isLoggedIn = isset($_SESSION['user_id']) || isset($_SESSION['admin_logged_in']);
    $userType = '';
    $userName = '';
    
    if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
        $userType = 'admin';
        $userName = $_SESSION['admin_username'] ?? 'Admin';
    } elseif (isset($_SESSION['user_id'])) {
        $userType = 'user';
        $userName = $_SESSION['username'] ?? 'User';
    }
    ?>
    
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
            <div class="container">
                <a class="navbar-brand" href="?page=home">
                    <i class="fas fa-gamepad me-2"></i>MMO Services
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="?page=home">
                                <i class="fas fa-home me-1"></i>Trang chủ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?page=product">
                                <i class="fas fa-shopping-bag me-1"></i>Sản phẩm
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#features">
                                <i class="fas fa-star me-1"></i>Tính năng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#contact">
                                <i class="fas fa-envelope me-1"></i>Liên hệ
                            </a>
                        </li>
                    </ul>
                    
                    <form class="d-flex me-3" id="searchForm">
                        <div class="input-group">
                            <input class="form-control" type="search" placeholder="Tìm kiếm sản phẩm..." aria-label="Search" id="searchInput">
                            <button class="btn btn-outline-light" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                    
                    <div class="d-flex align-items-center">
                        <div class="theme-switch me-3" onclick="toggleTheme()" title="Chuyển đổi giao diện">
                            <i class="fas fa-moon"></i>
                        </div>
                        
                        <a href="?page=cart" class="btn btn-outline-light cart-badge me-3" title="Giỏ hàng">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count" id="cartCount">0</span>
                        </a>
                        
                        <?php if ($isLoggedIn): ?>
                            <!-- Hiển thị thông tin người dùng đã đăng nhập -->
                            <div class="user-dropdown dropdown me-2">
                                <button class="btn dropdown-toggle text-white" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user me-1"></i>
                                    <?php echo htmlspecialchars($userName); ?>
                                    <?php if ($userType === 'admin'): ?>
                                        <span class="admin-badge">Admin</span>
                                    <?php endif; ?>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li class="dropdown-header text-center py-2">
                                        <strong><?php echo htmlspecialchars($userName); ?></strong>
                                        <br><small class="text-muted"><?php echo $userType === 'admin' ? 'Quản trị viên' : 'Người dùng'; ?></small>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    
                                    <?php if ($userType === 'admin'): ?>
                                        <li>
                                            <a class="dropdown-item" href="admin/dashboard.php">
                                                <i class="fas fa-tachometer-alt me-2 text-primary"></i>Admin Panel
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                    <?php endif; ?>
                                    
                                    <li>
                                        <a class="dropdown-item" href="?page=profile">
                                            <i class="fas fa-user-edit me-2 text-info"></i>Hồ sơ cá nhân
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="?page=orders">
                                            <i class="fas fa-shopping-bag me-2 text-success"></i>Lịch sử đơn hàng
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="?page=settings">
                                            <i class="fas fa-cog me-2 text-warning"></i>Cài đặt
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="logout.php">
                                            <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <!-- Hiển thị nút đăng nhập/đăng ký khi chưa đăng nhập -->
                            <div class="auth-buttons">
                                <a href="?page=login" class="btn btn-outline-light me-2">
                                    <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
                                </a>
                                <a href="?page=register" class="btn btn-primary">
                                    <i class="fas fa-user-plus me-1"></i>Đăng ký
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function(){
    $('#searchForm').on('submit', function(e){
        e.preventDefault(); // Prevent default form submission
        var searchTerm = $('#searchInput').val();
        if (searchTerm.trim()) {
            // Redirect to the search page with the search term
            window.location.href = '?page=search&term=' + encodeURIComponent(searchTerm);
        }
    });
    
    // Update cart count on page load
    updateCartCount();
});

function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const count = cart.reduce((sum, item) => sum + item.quantity, 0);
    const cartCountElement = document.getElementById('cartCount');
    if (cartCountElement) {
        cartCountElement.textContent = count;
    }
}

// Theme toggle function
function toggleTheme() {
    const body = document.body;
    const themeIcon = document.querySelector('.theme-switch i');
    
    if (body.classList.contains('dark-theme')) {
        body.classList.remove('dark-theme');
        themeIcon.classList.remove('fa-sun');
        themeIcon.classList.add('fa-moon');
        localStorage.setItem('theme', 'light');
    } else {
        body.classList.add('dark-theme');
        themeIcon.classList.remove('fa-moon');
        themeIcon.classList.add('fa-sun');
        localStorage.setItem('theme', 'dark');
    }
}

// Load saved theme
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme');
    const themeIcon = document.querySelector('.theme-switch i');
    
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
        themeIcon.classList.remove('fa-moon');
        themeIcon.classList.add('fa-sun');
    }
});
</script>
