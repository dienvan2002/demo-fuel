# 🔐 Hướng dẫn Setup Authentication

## **Cách 1: Sử dụng Web Interface (Dễ nhất)**

### 1. Chạy migration để tạo bảng users:
```bash
cd /home/dienvan/demo-fuel/demo-fuel
php oil refine migrate --packages=auth
```

### 2. Tạo admin user qua web:
Truy cập: `http://localhost:8888/auth/create_admin`

---

## **Cách 2: Sử dụng Oil Task**

### 1. Chạy migration để tạo bảng users:
```bash
cd /home/dienvan/demo-fuel/demo-fuel
php oil refine migrate --packages=auth
```

### 2. Tạo admin user:
```bash
php oil r createadmin
```

### 3. Tạo user thường (tùy chọn):
```bash
php oil r createadmin:user username password
# Ví dụ:
php oil r createadmin:user john john123
```

### 4. Xem danh sách users:
```bash
php oil r createadmin:list
```

---

## **Cách 2: Sử dụng Oil Console**

### 1. Chạy migration:
```bash
php oil refine migrate --packages=auth
```

### 2. Mở Oil Console:
```bash
php oil console
```

### 3. Trong console, chạy lệnh tạo admin:
```php
// Tạo admin user
$user_id = Auth::create_user('admin', 'admin123', 'admin@demo.com', 100);

// Cập nhật thông tin bổ sung
$user = Model_User::find($user_id);
$user->name = 'Administrator';
$user->phone = '0123456789';
$user->gender = 0;
$user->avt = '';
$user->isAdmin = 1;
$user->save();

echo "Admin user created with ID: " . $user_id;
```

---

## **Cách 3: Tạo trực tiếp trong Controller (Development only)**

Thêm action tạm thời trong Controller_Auth:

```php
public function action_setup()
{
    if (ENVIRONMENT !== 'development') {
        throw new HttpNotFoundException();
    }
    
    try {
        $user_id = Auth::create_user('admin', 'admin123', 'admin@demo.com', 100);
        
        $user = Model_User::find($user_id);
        $user->name = 'Administrator';
        $user->phone = '0123456789';
        $user->gender = 0;
        $user->avt = '';
        $user->isAdmin = 1;
        $user->save();
        
        echo "Admin user created successfully! ID: " . $user_id;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
```

Sau đó truy cập: `http://localhost:8888/auth/setup`

---

## **📋 Thông tin đăng nhập mặc định:**

- **Username:** admin
- **Password:** admin123
- **Email:** admin@demo.com
- **Group:** 100 (Admin)

---

## **🌐 URLs:**

- **Login:** http://localhost:8888/auth/login
- **Register:** http://localhost:8888/auth/register
- **Admin Home:** http://localhost:8888/admin/home
- **User Home:** http://localhost:8888/user/home

---

## **✅ Kiểm tra hệ thống:**

1. Truy cập login page
2. Đăng nhập với admin/admin123
3. Kiểm tra redirect đến admin/home
4. Test logout
5. Test phân quyền (user thường không thể vào admin)

---

## **🔧 Troubleshooting:**

### Lỗi "Class Auth not found":
- Đảm bảo auth package được load trong config.php
- Kiểm tra file config/auth.php tồn tại

### Lỗi database:
- Chạy migration: `php oil refine migrate --packages=auth`
- Kiểm tra kết nối database trong config/db.php

### Lỗi permission:
- Kiểm tra file config/simpleauth.php
- Đảm bảo groups và roles được định nghĩa đúng
