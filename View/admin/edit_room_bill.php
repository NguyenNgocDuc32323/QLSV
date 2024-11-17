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
$student = null;

if (isset($_GET['room_bill_id'])) {
    $room_bill_id = (int) $_GET['room_bill_id'];
    $room_bill = $roomBillController->getRoomBillById($room_bill_id);
}   

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_room_bill'])) {
    $room_bill_id = isset($_POST['room_bill_id']) ? (int) $_POST['room_bill_id'] : null;
    $ten_phong = isset($_POST['id_phong']) ? $_POST['id_phong'] : null;
    $ngayThanhToan = isset($_POST['ngay_thanh_toan']) ? $_POST['ngay_thanh_toan'] : null;
    $so_nuoc_cu = isset($_POST['so_nuoc_cu']) ? $_POST['so_nuoc_cu'] : null;
    $so_nuoc_moi = isset($_POST['so_nuoc_moi'])? $_POST['so_nuoc_moi'] : null;
    $so_dien_cu = isset($_POST['so_dien_cu']) ? $_POST['so_dien_cu'] : null;
    $so_dien_moi = isset($_POST['so_dien_moi'])? $_POST['so_dien_moi'] : null;
    $ghiChu = isset($_POST['ghi_chu'])? $_POST['ghi_chu'] : null;
    if ($so_nuoc_moi < $so_nuoc_cu) {
        echo "Số nước mới không thể nhỏ hơn số nước cũ!";
        exit();
    }

    if ($so_dien_moi < $so_dien_cu) {
        echo "Số điện mới không thể nhỏ hơn số điện cũ!";
        exit();
    }
    $checkUpdate = $roomBillController->updateRoomBill($room_bill_id, $ten_phong, $so_nuoc_cu,$so_nuoc_moi,$so_dien_cu,$so_dien_moi,$ngayThanhToan,$ghiChu);
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
                                                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST"
                                                    enctype="multipart/form-data" class="w-50 mx-auto">
                                                    <input type="hidden" name="edit_room_bill" value="edit_room_bill">
                                                    <input type="hidden" name="room_bill_id" value="<?php echo $room_bill['hoa_don_id']?>">
                                                    <div class="form-group mb-3">
                                                        <label for="ten_phong">Tên Phòng</label>
                                                        <input type="hidden" value="<?php echo $room_bill['id_phong']?>" name="id_phong" id="id_phong" readonly >
                                                        <input type="text" class="form-control" value="<?php echo $room_bill['ten_phong']?>" name="ten_phong" id="ten_phong" readonly>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="so_nuoc_cu">Số Nước Cũ</label>
                                                        <input type="number" class="form-control" id="so_nuoc_cu"
                                                            name="so_nuoc_cu" required value="<?php echo $room_bill['so_nuoc_cu']?>">
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="so_dien_cu">Số Nước Mới</label>
                                                        <input type="number" class="form-control" id="so_nuoc_moi"
                                                            name="so_nuoc_moi" required value="<?php echo $room_bill['so_nuoc_moi']?>">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="so_dien_cu">Số Điện Cũ</label>
                                                        <input type="number" class="form-control" id="so_dien_cu"
                                                            name="so_dien_cu" required value="<?php echo $room_bill['so_dien_cu']?>">
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="so_dien_moi">Số Điện Mới</label>
                                                        <input type="number" class="form-control" id="so_dien_moi"
                                                            name="so_dien_moi" required value="<?php echo $room_bill['so_dien_moi']?>">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="ngay_thanh_toan">Ngày Thanh Toán</label>
                                                        <input type="date" class="form-control" id="ngay_thanh_toan"
                                                            name="ngay_thanh_toan" required value="<?php echo $room_bill['ngay_thanh_toan']?>">
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="ngay_thanh_toan">Ghi Chú</label>
                                                        <textarea name="ghi_chu" class="form-control"
                                                            rows="5"><?php echo $room_bill['ghi_chu']?></textarea>
                                                    </div>
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
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js">
            </script>
            <script src="../assets/js/app.js"></script>
            <script src="../assets/js/admin-dashboard.js"></script>
</body>

</html>