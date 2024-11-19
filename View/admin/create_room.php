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
require_once '../../Controller/admin/Room/RoomController.php';
$database = new Database();
$conn = $database->connect();
$roomController = new RoomController($conn);
$admins = $roomController->getAdmin();
if (isset($_GET['student_id'])) {
    $student_id = (int) $_GET['student_id'];
    $dashboardController = new DashboardController($conn);
    $student = $dashboardController->getStudentById($student_id);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_room'])) {
    $ma_phong = $_POST['ma_phong'] ?? null;
    $tang = $_POST['tang'] ?? null;
    $dien_tich = $_POST['dien_tich'] ?? null;
    $suc_chua_toi_da = $_POST['suc_chua_toi_da'] ?? null;
    $mo_ta = $_POST['mo_ta'] ?? null;
    $trang_thai = $_POST['trang_thai'] ?? null;
    $nhan_vien_phu_trach = $_POST['nhan_vien_phu_trach'] ?? null;
    $fileName = null;
    if (isset($_FILES['anh_phong']) && $_FILES['anh_phong']['error'] === UPLOAD_ERR_OK) {
        $fileName = $_FILES['anh_phong']['name'];
    }
    $check_add_room = $roomController->createRoom($ma_phong, $tang, $dien_tich, $suc_chua_toi_da, $mo_ta, $trang_thai, $nhan_vien_phu_trach, $fileName);
    if ($check_add_room) {
        header('Location: dashboard.php?tab=category');
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
                            <h1 class="fw-semibold mb-0 body-title text-white">Thêm Phòng</h1>
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
                                                    <div class="form-group mb-3">
                                                        <label for="anh_phong">Ảnh Phòng</label>
                                                        <input type="file" class="form-control" id="anh_phong" name="anh_phong">
                                                        <?php if (isset($fileName) && !empty($fileName)): ?>
                                                            <img src="<?php echo htmlspecialchars($fileName); ?>" alt="User Selected Image" />
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="ma_phong">Mã Phòng</label>
                                                        <input type="text" class="form-control" id="ma_phong" name="ma_phong">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="tang">Tầng</label>
                                                        <select name="tang" id="tang" class="form-control">
                                                            <option value="1">Tầng 1</option>
                                                            <option value="2">Tầng 2</option>
                                                            <option value="3">Tầng 3</option>
                                                            <option value="4">Tầng 4</option>
                                                            <option value="5">Tầng 5</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="dien_tich">Diện Tích</label>
                                                        <input type="text" class="form-control" id="dien_tich" name="dien_tich"
                                                            value="">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="suc_chua_toi_da">Sức Chứa Tối Đa</label>
                                                        <select name="suc_chua_toi_da" id="suc_chua_toi_da" class="form-control">
                                                            <option value="1">1 Người</option>
                                                            <option value="2">2 Người</option>
                                                            <option value="3">3 Người</option>
                                                            <option value="4">4 Người</option>
                                                            <option value="5">5 Người</option>
                                                            <option value="6">6 Người</option>
                                                            <option value="7">7 Người</option>
                                                            <option value="8">8 Người</option>
                                                            <option value="9">9 Người</option>
                                                            <option value="10">10 Người</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="mo_ta">Mô Tả</label>
                                                        <textarea name="mo_ta" id="mo_ta" class="form-control"></textarea>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="trang_thai">Trạng Thái</label>
                                                        <select name="trang_thai" id="trang_thai" class="form-control">
                                                            <option value="1">Còn Chỗ</option>
                                                            <option value="2">Hết Chỗ</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="nhan_vien_phu_trach">Nhân Viên Phụ Trách</label>
                                                        <select name="nhan_vien_phu_trach" id="nhan_vien_phu_trach" class="form-control">
                                                        <?php foreach($admins as $admin): ?>
                                                            <option value="<?php echo ($admin['id']); ?>">
                                                                <?php echo ($admin['ho_ten']); ?>
                                                            </option>
                                                        <?php endforeach; ?>


                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-3 mt-4">
                                                        <button type="submit" class="btn btn-success">Tạo Mới Phòng</button>
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

            
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
            <script src="../assets/js/app.js"></script>
            <script src="../assets/js/admin-dashboard.js"></script>
</body>

</html>