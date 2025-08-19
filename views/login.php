<?php
// Kiểm tra session và redirect TRƯỚC KHI có bất kỳ output nào
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nếu đã đăng nhập thì chuyển hướng theo quyền
if (isset($_SESSION['user_id']) || isset($_SESSION['admin_logged_in'])) {
    if (isset($_SESSION['admin_logged_in'])) {
        // Admin đã đăng nhập -> vào dashboard
        header('Location: ../admin/dashboard.php');
    } else {
        // User thường đã đăng nhập -> về homepage
        header('Location: index.php');
    }
    exit;
}

// Xử lý đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../config/database.php';
    
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("SET NAMES utf8mb4");
        
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        
        if (empty($username) || empty($password)) {
            $error = 'Vui lòng nhập đầy đủ thông tin!';
        } else {
            // Kiểm tra đăng nhập admin trước
            $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
            $stmt->execute([$username]);
            $adminUser = $stmt->fetch();
            
            if ($adminUser && password_verify($password, $adminUser['password'])) {
                // Đăng nhập admin
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $adminUser['id'];
                $_SESSION['admin_username'] = $adminUser['username'];
                header('Location: ../admin/dashboard.php');
                exit;
            } else {
                // Kiểm tra đăng nhập user thường
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->execute([$username]);
                $user = $stmt->fetch();
                
                if ($user && password_verify($password, $user['password'])) {
                    // Đăng nhập user
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    header('Location: index.php');
                    exit;
                } else {
                    $error = 'Tên đăng nhập hoặc mật khẩu không đúng!';
                }
            }
        }
    } catch (Exception $e) {
        $error = 'Có lỗi xảy ra, vui lòng thử lại!';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - MMO Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .login-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #1db954, #1ed760);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
            animation: slideIn 0.6s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-header {
            background: linear-gradient(135deg, #1db954, #1ed760);
            color: white;
            padding: 35px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255,255,255,0.1);
            transform: rotate(45deg);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            0% { transform: rotate(45deg) translateX(-100%); }
            100% { transform: rotate(45deg) translateX(100%); }
        }
        
        .login-header h3 {
            margin: 0;
            font-weight: bold;
            position: relative;
            z-index: 1;
        }
        
        .login-header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        
        .login-body {
            padding: 40px;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 15px 18px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .form-control:focus {
            border-color: #1db954;
            box-shadow: 0 0 0 0.25rem rgba(29, 185, 84, 0.15);
            background: white;
            transform: translateY(-2px);
        }
        
        .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.15);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #1db954, #1ed760);
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(29, 185, 84, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(-1px);
        }
        
        .password-field {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 5px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .password-toggle:hover {
            background: #e9ecef;
            color: #495057;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .form-floating {
            margin-bottom: 20px;
        }
        
        .links-section {
            text-align: center;
            margin-top: 25px;
        }
        
        .links-section a {
            color: #1db954;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .links-section a:hover {
            color: #1ed760;
            text-decoration: underline;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            color: #6c757d;
            text-decoration: none;
            margin-top: 15px;
            transition: color 0.3s ease;
        }
        
        .back-link:hover {
            color: #495057;
        }
        
        .input-group-text {
            background: transparent;
            border: none;
            color: #6c757d;
        }
        
        .form-control:focus + .input-group-text {
            color: #1db954;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h3><i class="fas fa-sign-in-alt me-2"></i>Đăng nhập</h3>
                <p>MMO Services</p>
            </div>
            <div class="login-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" id="loginForm" novalidate>
                    <div class="mb-4">
                        <label for="username" class="form-label">
                            <i class="fas fa-user me-2"></i>Tên đăng nhập
                        </label>
                        <input type="text" class="form-control" id="username" name="username" 
                               value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                               placeholder="Nhập tên đăng nhập của bạn"
                               required>
                        <div class="invalid-feedback">
                            Vui lòng nhập tên đăng nhập
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2"></i>Mật khẩu
                        </label>
                        <div class="password-field">
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Nhập mật khẩu của bạn"
                                   required>
                            <button type="button" class="password-toggle" id="togglePassword" title="Hiện/ẩn mật khẩu">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback">
                            Vui lòng nhập mật khẩu
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-login text-white w-100 mb-4" id="loginBtn">
                        <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                    </button>
                </form>
                
                <div class="links-section">
                    <p class="mb-2">Chưa có tài khoản? 
                        <a href="?page=register" class="fw-bold">
                            Đăng ký ngay
                        </a>
                    </p>
                    <a href="index.php" class="back-link">
                        <i class="fas fa-arrow-left me-2"></i>Quay về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    });
    
    // Simple form validation - không prevent default
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const username = document.getElementById('username');
        const password = document.getElementById('password');
        const loginBtn = document.getElementById('loginBtn');
        
        // Reset validation states
        username.classList.remove('is-invalid');
        password.classList.remove('is-invalid');
        
        let hasError = false;
        
        // Validate username
        if (!username.value.trim()) {
            username.classList.add('is-invalid');
            hasError = true;
        }
        
        // Validate password
        if (!password.value.trim()) {
            password.classList.add('is-invalid');
            hasError = true;
        }
        
        if (hasError) {
            e.preventDefault();
            return false;
        }
        
        // Show loading state
        loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';
        loginBtn.disabled = true;
        
        // Form sẽ submit bình thường
    });
    
    // Real-time validation
    document.getElementById('username').addEventListener('blur', function() {
        if (!this.value.trim()) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });
    
    document.getElementById('password').addEventListener('blur', function() {
        if (!this.value.trim()) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });
    
    // Enter key to submit
    document.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
            const form = document.getElementById('loginForm');
            if (form) {
                form.submit();
            }
        }
    });
    </script>
</body>
</html>
