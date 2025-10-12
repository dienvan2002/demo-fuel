# 🏗️ Kiến trúc dự án Demo-Fuel

## **📋 Tổng quan**

Dự án sử dụng kiến trúc **Service-Oriented** với **Separation of Concerns**:

```
┌─────────────────┐
│   Controller    │ ← Xử lý HTTP Request/Response
└────────┬────────┘
         │
         ↓
┌─────────────────┐
│    Service      │ ← Business Logic
└────────┬────────┘
         │
         ↓
┌─────────────────┐
│  Model / Auth   │ ← Data Access Layer
└─────────────────┘
```

---

## **🔧 Các Layer**

### **1. Controller Layer** (`fuel/app/classes/controller/`)

**Trách nhiệm:**
- Nhận HTTP Request
- Validate input (basic)
- Gọi Service xử lý
- Trả về HTTP Response (View/JSON/Redirect)

**KHÔNG được:**
- Chứa business logic
- Truy cập database trực tiếp
- Xử lý authentication logic

**Ví dụ:**
```php
// ✅ ĐÚNG - Controller nhẹ, chỉ điều phối
public function action_login()
{
    if (Input::method() === 'POST') {
        $result = Service_Auth::login(
            Input::post('username'),
            Input::post('password'),
            Input::post('remember')
        );
        
        if ($result['success']) {
            Response::redirect($result['redirect']);
        }
    }
    
    return View::forge('auth/login');
}

// ❌ SAI - Controller chứa quá nhiều logic
public function action_login()
{
    if (Input::method() === 'POST') {
        $user = DB::select()->from('users')->where('username', $username)->execute();
        if ($user && password_verify($password, $user->password)) {
            Auth::login($username, $password);
            if ($user->group == 100) {
                Response::redirect('admin/home');
            }
        }
    }
}
```

---

### **2. Service Layer** (`fuel/app/classes/service/`)

**Trách nhiệm:**
- Chứa toàn bộ business logic
- Validate dữ liệu phức tạp
- Xử lý luồng nghiệp vụ
- Gọi Model/Auth để truy cập data
- Trả về kết quả chuẩn hóa

**Ưu điểm:**
- ✅ **Reusable**: Service có thể được gọi từ nhiều Controller
- ✅ **Testable**: Dễ test business logic độc lập
- ✅ **Maintainable**: Tập trung logic ở một chỗ
- ✅ **Single Responsibility**: Mỗi service làm 1 việc rõ ràng

**Ví dụ:**
```php
// Service_Auth - Xử lý tất cả logic authentication
class Service_Auth
{
    public static function login($username, $password, $remember = false)
    {
        // Validate
        if (empty($username) || empty($password)) {
            return ['success' => false, 'message' => 'Missing data'];
        }
        
        // Business logic
        if (!Auth::instance()->login($username, $password)) {
            Log::error('Login failed for: ' . $username);
            return ['success' => false, 'message' => 'Invalid credentials'];
        }
        
        // Remember me logic
        if ($remember) {
            Auth::remember_me();
        }
        
        // Determine redirect
        $redirect = Auth::member(100) ? 'admin/home' : 'user/home';
        
        return ['success' => true, 'redirect' => $redirect];
    }
}
```

---

### **3. Model Layer** (`fuel/app/classes/model/`)

**Trách nhiệm:**
- Truy cập database
- Định nghĩa relationships
- Provide data access methods

**KHÔNG được:**
- Chứa business logic phức tạp
- Validate phức tạp (để Service làm)

---

## **📁 Cấu trúc thư mục**

```
fuel/app/
├── classes/
│   ├── controller/          # HTTP Request/Response handlers
│   │   ├── base.php         # Base controller với helper methods
│   │   ├── auth.php         # Auth controller (login/register)
│   │   ├── admin/           # Admin controllers
│   │   └── user/            # User controllers
│   │
│   ├── service/             # ⭐ Business Logic Layer
│   │   └── auth.php         # Auth service
│   │
│   ├── model/               # Data Access Layer
│   │   └── user.php         # User model wrapper
│   │
│   └── middleware/          # Middleware (optional)
│       └── auth.php         # Auth middleware
│
├── config/                  # Configuration
│   ├── auth.php             # Auth config
│   └── simpleauth.php       # SimpleAuth config
│
└── views/                   # Views
    └── auth/
        ├── login.php
        └── register.php
```

---

## **🎯 Luồng xử lý Authentication**

### **Login Flow:**

```
1. User submit form
   ↓
2. Controller_Auth::action_login()
   - Nhận username, password từ POST
   ↓
3. Service_Auth::login()
   - Validate input
   - Check user in DB
   - Call Auth::instance()->login()
   - Handle remember me
   - Determine redirect URL
   - Return result array
   ↓
4. Controller nhận result
   - Nếu success: redirect
   - Nếu fail: show error
```

### **Register Flow:**

```
1. User submit form
   ↓
2. Controller_Auth::action_register()
   - Nhận data từ POST
   ↓
3. Service_Auth::register()
   - Validate data
   - Check username exists
   - Call Auth::create_user()
   - Save profile data
   - Return result
   ↓
4. Controller nhận result
   - Nếu success: redirect to login
   - Nếu fail: show error
```

---

## **✅ Best Practices**

### **1. Controller**
```php
// ✅ ĐÚNG
public function action_something()
{
    $result = Service_Something::doSomething(Input::post());
    
    if ($result['success']) {
        Response::redirect('success');
    }
    
    return View::forge('view', ['error' => $result['message']]);
}
```

### **2. Service**
```php
// ✅ ĐÚNG
class Service_Something
{
    public static function doSomething($data)
    {
        // Validate
        // Business logic
        // Call Model/Auth
        // Return standardized result
        
        return [
            'success' => true/false,
            'message' => 'Message',
            'data' => $result_data
        ];
    }
}
```

### **3. Debugging**
```php
// Sử dụng Log trong Service
\Log::info('🔍 Debug info: ' . $data);
\Log::error('❌ Error: ' . $e->getMessage());

// Log file: fuel/app/logs/YYYY/MM/DD.php
```

---

## **🚀 Lợi ích của kiến trúc này**

1. **Clean Code**: Mỗi layer có trách nhiệm rõ ràng
2. **Testable**: Dễ test từng layer độc lập
3. **Maintainable**: Dễ maintain và extend
4. **Reusable**: Service có thể dùng ở nhiều nơi
5. **Scalable**: Dễ scale khi project lớn lên

---

## **📚 Tài liệu tham khảo**

- [FuelPHP SimpleAuth](https://fuelphp.com/docs/packages/auth/simpleauth/intro.html)
- [Service Layer Pattern](https://martinfowler.com/eaaCatalog/serviceLayer.html)
- [Separation of Concerns](https://en.wikipedia.org/wiki/Separation_of_concerns)
