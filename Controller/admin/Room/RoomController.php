<?php
require_once '../../Model/admin/Room.php';
require_once '../../config/database.php';
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
}
?>