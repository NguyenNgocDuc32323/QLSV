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
//     public function getProfileData() {
//     $query = "
//         SELECT 
//             nguoidung.ho_ten, nguoidung.email, nguoidung.so_dien_thoai, nguoidung.ngay_sinh, nguoidung.gioi_tinh, nguoidung.avatar,
//             hocsinh.ma_sinh_vien, hocsinh.so_cmnd, hocsinh.que_quan
//         FROM 
//             nguoidung
//         LEFT JOIN 
//             hocsinh ON nguoidung.id = hocsinh.id_nguoi_dung
//              WHERE 
//         nguoidung.vai_tro = 'Hoc Sinh'
//     ";

//     if ($stmt = $this->conn->prepare($query)) {
//         $stmt->execute();
//         $result = $stmt->get_result();
        
//         $data = [];
//         while ($row = $result->fetch_assoc()) {
//             $data[] = $row; // Collect each row as an associative array
//         }

//         return !empty($data) ? $data : false; // Return the array if there are rows, else false
//     } else {
//         return false;
//     }
// }


}
?>