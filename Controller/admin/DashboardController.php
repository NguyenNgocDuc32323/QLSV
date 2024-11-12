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

        if (!$room_data) {
            $_SESSION['login_failure'] = "Không tìm thấy dữ liệu người dùng.";
            header('Location: login.php');
            exit();
        }

        return $room_data;
    }
    public function showContract() {
        $contractModel = new Dashboard($this->conn);
        $contract_data = $contractModel->getContractData(); // Call the method without passing an ID

        if (!$contract_data) {
            $_SESSION['login_failure'] = "Không tìm thấy dữ liệu người dùng.";
            header('Location: login.php');
            exit();
        }

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
}
?>