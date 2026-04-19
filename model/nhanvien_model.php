<?php
include_once "../../includes/api_helper.php";

class NhanVien {
    /**
     * Lấy tất cả nhân viên từ API
     * @return array Mảng các nhân viên
     */
    public static function getAllEmployees() {
        $result = callNhanvienAPI(['action' => 'getall']);
        return ($result && $result['status']) ? $result['data'] : [];
    }
}

class NhanVien_REST {
    public static function getAll() {
        return callAPIMethod(NHANVIEN_REST_API_URL, [], 'GET')['data'] ?? [];
    }
    public static function getOne($manv) {
        return callAPIMethod(NHANVIEN_REST_API_URL . "?manv=$manv", [], 'GET')['data'] ?? null;
    }
    public static function add($tennv, $diachi = '', $sdt = '', $ns = '') {
        return callAPIMethod(NHANVIEN_REST_API_URL, [
            'tennv' => $tennv, 'diachi' => $diachi, 'sdt' => $sdt, 'ns' => $ns
        ], 'POST');
    }
    public static function update($manv, $tennv, $diachi = '', $sdt = '', $ns = '') {
        return callAPIMethod(NHANVIEN_REST_API_URL, [
            'manv' => $manv, 'tennv' => $tennv, 'diachi' => $diachi, 'sdt' => $sdt, 'ns' => $ns
        ], 'PUT');
    }
    public static function delete($manv) {
        return callAPIMethod(NHANVIEN_REST_API_URL . "?manv=$manv", [], 'DELETE');
    }
}
?>