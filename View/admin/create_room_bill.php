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
require_once '../../Controller/admin/RoomBill/RoomBillController.php';
$database = new Database();
$conn = $database->connect();
$roomBillController = new RoomBillController($conn);
$roomData = $roomBillController->getRoom();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_room'])) {
    $maPhong = isset($_POST['maPhong']) ? (int) $_POST['maPhong'] : 0;
    $soNuocCu = isset($_POST['so_nuoc_cu']) ? (int) $_POST['so_nuoc_cu'] : 0;
    $soNuocMoi = isset($_POST['so_nuoc_moi']) ? (int) $_POST['so_nuoc_moi'] : 0;
    $soDienCu = isset($_POST['so_dien_cu']) ? (int) $_POST['so_dien_cu'] : 0;
    $soDienMoi = isset($_POST['so_dien_moi']) ? (int) $_POST['so_dien_moi'] : 0;
    $ngayThanhToan = isset($_POST['ngayThanhToan']) ? $_POST['ngayThanhToan'] : '';
    $ghiChu = isset($_POST['ghi_chu']) ? htmlspecialchars($_POST['ghi_chu']) : '';
    if ($soNuocMoi < $soNuocCu) {
        echo "Số nước mới không thể nhỏ hơn số nước cũ!";
        exit();
    }

    if ($soDienMoi < $soDienCu) {
        echo "Số điện mới không thể nhỏ hơn số điện cũ!";
        exit();
    }
    $check_add_room = $roomBillController->createRoomBill($maPhong, $soNuocCu, $soNuocMoi, $soDienCu, $soDienMoi, $ngayThanhToan,$ghiChu);
    if ($check_add_room) {
        header('Location: dashboard.php?tab=transaction');
    } else {
        echo "Thêm hóa đơn phòng thất bại!";
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
                            <h1 class="fw-semibold mb-0 body-title text-white">Thêm hóa đơn phòng</h1>
                        </div>
                        <div class="row justify-content-center align-items-center user-manage-block">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="tab-pane fade show active" id="profile" role="tabpanel"
                                            aria-labelledby="profile-tab">
                                            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data" class="w-50 mx-auto">
                                                <input type="hidden" name="create_room" value="create_room">
                                                <div class="form-group mb-3">
                                                    <label for="ma_phong">Mã Phòng</label>
                                                    <select class="form-control" id="ma_phong" name="maPhong">
                                                        <?php foreach ($roomData as $room): ?>
                                                            <option value="<?= htmlspecialchars($room['id']) ?>">
                                                                <?= htmlspecialchars($room['ma_phong']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label for="so_nuoc_cu">Số Nước Cũ</label>
                                                    <input type="number" class="form-control" id="so_nuoc_cu" name="so_nuoc_cu" required>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label for="so_nuoc_moi">Số Nước Mới</label>
                                                    <input type="number" class="form-control" id="so_nuoc_moi" name="so_nuoc_moi" required>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label for="so_dien_cu">Số Điện Cũ</label>
                                                    <input type="number" class="form-control" id="so_dien_cu" name="so_dien_cu" required>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label for="so_dien_moi">Số Điện Mới</label>
                                                    <input type="number" class="form-control" id="so_dien_moi" name="so_dien_moi" required>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label for="ngay_thanh_toan">Ngày Thanh Toán</label>
                                                    <input type="date" class="form-control" id="ngay_thanh_toan" name="ngayThanhToan" required>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="ngay_thanh_toan">Ghi Chú</label>
                                                    <textarea name="ghi_chu" class="form-control" rows="5"></textarea>
                                                </div>
                                                <div class="form-group mb-3 mt-4">
                                                    <button type="submit" class="btn btn-success">Tạo Mới Hóa Đơn</button>
                                                </div>
                                            </form>
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