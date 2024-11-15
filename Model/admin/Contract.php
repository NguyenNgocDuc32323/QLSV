<?php

class Contract {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function getUser(){
        $query = "SELECT * FROM hocsinh"; 
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function getRoom(){
        $query = "SELECT * FROM phong "; 
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function getAllContract(){
    $query = "SELECT 
        hd.id AS hop_dong_id,
        hs.id AS id_hoc_sinh,
        nd.id AS id_nguoi_dung,
        hs.ma_sinh_vien AS ma_sinh_vien,  -- Thay đổi từ nd.ho_ten sang hs.ma_sinh_vien
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

        // Execute the SQL statement and check the result
        if ($stmt->execute()) {
            return true;  // Success
        } else {
            return false; // Error during execution
        }
    } else {
        return false;  // Error preparing the statement
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
        hopdong.gia AS gia,                      -- Make sure these columns are included
        hopdong.gia_nuoc AS gia_nuoc,            -- Include all necessary columns
        hopdong.gia_dien AS gia_dien,            
        hopdong.gia_don_dep AS gia_don_dep,      
        hocsinh.ma_sinh_vien AS ma_sinh_vien,
        nguoidung.ho_ten AS ten_hoc_sinh,
        phong.ma_phong AS ma_phong
    FROM hopdong
    LEFT JOIN hocsinh ON hopdong.id_hoc_sinh = hocsinh.id
    LEFT JOIN nguoidung ON hocsinh.id_nguoi_dung = nguoidung.id
    LEFT JOIN phong ON hopdong.id_phong = phong.id
    WHERE 
        hocsinh.ma_sinh_vien LIKE ? 
        OR nguoidung.ho_ten LIKE ? 
        OR phong.ma_phong LIKE ? 
        OR hopdong.ngay_ky_hop_dong LIKE ? 
        OR hopdong.ngay_bat_dau LIKE ? 
        OR hopdong.ngay_ket_thuc LIKE ? 
        OR hopdong.ngay_dat_coc LIKE ? 
    ";

    if ($stmt = $this->conn->prepare($query)) {
        $searchTerm = "%" . $searchTerm . "%"; 
        $stmt->bind_param("sssssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
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
public function createContract($studentId, $roomId, $price, $waterPrice, $electricityPrice, $cleaningPrice, $deposit, $depositDate, $startDate, $endDate, $contractDate) {
    $stmt = $this->conn->prepare("INSERT INTO hopdong (id_hoc_sinh, id_phong, gia, gia_nuoc, gia_dien, gia_don_dep, tien_dat_coc, ngay_dat_coc, ngay_bat_dau, ngay_ket_thuc, ngay_ky_hop_dong) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iidddddssss", $studentId, $roomId, $price, $waterPrice, $electricityPrice, $cleaningPrice, $deposit, $depositDate, $startDate, $endDate, $contractDate);
    $stmt->execute();
    return $stmt->affected_rows > 0;
}





    
}
?>