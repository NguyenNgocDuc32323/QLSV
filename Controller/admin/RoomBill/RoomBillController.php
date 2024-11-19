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

public function updateRoomBill($room_bill_id, $ten_phong, $so_nuoc_cu,$so_nuoc_moi,$so_dien_cu,$so_dien_moi,$ngayThanhToan,$ghiChu) {
    if (!isset($_SESSION['login'])) {
        header('Location: ../login.php');
        exit();
    }
    $roomBillModel = new RoomBill($this->conn);
    $checkUpdate = $roomBillModel->updateRoomBill($room_bill_id, $ten_phong, $so_nuoc_cu,$so_nuoc_moi,$so_dien_cu,$so_dien_moi,$ngayThanhToan,$ghiChu);

    return $checkUpdate;
}
public function getRoom(){
        $roomModel = new RoomBill($this->conn);
        $room_data = $roomModel->getRoom();

        if (!$room_data) {
            $_SESSION['room_failure'] = "Không tìm thấy dữ liệu.";
        }

        return $room_data;
}
public function searchRoomBill($search) {
    $roomBillModel = new RoomBill($this->conn);
    $searchDatas = $roomBillModel->searchRoomBill($search);
    return $searchDatas;
}
public function createRoomBill($maPhong, $soNuocCu, $soNuocMoi, $soDienCu, $soDienMoi, $ngayThanhToan,$ghiChu) {
    $room = new RoomBill($this->conn);
    $checkCreate =  $room->createRoomBill($maPhong, $soNuocCu, $soNuocMoi, $soDienCu, $soDienMoi, $ngayThanhToan,$ghiChu);
    return $checkCreate;
}



    

}
?>