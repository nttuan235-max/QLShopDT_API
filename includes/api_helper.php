<?php
/**
 * API Helper - Hàm hỗ trợ gọi API, xác thực và tiện ích
 * Mọi hàm đều có function_exists guard để tránh conflict với bootstrap.php
 */

// ========== CONSTANTS ==========

if (!defined('APP_BASE_URL')) {
    define('APP_BASE_URL', 'http://localhost/QLShopDT_API');
}
// ========== CSRF ==========

<<<<<<< HEAD
// API URLs (RPC - dùng cho các module chưa migrate)
define('KHACHHANG_API_URL','http://localhost/QLShopDT_API/api/khachhang_api.php');
define('GIOHANG_API_URL',  'http://localhost/QLShopDT_API/api/giohang_api.php');
define('NHANVIEN_API_URL', 'http://localhost/QLShopDT_API/api/nhanvien_api.php');
define('PROFILE_API_URL',  'http://localhost/QLShopDT_API/api/profile_api.php');
<<<<<<< HEAD
define('THONGKE_API_URL', 'http://localhost/QLShopDT_API/api/thongke_api.php');
define('AUTH_API_URL',     'http://localhost/QLShopDT_API/api/auth_api.php');
define('REGISTER_API_URL', 'http://localhost/QLShopDT_API/api/register_api.php');
define('DONHANG_API_URL',  'http://localhost/QLShopDT_API/api/donhang_api.php');
define('VANCHUYEN_API_URL','http://localhost/QLShopDT_API/api/vanchuyen_api.php');

define('NHANVIEN_REST_API_URL', 'http://localhost/QLShopDT_API/api/nhanvien_rest_api.php');
=======
define('VANCHUYEN_API_URL','http://localhost/QLShopDT_API/api/vanchuyen_api.php');

// ========== CSRF PROTECTION ==========
>>>>>>> dac04e628c9690cc1973fddf27fd33bc89a04ed4

/**
 * Tạo CSRF token
 */
function csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
=======
if (!function_exists('csrf_token')) {
    function csrf_token(): string {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
>>>>>>> 2fdf90839d863e34b82cd7546dcdeaf0327405fd
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
    }
}

if (!function_exists('verify_csrf')) {
    function verify_csrf(?string $token = null): bool {
        $token ??= $_POST['csrf_token'] ?? '';
        return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}

if (!function_exists('regenerate_csrf')) {
    function regenerate_csrf(): string {
        return $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

// ========== API CALLS ==========

/**
 * Gọi RESTful API (GET / POST / PUT / DELETE).
 * Tự động forward session cookie để controller nhận diện người dùng.
 */
if (!function_exists('callAPI')) {
    function callAPI(string $method, string $endpoint, array $data = []): array {
        $url    = APP_BASE_URL . $endpoint;
        $method = strtoupper($method);

        if ($method === 'GET' && !empty($data)) {
            $url .= '?' . http_build_query($data);
        }

        $sessionName = session_name();
        $sessionId   = session_id();
        $wasActive   = (session_status() === PHP_SESSION_ACTIVE);

        if ($wasActive) session_write_close();

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_TIMEOUT        => 10,
        ]);

        if (in_array($method, ['POST', 'PUT', 'PATCH']) && !empty($data)) {
            $json = json_encode($data, JSON_UNESCAPED_UNICODE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($json),
            ]);
        }

        if ($sessionName && $sessionId) {
            curl_setopt($ch, CURLOPT_COOKIE, $sessionName . '=' . $sessionId);
        }

        $response = curl_exec($ch);
        unset($ch);

        if ($wasActive && !headers_sent()) session_start();

        if ($response === false) {
            return ['status' => false, 'message' => 'Không thể kết nối đến API'];
        }

        return json_decode($response, true) ?? ['status' => false, 'message' => 'Phản hồi không hợp lệ'];
    }
}

/**
 * Gọi RPC API kiểu cũ (action-based).
 * Dùng cURL thay file_get_contents để forward session cookie.
 */
