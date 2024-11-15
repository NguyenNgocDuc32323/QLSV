<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: ../login.php');
    exit();
}

require_once '../../config/database.php';
require_once '../../Controller/admin/Contract/ContractController.php';
require_once '../../Controller/admin/DashboardController.php';

$database = new Database();
$conn = $database->connect();
$contractController = new ContractController($conn);

if (isset($_GET['contract_id'])) {
    $contract_id = (int) $_GET['contract_id'];
    $contract = $contractController->getContractById($contract_id);
}

$students = $contractController->getUser();
$rooms = $contractController->getRoom();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_profile'])) {
    $contract_id = isset($_POST['contract_id']) ? (int) $_POST['contract_id'] : null;
    $studentId = $_POST['studentId'] ?? null;
    $roomId = $_POST['roomId'] ?? null;
    $price = $_POST['price'] ?? null;
    $waterPrice = $_POST['waterPrice'] ?? null;
    $electricityPrice = $_POST['electricityPrice'] ?? null;
    $cleaningPrice = $_POST['cleaningPrice'] ?? null;
    $deposit = $_POST['deposit'] ?? null;
    $depositDate = $_POST['depositDate'] ?? null;
    $startDate = $_POST['startDate'] ?? null;
    $endDate = $_POST['endDate'] ?? null;
    $contractDate = $_POST['contractDate'] ?? null;

    $checkUpdate = $contractController->updateContract(
        $contract_id, $studentId, $roomId, $price, $waterPrice, $electricityPrice, $cleaningPrice, $deposit, $depositDate, $startDate, $endDate, $contractDate
    );
    if ($checkUpdate) {
    header('Location: dashboard.php?tab=account');
    exit();
} else {
    echo "<script>alert('Cập nhật thông tin hợp đồng thất bại!'); window.history.back();</script>";
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
                                <h1 class="fw-semibold mb-0 body-title">Cập Nhật Sinh Viên</h1>
                                <ul class="d-flex align-items-center gap-2">
                                    <li class="fw-medium">
                                        <a href="index.php" class="d-flex align-items-center gap-1 hover-text-primary">
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
                                            <!-- Các tab điều hướng -->
                                            <ul class="nav" id="myTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link active" id="profile-tab" data-bs-toggle="tab"
                                                        href="#profile" role="tab" aria-controls="profile"
                                                        aria-selected="true">Thông tin hợp đồng</a>
                                                </li>
                                            </ul>
                                            <hr>
                                            <!-- Nội dung các tab -->
                                            <div class="tab-content" id="myTabContent">
                                                <div class="tab-pane fade show active" id="profile" role="tabpanel"
                                                    aria-labelledby="profile-tab">
                                                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST"
                                                        enctype="multipart/form-data" class="w-50 mx-auto">
                                                        <input type="hidden" name="change_profile"
                                                            value="change_profile">
                                                        <input type="hidden" name="contract_id"
                                                            value="<?php echo($contract['contract_id'])?>">
                                                        <div class="form-group mb-3">
                                                            <label for="studentId">Mã sinh viên</label>
                                                            <select name="studentId" id="studentId" class="form-control"
                                                                required>
                                                                <?php foreach ($students as $student): ?>
                                                                <option value="<?php echo ($student['id']); ?>"
                                                                    <?php echo (isset($contract['student_code']) && $contract['student_code'] == $student['ma_sinh_vien']) ? 'selected' : ''; ?>>
                                                                    <?php echo ($student['ma_sinh_vien']); ?>
                                                                </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="roomId">Tên phòng</label>
                                                            <select name="roomId" id="roomId" class="form-control"
                                                                required>
                                                                <?php foreach($rooms as $room): ?>
                                                                <option value="<?php echo ($room['id']); ?>"
                                                                    <?php echo (isset($contract['room_code']) && $contract['room_code'] == $room['ma_phong']) ? 'selected' : ''; ?>>
                                                                    <?php echo ($room['ma_phong']); ?>
                                                                </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="price">Giá</label>
                                                            <input type="price" class="form-control" id="price"
                                                                name="price"
                                                                value="<?= htmlspecialchars($contract['gia'] ?? '') ?>">
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="waterPrice">Giá nước</label>
                                                            <input type="text" class="form-control" id="waterPrice"
                                                                name="waterPrice"
                                                                value="<?= htmlspecialchars($contract['gia_nuoc'] ?? '') ?>">
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="electricityPrice">Giá điện</label>
                                                            <input type="text" class="form-control"
                                                                id="electricityPrice" name="electricityPrice"
                                                                value="<?= htmlspecialchars($contract['gia_dien'] ?? '') ?>">
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="cleaningPrice">Giá vệ sinh chung</label>
                                                            <input type="text" class="form-control" id="cleaningPrice"
                                                                name="cleaningPrice"
                                                                value="<?= htmlspecialchars($contract['gia_don_dep'] ?? '') ?>">
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="deposit">Tiền đặt cọc</label>
                                                            <input type="text" class="form-control" id="deposit"
                                                                name="deposit"
                                                                value="<?= htmlspecialchars($contract['tien_dat_coc'] ?? '') ?>">
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="depositDate">Ngày ký hợp đồng</label>
                                                            <input type="date" class="form-control" id="depositDate"
                                                                name="depositDate"
                                                                value="<?= htmlspecialchars($contract['ngay_ky_hop_dong'] ?? '') ?>">
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="startDate">Ngày bắt đâu</label>
                                                            <input type="date" class="form-control" id="startDate"
                                                                name="startDate"
                                                                value="<?= htmlspecialchars($contract['ngay_bat_dau'] ?? '') ?>">
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="endDate">Ngày kết thúc</label>
                                                            <input type="date" class="form-control" id="endDate"
                                                                name="endDate"
                                                                value="<?= htmlspecialchars($contract['ngay_ket_thuc'] ?? '') ?>">
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="contractDate">Ngày đặt cọc</label>
                                                            <input type="date" class="form-control" id="contractDate"
                                                                name="contractDate"
                                                                value="<?= htmlspecialchars($contract['ngay_dat_coc'] ?? '') ?>">
                                                        </div>
                                                        <div class="form-group mb-3 mt-4">
                                                            <button type="submit" class="btn btn-success">Cập nhật thông
                                                                tin hợp đồng</button>
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
                </div>


                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js">
                </script>
                <script src="../assets/js/app.js"></script>
                <script src="../assets/js/admin-dashboard.js"></script>
    </body>

</html>