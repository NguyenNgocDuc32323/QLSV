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

public function updatePassword($userId, $currentPassword, $newPassword) {
    // Initialize $storedPassword to avoid warnings
    $storedPassword = null;

    // Query to fetch the current password hash
    $query = "SELECT mat_khau FROM nguoidung WHERE id = ?";
    $stmt = $this->conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->store_result();

        // Check if the result has a row
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($storedPassword);
            $stmt->fetch();
        } else {
            // Handle case where no user is found
            return false; // or handle the error appropriately
        }

        $stmt->close();
    } else {
        // Handle preparation failure
        return false;
    }

    // Verify that $storedPassword has been assigned before using it
    if ($storedPassword !== null && sha1($currentPassword) === $storedPassword) {
        // Hash the new password with sha1
        $newHashedPassword = sha1($newPassword);

        // Update query to change the password
        $updateQuery = "UPDATE nguoidung SET mat_khau = ? WHERE id = ?";
        $updateStmt = $this->conn->prepare($updateQuery);

        if ($updateStmt) {
            $updateStmt->bind_param("si", $newHashedPassword, $userId);

            if ($updateStmt->execute()) {
                $updateStmt->close();
                return true; // Password updated successfully
            }

            $updateStmt->close();
        }
    }

    return false; // Current password does not match or an error occurred
}




}
?>