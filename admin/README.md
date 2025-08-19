# 🎯 MMO Services Admin Panel

## 📋 **Hướng dẫn sử dụng:**

### **🔐 Đăng nhập Admin:**
- **URL:** `http://localhost/tf/index.php?page=login`
- **Username:** `admin`
- **Password:** `admin1234`

### **📊 Dashboard Admin:**
- **URL:** `http://localhost/tf/admin/dashboard.php`
- **Chức năng:** Quản lý sản phẩm, người dùng, đơn hàng

### **⚠️ Lưu ý quan trọng:**

1. **KHÔNG sử dụng** `/admin/index.php` để đăng nhập
2. **Sử dụng** form đăng nhập chính tại `/index.php?page=login`
3. **Hệ thống sẽ tự động** phân biệt admin và user thường

### **🔗 Liên kết nhanh:**

- **🏠 Trang chủ:** `http://localhost/tf/`
- **🔐 Đăng nhập:** `http://localhost/tf/index.php?page=login`
- **📊 Dashboard:** `http://localhost/tf/admin/dashboard.php`
- **🧹 Xóa session:** `http://localhost/tf/admin/clear_session.php`

### **🔄 Quy trình đăng nhập:**

1. **Truy cập:** `/index.php?page=login`
2. **Nhập:** `admin` / `admin1234`
3. **Hệ thống tự động** chuyển đến dashboard admin
4. **Nếu nhập sai** sẽ hiển thị thông báo lỗi

### **🚫 Không sử dụng:**
- ❌ `/admin/index.php` (form đăng nhập cũ)
- ❌ `/admin/controllers/auth.php` (trực tiếp)

### **✅ Sử dụng:**
- ✅ `/index.php?page=login` (form đăng nhập chính)
- ✅ Hệ thống tự động xử lý admin/user
