<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';
require_once '../Model/Profile.php';

class ProfileController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function showProfile()
    {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true || !isset($_SESSION['login'])) {
            header('Location: login.php');
            exit();
        }
        $id_nguoidung = $_SESSION['login'];
        $profileModel = new Profile($this->conn);
        $profile_data = $profileModel->getProfileData($id_nguoidung);
        if (!$profile_data) {
            $_SESSION['login_failure'] = "Không tìm thấy dữ liệu người dùng.";
            header('Location: login.php');
            exit();
        }
        return $profile_data;
    }

    public function updatePassword($userId, $currentPassword, $newPassword)
    {
        $profileModel = new Profile($this->conn);

        if ($profileModel->updatePassword($userId, $currentPassword, $newPassword)) {
            // Trả về mã JavaScript để hiển thị thông báo thành công
            echo "<script>alert('Password updated successfully'); window.location.href = 'profile.php';</script>";
            exit();
        } else {
            // Trả về mã JavaScript để hiển thị thông báo lỗi
            echo "<script>alert('Failed to update password. Current password may be incorrect or an error occurred.'); window.history.back();</script>";
            exit();
        }
    }
    public function updateProfile($userId, $fullName, $email, $phone, $birthYear, $gender, $idNumber, $hometown, $avatar) {

        if (!isset($_SESSION['login'])) {
            header('Location: login.php');
            exit();
        }
    
        $id_nguoidung = $_SESSION['login'];
    
        // Xử lý upload ảnh nếu có
        $avatarPath = null;
        $avatarName = null;
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $avatarName = basename($_FILES['avatar']['name']); // Lấy tên file gốc
            $avatarPath = "assets/images/avatar/" . $avatarName; // Đường dẫn lưu file
    
            // Kiểm tra xem file có phải là hình ảnh hợp lệ không
            $fileExtension = pathinfo($avatarName, PATHINFO_EXTENSION);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                echo "Định dạng file không hợp lệ. Chỉ chấp nhận JPG, JPEG, PNG, GIF.";
                return;
            }
    
            // Di chuyển file đến thư mục đích
            if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $avatarPath)) {
                echo "Lỗi khi tải lên ảnh đại diện.";
                return;
            }
        }
    
        // Khởi tạo model và cập nhật dữ liệu vào cơ sở dữ liệu
        $profileModel = new Profile($this->conn);
        return $profileModel->updateProfileData(
            $id_nguoidung, 
            $fullName, 
            $email, 
            $phone, 
            $birthYear, 
            $gender, 
            $idNumber, 
            $hometown, 
            $avatarPath ? $avatarName : null // Truyền tên file nếu có, nếu không thì null
        );
    }
    
    
}
