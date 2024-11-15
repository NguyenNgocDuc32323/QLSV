<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: ../login.php');
    exit();
}

require_once '../../config/database.php';
require_once '../../Controller/admin/RoomBill/RoomBillController.php';
require_once '../../Controller/admin/DashboardController.php';

$database = new Database();
$conn = $database->connect();
$roomBillController = new RoomBillController($conn);
$roomData = $roomBillController->getRoom();

// Initialize student data to avoid warnings if not found
$student = null;

if (isset($_GET['contract_id'])) {
    $phong_id = (int) $_GET['contract_id'];
    // Fetch student/room bill by ID
    $student = $roomBillController->getRoomBillById($phong_id); 
    if ($student === null) {
        echo "Hóa đơn phòng không tồn tại!";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_room_bill'])) {
    // Sanitize and validate POST data
    $billId = isset($_POST['contract_id']) ? (int) $_POST['contract_id'] : null;
    $maPhong = isset($_POST['roomCode']) ? $_POST['roomCode'] : null;
    $ngayThanhToan = isset($_POST['paymentDate']) ? $_POST['paymentDate'] : null;
    $thang = isset($_POST['month']) ? $_POST['month'] : null;
    $newGiaPhong = isset($_POST['newRoomPrice']) ? $_POST['newRoomPrice'] : null;
    $newPhiDonDep = isset($_POST['newCleaningFee']) ? $_POST['newCleaningFee'] : null;

    // Update room bill details
    $checkUpdate = $roomBillController->updateRoomBill($billId, $maPhong, $ngayThanhToan, $thang, $newGiaPhong, $newPhiDonDep);
    
    if ($checkUpdate) {
        header('Location: dashboard.php?tab=transaction');
        exit();
    } else {
        echo "Cập nhật thông tin hóa đơn phòng thất bại!";
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
                                            <ul class="nav" id="myTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link active" id="profile-tab" data-bs-toggle="tab"
                                                        href="#profile" role="tab" aria-controls="profile"
                                                        aria-selected="true">Thông tin phòng</a>
                                                </li>
                                            </ul>
                                            <hr>
                                            <div class="tab-content" id="myTabContent">
                                                <div class="tab-pane fade show active" id="profile" role="tabpanel"
                                                    aria-labelledby="profile-tab">
                                                    <?php if ($student): ?>
                                                    <?php var_dump($student); ?>
                                                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST"
                                                        enctype="multipart/form-data" class="w-50 mx-auto">
                                                        <input type="hidden" name="change_room_bill"
                                                            value="change_room_bill">
                                                        <input type="hidden" name="contract_id"
                                                            value="<?= htmlspecialchars($student['bill_id']) ?>">
                                                        <div class="form-group mb-3">
                                                            <label for="roomCode">Mã Phòng</label>
                                                            <select class="form-control" id="roomCode" name="roomCode">
                                                                <?php foreach ($roomData as $room): ?>
                                                                <option value="<?= htmlspecialchars($room['id']) ?>"
                                                                    <?= (isset($student['ma_phong']) && $student['ma_phong'] == $room['ma_phong']) ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars($room['ma_phong']) ?>
                                                                </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label for="paymentDate">Ngày thanh toán</label>
                                                            <input type="date" class="form-control" id="paymentDate"
                                                                name="paymentDate"
                                                                value="<?= htmlspecialchars($student['ngay_thanh_toan'] ?? '') ?>">
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label for="month">Tháng</label>
                                                            <input type="number" class="form-control" id="month"
                                                                name="month"
                                                                value="<?= htmlspecialchars($student['thang'] ?? '') ?>">
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label for="newRoomPrice">Giá phòng</label>
                                                            <input type="text" class="form-control" id="newRoomPrice"
                                                                name="newRoomPrice"
                                                                value="<?= htmlspecialchars($student['gia_phong'] ?? '') ?>">
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label for="newCleaningFee">Phí dọn dẹp</label>
                                                            <input type="text" class="form-control" id="newCleaningFee"
                                                                name="newCleaningFee"
                                                                value="<?= htmlspecialchars($student['phi_don_dep'] ?? '') ?>">
                                                        </div>

                                                        <div class="form-group mb-3 mt-4">
                                                            <button type="submit" class="btn btn-success">Cập nhật thông
                                                                tin hóa đơn phòng</button>
                                                        </div>
                                                    </form>
                                                    <?php else: ?>
                                                    <p>Không tìm thấy thông tin hóa đơn phòng!</p>
                                                    <?php endif; ?>
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