<?php

class RoomBill {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function getAllRoomBill(){
    $query = "SELECT 
    phong.ma_phong  AS ten_phong, 
    hoadon.ngay_thanh_toan, 
    chitiethoadon.thang, 
    SUM(CASE WHEN chitiethoadon.loai_mon = 'Gia Phong' THEN chitiethoadon.so_tien ELSE 0 END) AS gia_phong,
    SUM(CASE WHEN chitiethoadon.loai_mon = 'Phi Don Dep' THEN chitiethoadon.so_tien ELSE 0 END) AS phi_don_dep,
    SUM(chitiethoadon.so_tien) AS tong_phi,
    MIN(chitiethoadon.id) AS id -- or use another unique identifier if applicable
FROM 
    chitiethoadon
JOIN 
    hoadon ON chitiethoadon.id_hoa_don = hoadon.id
JOIN 
    phong ON hoadon.id_phong = phong.id
GROUP BY 
    phong.ma_phong, hoadon.ngay_thanh_toan, chitiethoadon.thang";


    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
public function getRoomBillById(int $bill_id) {
    $query = "SELECT 
                chitiethoadon.id AS bill_id,  -- Add the bill ID here
                phong.ma_phong AS ten_phong, 
                hoadon.ngay_thanh_toan, 
                chitiethoadon.thang, 
                SUM(CASE WHEN chitiethoadon.loai_mon = 'Gia Phong' THEN chitiethoadon.so_tien ELSE 0 END) AS gia_phong,
                SUM(CASE WHEN chitiethoadon.loai_mon = 'Phi Don Dep' THEN chitiethoadon.so_tien ELSE 0 END) AS phi_don_dep,
                SUM(chitiethoadon.so_tien) AS tong_phi
              FROM 
                chitiethoadon
              JOIN 
                hoadon ON chitiethoadon.id_hoa_don = hoadon.id
              JOIN 
                phong ON hoadon.id_phong = phong.id
              WHERE 
                chitiethoadon.id = ?
              GROUP BY 
                chitiethoadon.id, phong.ma_phong, hoadon.ngay_thanh_toan, chitiethoadon.thang";

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param('i', $bill_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
public function getRoom(){
    $query = "SELECT * FROM phong";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

public function updateRoomBill($billId, $maPhong, $ngayThanhToan, $thang, $newGiaPhong, $newPhiDonDep) {
    $query = "
        UPDATE chitiethoadon
        JOIN hoadon ON chitiethoadon.id_hoa_don = hoadon.id
        JOIN phong ON hoadon.id_phong = phong.id
        SET 
            hoadon.id_phong = ?, 
            hoadon.ngay_thanh_toan = ?, 
            chitiethoadon.thang = ?, 
            chitiethoadon.so_tien = CASE 
                                        WHEN chitiethoadon.loai_mon = 'Gia Phong' THEN ? 
                                        WHEN chitiethoadon.loai_mon = 'Phi Don Dep' THEN ? 
                                        ELSE chitiethoadon.so_tien 
                                    END
        WHERE 
            chitiethoadon.id = ?;  -- Use bill_id to specify which record to update
    ";
    

    if ($stmt = $this->conn->prepare($query)) {
        $stmt->bind_param(
            "issidd", 
            $billId ,
            $maPhong, 
            $ngayThanhToan, 
            $thang, 
            $newGiaPhong, 
            $newPhiDonDep, 
        );

        if ($stmt->execute()) {
            return true;  // Success
        } else {
            echo "Error executing update: " . $this->conn->error;
            return false; // Error during execution
        }
    } else {
        echo "Error preparing statement: " . $this->conn->error;
        return false;  // Error preparing the statement
    }
}

public function searchRoomBill(array $searchParams) {
    // Start building the query to search only the selected fields
    $query = "
    SELECT 
    phong.ma_phong  AS ten_phong, 
    hoadon.ngay_thanh_toan, 
    chitiethoadon.thang, 
    SUM(CASE WHEN chitiethoadon.loai_mon = 'Gia Phong' THEN chitiethoadon.so_tien ELSE 0 END) AS gia_phong,
    SUM(CASE WHEN chitiethoadon.loai_mon = 'Phi Don Dep' THEN chitiethoadon.so_tien ELSE 0 END) AS phi_don_dep,
    SUM(chitiethoadon.so_tien) AS tong_phi,
    MIN(chitiethoadon.id) AS id -- or use another unique identifier if applicable
FROM 
    chitiethoadon
JOIN 
    hoadon ON chitiethoadon.id_hoa_don = hoadon.id
JOIN 
    phong ON hoadon.id_phong = phong.id
     WHERE 
        (
            phong.ma_phong LIKE ? 
            OR hoadon.ngay_thanh_toan LIKE ? 
            OR chitiethoadon.thang LIKE ? 
            OR chitiethoadon.loai_mon LIKE ?
        )
GROUP BY 
    phong.ma_phong, hoadon.ngay_thanh_toan, chitiethoadon.thang

 
    ";

    // Prepare the statement
    if ($stmt = $this->conn->prepare($query)) {
        // Bind parameters for the LIKE clause
        $searchTerm = "%" . $searchParams['search_term'] . "%";  // Using a single search term for simplicity
        $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm); // Corrected bind_param for 4 parameters

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();
        $data = [];
        
        // Fetch the data
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Return results or false if no results found
        return !empty($data) ? $data : false;
    } else {
        return false; // Return false if statement preparation fails
    }
}






    
}
?>