<?php

class RoomBill
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllRoomBill()
    {
        $query = "SELECT 
            hoadon.id as hoa_don_id,
            chitiethoadon.id as chi_tiet_hoa_don_id,
            hoadon.*, 
            chitiethoadon.*,
            phong.ma_phong AS ten_phong,
            hopdong.*
        FROM 
            chitiethoadon
        JOIN 
            hoadon ON chitiethoadon.id_hoa_don = hoadon.id
        JOIN 
            phong ON hoadon.id_phong = phong.id
        JOIN 
            hopdong ON hoadon.id_hop_dong = hopdong.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function getRoom()
    {
        // Lấy tháng và năm hiện tại
        $currentYear = date('Y');  // Lấy năm hiện tại
        $currentMonth = date('m'); // Lấy tháng hiện tại

        // Truy vấn kiểm tra các phòng đã có hóa đơn trong tháng và năm hiện tại
        $queryCheck = "SELECT id_phong 
                   FROM hoadon 
                   WHERE MONTH(ngay_thanh_toan) = ? 
                   AND YEAR(ngay_thanh_toan) = ?";

        // Chuẩn bị và thực thi câu truy vấn
        $stmtCheck = $this->conn->prepare($queryCheck);
        $stmtCheck->bind_param("ii", $currentMonth, $currentYear);  // Truyền tháng và năm hiện tại vào
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        // Lấy tất cả các id_phong đã có hóa đơn trong tháng hiện tại
        $usedRooms = [];
        while ($row = $resultCheck->fetch_assoc()) {
            $usedRooms[] = $row['id_phong'];
        }

        // Truy vấn lấy tất cả các phòng chưa có hóa đơn trong tháng hiện tại và có trạng thái không phải là "Hết Chỗ"
        if (count($usedRooms) > 0) {
            // Tạo câu lệnh SQL với các id phòng đã sử dụng và điều kiện trạng thái
            $query = "SELECT * FROM phong 
                  WHERE id NOT IN (" . implode(',', array_fill(0, count($usedRooms), '?')) . ") 
                  AND trang_thai_phong != 'Hết Chỗ'";  // Điều kiện không lấy phòng có trạng thái 'Hết Chỗ'

            // Chuẩn bị câu lệnh truy vấn
            $stmt = $this->conn->prepare($query);
            // Liên kết các tham số
            $stmt->bind_param(str_repeat('i', count($usedRooms)), ...$usedRooms);
        } else {
            // Nếu không có phòng nào đã có hóa đơn, truy vấn tất cả các phòng không có trạng thái 'Hết Chỗ'
            $query = "SELECT * FROM phong WHERE trang_thai_phong != 'Hết Chỗ'";  // Điều kiện không lấy phòng có trạng thái 'Hết Chỗ'
            $stmt = $this->conn->prepare($query);
        }

        // Thực thi câu lệnh truy vấn và trả về kết quả
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function createRoomBill($maPhong, $soNuocCu, $soNuocMoi, $soDienCu, $soDienMoi, $ngayThanhToan, $ghiChu)
    {
        // Bắt đầu giao dịch
        $this->conn->begin_transaction();

        try {
            // Lấy thông tin hợp đồng từ bảng 'hopdong'
            $foundHopDong = "SELECT id, gia, gia_nuoc, gia_dien, gia_don_dep FROM hopdong WHERE id_phong = ?";
            $stmt = $this->conn->prepare($foundHopDong);
            $stmt->bind_param("i", $maPhong);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            // Kiểm tra xem có hợp đồng không
            if (!$row) {
                throw new Exception("Không tìm thấy hợp đồng cho phòng này.");
            }

            $idHopDong = $row['id'];
            $gia = $row['gia'];
            $giaNuoc = $row['gia_nuoc'];
            $giaDien = $row['gia_dien'];
            $giaDonDep = $row['gia_don_dep'];

            // Tính phí nước và điện
            $phiNuoc = ($soNuocMoi - $soNuocCu) * $giaNuoc;
            $phiDien = ($soDienMoi - $soDienCu) * $giaDien;

            // Tính tổng tiền
            $tienNha = $gia;
            $tienNuoc = $phiNuoc;
            $tienDien = $phiDien;
            $tienDonDep = $giaDonDep;

            $tongTien = $tienNha + $tienNuoc + $tienDien + $tienDonDep;

            // Thêm hóa đơn vào bảng 'hoadon'
            $newHoaDon = 'INSERT INTO hoadon (id_hop_dong, id_phong, ngay_thanh_toan, tong_so_tien) VALUES (?, ?, ?, ?)';
            $stmt = $this->conn->prepare($newHoaDon);
            $stmt->bind_param("iisd", $idHopDong, $maPhong, $ngayThanhToan, $tongTien);
            $stmt->execute();

            // Kiểm tra xem có lỗi khi thêm hóa đơn không
            if ($stmt->affected_rows == 0) {
                throw new Exception("Không thể tạo hóa đơn.");
            }

            $idHoaDon = $this->conn->insert_id;
            $newChiTietHoaDon = 'INSERT INTO chitiethoadon (id_hoa_don, ghi_chu, so_nuoc_cu, so_nuoc_moi, so_dien_cu, so_dien_moi, phi_nuoc, phi_dien) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
            $stmt = $this->conn->prepare($newChiTietHoaDon);
            $stmt->bind_param("isiiiddd", $idHoaDon, $ghiChu, $soNuocCu, $soNuocMoi, $soDienCu, $soDienMoi, $phiNuoc, $phiDien);
            $stmt->execute();

            if ($stmt->affected_rows == 0) {
                throw new Exception("Không thể thêm chi tiết hóa đơn.");
            }

            // Commit giao dịch sau khi thành công
            $this->conn->commit();
            return true; // Thành công
        } catch (Exception $e) {
            // Nếu có lỗi xảy ra, rollback giao dịch
            $this->conn->rollback();
            // Ghi log lỗi
            error_log("Error creating room bill: " . $e->getMessage());
            return false; // Thất bại
        }
    }
    public function updateRoomBill($room_bill_id, $maPhong, $soNuocCu, $soNuocMoi, $soDienCu, $soDienMoi, $ngayThanhToan, $ghiChu)
{
    $checkUpdate = false; // Khởi tạo giá trị mặc định cho checkUpdate
    try {
        // Lấy thông tin hóa đơn
        $hoadonQuery = "SELECT * FROM hoadon WHERE id = ?";
        $stmt = $this->conn->prepare($hoadonQuery);
        $stmt->bind_param("i", $room_bill_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $hoadon = $result->fetch_assoc();
        if (!$hoadon) {
            throw new Exception("Không tìm thấy hóa đơn với ID: $room_bill_id.");
        }

        // Lấy thông tin hợp đồng
        $hopdongQuery = "SELECT * FROM hopdong WHERE id = ?";
        $stmt = $this->conn->prepare($hopdongQuery);
        $stmt->bind_param("i", $hoadon['id_hop_dong']);
        $stmt->execute();
        $result = $stmt->get_result();
        $foundHopDong = $result->fetch_assoc();
        if (!$foundHopDong) {
            throw new Exception("Không tìm thấy hợp đồng với ID: " . $hoadon['id_hop_dong']);
        }

        // Tính toán các khoản phí
        $tien_nha = $foundHopDong['gia'];
        $giaNuoc = $foundHopDong['gia_nuoc'];
        $giaDien = $foundHopDong['gia_dien'];
        $giaDonDep = $foundHopDong['gia_don_dep'];
        $phiNuoc = ($soNuocMoi - $soNuocCu) * $giaNuoc;
        $phiDien = ($soDienMoi - $soDienCu) * $giaDien;
        $tongTien = $tien_nha + $phiNuoc + $phiDien + $giaDonDep;

        // Cập nhật thông tin hóa đơn
        $updateHoaDon = 'UPDATE hoadon SET id_phong = ?, ngay_thanh_toan = ?, tong_so_tien = ? WHERE id = ?';
        $stmt = $this->conn->prepare($updateHoaDon);
        $stmt->bind_param("isdi", $maPhong, $ngayThanhToan, $tongTien, $room_bill_id);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            throw new Exception("Không thể cập nhật hóa đơn.");
        }

        // Cập nhật chi tiết hóa đơn
        $updateChiTietHoaDon = 'UPDATE chitiethoadon SET ghi_chu = ?, so_nuoc_cu = ?, so_nuoc_moi = ?, so_dien_cu = ?, so_dien_moi = ?, phi_nuoc = ?, phi_dien = ? WHERE id_hoa_don = ?';
        $stmt = $this->conn->prepare($updateChiTietHoaDon);
        $stmt->bind_param("siiidddi", $ghiChu, $soNuocCu, $soNuocMoi, $soDienCu, $soDienMoi, $phiNuoc, $phiDien, $room_bill_id);
        $checkUpdate = $stmt->execute();

        // Kiểm tra kết quả cập nhật chi tiết hóa đơn
        if ($stmt->affected_rows == 0) {
            throw new Exception("Không thể cập nhật chi tiết hóa đơn.");
        }

        return $checkUpdate; // Trả về kết quả
    } catch (Exception $e) {
        // Log lỗi và trả về false nếu có lỗi xảy ra
        error_log("Error updating room bill: " . $e->getMessage());
        return false; // Trả về false nếu có lỗi
    }
    }

    public function getRoomBillById(int $bill_id)
{
    $query = "
        SELECT 
            hoadon.id_phong as id_phong,        
            hoadon.id AS hoa_don_id,
            phong.ma_phong AS ten_phong,
            hoadon.ngay_thanh_toan,
            hopdong.gia,
            hopdong.gia_don_dep,
            chitiethoadon.phi_nuoc,
            chitiethoadon.phi_dien,
            hoadon.tong_so_tien,
            chitiethoadon.ghi_chu,
            chitiethoadon.so_dien_cu,
            chitiethoadon.so_dien_moi,
            chitiethoadon.so_nuoc_cu,
            chitiethoadon.so_nuoc_moi
        FROM 
            chitiethoadon
        JOIN 
            hoadon ON chitiethoadon.id_hoa_don = hoadon.id
        JOIN 
            phong ON hoadon.id_phong = phong.id
        JOIN 
            hopdong ON hopdong.id = hoadon.id_hop_dong
        WHERE 
            hoadon.id = ?
    ";

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param('i', $bill_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
    }

    public function searchRoomBill($searchTerm)
    {
        $query = "
    SELECT 
        hoadon.id as hoa_don_id,
        phong.ma_phong as ten_phong,
        hoadon.ngay_thanh_toan,
        hopdong.gia,
        hopdong.gia_don_dep,
        chitiethoadon.phi_nuoc,
        chitiethoadon.phi_dien,
        hoadon.tong_so_tien
    FROM 
        chitiethoadon
    JOIN 
        hoadon ON chitiethoadon.id_hoa_don = hoadon.id
    JOIN 
        phong ON hoadon.id_phong = phong.id
    JOIN 
        hopdong ON hopdong.id = hoadon.id_hop_dong
    WHERE 
        (
            phong.ma_phong LIKE ? 
            OR hoadon.ngay_thanh_toan LIKE ? 
            OR hopdong.gia = ? 
            OR hopdong.gia_don_dep = ? 
            OR chitiethoadon.phi_nuoc = ? 
            OR chitiethoadon.phi_dien = ? 
            OR hoadon.tong_so_tien = ?
        )
    ";

        if ($stmt = $this->conn->prepare($query)) {
            if (is_numeric($searchTerm)) {
                $searchTermNumeric = (float) $searchTerm;
            } else {
                $searchTermNumeric = 0.0;
            }

            $searchTermLike = "%" . $searchTerm . "%";

            $stmt->bind_param(
                "ssddddd", // s: chuỗi, d: số thực
                $searchTermLike, // phong.ma_phong
                $searchTermLike, // hoadon.ngay_thanh_toan
                $searchTermNumeric, // hopdong.gia
                $searchTermNumeric, // hopdong.gia_don_dep
                $searchTermNumeric, // chitiethoadon.phi_nuoc
                $searchTermNumeric, // chitiethoadon.phi_dien
                $searchTermNumeric  // hoadon.tong_so_tien
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

}
