<?php
// Model/User.php

class Room {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function getAdmin(){
        $query = "SELECT * FROM nguoidung WHERE vai_tro = 'Quan Tri Vien'"; 
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function createRoom($ma_phong, $tang, $dien_tich, $suc_chua_toi_da, $mo_ta, $trang_thai, $nhan_vien_phu_trach, $fileName) {
        $query = "INSERT INTO phong (ma_phong, tang, dien_tich, suc_chua_toi_da, mo_ta, trang_thai_phong, nhan_vien_phu_trach, anh_phong) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sidissis", $ma_phong, $tang, $dien_tich, $suc_chua_toi_da, $mo_ta, $trang_thai, $nhan_vien_phu_trach, $fileName);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
    public function getAllRooms(){
        $query= "SELECT p.id as phong_id, p.ma_phong, p.anh_phong, p.tang, p.dien_tich, p.suc_chua_toi_da, p.mo_ta, p.trang_thai_phong, p.nhan_vien_phu_trach, n.ho_ten, p.tao_moi, p.cap_nhat FROM phong p LEFT JOIN nguoidung n ON p.nhan_vien_phu_trach = n.id;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function searchRoom(string $room) {
    $query = "
    SELECT 
        phong.id as phong_id,
        phong.ma_phong, 
        phong.tang, 
        phong.dien_tich, 
        phong.suc_chua_toi_da, 
        phong.mo_ta, 
        phong.trang_thai_phong,
        phong.nhan_vien_phu_trach,
        nguoidung.ho_ten as ten_nhan_vien,
        phong.anh_phong
    FROM phong
    LEFT JOIN nguoidung ON phong.nhan_vien_phu_trach = nguoidung.id
    WHERE 
        (
            phong.ma_phong LIKE ? 
            OR phong.tang LIKE ? 
            OR phong.dien_tich LIKE ? 
            OR phong.mo_ta LIKE ? 
            OR phong.trang_thai_phong LIKE ? 
            OR nguoidung.ho_ten LIKE ?
        )
    ";

    if ($stmt = $this->conn->prepare($query)) {
        $searchTerm = "%" . $room . "%"; 
        $stmt->bind_param("ssssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return !empty($data) ? $data : false;
    } else {
        return false;
    }
}
public function getRoom(int $room_id) {
    $query = "SELECT phong.id AS phong_id, phong.*, nguoidung.ho_ten AS ten_nhan_vien
              FROM phong
              LEFT JOIN nguoidung ON phong.nhan_vien_phu_trach = nguoidung.id
              WHERE phong.id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param('i', $room_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}



public function updateRoomData($room_id, $roomCode, $floor, $area, $capacity, $description, $status, $staffId, $avatar) {
    // Kiểm tra và chuyển đổi trạng thái từ tiếng Anh sang tiếng Việt
    if ($status === 'available') {
        $status = 'Còn chỗ';
    } elseif ($status === 'unavailable') {
        $status = 'Hết chỗ';
    } else {
        $status = 'Còn chỗ'; // Giá trị mặc định
    }

    if ($avatar) {
        $query = "
            UPDATE phong
            SET 
                ma_phong = ?, 
                tang = ?, 
                dien_tich = ?, 
                suc_chua_toi_da = ?, 
                mo_ta = ?, 
                trang_thai_phong = ?, 
                nhan_vien_phu_trach = ?, 
                anh_phong = ?
            WHERE 
                id = ?
        ";
    } else {
        $query = "
            UPDATE phong
            SET 
                ma_phong = ?, 
                tang = ?, 
                dien_tich = ?, 
                suc_chua_toi_da = ?, 
                mo_ta = ?, 
                trang_thai_phong = ?, 
                nhan_vien_phu_trach = ?
            WHERE 
                id = ?
        ";
    }

    if ($stmt = $this->conn->prepare($query)) {
        if ($avatar) {
            $stmt->bind_param("ssisssssi", $roomCode, $floor, $area, $capacity, $description, $status, $staffId, $avatar, $room_id);
        } else {
            $stmt->bind_param("ssissssi", $roomCode, $floor, $area, $capacity, $description, $status, $staffId, $room_id);
        }

        // Thực thi câu lệnh SQL và kiểm tra kết quả
        if ($stmt->execute()) {
            return true;  // Thành công
        } else {
            return false; // Lỗi khi thực thi
        }
    } else {
        return false;  // Lỗi khi chuẩn bị câu lệnh
    }
}
public function deleteRoom(int $room_id) {
    // Bắt đầu giao dịch (transaction)
    $this->conn->begin_transaction();

    try {
        // Xóa thanh toán liên quan đến phòng
        $delete_payments = $this->conn->prepare("
            DELETE FROM thanhtoan 
            WHERE id_hoa_don IN (SELECT id FROM hoadon WHERE id_phong = ?)
        ");
        $delete_payments->bind_param("i", $room_id);
        if (!$delete_payments->execute()) {
            throw new Exception("Lỗi khi xóa thanh toán.");
        }

        // Xóa chi tiết hóa đơn liên quan đến phòng
        $delete_invoice_details = $this->conn->prepare("
            DELETE FROM chitiethoadon 
            WHERE id_hoa_don IN (SELECT id FROM hoadon WHERE id_phong = ?)
        ");
        $delete_invoice_details->bind_param("i", $room_id);
        if (!$delete_invoice_details->execute()) {
            throw new Exception("Lỗi khi xóa chi tiết hóa đơn.");
        }

        // Xóa hóa đơn liên quan đến phòng
        $delete_invoices = $this->conn->prepare("
            DELETE FROM hoadon 
            WHERE id_phong = ?
        ");
        $delete_invoices->bind_param("i", $room_id);
        if (!$delete_invoices->execute()) {
            throw new Exception("Lỗi khi xóa hóa đơn.");
        }

        // Xóa hợp đồng liên quan đến phòng
        $delete_contracts = $this->conn->prepare("
            DELETE FROM hopdong WHERE id_phong = ?
        ");
        $delete_contracts->bind_param("i", $room_id);
        if (!$delete_contracts->execute()) {
            throw new Exception("Lỗi khi xóa hợp đồng.");
        }

        // Xóa phòng
        $delete_room = $this->conn->prepare("
            DELETE FROM phong WHERE id = ?
        ");
        $delete_room->bind_param("i", $room_id);
        if (!$delete_room->execute()) {
            throw new Exception("Lỗi khi xóa phòng.");
        }

        // Kiểm tra nếu không có lỗi, commit giao dịch
        if ($delete_room->affected_rows > 0) {
            $this->conn->commit();
            return "Xóa phòng và các thông tin liên quan thành công!";
        } else {
            // Nếu không có bản ghi nào bị xóa, rollback giao dịch
            $this->conn->rollback();
            return "Không tìm thấy phòng với ID đã cho.";
        }
    } catch (Exception $e) {
        // Nếu có lỗi, rollback giao dịch và log error
        $this->conn->rollback();
        // Log the exception message
        error_log($e->getMessage());
        return "Lỗi khi xóa phòng: " . $e->getMessage();
    }
}




    
    
}
?>