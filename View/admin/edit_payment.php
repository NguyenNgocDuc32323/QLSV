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
require_once '../../Controller/admin/Payment/PaymentController.php';
require_once '../../Controller/admin/DashboardController.php';

$database = new Database();
$conn = $database->connect();
$paymentController = new PaymentController($conn);
if (isset($_GET['payment_id'])) {
    $payment_id = (int) $_GET['payment_id'];
    $payment = $paymentController->getPaymentById($payment_id);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_payment'])) {
    $payment_id = $_POST['payment_id'];
    $ten_phong = $_POST['id_phong'];
    $so_tien = $_POST['so_tien'];
    $id_hoc_sinh = $_POST['id_hoc_sinh'];
    $method = $_POST['phuong_thuc_thanh_toan'];
    $ngay_thanh_toan = $_POST['ngay_thanh_toan'];
    $trang_thai = $_POST['trang_thai'];
    $students = $paymentController->getStudent();
    $checkEditPayment = $paymentController->editPayment($payment_id, $ten_phong, $so_tien, $id_hoc_sinh, $method, $ngay_thanh_toan, $trang_thai);
    if ($checkEditPayment) {
        header('Location: dashboard.php?tab=delivery');
        exit();
    } else {
        echo "Cập nhật thông tin thanh toán thất bại!";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap-grid.min.css"
        integrity="sha512-i1b/nzkVo97VN5WbEtaPebBG8REvjWeqNclJ6AItj7msdVcaveKrlIIByDpvjk5nwHjXkIqGZscVxOrTb9tsMA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../assets/css/edit-profile.css">
    <link rel="stylesheet" href="../assets/css/admin-dashboard.css">
    <title>Quản lý người dùng</title>
</head>

<body>
    <div class="d-flex align-items-center">
        <?php include '../admin/sidebar.php'; ?>
        <div class="dashboard-main">
            <?php include '../admin/navbar.php'; ?>
            <div class="content-wrapper">
                <div class="container-fluid flex-grow-1 container-p-y">
                    <div class="dashboard-main-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24 mt-4">
                            <h1 class="text-white fw-semibold mb-0 body-title">Cập Nhật Thanh Toán</h1>
                            <ul class="d-flex align-items-center gap-2">
                                <li class="fw-medium">
                                    <a href="index.php" class="text-white d-flex align-items-center gap-1 hover-text-primary">
                                        <i class="fa-solid fa-house"></i>
                                        Trang Chủ
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="row justify-content-center align-items-center user-manage-block">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <ul class="nav" id="myTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active" id="profile-tab" data-bs-toggle="tab"
                                                    href="#profile" role="tab" aria-controls="profile"
                                                    aria-selected="true">Thông Tin Thanh Toán</a>
                                            </li>

                                        </ul>
                                        <hr>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="profile" role="tabpanel"
                                                aria-labelledby="profile-tab">
                                                <form action="edit_payment.php?tab=delivery" method="POST">
                                                    <input type="hidden" name="edit_payment" value="edit_payment">
                                                    <input type="hidden" name="payment_id" value="<?php echo $payment['thanh_toan_id'] ?>">
                                                    <div class="form-group">
                                                        <label for="id_phong">Tên Phòng:</label>
                                                        <input type="text" value="<?php echo $payment['ten_phong']; ?>" class="form-control" readonly>
                                                        <input type="hidden" name="id_phong" value="<?php echo $payment['id_phong']; ?>">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="so_tien">Số Tiền:</label>
                                                        <input type="number" class="form-control" id="so_tien" name="so_tien" value="<?php echo $payment['so_tien']; ?>" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="id_hoc_sinh">Tên Học Sinh:</label>
                                                        <select name="id_hoc_sinh" id="id_hoc_sinh" class="form-control" required>
                                                            <option value="<?php echo $payment['id_hoc_sinh']; ?>" selected><?php echo $payment['ho_ten']; ?></option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="phuong_thuc_thanh_toan">Phương Thức Thanh Toán:</label>
                                                        <select name="phuong_thuc_thanh_toan" id="phuong_thuc_thanh_toan" class="form-control">
                                                            <option value="Tien Mat" <?php echo ($payment['phuong_thuc_thanh_toan'] == 'Tien Mat') ? 'selected' : ''; ?>>Tiền Mặt</option>
                                                            <option value="Chuyen Khoan Ngan Hang" <?php echo ($payment['phuong_thuc_thanh_toan'] == 'Chuyen Khoan Ngan Hang') ? 'selected' : ''; ?>>Chuyển Khoản</option>
                                                            <option value="Thanh Toan Online" <?php echo ($payment['phuong_thuc_thanh_toan'] == 'Thanh Toan Online') ? 'selected' : ''; ?>>Visa/Mastercard</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="ngay_thanh_toan">Ngày Thanh Toán:</label>
                                                        <input type="date" class="form-control" id="ngay_thanh_toan" name="ngay_thanh_toan" value="<?php echo $payment['ngay_thanh_toan']; ?>" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="trang_thai">Trạng Thái:</label>
                                                        <select name="trang_thai" id="trang_thai" class="form-control" required <?php echo ($payment['trang_thai'] == 'Hoan Thanh') ? 'disabled' : ''; ?>>
                                                            <option value="Hoan Thanh" <?php echo ($payment['trang_thai'] == 'Hoan Thanh') ? 'selected' : ''; ?>>Đã Thanh Toán</option>
                                                            <option value="Cho Xu Ly" <?php echo ($payment['trang_thai'] == 'Cho Xu Ly') ? 'selected' : ''; ?>>Đang Xử Lý</option>
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
                    </div>
                </div>
            </div>


            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js">
            </script>
            <script src="../assets/js/app.js"></script>
            <script src="../assets/js/admin-dashboard.js"></script>
</body>

</html>