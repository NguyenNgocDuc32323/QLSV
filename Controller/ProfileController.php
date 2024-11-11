<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';
require_once '../Model/Profile.php';   

class ProfileController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function showProfile() {
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


    public function updateProfile($data, $files) {
        if (!isset($_SESSION['login'])) {
            header('Location: login.php');
            exit();
        }

        // Get the user ID from the session
        $id_nguoidung = $_SESSION['login'];

        // Collect data from form
        $ho_ten = $data['ho_ten'];
        $ngay_sinh = $data['ngay_sinh'];
        $so_cmnd = $data['so_cmnd'];
        $gioi_tinh = $data['gioi_tinh'];
        $email = $data['email'];
        $so_dien_thoai = $data['so_dien_thoai'];
        $que_quan = $data['que_quan'];

        // Call the helper function to handle file upload and get the avatar path
        $avatarPath = $this->handleAvatarUpload($files);

        // Update the profile data in the database
        $profileModel = new Profile($this->conn);
        return $profileModel->updateProfileData(
            $id_nguoidung, 
            $ho_ten, 
            $email, 
            $so_dien_thoai, 
            $ngay_sinh, 
            $gioi_tinh, 
            $so_cmnd, 
            $que_quan, 
            $avatarPath // Pass the avatar path (name of the file)
        );
    }

    // Helper method to handle avatar file upload
    private function handleAvatarUpload($files) {
    // Default avatar path (if no file is uploaded)
    $avatarPath = 'default-avatar1.png';

    // Debugging: Check if a file is uploaded
    if (isset($files['avatar']) && $files['avatar']['error'] == 0) {
        // Debugging: Show file info
        echo "File uploaded: " . $files["avatar"]["name"] . "<br>";
        echo "Temp file path: " . $files["avatar"]["tmp_name"] . "<br>";
        echo "File size: " . $files["avatar"]["size"] . "<br>";

        // Set target directory and file path
        $targetDir = "./assets/images/avatar/";
        $targetFile = $targetDir . basename($files["avatar"]["name"]);

        // Validate if the uploaded file is an image
        $check = getimagesize($files["avatar"]["tmp_name"]);
        if ($check === false) {
            echo "File không phải là hình ảnh.<br>";
            exit();
        }

        // Validate file size (max 2MB)
        if ($files["avatar"]["size"] > 2000000) {
            echo "File quá lớn. Vui lòng tải lên một file nhỏ hơn 2MB.<br>";
            exit();
        }

        // Ensure the target directory exists and is writable
        if (!is_writable($targetDir)) {
            echo "Thư mục đích không thể ghi vào.<br>";
            exit();
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($files["avatar"]["tmp_name"], $targetFile)) {
            // Set the avatar path to the file name
            $avatarPath = basename($files["avatar"]["name"]);
            echo "Avatar uploaded successfully: " . $avatarPath . "<br>";
        } else {
            echo "Có lỗi khi tải ảnh lên.<br>";
            exit();
        }
    } else {
        // Debugging: Show if no file is uploaded
        echo "No file uploaded, using default avatar.<br>";
    }

    return $avatarPath;
}




public function updatePassword($userId, $currentPassword, $newPassword) {
    $profileModel = new Profile($this->conn);

    if ($profileModel->updatePassword($userId, $currentPassword, $newPassword)) {
        return ['status' => 'success', 'message' => 'Password updated successfully'];
    } else {
        return ['status' => 'error', 'message' => 'Failed to update password. Current password may be incorrect or an error occurred.'];
    }
}

}

?>