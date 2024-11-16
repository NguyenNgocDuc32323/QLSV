<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: ../login.php');
    exit();
}
require_once '../../config/database.php';
require_once '../../Controller/admin/DashboardController.php';
require_once '../../Controller/admin/RoomBill/RoomBillController.php';
$database = new Database();
$conn = $database->connect();
$roomBillController = new RoomBillController($conn);
$roomData = $roomBillController->getRoom();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_room'])) {
    $maPhong = $_POST['maPhong'] ?? null;
    $ngayThanhToan = $_POST['ngayThanhToan'] ?? null;
    $thang = $_POST['thang'] ?? null;
    $giaPhong = $_POST['giaPhong'] ?? null;
    $phiDonDep = $_POST['phiDonDep'] ?? null;
    $check_add_room = $roomBillController->createRoomBill($maPhong, $ngayThanhToan, $thang, $giaPhong, $phiDonDep);
    if ($check_add_room) {
        header('Location: dashboard.php?tab=transaction');
    }
    else {
        echo "Thêm phòng thất bại!";
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
                                                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST"
                                                    enctype="multipart/form-data" class="w-50 mx-auto">
                                                    <input type="hidden" name="create_room" value="create_room">

                                                    <!-- Mã Phòng -->
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

                                                    <!-- Ngày Thanh Toán -->
                                                    <div class="form-group mb-3">
                                                        <label for="ngay_thanh_toan">Ngày Thanh Toán</label>
                                                        <input type="date" class="form-control" id="ngay_thanh_toan"
                                                            name="ngayThanhToan" required>
                                                    </div>

                                                    <!-- Tháng -->
                                                    <div class="form-group mb-3">
                                                        <label for="thang">Tháng</label>
                                                        <input type="number" class="form-control" id="thang"
                                                            name="thang" min="1" max="12" required>
                                                    </div>

                                                    <!-- Giá Phòng -->
                                                    <div class="form-group mb-3">
                                                        <label for="gia_phong">Giá Phòng</label>
                                                        <input type="text" class="form-control" id="gia_phong"
                                                            name="giaPhong" required>
                                                    </div>

                                                    <!-- Phí Dọn Dẹp -->
                                                    <div class="form-group mb-3">
                                                        <label for="phi_don_dep">Phí Dọn Dẹp</label>
                                                        <input type="text" class="form-control" id="phi_don_dep"
                                                            name="phiDonDep" required>
                                                    </div>

                                                    <!-- Submit Button -->
                                                    <div class="form-group mb-3 mt-4">
                                                        <button type="submit" class="btn btn-success">Tạo Mới Hóa
                                                            Đơn</button>
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