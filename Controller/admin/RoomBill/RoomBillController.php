<?php
require_once '../../Model/admin/RoomBill.php';
require_once '../../config/database.php';
class RoomBillController{
   private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
      public function getAllRoomBill() {
        $roomModel = new RoomBill($this->conn);
        $room_data = $roomModel->getAllRoomBill();

        if (!$room_data) {
            $_SESSION['room_failure'] = "Không tìm thấy dữ liệu.";
        }

        return $room_data;
    }
     public function getRoomBillById($bill_id) {
    $roomBillModel = new RoomBill($this->conn);
    $roomBill_data = $roomBillModel->getRoomBillById($bill_id);
    return $roomBill_data;
}

public function updateRoomBill($contract_id, $roomCode, $paymentDate, $month, $newRoomPrice, $newCleaningFee) {
    if (!isset($_SESSION['login'])) {
        header('Location: ../login.php');
        exit();
    }

    $roomBillModel = new RoomBill($this->conn);
    return $roomBillModel->updateRoomBill(
        $roomCode, $paymentDate, $month, $newRoomPrice, $newCleaningFee, $contract_id
    );
}
public function getRoom(){
     $roomModel = new RoomBill($this->conn);
        $room_data = $roomModel->getRoom();

        if (!$room_data) {
            $_SESSION['room_failure'] = "Không tìm thấy dữ liệu.";
        }

        return $room_data;
}
public function searchRoomBill(string $room) {
    // Instantiate the RoomBill model
    $roomBillModel = new RoomBill($this->conn);

    // Prepare the search data as an array with the expected format
    $searchParams = ['search_term' => $room];

    // Call the model's searchRoomBill method, passing the array instead of a string
    $searchDatas = $roomBillModel->searchRoomBill($searchParams);

    // Return the search results
    return $searchDatas;
}

    

}
?>