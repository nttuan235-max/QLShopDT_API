<?php
include_once __DIR__ . "/../includes/api_helper.php";

class DanhMuc {
    // ── Cấu trúc dữ liệu ──
    public $madm;
    public $tendm;

    // ── Ràng buộc ──
    const TENDM_MAX_LENGTH = 100;
    const TENDM_MIN_LENGTH = 1;

    public function __construct($madm = null, $tendm = '') {
        $this->madm  = $madm;
        $this->tendm = trim($tendm);
    }

    // ── Validate ──

    /**
     * @param string $tendm
     * @return string|null - Trả về message lỗi, null nếu hợp lệ
     */
    public static function validateTenDM($tendm) {
        $tendm = trim($tendm);
        if ($tendm === '') {
            return 'Tên danh mục không được để trống';
        }
        if (mb_strlen($tendm) > self::TENDM_MAX_LENGTH) {
            return 'Tên danh mục tối đa ' . self::TENDM_MAX_LENGTH . ' ký tự';
        }
        return null;
    }

    /**
     * @param mixed $madm
     * @return string|null - Trả về message lỗi, null nếu hợp lệ
     */
    public static function validateMaDM($madm) {
        if (empty($madm) || !is_numeric($madm) || $madm <= 0) {
            return 'Mã danh mục không hợp lệ';
        }
        return null;
    }

    // ── CRUD ──

    /**
     * @return array - Trả về toàn bộ danh mục có trong CSDL
     */
    public static function getAll() {
        $result = callDanhmucAPI(['action' => 'getall']);
        return ($result && $result['status']) ? $result['data'] : [];
    }

    /**
     * @param int $madm - Mã danh mục
     * @return array - Trả về thông tin danh mục {status, message, data?}
     */
    public static function getOne($madm) {
        $err = self::validateMaDM($madm);
        if ($err) return ['status' => false, 'message' => $err];

        return callDanhmucAPI(['action' => 'getone', 'madm' => $madm]);
    }

    /**
     * @param string $tendm - Tên danh mục cần thêm
     * @return array - Trả về kết quả {status, message}
     */
    public static function add($tendm) {
        $tendm = trim($tendm);
        $err = self::validateTenDM($tendm);
        if ($err) return ['status' => false, 'message' => $err];

        return callDanhmucAPI(['action' => 'add', 'tendm' => $tendm]);
    }

    /**
     * @param int $madm - Mã danh mục cần cập nhật
     * @param string $tendm - Tên danh mục mới
     * @return array - Trả về kết quả {status, message}
     */
    public static function update($madm, $tendm) {
        $tendm = trim($tendm);
        $err = self::validateMaDM($madm);
        if ($err) return ['status' => false, 'message' => $err];

        $err = self::validateTenDM($tendm);
        if ($err) return ['status' => false, 'message' => $err];

        return callDanhmucAPI(['action' => 'update', 'madm' => $madm, 'tendm' => $tendm]);
    }

    /**
     * @param int $madm - Mã danh mục cần xóa
     * @return array - Trả về kết quả {status, message}
     */
    public static function delete($madm) {
        $err = self::validateMaDM($madm);
        if ($err) return ['status' => false, 'message' => $err];

        return callDanhmucAPI(['action' => 'delete', 'madm' => $madm]);
    }
}
?>          