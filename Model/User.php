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
    public function checkPhoneExists($phone) {
        $query = "SELECT id FROM nguoidung WHERE so_dien_thoai = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0; // Return true if phone number exists
    }



public function registerUser($username, $email, $password, $phone, $gender, $birth) {
    // Hash the password using SHA-1
    $hashed_password = sha1($password);

    // Start a transaction to ensure both inserts succeed or fail together
    $this->conn->begin_transaction();

    try {
        // Insert the user into the nguoidung table
        $query = "INSERT INTO nguoidung (ho_ten, email, mat_khau, so_dien_thoai, gioi_tinh, ngay_sinh) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssss", $username, $email, $hashed_password, $phone, $gender, $birth);
        $stmt->execute();

        // Get the last inserted ID
        $userId = $this->conn->insert_id;

        // Generate ma_sinh_vien with first two uppercase letters of the username and three random digits
        $prefix = strtoupper(substr($username, 0, 2));
        $suffix = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        $ma_sinh_vien = $prefix . $suffix;

        // Insert into the hocsinh table
        $query_hocsinh = "INSERT INTO hocsinh (id_nguoi_dung, ma_sinh_vien) VALUES (?, ?)";
        $stmt_hocsinh = $this->conn->prepare($query_hocsinh);
        $stmt_hocsinh->bind_param("is", $userId, $ma_sinh_vien);
        $stmt_hocsinh->execute();

        // Commit the transaction
        $this->conn->commit();
        return true; // Return true if both inserts were successful

    } catch (Exception $e) {
        // Rollback the transaction in case of any failure
        $this->conn->rollback();
        return false; // Return false if any error occurs
    }
}

}
?>