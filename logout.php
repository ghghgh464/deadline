<?php
session_start();

// Lưu thông tin user trước khi logout để hiển thị thông báo
$userType = '';
$userName = '';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    $userType = 'admin';
    $userName = $_SESSION['admin_username'] ?? 'Admin';
} elseif (isset($_SESSION['user_id'])) {
    $userType = 'user';
    $userName = $_SESSION['username'] ?? 'User';
}

// Xóa tất cả session variables
$_SESSION = array();

// Nếu sử dụng session cookie, xóa cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hủy session
session_destroy();

// Hiển thị trang logout đẹp
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng xuất - MMO Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1db954, #1ed760);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .logout-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            padding: 40px;
            text-align: center;
            max-width: 500px;
            width: 90%;
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
        
        .logout-icon {
            font-size: 4rem;
            color: #1db954;
            margin-bottom: 20px;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
        
        .logout-title {
            color: #333;
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .logout-message {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .user-info {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            border-left: 4px solid #1db954;
        }
        
        .user-info h5 {
            color: #1db954;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .user-info p {
            color: #666;
            margin: 0;
        }
        
        .btn-home {
            background: linear-gradient(135deg, #1db954, #1ed760);
            border: none;
            border-radius: 12px;
            padding: 15px 30px;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            margin: 0 10px;
        }
        
        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(29, 185, 84, 0.4);
            color: white;
            text-decoration: none;
        }
        
        .btn-login {
            background: transparent;
            border: 2px solid #1db954;
            border-radius: 12px;
            padding: 13px 28px;
            font-weight: 600;
            font-size: 1.1rem;
            color: #1db954;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            margin: 0 10px;
        }
        
        .btn-login:hover {
            background: #1db954;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(29, 185, 84, 0.4);
            text-decoration: none;
        }
        
        .countdown {
            color: #666;
            font-size: 0.9rem;
            margin-top: 20px;
        }
        
        .countdown strong {
            color: #1db954;
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="logout-icon">
            <i class="fas fa-sign-out-alt"></i>
        </div>
        
        <h1 class="logout-title">Đăng xuất thành công!</h1>
        <p class="logout-message">
            Bạn đã đăng xuất khỏi hệ thống MMO Services.<br>
            Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!
        </p>
        
        <?php if ($userName): ?>
        <div class="user-info">
            <h5><i class="fas fa-user me-2"></i>Thông tin tài khoản</h5>
            <p><strong>Tên:</strong> <?php echo htmlspecialchars($userName); ?></p>
            <p><strong>Loại tài khoản:</strong> 
                <?php echo $userType === 'admin' ? 'Quản trị viên' : 'Người dùng'; ?>
            </p>
        </div>
        <?php endif; ?>
        
        <div class="d-flex justify-content-center">
            <a href="index.php" class="btn-home">
                <i class="fas fa-home me-2"></i>Về trang chủ
            </a>
        </div>
        
        <div class="countdown">
            Tự động chuyển về trang chủ sau <strong id="timer">5</strong> giây
        </div>
    </div>

    <script>
        // Countdown timer
        let timeLeft = 5;
        const timerElement = document.getElementById('timer');
        
        const countdown = setInterval(() => {
            timeLeft--;
            timerElement.textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(countdown);
                window.location.href = 'index.php';
            }
        }, 1000);
        
        // Pause countdown when hovering over buttons
        const buttons = document.querySelectorAll('.btn-home, .btn-login');
        buttons.forEach(button => {
            button.addEventListener('mouseenter', () => {
                clearInterval(countdown);
                timerElement.textContent = 'Tạm dừng';
            });
            
            button.addEventListener('mouseleave', () => {
                timeLeft = 5;
                timerElement.textContent = timeLeft;
                const newCountdown = setInterval(() => {
                    timeLeft--;
                    timerElement.textContent = timeLeft;
                    
                    if (timeLeft <= 0) {
                        clearInterval(newCountdown);
                        window.location.href = 'index.php';
                    }
                }, 1000);
            });
        });
    </script>
</body>
</html>
