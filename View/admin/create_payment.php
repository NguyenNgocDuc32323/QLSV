<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: ../login.php');
    exit();
}
if (isset($_SESSION['success'])) {
    $message = json_encode($_SESSION['success']);
    echo "<script> alert($message); </script>";
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    $message = json_encode($_SESSION['error']);
    echo "<script> alert($message); </script>";
    unset($_SESSION['error']);
}

require_once '../../config/database.php';
require_once '../../Controller/admin/DashboardController.php';
require_once '../../Controller/admin/Payment/PaymentController.php';
$database = new Database();
$conn = $database->connect();
$paymentController = new PaymentController($conn);
$students = $paymentController->getStudent();
$rooms  = $paymentController->getRoom();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_payment'])) {
    $ten_phong = $_POST['ten_phong'] ?? '';
    $so_tien = $_POST['so_tien'] ?? 0;
    $id_hoc_sinh = $_POST['id_hoc_sinh'] ?? '';
    $method = $_POST['method'] ?? '';
    $ngay_thanh_toan = $_POST['ngay_thanh_toan'] ?? '';
    $trang_thai = $_POST['trang_thai'] ?? '';
    $checkCreatePayment = $paymentController->createPayment($ten_phong, $so_tien, $id_hoc_sinh, $method, $ngay_thanh_toan, $trang_thai);
    if ($checkCreatePayment) {
        header('Location: dashboard.php?tab=delivery');
        exit();
    }
    else {
        echo "Tạo hóa đơn phòng thất bại!";
        exit();
    }
}
if (isset($_GET['id_phong'])) {
    $id_phong = intval($_GET['id_phong']);
    
    // Câu truy vấn để lấy ID học sinh, tên học sinh và tổng số tiền
    $query = "
        SELECT h.id AS id_hoc_sinh, 
               n.ho_ten, 
               hoadon.tong_so_tien
        FROM hoadon
        JOIN hopdong AS hd ON hd.id = hoadon.id_hop_dong
        JOIN hocsinh AS h ON h.id = hd.id_hoc_sinh
        JOIN nguoidung AS n ON n.id = h.id_nguoi_dung
        WHERE hd.id_phong = ?
    ";

    // Chuẩn bị câu lệnh
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_phong);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $data = [];

        // Lấy tất cả các dòng kết quả
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                "id_hoc_sinh" => $row['id_hoc_sinh'],
                "ho_ten" => $row['ho_ten'],
                "tong_so_tien" => $row['tong_so_tien'],
            ];
        }

        if (!empty($data)) {
            echo json_encode(["success" => true, "data" => $data]);
        } else {
            echo json_encode(["success" => false, "message" => "Không tìm thấy dữ liệu"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Lỗi truy vấn cơ sở dữ liệu"]);
    }

    $stmt->close();
    $conn->close();
    exit();
}


?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/edit-profile.css">
    <link rel="stylesheet" href="../assets/css/admin-dashboard.css">
    <title>Thêm Hóa Đơn Phòng</title>
</head>

<body>
    <div class="d-flex align-items-center">
        <?php include '../admin/sidebar.php'; ?>
        <div class="dashboard-main">
            <?php include '../admin/navbar.php'; ?>
            <div class="content-wrapper">
                <div class="container-fluid">
                    <h1 class="fw-semibold mb-4 text-white">Thêm Hóa Đơn Phòng</h1>
                    <div class="card">
                        <div class="card-body">
                            <form action="create_payment.php?tab=delivery" method="POST">
                                <input type="hidden" name="create_payment" value="create_payment">
                                
                                <div class="form-group">
                                    <label for="ten_phong">Tên Phòng:</label>
                                    <select name="ten_phong" id="ten_phong" class="form-control">
                                        <option value="" disabled selected>Chọn Phòng</option>
                                        <?php foreach ($rooms as $room): ?>
                                            <option value="<?= htmlspecialchars($room['id']) ?>"><?= htmlspecialchars($room['ma_phong']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="so_tien">Số Tiền:</label>
                                    <input type="number" class="form-control" id="so_tien" name="so_tien" required readonly>
                                </div>

                                <div class="form-group">
                                    <label for="id_hoc_sinh">Tên Học Sinh:</label>
                                    <select name="id_hoc_sinh" id="id_hoc_sinh" class="form-control" required>
                                        <?php foreach ($students as $student): ?>
                                            <option value="<?= htmlspecialchars($student['id_hoc_sinh']) ?>"><?= htmlspecialchars($student['ho_ten']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="method">Phương Thức Thanh Toán:</label>
                                    <select name="method" id="method" class="form-control">
                                        <option value="Tien Mat">Tiền Mặt</option>
                                        <option value="Chuyen Khoan Ngan Hang">Chuyển Khoản</option>
                                        <option value="Thanh Toan Online">Visa/Mastercard</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="ngay_thanh_toan">Ngày Thanh Toán:</label>
                                    <input type="date" class="form-control" id="ngay_thanh_toan" name="ngay_thanh_toan" required>
                                </div>

                                <div class="form-group">
                                    <label for="trang_thai">Trạng Thái:</label>
                                    <select name="trang_thai" id="trang_thai" class="form-control" required>
                                        <option value="Hoan Thanh">Đã Thanh Toán</option>
                                        <option value="Cho Xu Ly">Đang Xử Lý</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-success">Tạo Thanh Toán</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('ten_phong').addEventListener('change', function () {
    const roomId = this.value;
    fetch(`create_payment.php?id_phong=${roomId}`)
        .then(response => response.json())
        .then(data => {
            console.log(data);

            if (data.success) {
                // Gán tổng số tiền vào thẻ input #so_tien
                if (data.data && data.data.length > 0) {
                    document.getElementById('so_tien').value = data.data[0].tong_so_tien || 0;
                } else {
                    document.getElementById('so_tien').value = ''; // Xóa giá trị nếu không có dữ liệu
                }

                // Gán danh sách học sinh vào thẻ select #id_hoc_sinh
                const selectElement = document.getElementById('id_hoc_sinh');
                selectElement.innerHTML = ''; // Xóa các tùy chọn cũ

                if (data.data && data.data.length > 0) {
                    // Tạo các tùy chọn từ danh sách học sinh
                    data.data.forEach(student => {
                        const option = document.createElement('option');
                        option.value = student.id_hoc_sinh;
                        option.textContent = student.ho_ten;
                        selectElement.appendChild(option);
                    });
                } else {
                    // Nếu không có học sinh, thêm tùy chọn mặc định
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Không có học sinh nào';
                    selectElement.appendChild(option);
                }
            } else {
                // Xóa giá trị và hiển thị thông báo lỗi nếu không thành công
                document.getElementById('so_tien').value = '';
                const selectElement = document.getElementById('id_hoc_sinh');
                selectElement.innerHTML = ''; // Xóa danh sách học sinh
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Không có học sinh nào';
                selectElement.appendChild(option);

                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error fetching room data:', error);
            alert('Đã xảy ra lỗi khi lấy dữ liệu.');
        });
});


    </script>
</body>

</html>
