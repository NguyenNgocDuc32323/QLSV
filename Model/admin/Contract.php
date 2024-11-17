<?php

class Contract {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function getUser() {
        $query = "
            SELECT hocsinh.* 
            FROM hocsinh 
            LEFT JOIN hopdong ON hocsinh.id = hopdong.id_hoc_sinh
            WHERE hopdong.id IS NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    
    public function getRoom() {
        $query = "SELECT * FROM phong WHERE trang_thai_phong = 'Còn Chỗ'"; 
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getAllContract(){
    $query = "SELECT 
        hd.id AS hop_dong_id,
        hs.id AS id_hoc_sinh,
        nd.id AS id_nguoi_dung,
        hs.ma_sinh_vien AS ma_sinh_vien,
        p.id AS id_phong,
        p.ma_phong,
        hd.gia,
        hd.gia_nuoc,
        hd.gia_dien,
        hd.gia_don_dep,
        hd.tien_dat_coc,
        hd.ngay_dat_coc,
        hd.ngay_bat_dau,
        hd.ngay_ket_thuc,
        hd.ngay_ky_hop_dong,
        hd.tao_moi,
        hd.cap_nhat
    FROM 
        hopdong hd
    LEFT JOIN 
        hocsinh hs ON hd.id_hoc_sinh = hs.id
    LEFT JOIN 
        nguoidung nd ON hs.id_nguoi_dung = nd.id
    LEFT JOIN 
        phong p ON hd.id_phong = p.id;";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getContractById(int $contract_id) {
        $query = "SELECT hopdong.id AS contract_id, hopdong.*, hocsinh.id AS student_id, hocsinh.ma_sinh_vien AS student_code, phong.ma_phong AS room_code
                FROM hopdong
                LEFT JOIN hocsinh ON hopdong.id_hoc_sinh = hocsinh.id
                LEFT JOIN nguoidung ON hocsinh.id_nguoi_dung = nguoidung.id
                LEFT JOIN phong ON hopdong.id_phong = phong.id
                WHERE hopdong.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $contract_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }


    public function updateContractData($contract_id, $studentId, $roomId, $price, $waterPrice, $electricityPrice, $cleaningPrice, $deposit, $depositDate, $startDate, $endDate, $contractDate) {
        $query = "
            UPDATE hopdong
            SET 
                id_hoc_sinh = ?, 
                id_phong = ?, 
                gia = ?, 
                gia_nuoc = ?, 
                gia_dien = ?, 
                gia_don_dep = ?, 
                tien_dat_coc = ?, 
                ngay_dat_coc = ?, 
                ngay_bat_dau = ?, 
                ngay_ket_thuc = ?, 
                ngay_ky_hop_dong = ?
            WHERE 
                id = ?
        ";
    
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("iiddddsssssi", $studentId, $roomId, $price, $waterPrice, $electricityPrice, $cleaningPrice, $deposit, $depositDate, $startDate, $endDate, $contractDate, $contract_id);
    
            if ($stmt->execute()) {
                // Truy vấn để lấy thông tin hóa đơn và chi tiết hóa đơn
                $hoaDonQuery = "SELECT hoadon.id as hoa_don_id, hoadon.*, chitiethoadon.* 
                                FROM hoadon 
                                JOIN chitiethoadon ON hoadon.id = chitiethoadon.id_hoa_don 
                                WHERE hoadon.id_hop_dong = ?";
                $stmtHoaDon = $this->conn->prepare($hoaDonQuery);
                $stmtHoaDon->bind_param("i", $contract_id);
                $stmtHoaDon->execute();
                $resultHoaDon = $stmtHoaDon->get_result();
    
                if ($hoaDon = $resultHoaDon->fetch_assoc()) {
                    $hoaDonId = $hoaDon['hoa_don_id'];
                    $soNuocCu = $hoaDon['so_nuoc_cu'];
                    $soNuocMoi = $hoaDon['so_nuoc_moi'];
                    $soDienCu = $hoaDon['so_dien_cu'];
                    $soDienMoi = $hoaDon['so_dien_moi'];
    
                    // Tính toán các khoản phí
                    $phiNuoc = $waterPrice * ($soNuocMoi - $soNuocCu);
                    $phiDien = $electricityPrice * ($soDienMoi - $soDienCu);
    
                    // Cập nhật tổng số tiền trong bảng hoadon
                    $tongTienQuery = "UPDATE hoadon SET tong_so_tien = ? WHERE id = ?";
                    $stmtTongTien = $this->conn->prepare($tongTienQuery);
                    $tongTien = $price + $phiNuoc + $phiDien + $cleaningPrice; // Tính tổng tiền
                    $stmtTongTien->bind_param("di", $tongTien, $hoaDonId);
                    $stmtTongTien->execute();
    
                    return true; // Thành công
                } else {
                    return false; // Không tìm thấy hóa đơn cho hợp đồng này
                }
            } else {
                return false; // Lỗi khi thực hiện cập nhật bảng hopdong
            }
        } else {
            return false; // Lỗi khi chuẩn bị câu lệnh SQL
        }
    }
    
    

