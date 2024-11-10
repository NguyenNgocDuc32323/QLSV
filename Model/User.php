<?php
// Model/User.php

class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function checkLogin($ten_dang_nhap, $mat_khau) {
        // Mã hóa mật khẩu người dùng nhập vào bằng SHA-1
        $mat_khau_ma_hoa = sha1($mat_khau);

        // Thực hiện truy vấn
        $query = "SELECT * FROM nguoidung WHERE ten_dang_nhap = ? AND mat_khau = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $ten_dang_nhap, $mat_khau_ma_hoa);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>