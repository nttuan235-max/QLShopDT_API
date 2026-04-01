<?php
include_once "../../includes/api_helper.php";

class ThongSo {
    /**
     * Lấy tất cả thông số của một sản phẩm từ API
     * @param string $masp Mã sản phẩm
     * @return array Mảng các thông số
     */
    public static function getThongSoByProduct($masp) {
        $result = callThongsoAPI([
            'action' => 'getall',
            'masp'   => $masp
        ]);
        return ($result && $result['status']) ? $result['data'] : [];
    }
}
?>