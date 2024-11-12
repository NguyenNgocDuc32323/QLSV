<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../../config/database.php';
require_once '../../Controller/admin/DashboardController.php' ;

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

if (isset($_SESSION['vai_tro']) && $_SESSION['vai_tro'] !== 'Quan Tri Vien') {
    header('Location: login.php');
    exit();
}

$database = new Database();
$conn = $database->connect();
$dashboardController = new DashboardController($conn);
$result = $dashboardController->showProfile();
$room = $dashboardController->showRoom();
$contract = $dashboardController->showContract();
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
        <div class="sidebar text-white position-fixed vh-100 p-3">
            <a href="../../View/admin/dashboard.php" class="d-block text-center my-3">
                <img src="../assets/images/logo.webp" alt="site logo" class="img-fluid" style="max-height: 80px" />
            </a>
            <nav class="sidebar-menu-area">
                <ul class="nav flex-column" id="sidebar-menu">
                    <li class="nav-item bg-success" id="tab-product">
                        <a href="#" class="nav-link text-white">
                            <i class="fa-solid fa-user me-2"></i>
                            Sinh Viên
                        </a>
                    </li>
                    <li class="nav-item bg-success" id="tab-category">
                        <a href="#" class="nav-link text-white">
                            <i class="fa-solid fa-list me-2"></i>
                            Phòng
                        </a>
                    </li>
                    <li class="nav-item bg-success" id="tab-account">
                        <a href="#" class="nav-link text-white">
                            <i class="fa-solid fa-bottle-water me-2"></i>
                            Hợp Đồng
                        </a>
                    </li>
                    <li class="nav-item bg-success" id="tab-transaction">
                        <a href="#" class="nav-link text-white">
                            <i class="fa-solid fa-cart-shopping me-2"></i>
                            Hóa Đơn Phòng
                        </a>
                    </li>
                    <li class="nav-item bg-success" id="tab-delivery">
                        <a href="#" class="nav-link text-white">
                            <i class="fa-solid fa-truck-fast me-2"></i>
                            Hóa Đơn Điện Nước
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="dashboard-main">
            <div class="d-flex align-items-center justify-content-between p-4">
                <i class="fa-solid fa-bars btn btn-success btn-navbar"></i>
                <div class="user-infor">
                    <img src="./asset/images/admin.png" alt="" class="img-fluid admin-logo" />
                </div>
                <div class="content">
                    <div id="content-product" class="content-tab">
                        <div class="p-4 main-content bg-success">
                            <!-- <?php
                                var_dump($result); ?> -->
                            <div class="d-flex align-items-center justify-content-between manage-prd-title">

                                <h1 class="text-white fw-bold p-3 fw-700">Quản Lý Sản Phẩm</h1>
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

                                                </td>
                                                <td><?php echo htmlspecialchars($row['ho_ten']); ?></td>
                                                <td><?php echo htmlspecialchars($row['ngay_sinh']); ?></td>
                                                <td><?php echo htmlspecialchars($row['so_cmnd']); ?></td>
                                                <td class="bg-success text-white">
                                                    <?php echo htmlspecialchars($row['gioi_tinh']); ?></td>
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
                            <ul class="to-top-list">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="../../View/index.php">
                                        <i class="icon-user-item fa-regular fa-user"></i> <span>Trang Người Dùng</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="../../Controller/logoutController.php">
                                        <i class="icon-user-item fa-solid fa-power-off"></i><span>Đăng Xuất</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content">
                <div id="content-product" class="content-tab">
                    <div class="p-4 main-content bg-success">
                        <div class="d-flex align-items-center justify-content-between manage-prd-title">
                            <h1 class="text-white fw-bold p-3 fw-700">Quản Lý Sản Phẩm</h1>
                            <button class="btn btn-success bg-white btn-add-prd">
                                Thêm Sản Phẩm
                            </button>
                        </div>
                        <hr />
                        <div class="p-3 bg-white h-100 prd-list">
                            <div class="table-responsive">
                                <table class="table text-center">
                                    <thead>
                                        <tr>
                                            <th>Mã Sinh Viên</th>
                                            <th>Ảnh</th>
                                            <th>Họ Và Tên</th>
                                            <th>Giá</th>
                                            <th>Giá Khuyến Mại</th>
                                            <th>Danh Mục</th>
                                            <th>Tình Trạng</th>
                                            <th>Hot</th>
                                            <th>Mới Về</th>
                                            <th class="prd-desc">Mô Tả</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>6</td>
                                            <td>
                                                <img src="../assets/images/admin/room/room_1.jpg"
                                                    alt="Product Image" />
                                            </td>
                                            <td>Áo Sơ Mi Nam</td>
                                            <td>300,000 VND</td>
                                            <td>250,000 VND</td>
                                            <td class="bg-success text-white">Còn Hàng</td>
                                            <td><i class="fa fa-times" style="color: red;"></i></td>
                                            <td>Mới</td>
                                            <td><i class="fa fa-check" style="color: green;"></i></td>
                                            <td class="prd-desc">
                                                Áo sơ mi nam kiểu dáng hiện đại, chất liệu cotton mềm mại,
                                                dễ chịu. Phù hợp với nhiều dịp, từ công sở đến gặp gỡ bạn
                                                bè.
                                            </td>
                                            <td class="d-flex align-items-center">
                                                <button class="btn btn-success merge">Sửa</button>
                                                <button class="btn btn-warning">Xóa</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="content-category" class="content-tab p-4" style="display: none;">
                        <div class="p-4 main-content bg-success">
                            <?php
                                // var_dump($room); ?>
                            <div class="d-flex align-items-center justify-content-between manage-prd-title">

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

                                                </td>
                                                <td><?php echo htmlspecialchars($row['ho_ten']); ?></td>
                                                <td><?php echo htmlspecialchars($row['ngay_sinh']); ?></td>
                                                <td><?php echo htmlspecialchars($row['so_cmnd']); ?></td>
                                                <td class="bg-success text-white">
                                                    <?php echo htmlspecialchars($row['gioi_tinh']); ?></td>
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

                    <div id="content-account" class="content-tab p-4" style="display: none;">
                        <div class="p-4 main-content bg-success">
                            <!-- <?php
                                var_dump($result); ?> -->
                            <div class="d-flex align-items-center justify-content-between manage-prd-title">

                                <h1 class="text-white fw-bold p-3 fw-700">Quản Lý Hợp Đồng</h1>
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

                                                </td>
                                                <td><?php echo htmlspecialchars($row['ho_ten']); ?></td>
                                                <td><?php echo htmlspecialchars($row['ngay_sinh']); ?></td>
                                                <td><?php echo htmlspecialchars($row['so_cmnd']); ?></td>
                                                <td class="bg-success text-white">
                                                    <?php echo htmlspecialchars($row['gioi_tinh']); ?></td>
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

                    <div id="content-transaction" class="content-tab p-4" style="display: none;">
                        <div class="p-4 main-content bg-success">
                            <!-- <?php
                                var_dump($result); ?> -->
                            <div class="d-flex align-items-center justify-content-between manage-prd-title">

                                <h1 class="text-white fw-bold p-3 fw-700">Quản Lý Hóa Đơn Phòng</h1>
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

                                                </td>
                                                <td><?php echo htmlspecialchars($row['ho_ten']); ?></td>
                                                <td><?php echo htmlspecialchars($row['ngay_sinh']); ?></td>
                                                <td><?php echo htmlspecialchars($row['so_cmnd']); ?></td>
                                                <td class="bg-success text-white">
                                                    <?php echo htmlspecialchars($row['gioi_tinh']); ?></td>
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

                    <div id="content-delivery" class="content-tab p-4" style="display: none;">
                        <div class="p-4 main-content bg-success">
                            <!-- <?php
                                var_dump($result); ?> -->
                            <div class="d-flex align-items-center justify-content-between manage-prd-title">

                                <h1 class="text-white fw-bold p-3 fw-700">Quản Lý Hóa Đơn Điện Nước</h1>
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

                                                </td>
                                                <td><?php echo htmlspecialchars($row['ho_ten']); ?></td>
                                                <td><?php echo htmlspecialchars($row['ngay_sinh']); ?></td>
                                                <td><?php echo htmlspecialchars($row['so_cmnd']); ?></td>
                                                <td class="bg-success text-white">
                                                    <?php echo htmlspecialchars($row['gioi_tinh']); ?></td>
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
        </div>

    </div>
    </div>
    <script src="../assets/js/admin-dashboard.js"></script>
</body>

</html>