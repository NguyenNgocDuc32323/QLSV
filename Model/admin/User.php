<?php
// Model/User.php

class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function checkLogin($email, $mat_khau) {
        // Mã hóa mật khẩu người dùng nhập vào bằng SHA-1
        $mat_khau_ma_hoa = sha1($mat_khau);

        // Thực hiện truy vấn
        $query = "SELECT * FROM nguoidung WHERE email = ? AND mat_khau = ?  AND vai_tro = 'Quan Tri Vien'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $email, $mat_khau_ma_hoa);
        $stmt->execute();
        return $stmt->get_result();
    }

}
?>