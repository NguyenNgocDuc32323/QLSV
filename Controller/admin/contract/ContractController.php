<?php
require_once '../../Model/admin/Contract.php';
require_once '../../config/database.php';
class ContractController{
   private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function getUser(){
        $contractModel = new Contract($this->conn);
        $users = $contractModel->getUser();
        return $users;
    }
    public function getRoom(){
        $contractModel = new Contract($this->conn);
        $rooms = $contractModel->getRoom();
        return $rooms;
    }
    public function getAllContract() {
        $contractModel = new Contract($this->conn);
        $contract_data = $contractModel->getAllContract();

        if (!$contract_data) {
            $_SESSION['contract_failure'] = "Không tìm thấy dữ liệu.";
        }

        return $contract_data;
    }
    public function getContractById($contract_id) {
    $contractModel = new Contract($this->conn);
    $contract_data = $contractModel->getContractById($contract_id);
    return $contract_data;
}

    public function updateContract($contract_id, $studentId, $roomId, $price, $waterPrice, $electricityPrice, $cleaningPrice, $deposit, $depositDate, $startDate, $endDate, $contractDate) {
    if (!isset($_SESSION['login'])) {
        header('Location: ../login.php');
        exit();
    }

    $contractModel = new Contract($this->conn);
    return $contractModel->updateContractData(
        $contract_id, $studentId, $roomId, $price, $waterPrice, $electricityPrice, $cleaningPrice, $deposit, $depositDate, $startDate, $endDate, $contractDate
    );
}
public function searchContract(string $searchTerm) {
    $contractModel = new Contract($this->conn);  // Use the Contract model instead of Room
    $searchDatas = $contractModel->searchContract($searchTerm);  // Call the searchContract method
    return $searchDatas;
}
 public function createContract() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_room'])) {
            // Lấy các giá trị từ form (hoặc API) gửi đến
            $studentId = isset($_POST['student_id']) ? $_POST['student_id'] : null;
            $roomId = isset($_POST['room_id']) ? $_POST['room_id'] : null;
            $price = isset($_POST['price']) ? $_POST['price'] : null;
            $waterPrice = isset($_POST['water_price']) ? $_POST['water_price'] : null;
            $electricityPrice = isset($_POST['electricity_price']) ? $_POST['electricity_price'] : null;
            $cleaningPrice = isset($_POST['cleaning_price']) ? $_POST['cleaning_price'] : null;
            $deposit = isset($_POST['deposit']) ? $_POST['deposit'] : null;
            $depositDate = isset($_POST['deposit_date']) ? $_POST['deposit_date'] : null;
            $startDate = isset($_POST['start_date']) ? $_POST['start_date'] : null;
            $endDate = isset($_POST['end_date']) ? $_POST['end_date'] : null;
            $contractDate = isset($_POST['contract_date']) ? $_POST['contract_date'] : null;

            // Kiểm tra xem tất cả các tham số có hợp lệ không
            if ($studentId && $roomId && $price && $deposit && $depositDate && $startDate && $endDate && $contractDate) {
                $contractModel = new Contract($this->conn);
                $isCreated = $contractModel->createContract($studentId, $roomId, $price, $waterPrice, $electricityPrice, $cleaningPrice, $deposit, $depositDate, $startDate, $endDate, $contractDate);
                if ($isCreated) {
                    $_SESSION['success'] = 'Hợp đồng đã được tạo thành công!';
                    header('Location: dashboard.php');
                    exit();
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi tạo hợp đồng. Vui lòng thử lại!';
                    header('Location: create_contract.php?tab=account');
                    exit();
                }
            } else {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin hợp đồng!';
                header('Location: create_contract.php');
                exit();
            }
        }
    }


}
?>