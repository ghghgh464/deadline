<?php
session_start();

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

// Chuyển hướng về trang đăng nhập
header('Location: ../index.php');
exit;
?>
