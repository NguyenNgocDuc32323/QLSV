<?php
class Profile {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function getProfileData($id_nguoidung) {
        $query = "
            SELECT 
                nguoidung.ho_ten, nguoidung.email, nguoidung.so_dien_thoai, nguoidung.ngay_sinh, nguoidung.gioi_tinh, nguoidung.avatar,
                hocsinh.ma_sinh_vien, hocsinh.so_cmnd, hocsinh.que_quan
            FROM 
                nguoidung
            LEFT JOIN 
                hocsinh ON nguoidung.id = hocsinh.id_nguoi_dung
            WHERE 
                nguoidung.id = ?
        ";

        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("i", $id_nguoidung); 
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); 
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function updateProfileData($userId, $fullName, $email, $phone, $birthYear, $gender, $idNumber, $hometown, $avatar) {
    if ($gender === 'male') {
        $gender = 'Nam';
    } elseif ($gender === 'female') {
        $gender = 'Nu';
    } elseif ($gender === 'other') {
        $gender = 'Khac';
    } else {

        $gender = 'Khac';
    }

    $query = "
        UPDATE nguoidung AS nd
        JOIN hocsinh AS hs ON nd.id = hs.id_nguoi_dung
        SET 
            nd.ho_ten = ?, 
            nd.email = ?, 
            nd.so_dien_thoai = ?, 
            nd.ngay_sinh = ?, 
            nd.gioi_tinh = ?, 
            nd.avatar = ?, 
            hs.so_cmnd = ?, 
            hs.que_quan = ? 
        WHERE 
            nd.id = ?
    ";

    if ($stmt = $this->conn->prepare($query)) {
        $stmt->bind_param("ssssssssi", $fullName, $email, $phone, $birthYear, $gender, $avatar, $idNumber, $hometown, $userId);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

}
?>