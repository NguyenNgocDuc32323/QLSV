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
        $query = "SELECT * FROM nguoidung WHERE email = ? AND mat_khau = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $email, $mat_khau_ma_hoa);
        $stmt->execute();
        return $stmt->get_result();
    }

     // Check if email already exists in the database
    public function checkEmailExists($email) {
        $query = "SELECT id FROM nguoidung WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0; // Return true if email exists
    }

    // Register a new user
   public function registerUser($username, $email, $password, $phone, $gender, $birth) {
    // Hash the password using SHA-1
    $hashed_password = sha1($password);  // Hash password with SHA-1

    // Insert the user into the database
    $query = "INSERT INTO nguoidung (ho_ten, email, mat_khau, so_dien_thoai, gioi_tinh, ngay_sinh) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ssssss", $username, $email, $hashed_password, $phone, $gender, $birth);

    return $stmt->execute(); // Return true if insertion was successful
}

}
?>