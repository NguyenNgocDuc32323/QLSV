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
    $id_nguoidung = $_SESSION['login'];
    $profileModel = new Profile($this->conn);
    $ho_ten = $data['ho_ten'];
    $ngay_sinh = $data['ngay_sinh'];
    $so_cmnd = $data['so_cmnd'];
    $gioi_tinh = $data['gioi_tinh'];
    $email = $data['email'];
    $so_dien_thoai = $data['so_dien_thoai'];
    $que_quan = $data['que_quan'];
    $avatarPath = '';
    if (isset($files['avatar']) && $files['avatar']['error'] == 0) {
        $avatarTmpName = $files['avatar']['tmp_name'];
        $avatarName = $files['avatar']['name'];
        $avatarPath = './assets/images/' . basename($avatarName); 
        if (move_uploaded_file($avatarTmpName, $avatarPath)) {
        } else {
            echo "Error uploading the avatar.";
            exit();
        }
    } else {

        $avatarPath = 'default-avatar.png';  
    }
    return $profileModel->updateProfileData(
        $id_nguoidung, 
        $ho_ten, 
        $email, 
        $so_dien_thoai, 
        $ngay_sinh, 
        $gioi_tinh, 
        $so_cmnd, 
        $que_quan, 
        $avatarPath
    );
}

}

?>