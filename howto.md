# Cấu trúc dự án QLShopDT_API

## 📂 Tổ chức thư mục (Cách 1 - Tổ chức theo chức năng)

```
d:\xamp\htdocs\QLShopDT_API/
│
├── 📂 api/                         ← API files (xử lý dữ liệu)
│   ├── auth_api.php                • Đăng nhập
│   ├── sanpham_api.php             • Sản phẩm CRUD
│   ├── danhmuc_api.php             • Danh mục CRUD
│   ├── khachhang_api.php           • Khách hàng CRUD
│   ├── donhang_api.php             • Đơn hàng CRUD
│   └── db.php                      • Kết nối database
│
├── 📂 assets/                      ← Tài nguyên tĩnh
│   └── 📂 css/                     • Stylesheet files
│       ├── main.css                • CSS chung
│       ├── login.css               • CSS đăng nhập
│       ├── register.css            • CSS đăng ký
│       ├── nv.css                  • CSS quản lý
│       └── trangchu.css            • CSS trang chủ
│
├── 📂 includes/                    ← File dùng chung
│   └── header.php                  • Header & Navigation
│
├── 📂 views/                       ← Giao diện người dùng
│   ├── login.php                   • Trang đăng nhập
│   ├── register.php                • Trang đăng ký
│   ├── logout.php                  • Xử lý đăng xuất
│   ├── trangchu.php                • Trang chủ
│   │
│   ├── 📂 sanpham/                 • Module sản phẩm
│   │   ├── sanpham.php             - Danh sách
│   │   ├── sanpham_add.php         - Thêm mới
│   │   ├── sanpham_edit.php        - Sửa
│   │   ├── sanpham_edit_save.php   - Lưu chỉnh sửa
│   │   └── sanpham_del.php         - Xóa
│   │
│   ├── 📂 danhmuc/                 • Module danh mục
│   ├── 📂 khachhang/               • Module khách hàng
│   ├── 📂 nhanvien/                • Module nhân viên
│   ├── 📂 donhang/                 • Module đơn hàng
│   ├── 📂 giohang/                 • Module giỏ hàng
│   ├── 📂 thanhtoan/               • Module thanh toán
│   ├── 📂 thongso/                 • Module thông số
│   └── 📂 vanchuyen/               • Module vận chuyển
│
├── index.php                       ← Entry point (redirect to views/trangchu.php)
└── qlshopdienthoai.sql             ← Database schema


```

## 🔗 Đường dẫn URL

### Trang người dùng:
- Trang chủ: `http://localhost/QLShopDT_API/`
- Đăng nhập: `http://localhost/QLShopDT_API/views/login.php`
- Đăng ký: `http://localhost/QLShopDT_API/views/register.php`
- Quản lý sản phẩm: `http://localhost/QLShopDT_API/views/sanpham/sanpham.php`

### API Endpoints:
- Auth: `http://localhost/QLShopDT_API/api/auth_api.php`
- Sản phẩm: `http://localhost/QLShopDT_API/api/sanpham_api.php`
- Danh mục: `http://localhost/QLShopDT_API/api/danhmuc_api.php`
- Khách hàng: `http://localhost/QLShopDT_API/api/khachhang_api.php`
- Đơn hàng: `http://localhost/QLShopDT_API/api/donhang_api.php`

## 📝 Quy tắc đường dẫn trong code

### 1. Include header.php:

```php
// Từ views/*.php (cùng cấp views/)
include "../includes/header.php";

// Từ views/sanpham/*.php (trong subfolder của views/)
include "../../includes/header.php";
```

### 2. Link CSS:

```php
// Dùng đường dẫn tuyệt đối
$extra_css = '<link rel="stylesheet" href="/QLShopDT_API/assets/css/login.css">';

// Hoặc từ subfolder
<link rel="stylesheet" href="../../assets/css/nv.css">
```

### 3. Gọi API:

```php
// Từ bất kỳ đâu
$api_url = "http://localhost/QLShopDT_API/api/sanpham_api.php";
```

### 4. Link giữa các trang:

```php
// Dùng đường dẫn tuyệt đối
<a href="/QLShopDT_API/views/sanpham/sanpham.php">Sản phẩm</a>
```

## ✅ Ưu điểm cấu trúc mới:

1. **Tách biệt rõ ràng**: API, View, Assets, Includes đều có folder riêng
2. **Dễ tìm kiếm**: Muốn xem giao diện → vào `views/`, muốn sửa API → vào `api/`
3. **Dễ bảo trì**: Mỗi module có subfolder riêng trong `views/`
4. **Bảo mật tốt hơn**: Có thể chặn truy cập trực tiếp vào `api/` và `includes/`
5. **Dễ mở rộng**: Thêm module mới chỉ cần tạo folder trong `views/`

## 🔄 So sánh với cấu trúc cũ:

| **Cũ** | **Mới** |
|---------|---------|
| `/sanpham/sanpham.php` | `/views/sanpham/sanpham.php` |
| `/css/main.css` | `/assets/css/main.css` |
| `/header.php` | `/includes/header.php` |
| `/api/sanpham_api.php` | `/api/sanpham_api.php` ✅ giữ nguyên |

## 🚀 Tiếp theo có thể làm:

1. Tạo thêm `/assets/js/` cho JavaScript files
2. Tạo thêm `/assets/img/` cho hình ảnh
3. Thêm file `config.php` trong `includes/` cho cấu hình chung
4. Tách logic xử lý form ra khỏi view (tạo controllers/)
