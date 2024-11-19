<?php
class Paymnent {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function getAllPayment(){
        $query = "
            SELECT thanhtoan.ngay_thanh_toan as ngay_tra_tien,thanhtoan.id as thanh_toan_id,thanhtoan.*,phong.ma_phong as ten_phong,nguoidung.ho_ten
            FROM thanhtoan
            JOIN hocsinh ON thanhtoan.id_hoc_sinh = hocsinh.id
            JOIN nguoidung ON hocsinh.id_nguoi_dung = nguoidung.id
            JOIN phong ON phong.id = thanhtoan.id_phong
        ";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function searchPayment($string)
{
    $query = "
        SELECT 
            thanhtoan.ngay_thanh_toan AS ngay_tra_tien,
            thanhtoan.id AS thanh_toan_id,
            thanhtoan.*,
            phong.ma_phong AS ten_phong, 
            phong.id as id_phong,
            nguoidung.ho_ten
        FROM thanhtoan
        JOIN hocsinh ON thanhtoan.id_hoc_sinh = hocsinh.id
        JOIN nguoidung ON hocsinh.id_nguoi_dung = nguoidung.id
        JOIN phong ON phong.id = thanhtoan.id_phong
        WHERE 
            phong.ma_phong LIKE ? OR
            thanhtoan.so_tien LIKE ? OR
            nguoidung.ho_ten LIKE ? OR
            thanhtoan.phuong_thuc_thanh_toan LIKE ? OR
            thanhtoan.ngay_thanh_toan LIKE ? OR
            thanhtoan.trang_thai LIKE ?
    ";

    if ($stmt = $this->conn->prepare($query)) {
        $searchTerm = "%" . $string . "%"; 
        $stmt->bind_param("sdssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
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
    public function getStudent() {
        $query = "
            SELECT hocsinh.id as id_hoc_sinh,nguoidung.ho_ten
            FROM hocsinh
            LEFT JOIN thanhtoan ON hocsinh.id = thanhtoan.id_hoc_sinh
            JOIN nguoidung ON nguoidung.id = hocsinh.id_nguoi_dung
            WHERE thanhtoan.id_hoc_sinh IS NULL
        ";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function createPayment($ten_phong, $so_tien, $id_hoc_sinh, $method, $ngay_thanh_toan, $trang_thai){
        $query = "INSERT INTO thanhtoan (ngay_thanh_toan, trang_thai, phuong_thuc_thanh_toan, id_hoc_sinh, id_phong, so_tien) VALUES (?,?,?,?,?,?)";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("sssiid", $ngay_thanh_toan, $trang_thai, $method, $id_hoc_sinh, $ten_phong, $so_tien);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function getRoom() {
        $query = "
            SELECT p.id, p.ma_phong 
            FROM phong p
            LEFT JOIN hopdong hd ON p.id = hd.id_phong
            LEFT JOIN thanhtoan tt ON hd.id = tt.id_hop_dong
            GROUP BY p.id, p.ma_phong, p.suc_chua_toi_da
            HAVING 
                COUNT(tt.id) < p.suc_chua_toi_da
        ";
        
        $result = $this->conn->query($query);
        
        if (!$result) {
            die("Lỗi truy vấn: " . $this->conn->error);
        }
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function deletePayment($payment_id){
        $query = "DELETE FROM thanhtoan WHERE id =?";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("i", $payment_id);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function getPaymentById($payment_id) {
        $query = "
            SELECT thanhtoan.ngay_thanh_toan as ngay_tra_tien,thanhtoan.id as thanh_toan_id,thanhtoan.*,phong.ma_phong as ten_phong,nguoidung.ho_ten
            FROM thanhtoan
            JOIN hocsinh ON thanhtoan.id_hoc_sinh = hocsinh.id
            JOIN nguoidung ON hocsinh.id_nguoi_dung = nguoidung.id
            JOIN phong ON phong.id = thanhtoan.id_phong
            WHERE thanhtoan.id = ?
        ";
    
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("i", $payment_id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } else {
            return false;
        }
    }
    public function editPayment($payment_id, $ten_phong, $so_tien, $id_hoc_sinh, $method, $ngay_thanh_toan, $trang_thai){
        $query = "UPDATE thanhtoan SET ngay_thanh_toan =?, trang_thai =?, phuong_thuc_thanh_toan =?, id_hoc_sinh =?, id_phong =?, so_tien =? WHERE id =?";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("sssiidi", $ngay_thanh_toan, $trang_thai, $method, $id_hoc_sinh, $ten_phong, $so_tien, $payment_id);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
}
?>