<?php
require_once '../Model/Room.php';
require_once '../config/database.php';
class RoomController{
   private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function getAdmin(){
        $roomModel = new Room($this->conn);
        $admins = $roomModel->getAdmin();
        return $admins;
    }
    public function createRoom($ma_phong, $tang, $dien_tich, $suc_chua_toi_da, $mo_ta, $trang_thai, $nhan_vien_phu_trach, $fileName){
        $roomPath = null;
        $roomName = null;
        if (isset($_FILES['anh_phong']) && $_FILES['anh_phong']['error'] === UPLOAD_ERR_OK) {
            $roomName = basename($_FILES['anh_phong']['name']);
            $roomPath = "../../View/assets/images/room/" . $roomName;
            $fileExtension = pathinfo($roomName, PATHINFO_EXTENSION);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                echo "Định dạng file không hợp lệ. Chỉ chấp nhận JPG, JPEG, PNG, GIF.";
                return;
            }
            if (!move_uploaded_file($_FILES['anh_phong']['tmp_name'], $roomPath)) {
                echo "Lỗi khi tải lên ảnh phòng.";
                return;
            }
        }
        $room = new Room($this->conn);
        return $room->createRoom($ma_phong, $tang, $dien_tich, $suc_chua_toi_da, $mo_ta, $trang_thai, $nhan_vien_phu_trach, $fileName);
    }
    public function getAllRoom() {
        $roomModel = new Room($this->conn);
        $room_data = $roomModel->getAllRooms();

        if (!$room_data) {
            $_SESSION['room_failure'] = "Không tìm thấy dữ liệu.";
        }

        return $room_data;
    }
    public function searchRoom(string $room){
        $contractModel = new Room($this->conn);
        $searchDatas = $contractModel->searchRoom($room);
        return $searchDatas;
    }
    
public function getRoomById($room_id) {
    $adminModel = new Room($this->conn);
    $room_data = $adminModel->getRoom($room_id);
    return $room_data;
}

    public function updateRoom($room_id, $roomCode, $floor, $area, $capacity, $description, $status, $staffId , $avatar) {
    if (!isset($_SESSION['login'])) {
        header('Location: ../login.php');
        exit();
    }

    $avatarPath = null;
    $avatarName = null;
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $avatarName = basename($_FILES['avatar']['name']); // Lấy tên file gốc
        $avatarPath = "../../View/assets/images/room/" . $avatarName; // Đường dẫn lưu file

        // Kiểm tra xem file có phải là hình ảnh hợp lệ không
        $fileExtension = pathinfo($avatarName, PATHINFO_EXTENSION);
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
            echo "Định dạng file không hợp lệ. Chỉ chấp nhận JPG, JPEG, PNG, GIF.";
            return;
        }
        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $avatarPath)) {
            echo "Lỗi khi tải lên ảnh phòng.";
            return;
        }
    }
    

    

    $dashboardModel = new Room($this->conn);
    return $dashboardModel->updateRoomData(
        $room_id, $roomCode, $floor, $area, $capacity, $description, $status, $staffId, $avatar
    );
}

public function deleteRoom(int $room) {
        $contractModel = new Room($this->conn);
        $deleteSuccess = $contractModel->deleteRoom($room);
    
        if ($deleteSuccess) {
            $_SESSION['success_message'] = 'Xóa thành công';
        } else {
            $_SESSION['error_message'] = 'Không thể xóa. Vui lòng thử lại.';
        }
    }

}
?>