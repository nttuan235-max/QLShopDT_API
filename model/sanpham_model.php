<?php
include_once "../../includes/api_helper.php";

class SanPham {
    /**
     * Lấy tất cả sản phẩm từ API
     * @return array Mảng các sản phẩm
     */
    public static function getAllProducts() {
        $result = callSanphamAPI(['action' => 'getall']);
        return ($result && $result['status']) ? $result['data'] : [];
    }
}
?>