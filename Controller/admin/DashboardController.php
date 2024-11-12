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
        $profile_data = $profileModel->getProfileData(); // Call the method without passing an ID

        if (!$profile_data) {
            $_SESSION['login_failure'] = "Không tìm thấy dữ liệu người dùng.";
            header('Location: login.php');
            exit();
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

}
?>