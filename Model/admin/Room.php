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
    
    
}
?>