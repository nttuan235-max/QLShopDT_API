<?php
include_once "../../includes/api_helper.php";

class VanChuyen {
    /**
     * Lấy tất cả đơn vận chuyển từ API
     * @return array Mảng các đơn vận chuyển
     */
    public static function getAllShipments() {
        $result = callVanchuyenAPI(['action' => 'getall']);
        return ($result && $result['status']) ? $result['data'] : [];
    }

    /**
     * Lấy chi tiết 1 đơn vận chuyển
     * @param int $mavc - Mã vận chuyển
     * @return array Chi tiết đơn vận chuyển hoặc mảng rỗng
     */
    public static function getShipmentDetail($mavc) {
        $result = callVanchuyenAPI(['action' => 'getone', 'mavc' => $mavc]);
        return ($result && $result['status']) ? $result['data'] : [];
    }

    /**
     * Thêm đơn vận chuyển mới
     * @param int $madh - Mã đơn hàng
     * @param int $makh - Mã khách hàng
     * @param string $ngaygiao - Ngày giao dự kiến (YYYY-MM-DD)
     * @return array Kết quả từ API
     */
    public static function addShipment($madh, $makh, $ngaygiao) {
        return callVanchuyenAPI([
            'action' => 'add',
            'madh' => $madh,
            'makh' => $makh,
            'ngaygiao' => $ngaygiao
        ]);
    }

    /**
     * Cập nhật đơn vận chuyển
     * @param int $mavc - Mã vận chuyển
     * @param int $madh - Mã đơn hàng
     * @param int $makh - Mã khách hàng
     * @param string $ngaygiao - Ngày giao dự kiến (YYYY-MM-DD)
     * @return array Kết quả từ API
     */
    public static function updateShipment($mavc, $madh, $makh, $ngaygiao) {
        return callVanchuyenAPI([
            'action' => 'update',
            'mavc' => $mavc,
            'madh' => $madh,
            'makh' => $makh,
            'ngaygiao' => $ngaygiao
        ]);
    }

    /**
     * Xóa đơn vận chuyển
     * @param int $mavc - Mã vận chuyển
     * @return array Kết quả từ API
     */
    public static function deleteShipment($mavc) {
        return callVanchuyenAPI(['action' => 'delete', 'mavc' => $mavc]);
    }
}
?>