if (!function_exists('callRpcAPI')) {
    function callRpcAPI(string $url, array $data): array {
        $sessionName = session_name();
        $sessionId   = session_id();
        $wasActive   = (session_status() === PHP_SESSION_ACTIVE);

        if ($wasActive) session_write_close();

<<<<<<< HEAD
    return json_decode($response, true);
}
///////////////////call API chung sử dụng các method trong postman GET, POST, PUT, DELETE
function callAPIMethod($url, $data = [], $method = 'POST') {
    $options = [
        "http" => [
            "method" => $method,
            "header" => "Content-Type: application/json",
        ]
    ];
    if (in_array($method, ['POST', 'PUT']) && !empty($data)) {
        $options['http']['content'] = json_encode($data);
    }
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    return json_decode($response, true);
}

/** Gọi API khách hàng */
function callKhachhangAPI($data) {
    return callRpcAPI(KHACHHANG_API_URL, $data);
}
<<<<<<< HEAD
////////////** Gọi API nhân viên */
=======

/** Gọi API nhân viên */
>>>>>>> dac04e628c9690cc1973fddf27fd33bc89a04ed4
function callNhanVienAPI($data) {
    return callRpcAPI(NHANVIEN_API_URL, $data);
}
///////////** Gọi API nhân viên với các method  */
function callNhanVienAPIMethod($data = [], $method = 'GET', $query = '') {
    $url =  NHANVIEN_REST_API_URL. $query;  
    return callAPIMethod($url, $data, $method);
}

///////////////////thong ke
function callThongKeAPI($filters = []) {
    $query = !empty($filters) ? '?' . http_build_query($filters) : '';
    return callAPIMethod(THONGKE_API_URL . $query, [], 'GET');
}
=======
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        $ch   = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $json,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json; charset=utf-8'],
            CURLOPT_TIMEOUT        => 10,
        ]);

        if ($sessionName && $sessionId) {
            curl_setopt($ch, CURLOPT_COOKIE, $sessionName . '=' . $sessionId);
        }

        $response = curl_exec($ch);
        unset($ch);
>>>>>>> 2fdf90839d863e34b82cd7546dcdeaf0327405fd

        if ($wasActive && !headers_sent()) session_start();

        if ($response === false) {
            return ['status' => false, 'message' => 'Không thể kết nối đến API'];
        }

        return json_decode($response, true) ?? ['status' => false, 'message' => 'Phản hồi không hợp lệ'];
    }
}

// ========== ROLE & AUTH ==========

if (!function_exists('getCurrentRole')) {
    function getCurrentRole(): ?int {
        return isset($_SESSION['role']) ? (int)$_SESSION['role'] : null;
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin(): bool { return getCurrentRole() === 1; }
}

if (!function_exists('isStaff')) {
    function isStaff(): bool { return getCurrentRole() === 2; }
}

if (!function_exists('isCustomer')) {
    function isCustomer(): bool { return getCurrentRole() === 0; }
}

if (!function_exists('isAdminOrStaff')) {
    function isAdminOrStaff(): bool {
        $r = getCurrentRole();
        return $r === 1 || $r === 2;
    }
}

if (!function_exists('requireLogin')) {
    function requireLogin(): void {
        if (!isset($_SESSION['username'])) {
            header('Location: /QLShopDT_API/views/auth/login.php');
            exit();
        }
    }
}

if (!function_exists('requireRole')) {
    function requireRole($roles): void {
        requireLogin();
        if (!is_array($roles)) $roles = [$roles];
        if (!in_array(getCurrentRole(), $roles)) {
            http_response_code(403);
            echo 'Bạn không có quyền truy cập trang này.';
            exit();
        }
    }
}

// ========== UTILITIES ==========

if (!function_exists('e')) {
    function e($string): string {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('formatMoney')) {
    function formatMoney($amount): string {
        return number_format((float)$amount, 0, ',', '.') . ' đ';
    }
}

if (!function_exists('setFlash')) {
    function setFlash(string $key, string $message): void {
        $_SESSION['flash'][$key] = $message;
    }
}

if (!function_exists('getFlash')) {
    function getFlash(string $key): ?string {
        if (isset($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
        return null;
    }
}

if (!function_exists('hasFlash')) {
    function hasFlash(string $key): bool {
        return isset($_SESSION['flash'][$key]);
    }
}
