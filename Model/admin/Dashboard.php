<?php
// Model/User.php

class Dashboard {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getProfileData() {
    $query = "
        SELECT 
            hocsinh.id as hoc_sinh_id,nguoidung.ho_ten, nguoidung.email, nguoidung.so_dien_thoai, nguoidung.ngay_sinh, nguoidung.gioi_tinh, nguoidung.avatar,
            hocsinh.ma_sinh_vien, hocsinh.so_cmnd, hocsinh.que_quan
        FROM 
            nguoidung
        LEFT JOIN 
            hocsinh ON nguoidung.id = hocsinh.id_nguoi_dung
             WHERE 
        nguoidung.vai_tro = 'Hoc Sinh'
    ";

    if ($stmt = $this->conn->prepare($query)) {
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row; // Collect each row as an associative array
        }

        return !empty($data) ? $data : false; // Return the array if there are rows, else false
    } else {
        return false;
    }
    }
    public function getRoomData() {
        $query = "
            SELECT 
                ma_phong,
                tang,
                dien_tich,
                suc_chua_toi_da,
                mo_ta,
                trang_thai_phong,
                nhan_vien_phu_trach
            FROM 
                phong;

        ";

        if ($stmt = $this->conn->prepare($query)) {
            $stmt->execute();
            $result = $stmt->get_result();
            
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row; // Collect each row as an associative array
            }

            return !empty($data) ? $data : false; // Return the array if there are rows, else false
        } else {
            return false;
        }
    }
    public function getContractData() {
        $query = "
            SELECT 
                id_hoc_sinh,
                id_phong,
                gia,
                gia_nuoc,
                gia_dien,
                gia_don_dep,
                tien_dat_coc,
                ngay_dat_coc,
                ngay_bat_dau,
                ngay_ket_thuc,
                ngay_ky_hop_dong
            FROM 
                hopdong;

        ";

        if ($stmt = $this->conn->prepare($query)) {
            $stmt->execute();
            $result = $stmt->get_result();
            
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row; // Collect each row as an associative array
            }

            return !empty($data) ? $data : false; // Return the array if there are rows, else false
        } else {
            return false;
        }
    }
    public function getRoomBillData() {
        $query = "
            SELECT 
                hoadon.id AS ma_hoa_don,
                hoadon.id_hop_dong AS ma_hop_dong,
                hoadon.ngay_thanh_toan,
                hoadon.so_thang,
                MAX(CASE WHEN chitiethoadon.loai_mon = 'Gia Phong' THEN chitiethoadon.so_tien ELSE 0 END) AS tien_phong,
                MAX(CASE WHEN chitiethoadon.loai_mon = 'Phi Don Dep' THEN chitiethoadon.so_tien ELSE 0 END) AS tien_ve_sinh,
                hoadon.tong_so_tien,
                hoadon.nhan_vien_phu_trach
            FROM 
                hoadon
            LEFT JOIN 
                chitiethoadon ON hoadon.id = chitiethoadon.id_hoa_don
            GROUP BY 
                hoadon.id, hoadon.id_hop_dong, hoadon.ngay_thanh_toan, hoadon.so_thang, hoadon.tong_so_tien, hoadon.nhan_vien_phu_trach;


        ";

        if ($stmt = $this->conn->prepare($query)) {
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
    public function searchStudent(string $student) {
        $query = "
        SELECT 
            hocsinh.id as hoc_sinh_id,
            hocsinh.ma_sinh_vien, 
            nguoidung.ho_ten, 
            nguoidung.ngay_sinh, 
            hocsinh.so_cmnd, 
            nguoidung.gioi_tinh, 
            nguoidung.email, 
            nguoidung.so_dien_thoai, 
            hocsinh.que_quan,
            nguoidung.avatar
        FROM hocsinh
        JOIN nguoidung ON hocsinh.id_nguoi_dung = nguoidung.id
        WHERE 
            (
                hocsinh.ma_sinh_vien LIKE ? 
                OR nguoidung.ho_ten LIKE ? 
                OR nguoidung.ngay_sinh LIKE ? 
                OR hocsinh.so_cmnd LIKE ? 
                OR nguoidung.gioi_tinh LIKE ? 
                OR nguoidung.email LIKE ? 
                OR nguoidung.so_dien_thoai LIKE ? 
                OR hocsinh.que_quan LIKE ?
            )
            AND nguoidung.vai_tro = 'Hoc Sinh'
        ";
        if ($stmt = $this->conn->prepare($query)) {
            $searchTerm = "%" . $student . "%"; 
            $stmt->bind_param("ssssssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
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
    
    public function deleteStudent(int $student_id) {
        $this->conn->begin_transaction();
    
        try {
            // Xóa các chi tiết hóa đơn
            $delete_invoice_details = $this->conn->prepare("
                DELETE FROM chitiethoadon 
                WHERE id_hoa_don IN (SELECT id FROM hoadon WHERE id_hop_dong IN (SELECT id FROM hopdong WHERE id_hoc_sinh = ?))
            ");
            $delete_invoice_details->bind_param("i", $student_id); // Sử dụng "i" để bind int
            $delete_invoice_details->execute();
    
            // Xóa các hóa đơn
            $delete_invoices = $this->conn->prepare("
                DELETE FROM hoadon 
                WHERE id_hop_dong IN (SELECT id FROM hopdong WHERE id_hoc_sinh = ?)
            ");
            $delete_invoices->bind_param("i", $student_id); // Sử dụng "i" để bind int
            $delete_invoices->execute();
    
            // Xóa các bản ghi thanh toán
            $delete_payments = $this->conn->prepare("
                DELETE FROM thanhtoan 
                WHERE id_hop_dong IN (SELECT id FROM hopdong WHERE id_hoc_sinh = ?)
            ");
            $delete_payments->bind_param("i", $student_id); // Sử dụng "i" để bind int
            $delete_payments->execute();
    
            // Xóa các hợp đồng liên quan
            $delete_contracts = $this->conn->prepare("
                DELETE FROM hopdong WHERE id_hoc_sinh = ?
            ");
            $delete_contracts->bind_param("i", $student_id); // Sử dụng "i" để bind int
            $delete_contracts->execute();
    
            // Lấy id người dùng liên quan đến sinh viên
            $get_user_id = $this->conn->prepare("
                SELECT id_nguoi_dung FROM hocsinh WHERE id = ?
            ");
            $get_user_id->bind_param("i", $student_id); // Sử dụng "i" để bind int
            $get_user_id->execute();
            $result = $get_user_id->get_result();
            $user = $result->fetch_assoc();
    
            if ($user) {
                $user_id = $user['id_nguoi_dung'];
    
                // Xóa thông tin người dùng từ bảng nguoidung
                $delete_user = $this->conn->prepare("
                    DELETE FROM nguoidung WHERE id = ?
                ");
                $delete_user->bind_param("i", $user_id); // Sử dụng "i" để bind int
                $delete_user->execute();
            }
    
            // Xóa sinh viên
            $delete_student = $this->conn->prepare("
                DELETE FROM hocsinh WHERE id = ?
            ");
            $delete_student->bind_param("i", $student_id); // Sử dụng "i" để bind int
            $delete_student->execute();
    
            // Kiểm tra nếu không có lỗi, commit giao dịch
            if ($delete_student->affected_rows > 0) {
                // Commit giao dịch nếu không có lỗi
                $this->conn->commit();
                return "Xóa sinh viên và các thông tin liên quan thành công!";
            } else {
                // Nếu không có bản ghi nào bị xóa, rollback giao dịch
                $this->conn->rollback();
                return "Không tìm thấy sinh viên với ID đã cho.";
            }
        } catch (Exception $e) {
            // Nếu có lỗi, rollback giao dịch
            $this->conn->rollback();
            return "Lỗi khi xóa sinh viên: " . $e->getMessage();
        }
    }
    }

?>