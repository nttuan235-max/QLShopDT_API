<?php
include_once "../../includes/api_helper.php";

class DonHang {
    /**
     * Lấy tất cả đơn hàng từ API
     * @return array Mảng các đơn hàng
     */
    public static function getAllOrders() {
        $result = callDonhangAPI(['action' => 'getall']);
        return ($result && $result['status']) ? $result['data'] : [];
    }

    /**
     * Lấy đơn hàng của khách hàng từ API
     * @param int $makh Mã khách hàng
     * @return array Mảng các đơn hàng
     */
    public static function getOrdersByCustomer($makh) {
        $result = callDonhangAPI(['action' => 'getbycustomer', 'makh' => $makh]);
        return ($result && $result['status']) ? $result['data'] : [];
    }

    /**
     * Lấy chi tiết một đơn hàng từ API
     * @param int $madh Mã đơn hàng
     * @return array Chi tiết đơn hàng
     */
    public static function getOrderDetail($madh) {
        $result = callDonhangAPI(['action' => 'getone', 'madh' => $madh]);
        return ($result && $result['status']) ? $result['data'] : null;
    }
}
?>