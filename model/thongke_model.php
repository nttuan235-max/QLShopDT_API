<?php
include_once "../../includes/api_helper.php";

class ThongKe {
     public static function getThongKe($filters = []) {
        $result = callThongKeAPI($filters);
        return ($result && $result['status']) ? $result['data'] : [];
    }
}
?>
