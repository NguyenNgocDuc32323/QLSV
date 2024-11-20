<?php
class Profile {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function getProfileData($id_nguoidung) {
        $query = "
            SELECT 
                nguoidung.ho_ten, nguoidung.email, nguoidung.so_dien_thoai, nguoidung.ngay_sinh, nguoidung.gioi_tinh, nguoidung.avatar,nguoidung.vai_tro,
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

    public function updateProfileData($userId, $fullName, $email, $phone, $birthYear, $gender, $idNumber = null, $hometown = null, $avatar = null) {
        // Chuẩn hóa giới tính
        switch ($gender) {
            case 'male':
                $gender = 'Nam';
                break;
            case 'female':
                $gender = 'Nữ';
                break;
            case 'other':
            default:
                $gender = 'Khác';
                break;
        }
    
        // Kiểm tra vai trò người dùng
        $userRole = $this->getUserRole($userId); // Hàm lấy vai trò người dùng
        if ($userRole === 'Quan Tri Vien') {
            // Nếu vai trò là Quản Trị Viên, chỉ cập nhật bảng nguoidung
            $query = "
                UPDATE nguoidung 
                SET 
                    ho_ten = ?, 
                    email = ?, 
                    so_dien_thoai = ?, 
                    ngay_sinh = ?, 
                    gioi_tinh = ?, 
                    avatar = ?
                WHERE 
                    id = ?
            ";
    
            if ($stmt = $this->conn->prepare($query)) {
                $stmt->bind_param("ssssssi", $fullName, $email, $phone, $birthYear, $gender, $avatar, $userId);
    
                if ($stmt->execute()) {
                    return true; // Thành công
                } else {
                    echo "Lỗi SQL: " . $stmt->error;
                    return false;
                }
            } else {
                echo "Lỗi chuẩn bị SQL: " . $this->conn->error;
                return false;
            }
        } else {
            // Nếu không phải Quản Trị Viên, cập nhật cả bảng nguoidung và hocsinh
            if ($avatar) {
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
            } else {
                $query = "
                    UPDATE nguoidung AS nd
                    JOIN hocsinh AS hs ON nd.id = hs.id_nguoi_dung
                    SET 
                        nd.ho_ten = ?, 
                        nd.email = ?, 
                        nd.so_dien_thoai = ?, 
                        nd.ngay_sinh = ?, 
                        nd.gioi_tinh = ?, 
                        hs.so_cmnd = ?, 
                        hs.que_quan = ? 
                    WHERE 
                        nd.id = ?
                ";
            }
    
            if ($stmt = $this->conn->prepare($query)) {
                if ($avatar) {
                    $stmt->bind_param(
                        "ssssssssi",
                        $fullName,
                        $email,
                        $phone,
                        $birthYear,
                        $gender,
                        $avatar,
                        $idNumber,
                        $hometown,
                        $userId
                    );
                } else {
                    $stmt->bind_param(
                        "sssssssi",
                        $fullName,
                        $email,
                        $phone,
                        $birthYear,
                        $gender,
                        $idNumber,
                        $hometown,
                        $userId
                    );
                }
    
                if ($stmt->execute()) {
                    return true; // Thành công
                } else {
                    echo "Lỗi SQL: " . $stmt->error;
                    return false;
                }
            } else {
                echo "Lỗi chuẩn bị SQL: " . $this->conn->error;
                return false;
            }
        }
    }
    
    // Hàm hỗ trợ lấy vai trò người dùng
    private function getUserRole($userId) {
        $query = "SELECT vai_tro FROM nguoidung WHERE id = ?";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            return $result['vai_tro'] ?? null;
        }
        return null;
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
                return false; 
            }

            $stmt->close();
        } else {
            // Handle preparation failure
            return false;
        }
        if ($storedPassword !== null && sha1($currentPassword) === $storedPassword) {
            $newHashedPassword = sha1($newPassword);
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

        return false;
    }
    public function checkCurrentPassword($userId, $currentPassword) {
        // Giả sử bạn đã mã hóa mật khẩu trong cơ sở dữ liệu
        $query = "SELECT mat_khau FROM nguoidung WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId); // "i" để bind integer
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc(); // Lấy kết quả truy vấn

        if ($result) {
            // Kiểm tra mật khẩu hiện tại có khớp không
            if (password_verify($currentPassword, $result['mat_khau'])) {
                return true;
            }
        }
        return false;
    }




}
?>