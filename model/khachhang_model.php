<?php
include_once "../../includes/api_helper.php";

class KhachHang {
    /**
     * Lấy tất cả khách hàng từ API
     * @return array Mảng các khách hàng
     */
    public static function getAllCustomers() {
        $result = callKhachhangAPI(['action' => 'getall']);
        return ($result && $result['status']) ? $result['data'] : [];
    }
}
?>