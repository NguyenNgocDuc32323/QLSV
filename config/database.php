<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'qlsv';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function connect() {
        // Kiểm tra xem kết nối đã tồn tại chưa để tránh tạo nhiều kết nối
        if ($this->conn === null) {
            try {
                // Tạo kết nối MySQLi (thay vì PDO)
                $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

                // Kiểm tra kết nối
                if ($this->conn->connect_error) {
                    throw new Exception("Connection failed: " . $this->conn->connect_error);
                }
            } catch (Exception $e) {
                echo "Connection error: " . $e->getMessage();
            }
        }
        return $this->conn;
    }

    // Đảm bảo rằng khi không sử dụng nữa, kết nối được đóng
    public function closeConnection() {
        $this->conn->close();
    }
}
?>