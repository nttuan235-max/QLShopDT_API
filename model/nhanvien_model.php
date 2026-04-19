<?php
include_once "../../includes/api_helper.php";

class NhanVien {
    public static function getAll() {
        return callNhanVienAPIMethod([], 'GET')['data'] ?? [];
    }
    public static function getOne($manv) {
        return callNhanVienAPIMethod([], 'GET', "?manv=$manv")['data'] ?? null;
    }
    public static function add($tennv) {
        return callNhanVienAPIMethod(['tennv' => $tennv], 'POST');
    }
    public static function update($manv, $tennv) {
        return callNhanVienAPIMethod(['manv' => $manv, 'tennv' => $tennv], 'PUT');
    }
    public static function delete($manv) {
        return callNhanVienAPIMethod([], 'DELETE', "?manv=$manv");
    }
}
?>