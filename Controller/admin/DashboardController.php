<?php
require_once '../../Model/admin/Dashboard.php';
require_once '../../config/database.php';
class DashboardController {
   private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
     public function showProfile() {
        $profileModel = new Dashboard($this->conn);
        $profile_data = $profileModel->getProfileData();

        if (!$profile_data) {
            $_SESSION['login_failure'] = "Không tìm thấy dữ liệu người dùng.";
        }

        return $profile_data;
    }
    public function showRoom() {
        $roomModel = new Dashboard($this->conn);
        $room_data = $roomModel->getRoomData(); // Call the method without passing an ID


        return $room_data;
    }
    public function showContract() {
        $contractModel = new Dashboard($this->conn);
        $contract_data = $contractModel->getContractData(); // Call the method without passing an ID

        return $contract_data;
    }
    public function searchStudent(string $student){
        $contractModel = new Dashboard($this->conn);
        $searchDatas = $contractModel->searchStudent($student);
        return $searchDatas;
    }
    public function deleteStudent(int $student) {
        $contractModel = new Dashboard($this->conn);
        $deleteSuccess = $contractModel->deleteStudent($student);
    
        if ($deleteSuccess) {
            $_SESSION['success_message'] = 'Xóa thành công';
        } else {
            $_SESSION['error_message'] = 'Không thể xóa. Vui lòng thử lại.';
        }
    
        // header('Location: dashboard.php');
        // exit();
    }
    public function updateUserProfile($student_id, $fullName, $email, $phone, $birthYear, $gender, $idNumber, $hometown, $avatar) {
        if (!isset($_SESSION['login'])) {
            header('Location: ../login.php');
            exit();
        }
        $avatarPath = null;
        $avatarName = null;
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $avatarName = basename($_FILES['avatar']['name']); // Lấy tên file gốc
            $avatarPath = "../../View/assets/images/avatar/" . $avatarName; // Đường dẫn lưu file
    
            // Kiểm tra xem file có phải là hình ảnh hợp lệ không
            $fileExtension = pathinfo($avatarName, PATHINFO_EXTENSION);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                echo "Định dạng file không hợp lệ. Chỉ chấp nhận JPG, JPEG, PNG, GIF.";
                return;
            }
            if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $avatarPath)) {
                echo "Lỗi khi tải lên ảnh đại diện.";
                return;
            }
        }
        $dashboardModel = new Dashboard($this->conn);
        return $dashboardModel->updateStudentProfileData(
            $student_id, $fullName, $email, $phone, $birthYear, $gender, $idNumber, $hometown, $avatar
        );
    }
    public function updatePassword($userId, $currentPassword, $newPassword)
    {
        
        $profileModel = new Profile($this->conn);
        if (!$profileModel->checkCurrentPassword($userId, $currentPassword)) {
        // Nếu mật khẩu hiện tại không đúng, hiển thị thông báo lỗi
        echo "<script>alert('Mật khẩu hiện tại không chính xác.'); window.history.back();</script>";
        exit();
    }


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
    public function getAdmin($user_id){
        $adminModel = new Dashboard($this->conn);
        $admin_data = $adminModel->getAdmin($user_id);
        return $admin_data;
    }
    public function getStudentById($student_id){
        $adminModel = new Dashboard($this->conn);
        $student_data = $adminModel->getUser($student_id);
        return $student_data;
    }
}
?>