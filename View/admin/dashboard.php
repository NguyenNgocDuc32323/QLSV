<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../../config/database.php';
require_once '../../Controller/admin/DashboardController.php';
require_once '../../Controller/admin/Room/RoomController.php';

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: ../login.php');

    exit();
}

if (isset($_SESSION['vai_tro']) && $_SESSION['vai_tro'] !== 'Quan Tri Vien') {
    header('Location: ../index.php');

    exit();
}
$database = new Database();
$conn = $database->connect();
$dashboardController = new DashboardController($conn);
$roomController = new RoomController($conn);
$result = $dashboardController->showProfile();
$room = $dashboardController->showRoom();
$contract = $dashboardController->showContract();
$getallRoom = $roomController -> getAllRoom();

if (isset($_GET['search_rooms'])) {
    $searchString = htmlspecialchars($_GET['search_rooms']);
    $searchRoom = $roomController->searchRoom($searchString);
}

if (isset($_GET['search_student'])) {
    $searchString = htmlspecialchars($_GET['search_student']);
    $searchStudents = $dashboardController->searchStudent($searchString);
}
if (isset($_GET['delete_student_id'])) {
    $student_id = $_GET['delete_student_id'];
    $get_contracts = mysqli_query($conn, "SELECT id FROM hopdong WHERE id_hoc_sinh = $student_id");
    while ($contract = mysqli_fetch_assoc($get_contracts)) {
        $contract_id = $contract['id'];
        $delete_invoice_details = mysqli_query($conn, "DELETE FROM chitiethoadon WHERE id_hoa_don IN (SELECT id FROM hoadon WHERE id_hop_dong = $contract_id)");
        $delete_invoices = mysqli_query($conn, "DELETE FROM hoadon WHERE id_hop_dong = $contract_id");
        $delete_payments = mysqli_query($conn, "DELETE FROM thanhtoan WHERE id_hop_dong = $contract_id");
    }
    $delete_contract = mysqli_query($conn, "DELETE FROM hopdong WHERE id_hoc_sinh = $student_id");

    if ($delete_contract) {
        $get_user_id = mysqli_query($conn, "SELECT id_nguoi_dung FROM hocsinh WHERE id = $student_id");
        $user = mysqli_fetch_assoc($get_user_id);
        if ($user) {
            $user_id = $user['id_nguoi_dung'];
            $delete_user = mysqli_query($conn, "DELETE FROM nguoidung WHERE id = $user_id");
        }
        $delete_student = mysqli_query($conn, "DELETE FROM hocsinh WHERE id = $student_id");

        if ($delete_student) {
            echo "Deleted student and related user successfully!";
            header("Location:../../View/admin/dashboard.php");
            exit();
        } else {
            echo "Failed to delete student!";
            header("Location:../../View/admin/dashboard.php");
            exit();
        }
    } else {
        echo "Failed to delete contract records!";
        header("Location:../../View/admin/dashboard.php");
        exit();
    }
}

