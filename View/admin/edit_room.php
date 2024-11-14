<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: ../login.php');
    exit();
}
require_once '../../config/database.php';
require_once '../../Controller/admin/Room/RoomController.php';
require_once '../../Controller/admin/DashboardController.php';

$database = new Database();
$conn = $database->connect();
$roomController = new RoomController($conn);
if (isset($_GET['phong_id'])) {
    $phong_id = (int) $_GET['phong_id'];
    $student = $roomController->getRoomById($phong_id); 
}
$admins = $roomController->getAdmin();
if (isset($_GET['student_id'])) {
    $student_id = (int) $_GET['student_id'];
    $dashboardController = new DashboardController($conn);
    $student = $dashboardController->getStudentById($student_id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_profile'])) {
    $room_id = isset($_POST['phong_id']) ? (int) $_POST['phong_id'] : null;
    $roomCode = $_POST['roomCode'] ?? null;
    $floor = $_POST['floor'] ?? null;
    $area = $_POST['area'] ?? null;
    $capacity = $_POST['capacity'] ?? null;
    $description = $_POST['description'] ?? null;
    $status = $_POST['status'] ?? null;
    $staffId = $_POST['staffId'] ?? null;
    
    $fileName = null;
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $fileName = $_FILES['avatar']['name'];
    }

    $dashboardController = new RoomController($conn);
    $checkUpdate = $dashboardController->updateRoom($room_id, $roomCode, $floor, $area, $capacity, $description, $status, $staffId, $fileName);

    if ($checkUpdate) {
        header('Location: dashboard.php?tab=category');
        exit();
    } else {
        echo "Cập nhật thông tin phòng thất bại!";
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
                                                        aria-selected="true">Thông tin phòng</a>
                                                </li>

                                            </ul>
                                            <hr>
                                            <!-- Nội dung các tab -->
                                            <div class="tab-content" id="myTabContent">
                                                <div class="tab-pane fade show active" id="profile" role="tabpanel"
                                                    aria-labelledby="profile-tab">
                                                    <?php var_dump($student)?>
                                                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST"
                                                        enctype="multipart/form-data" class="w-50 mx-auto">
                                                        <input type="hidden" name="change_profile"
                                                            value="change_profile">
                                                        <input type="hidden" name="phong_id"
                                                            value="<?php echo($student['phong_id'])?>">
                                                        <div class="form-group mb-3">
                                                            <label for="name">Mã Phòng</label>
                                                            <input type="text" class="form-control" id="name"
                                                                name="roomCode"
                                                                value="<?= htmlspecialchars($student['ma_phong'] ?? '') ?>"
                                                                readonly>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="floor">Tầng</label>
                                                            <input type="number" class="form-control" id="floor"
                                                                name="floor"
                                                                value="<?= htmlspecialchars($student['tang'] ?? '') ?>">
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="area">Diện tích</label>
                                                            <input type="area" class="form-control" id="area"
                                                                name="area"
                                                                value="<?= htmlspecialchars($student['dien_tich'] ?? '') ?>">
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="capacity">Số người tối đa </label>
                                                            <input type="text" class="form-control" id="capacity"
                                                                name="capacity"
                                                                value="<?= htmlspecialchars($student['suc_chua_toi_da'] ?? '') ?>">
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="description">Mô tả</label>
                                                            <input type="text" class="form-control" id="description"
                                                                name="description"
                                                                value="<?= htmlspecialchars($student['mo_ta'] ?? '') ?>">
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="status">Trạng thái</label>
                                                            <select name="status" class="form-control" required>
                                                                <option value="available"
                                                                    <?= isset($student['trang_thai_phong']) && $student['trang_thai_phong'] == 'Nam' ? 'selected' : '' ?>>
                                                                    Còn chỗ</option>
                                                                <option value="unavailable"
                                                                    <?= isset($student['trang_thai_phong']) && $student['trang_thai_phong'] == 'Nữ' ? 'selected' : '' ?>>
                                                                    Hết chỗ</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="nhan_vien_phu_trach">Nhân Viên Phụ Trách</label>
                                                            <select name="staffId" id="nhan_vien_phu_trach"
                                                                class="form-control" required>
                                                                <?php foreach($admins as $admin): ?>
                                                                <option value="<?php echo ($admin['id']); ?>"
                                                                    <?php echo (isset($student['nhan_vien_phu_trach']) && $student['nhan_vien_phu_trach'] == $admin['id']) ? 'selected' : ''; ?>>
                                                                    <?php echo ($admin['ho_ten']); ?>
                                                                </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <div class="d-flex">
                                                                <label for="avatar">Ảnh phòng</label>
                                                                <?php if (!empty($student['anh_phong'])): ?>
                                                                <img src="../assets/images/room/<?php echo htmlspecialchars($student['anh_phong']); ?>"
                                                                    alt="Avatar hiện tại" id="currentAvatar"
                                                                    class="current-user-image">
                                                                <?php else: ?>
                                                                <img src="../assets/images/room/room1.jpg"
                                                                    alt="Avatar hiện tại" id="currentAvatar"
                                                                    class="current-user-image">
                                                                <?php endif; ?>
                                                            </div>
                                                            <input type="file" class="form-control mt-3 file-input"
                                                                id="avatar" name="avatar" accept="image/*">
                                                        </div>
                                                        <div class="form-group mb-3 mt-4">
                                                            <button type="submit" class="btn btn-success">Cập nhật thông
                                                                tin phòng</button>
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