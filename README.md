# MMO Services - Hệ thống quản lý dịch vụ MMO

## 🎯 Mô tả
MMO Services là một hệ thống web hoàn chỉnh để quản lý và bán các dịch vụ MMO (Make Money Online) với giao diện hiện đại, hệ thống đăng nhập/đăng ký, và admin panel chuyên nghiệp.

## ✨ Tính năng chính

### 🌐 Giao diện người dùng
- **Trang chủ:** Hiển thị sản phẩm nổi bật với thiết kế responsive
- **Danh mục sản phẩm:** Phân loại và tìm kiếm sản phẩm
- **Giỏ hàng:** Quản lý đơn hàng và thanh toán
- **Tìm kiếm:** Tìm kiếm sản phẩm theo từ khóa

### 🔐 Hệ thống xác thực
- **Đăng ký:** Tạo tài khoản mới với validation đầy đủ
- **Đăng nhập:** Hỗ trợ cả user thường và admin
- **Phân quyền:** Hiển thị thông tin khác nhau cho admin và user
- **Đăng xuất:** Xử lý session an toàn

### 🛡️ Admin Panel
- **Dashboard:** Thống kê tổng quan với biểu đồ tròn và cột
- **Quản lý sản phẩm:** CRUD sản phẩm, quản lý tồn kho
- **Thống kê:** Bảng thống kê chi tiết với phần trăm và xu hướng
- **Giao diện:** Theme hiện đại phù hợp với trang chủ

## 🚀 Cài đặt

### 1. Yêu cầu hệ thống
- PHP >= 7.4
- MySQL >= 5.7
- XAMPP/WAMP/LAMP

### 2. Cài đặt database
```sql
-- Import file database.sql để tạo cấu trúc cơ bản
-- Import file admin/setup_admin.sql để thiết lập admin panel
```

### 3. Cấu hình database
Chỉnh sửa file `admin/config/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'mmo');        // Tên database của bạn
define('DB_USER', 'root');       // Username database
define('DB_PASS', '');           // Password database
```

### 4. Cấu hình web server
- Copy toàn bộ thư mục vào `htdocs` (XAMPP) hoặc `www` (WAMP)
- Truy cập: `http://localhost/3cham/`

## 👥 Tài khoản mặc định

### 🔑 Admin
- **Username:** `admin`
- **Password:** `admin1234`
- **Quyền:** Toàn quyền quản trị hệ thống

### 👤 User thường
- **Username:** `user1`, `user2`, `user3`
- **Password:** `123456`
- **Quyền:** Xem sản phẩm, đặt hàng

## 📁 Cấu trúc thư mục

```
3cham/
├── admin/                      # Admin Panel
│   ├── assets/css/            # CSS cho admin
│   ├── config/                # Cấu hình database
│   ├── controllers/           # Xử lý admin
│   ├── views/                 # Giao diện admin
│   ├── dashboard.php          # Dashboard chính
│   ├── products.php           # Quản lý sản phẩm
│   └── setup_admin.sql        # Database admin
├── assets/                     # Tài nguyên frontend
│   ├── css/                   # Stylesheet chính
│   └── images/                # Hình ảnh sản phẩm
├── Controller/                 # Controllers chính
├── Model/                      # Models database
├── Views/                      # Views frontend
│   ├── login.php              # Trang đăng nhập
│   ├── register.php           # Trang đăng ký
│   └── ...                    # Các view khác
├── index.php                   # File chính
├── logout.php                  # Xử lý đăng xuất
└── README.md                   # Hướng dẫn này
```

## 🔧 Sử dụng

### Đăng nhập/Đăng ký
1. **Đăng ký:** Truy cập `?page=register` để tạo tài khoản mới
2. **Đăng nhập:** Truy cập `?page=login` với tài khoản đã có
3. **Phân quyền:** Hệ thống tự động nhận diện admin/user

### Admin Panel
1. Đăng nhập với tài khoản admin
2. Truy cập `admin/dashboard.php`
3. Quản lý sản phẩm, xem thống kê, biểu đồ

### Quản lý sản phẩm
- **Thêm sản phẩm:** `admin/product-add.php`
- **Sửa/Xóa:** Từ trang `admin/products.php`
- **Thống kê:** Dashboard hiển thị tổng quan

## 🎨 Tùy chỉnh

### Thay đổi theme
- **Frontend:** Chỉnh sửa `assets/css/style.css`
- **Admin:** Chỉnh sửa `admin/assets/css/admin-style.css`

### Thêm biểu đồ mới
Sử dụng Chart.js trong dashboard:
```javascript
new Chart(ctx, {
    type: 'line', // hoặc 'bar', 'pie', 'doughnut'
    data: { /* dữ liệu */ },
    options: { /* tùy chọn */ }
});
```

## 🔒 Bảo mật

### Tính năng bảo mật
- Password hashing với bcrypt
- Session management an toàn
- Input validation và sanitization
- CSRF protection

### Khuyến nghị
- Thay đổi password mặc định
- Sử dụng HTTPS trong production
- Backup database thường xuyên
- Cập nhật PHP và MySQL

## 🐛 Xử lý lỗi

### Lỗi thường gặp
1. **Không kết nối database:** Kiểm tra cấu hình trong `admin/config/config.php`
2. **Lỗi session:** Đảm bảo PHP session được bật
3. **Lỗi permission:** Kiểm tra quyền ghi file

### Debug
- Kiểm tra log PHP error
- Xác nhận cấu hình database
- Kiểm tra quyền truy cập file

## 📞 Hỗ trợ

Nếu gặp vấn đề:
1. Kiểm tra log PHP error
2. Xác nhận cấu hình database
3. Kiểm tra quyền truy cập file
4. Đảm bảo PHP version >= 7.4

## 📄 Phiên bản
- **Version:** 2.0
- **Cập nhật:** 13/08/2025
- **Tương thích:** PHP 7.4+, MySQL 5.7+

## 📝 Changelog

### Version 2.0 (13/08/2025)
- ✅ Thêm hệ thống đăng nhập/đăng ký hoàn chỉnh
- ✅ Cải thiện admin panel với biểu đồ và thống kê
- ✅ Thêm nút hiện mật khẩu
- ✅ Phân quyền admin/user
- ✅ Giao diện responsive và hiện đại

### Version 1.0
- ✅ Hệ thống quản lý sản phẩm cơ bản
- ✅ Giao diện frontend
- ✅ Admin panel đơn giản 