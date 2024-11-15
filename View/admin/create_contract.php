<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: ../login.php');
    exit();
}
require_once '../../config/database.php';
require_once '../../Controller/admin/DashboardController.php';
require_once '../../Controller/admin/contract/ContractController.php';
$database = new Database();
$conn = $database->connect();
$contractController = new ContractController($conn);
$students = $contractController->getUser();
$rooms = $contractController->getRoom();
$contractController->createContract();


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
                                <h1 class="fw-semibold mb-0 body-title text-white">Thêm Hợp Đồng</h1>
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
                                                        <label for="studentId">Mã sinh viên</label>
                                                        <select name="student_id" id="studentId" class="form-control"
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
                                                        <select name="room_id" id="roomId" class="form-control"
                                                            required>
                                                            <?php foreach ($rooms as $room): ?>
                                                            <option value="<?php echo ($room['id']); ?>"
                                                                <?php echo (isset($contract['room_code']) && $contract['room_code'] == $room['ma_phong']) ? 'selected' : ''; ?>>
                                                                <?php echo ($room['ma_phong']); ?>
                                                            </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="price">Giá</label>
                                                        <input type="number" class="form-control" id="price"
                                                            name="price" value="" min="0" required>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="waterPrice">Giá nước</label>
                                                        <input type="number" class="form-control" id="waterPrice"
                                                            name="water_price" value="" min="0" required>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="electricityPrice">Giá điện</label>
                                                        <input type="number" class="form-control" id="electricityPrice"
                                                            name="electricity_price" value="" min="0" required>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="cleaningPrice">Giá vệ sinh chung</label>
                                                        <input type="number" class="form-control" id="cleaningPrice"
                                                            name="cleaning_price" value="" min="0" required>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="deposit">Tiền đặt cọc</label>
                                                        <input type="number" class="form-control" id="deposit"
                                                            name="deposit" value="" min="0" required>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="contractDate">Ngày ký hợp đồng</label>
                                                        <input type="date" class="form-control" id="contractDate"
                                                            name="contract_date" value="" required>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="startDate">Ngày bắt đầu</label>
                                                        <input type="date" class="form-control" id="startDate"
                                                            name="start_date" value="" required>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="endDate">Ngày kết thúc</label>
                                                        <input type="date" class="form-control" id="endDate"
                                                            name="end_date" value="" required>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="depositDate">Ngày đặt cọc</label>
                                                        <input type="date" class="form-control" id="depositDate"
                                                            name="deposit_date" value="" required>
                                                    </div>

                                                    <div class="form-group mb-3 mt-4">
                                                        <button type="submit" class="btn btn-success">Tạo Mới
                                                            Phòng</button>
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