    public function searchContract(string $searchTerm) {
    $query = "
    SELECT 
        hopdong.id AS hop_dong_id,
        hopdong.ngay_ky_hop_dong AS ngay_ky_hop_dong,
        hopdong.ngay_bat_dau AS ngay_bat_dau,
        hopdong.ngay_ket_thuc AS ngay_ket_thuc,
        hopdong.ngay_dat_coc AS ngay_dat_coc,
        hopdong.tien_dat_coc AS tien_dat_coc,
        hopdong.gia AS gia, 
        hopdong.gia_nuoc AS gia_nuoc,
        hopdong.gia_dien AS gia_dien,            
        hopdong.gia_don_dep AS gia_don_dep,
        nguoidung.ho_ten AS ten_hoc_sinh,
        phong.ma_phong AS ma_phong
    FROM hopdong
    LEFT JOIN hocsinh ON hopdong.id_hoc_sinh = hocsinh.id
    LEFT JOIN nguoidung ON hocsinh.id_nguoi_dung = nguoidung.id
    LEFT JOIN phong ON hopdong.id_phong = phong.id
    WHERE 
        hopdong.id = ? 
        OR hopdong.ngay_ky_hop_dong LIKE ? 
        OR hopdong.ngay_bat_dau LIKE ? 
        OR hopdong.ngay_ket_thuc LIKE ? 
        OR hopdong.ngay_dat_coc LIKE ? 
        OR hopdong.tien_dat_coc = ? 
        OR hopdong.gia = ? 
        OR hopdong.gia_nuoc = ? 
        OR hopdong.gia_dien = ? 
        OR hopdong.gia_don_dep = ? 
        OR nguoidung.ho_ten LIKE ? 
        OR phong.ma_phong LIKE ?
    ";

    if ($stmt = $this->conn->prepare($query)) {
        $searchTermLike = "%" . $searchTerm . "%";
        $searchTermNumeric = is_numeric($searchTerm) ? $searchTerm : 0; 
        $stmt->bind_param(
            "issssdddddss",
            $searchTermNumeric,
            $searchTermLike,
            $searchTermLike,
            $searchTermLike,
            $searchTermLike,
            $searchTermNumeric,
            $searchTermNumeric,
            $searchTermNumeric,
            $searchTermNumeric,
            $searchTermNumeric,
            $searchTermLike,
            $searchTermLike
        );
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
    public function getStudentById($student_id) {
        $query = "
            SELECT 
                hocsinh.*, 
                nguoidung.ho_ten AS ten_nguoi_dung, 
                nguoidung.email AS email_nguoi_dung, 
                nguoidung.so_dien_thoai AS so_dien_thoai_nguoi_dung 
            FROM hocsinh
            JOIN nguoidung ON hocsinh.id_nguoi_dung = nguoidung.id
            WHERE hocsinh.id = ?
        ";
        
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param('i', $student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }
    
    public function getRoomById($room_id){
        $query = "SELECT * FROM phong WHERE id =?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $room_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function createContract($studentId, $roomId, $price, $waterPrice, $electricityPrice, $cleaningPrice, $deposit, $depositDate, $startDate, $endDate, $contractDate) {
        // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
        $this->conn->begin_transaction();
    
        try {
            // Thực hiện câu lệnh INSERT vào bảng hopdong
            $stmt = $this->conn->prepare(
                "INSERT INTO hopdong (id_hoc_sinh, id_phong, gia, gia_nuoc, gia_dien, gia_don_dep, tien_dat_coc, ngay_dat_coc, ngay_bat_dau, ngay_ket_thuc, ngay_ky_hop_dong) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("iidddddssss", $studentId, $roomId, $price, $waterPrice, $electricityPrice, $cleaningPrice, $deposit, $depositDate, $startDate, $endDate, $contractDate);
            $stmt->execute();
    
            // Kiểm tra nếu INSERT thành công
            if ($stmt->affected_rows > 0) {
                // Kiểm tra số lượng hợp đồng trong phòng
                $countQuery = "SELECT COUNT(*) AS totalContracts FROM hopdong WHERE id_phong = ?";
                $countStmt = $this->conn->prepare($countQuery);
                $countStmt->bind_param("i", $roomId);
                $countStmt->execute();
                $result = $countStmt->get_result();
                $countRow = $result->fetch_assoc();
                $totalContracts = $countRow['totalContracts'];
    
                // Kiểm tra công suất phòng
                $roomQuery = "SELECT suc_chua_toi_da FROM phong WHERE id = ?";
                $roomStmt = $this->conn->prepare($roomQuery);
                $roomStmt->bind_param("i", $roomId);
                $roomStmt->execute();
                $roomResult = $roomStmt->get_result();
                $roomRow = $roomResult->fetch_assoc();
                $capacity = $roomRow['suc_chua_toi_da'];
    
                // Cập nhật trạng thái phòng nếu đã đầy
                if ($totalContracts >= $capacity) {
                    $updateRoomQuery = "UPDATE phong SET trang_thai_phong = 'Hết Chỗ' WHERE id = ?";
                    $updateRoomStmt = $this->conn->prepare($updateRoomQuery);
                    $updateRoomStmt->bind_param("i", $roomId);
                    $updateRoomStmt->execute();
                }
    
                // Commit transaction nếu không có lỗi
                $this->conn->commit();
                return true;
            } else {
                // Rollback transaction nếu không có thay đổi nào
                $this->conn->rollback();
                return false;
            }
        } catch (Exception $e) {
            // Rollback nếu có lỗi trong bất kỳ truy vấn nào
            $this->conn->rollback();
            return false;
        }
    }
}
?>