if (isset($_GET['delete_room_id'])) {
    $room_id = $_GET['delete_room_id'];

    // Delete related payment records
    $delete_payments = mysqli_query($conn, "
        DELETE FROM thanhtoan 
        WHERE id_hoa_don IN (SELECT id FROM hoadon WHERE id_phong = $room_id)
    ");

    // Delete related invoice details
    $delete_invoice_details = mysqli_query($conn, "
        DELETE FROM chitiethoadon 
        WHERE id_hoa_don IN (SELECT id FROM hoadon WHERE id_phong = $room_id)
    ");

    // Delete related invoices
    $delete_invoices = mysqli_query($conn, "
        DELETE FROM hoadon 
        WHERE id_phong = $room_id
    ");

    // Delete contracts related to the room
    $delete_contracts = mysqli_query($conn, "
        DELETE FROM hopdong WHERE id_phong = $room_id
    ");

    // Delete the room itself
    $delete_room = mysqli_query($conn, "
        DELETE FROM phong WHERE id = $room_id
    ");

    // Check if room deletion was successful
    if ($delete_room) {
        echo "Deleted room and related information successfully!";
        header("Location: ../../View/admin/dashboard.php");
        exit();
    } else {
        echo "Failed to delete room!";
        header("Location: ../../View/admin/dashboard.php");
        exit();
    }
} 

?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Document</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
            integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="../assets/css/admin-dashboard.css" />
    </head>

    <body>
        <div class="d-flex align-items-center">
            <?php include '../admin/sidebar.php'; ?>
            <div class="dashboard-main">
                <?php include '../admin/navbar.php'; ?>
                <div class="content">
                    <div id="content-product" class="content-tab">
                        <div class="p-4 main-content bg-success">
                            <div class="d-flex align-items-center justify-content-between manage-prd-title">
                                <h1 class="text-white fw-bold p-3 fw-700">Quản Lý Sinh Viên</h1>
                            </div>
                            <div class="p-3 bg-white h-100 prd-list">
                                <div class="d-flex justify-content-between align-items-center mb-3 card-body-item">
                                    <div></div>
                                    <form id="order-listing_filter" class="dataTables_filter" method="GET"
                                        action="<?= $_SERVER['PHP_SELF'] ?>">
                                        <input type="text" id="search_student" name="search_student"
                                            class="form-control" placeholder="Search" value="<?php if (isset($_GET['search_student'])) {
                                                    echo htmlspecialchars($_GET['search_student']);
                                                } ?>">
                                        <button type="submit" class="btn-search">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="table-responsive">
                                    <table class="table text-center">
                                        <thead>
                                            <tr>
                                                <th>Mã Sinh Viên</th>
                                                <th>Avatar</th>
                                                <th>Họ Và Tên</th>
                                                <th>Năm sinh</th>
                                                <th>CCCD</th>
                                                <th>Giới Tính</th>
                                                <th>Email</th>
                                                <th>Số Điện Thoại</th>
                                                <th>Quê Quán</th>
                                                <th>Hành Động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($searchStudents) && !empty($searchStudents)): ?>
                                            <?php foreach ($searchStudents as $row): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['ma_sinh_vien']); ?></td>
                                                <td>
                                                    <img src="<?php echo $row['avatar'] ? '../assets/images/avatar/' . $row['avatar'] : '../assets/images/avatar/student_avatar.png'; ?>"
                                                        alt="Product Image" />
                                                </td>
                                                <td class="fw-bold table-name">
                                                    <?php echo htmlspecialchars($row['ho_ten']); ?></td>
                                                <td><?php echo htmlspecialchars($row['ngay_sinh']); ?></td>
                                                <td><?php echo $row['so_cmnd'] ? $row['so_cmnd'] : ""; ?></td>
                                                <td><?php echo htmlspecialchars($row['gioi_tinh']); ?></td>
                                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                <td><?php echo htmlspecialchars($row['so_dien_thoai'] ?: ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['que_quan'] ?: ''); ?></td>
                                                <td class="d-flex align-items-center btn-dashboard-block">
                                                    <a href="edit_student.php?student_id=<?php echo $row['hoc_sinh_id']; ?>"
                                                        class="btn btn-success merge">Sửa</a>
                                                    <a href="dashboard.php?delete_student_id=<?php echo $row['hoc_sinh_id'] ?>"
                                                        class="btn btn-warning delete-btn-student">Xóa</a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php elseif (isset($searchStudents) && empty($searchStudents)): ?>
                                            <tr>
                                                <td colspan="10">Không có dữ liệu tìm kiếm.</td>
                                            </tr>
                                            <?php elseif (!empty($result)): ?>
                                            <?php foreach ($result as $row): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['ma_sinh_vien']); ?></td>
                                                <td>
                                                    <img src="<?php echo $row['avatar'] ? '../assets/images/avatar/' . $row['avatar'] : '../assets/images/avatar/student_avatar.png'; ?>"
                                                        alt="Product Image" />
                                                </td>
                                                <td class="fw-bold table-name">
                                                    <?php echo htmlspecialchars($row['ho_ten']); ?></td>
                                                <td><?php echo htmlspecialchars($row['ngay_sinh']); ?></td>
                                                <td><?php echo $row['so_cmnd'] ? $row['so_cmnd'] : "" ?></td>
                                                <td><?php echo htmlspecialchars($row['gioi_tinh']); ?></td>
                                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                <td><?php echo htmlspecialchars($row['so_dien_thoai'] ?: ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['que_quan'] ?: ''); ?></td>
                                                <td class="d-flex align-items-center btn-dashboard-block">
                                                    <a href="edit_student.php?student_id=<?php echo $row['hoc_sinh_id']; ?>"
                                                        class="btn btn-success merge">Sửa</a>
                                                    <a href="dashboard.php?delete_student_id=<?php echo $row['hoc_sinh_id'] ?>"
                                                        class="btn btn-warning delete-btn-student">Xóa</a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php else: ?>
                                            <tr>
                                                <td colspan="10">Không có dữ liệu.</td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <ul class="to-top-list">

                        </div>
                    </div>
                    <div id="content-category" class="content-tab" style="display: none;">
                        <div class="p-4 main-content bg-success">
                            <div class="d-flex align-items-center justify-content-between manage-prd-title">
                                <h1 class="text-white fw-bold p-3 fw-700">Quản Lý Phòng</h1>
                                <a href="create_room.php" class="btn bg-white btn-add-admin">Thêm Phòng</a>
                            </div>
                            <div class="p-3 bg-white h-100 prd-list">
                                <div class="d-flex justify-content-between align-items-center mb-3 card-body-item">
                                    <div></div>
                                    <form id="order-listing_filter" class="dataTables_filter" method="GET"
                                        action="dashboard.php">
                                        <input type="hidden" name="tab" value="category">
                                        <input type="text" id="search_rooms" name="search_rooms" class="form-control"
                                            placeholder="Search" value="<?php if (isset($_GET['search_rooms'])) {
                                                echo htmlspecialchars($_GET['search_rooms']);
                                            } ?>">
                                        <button type="submit" class="btn-search">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </button>
                                    </form>

                                </div>
                                <div class="table-responsive">
                                    <table class="table text-center">
                                        <thead>
                                            <tr>
                                                <th>Mã Phòng</th>
                                                <th>Ảnh phòng</th>
                                                <th>Số tầng</th>
                                                <th>Diện tích</th>
                                                <th>Số người tối đa </th>
                                                <th>Mô tả</th>
                                                <th>Trạng thái</th>
                                                <th>Nhân viên phụ trách</th>
                                                <th>Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($searchRoom) && !empty($searchRoom)): ?>
                                            <?php foreach ($searchRoom as $row): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['ma_phong']); ?></td>
                                                <td>
                                                    <?php if (!empty($row['anh_phong']) && $row['anh_phong'] !== null): ?>
                                                    <img src="<?php echo '../assets/images/room/' . htmlspecialchars($row['anh_phong']); ?>"
                                                        alt="Product Image" />
                                                    <?php endif; ?>


                                                </td>
                                                <td class="fw-bold table-name">
                                                    <?php echo htmlspecialchars($row['tang']); ?></td>
                                                <td><?php echo htmlspecialchars($row['dien_tich']); ?></td>
                                                <td><?php echo $row['suc_chua_toi_da'] ? $row['suc_chua_toi_da'] : ""; ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['mo_ta']); ?></td>
                                                <td><?php echo htmlspecialchars($row['trang_thai_phong']); ?></td>
                                                <td><?php echo htmlspecialchars($row['ten_nhan_vien'] ?: ''); ?>
                                                </td>

                                                <td class="d-flex align-items-center btn-dashboard-block">
                                                    <a href="edit_student.php?phong_id=<?php echo $row['phong_id']; ?>"
                                                        class="btn btn-success merge">Sửa</a>
                                                    <a href="dashboard.php?delete_room_id=<?php echo $row['phong_id'] ?>"
                                                        class="btn btn-warning delete-btn-student">Xóa</a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php elseif (isset($searchRoom) && empty($searchRoom)): ?>
                                            <tr>
                                                <td colspan="10">Không có dữ liệu tìm kiếm.</td>
                                            </tr>
                                            <?php elseif (!empty($getallRoom)): ?>
                                            <?php foreach ($getallRoom as $row): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['ma_phong']); ?></td>
                                                <td>
                                                    <?php if (!empty($row['anh_phong']) && $row['anh_phong'] !== null): ?>
                                                    <img src="<?php echo '../assets/images/room/' . htmlspecialchars($row['anh_phong']); ?>"
                                                        alt="Product Image" />
                                                    <?php endif; ?>
                                                </td>
                                                <td class="fw-bold table-name">
                                                    <?php echo htmlspecialchars($row['tang']); ?></td>
                                                <td><?php echo htmlspecialchars($row['dien_tich']); ?></td>
                                                <td><?php echo $row['suc_chua_toi_da'] ? $row['suc_chua_toi_da'] : ""; ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['mo_ta']); ?></td>
                                                <td><?php echo htmlspecialchars($row['trang_thai_phong']); ?></td>
                                                <td><?php echo htmlspecialchars($row['ho_ten'] ?: ''); ?>
                                                </td>

                                                <td class="d-flex align-items-center btn-dashboard-block">
                                                    <a href="edit_room.php?phong_id=<?php echo $row['phong_id']; ?>"
                                                        class="btn btn-success merge">Sửa</a>
                                                    <a href="dashboard.php?phong_id="
                                                        class="btn btn-warning delete-btn-student">Xóa</a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php else: ?>
                                            <tr>
                                                <td colspan="10">Không có dữ liệu.</td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <ul class="to-top-list">

                        </div>
                    </div>
                    <div id="content-category" class="content-tab" style="display: none;">
                        <div class="p-4 main-content bg-success">
                            <div class="d-flex align-items-center justify-content-between manage-prd-title">

                                <div id="content-account" class="content-tab p-4" style="display: none;">
                                    <h1>Quản Lý Tài Khoản</h1>
                                </div>
                                <h1 class="text-white fw-bold p-3 fw-700">Quản Lý Phòng</h1>
                                <button class="btn btn-success bg-white btn-add-prd">
                                    Thêm Sản Phẩm
                                </button>
                            </div>
                            <hr />
                            <div class="input-group">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="search" id="form1" class="form-control" />
                                    <label class="form-label" for="form1">Search</label>
                                </div>
                                <button type="button" class="btn btn-primary" data-mdb-ripple-init>
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="p-3 bg-white h-100 prd-list">
                                <div class="table-responsive">
                                    <table class="table text-center">
                                        <thead>
                                            <tr>
                                                <th>Mã Sinh Viên</th>
                                                <th>Avatar</th>
                                                <th>Họ Và Tên</th>
                                                <th>Năm sinh</th>
                                                <th>CCCD</th>
                                                <th>Giới Tính</th>
                                                <th>Email</th>
                                                <th>Số Điện Thoại</th>
                                                <th>Quê Quán</th>
                                                <th>Hành Động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($result)): ?>
                                            <?php foreach ($result as $row): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['ma_sinh_vien']); ?></td>
                                                <td>
                                                    <img src="<?php echo '../assets/images/avatar/' . ($row['avatar'] ? ltrim($row['avatar'], './') : '../../View/assets/images/avatar/student_avatar.png'); ?>"
                                                        alt="Product Image" />

                                                    <div id="content-transaction" class="content-tab p-4"
                                                        style="display: none;">
                                                        <h1>Quản Lý Giao Dịch</h1>
                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['ho_ten']); ?></td>
                                                <td><?php echo htmlspecialchars($row['ngay_sinh']); ?></td>
                                                <td><?php echo htmlspecialchars($row['so_cmnd']); ?></td>
                                                <td>
                                                    <?php echo htmlspecialchars($row['gioi_tinh']); ?>
                                                </td>
                                                <td>
                                                    <?php echo htmlspecialchars($row['email']); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['so_dien_thoai'] ?: 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($row['que_quan'] ?: 'N/A'); ?></td>
                                                <td class="d-flex align-items-center">
                                                    <button class="btn btn-success merge">Sửa</button>
                                                    <button class="btn btn-warning">Xóa</button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php else: ?>
                                            <tr>
                                                <td colspan="10">Không có dữ liệu.</td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <script src="../assets/js/admin-dashboard.js"></script>

    </body>

</html>