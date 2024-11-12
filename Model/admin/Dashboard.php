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
            nguoidung.ho_ten, nguoidung.email, nguoidung.so_dien_thoai, nguoidung.ngay_sinh, nguoidung.gioi_tinh, nguoidung.avatar,
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
            $data[] = $row; // Collect each row as an associative array
        }

        return !empty($data) ? $data : false; // Return the array if there are rows, else false
    } else {
        return false;
    }
}




}
?>