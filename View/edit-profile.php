<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

require_once '../config/database.php'; 
require_once '../Controller/ProfileController.php';
$database = new Database();
$conn = $database->connect();
$userId = $_SESSION['login']; 

if (isset($_SESSION['user_data'])) {
    $user_data = $_SESSION['user_data'];
} else {
    echo "User data is missing from session!";
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $birthYear = $_POST['birthYear'];
    $gender = $_POST['gender'];
    $idNumber = $_POST['idNumber'];
    $hometown = $_POST['hometown'];
    $avatar = isset($_FILES['avatar']) ? $_FILES['avatar']['name'] : '';
    if ($avatar) {
        $targetDir = "./assets/images/";
        $targetFile = $targetDir . basename($_FILES["avatar"]["name"]);
        $check = getimagesize($_FILES["avatar"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            exit();
        }
        if ($_FILES["avatar"]["size"] > 2000000) {
            echo "File is too large.";
            exit();
        }

        move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFile);
    }

    $userData = [
        'ho_ten' => $fullName,
        'email' => $email,
        'so_dien_thoai' => $phone,
        'ngay_sinh' => $birthYear,
        'gioi_tinh' => $gender,
        'so_cmnd' => $idNumber,
        'que_quan' => $hometown,
        'avatar' => $avatar ? $avatar : '', 
    ];
    $profileController = new ProfileController($conn);
    $updateResult = $profileController->updateProfile($userData, $userId);

    if ($updateResult) {
        header('Location: profile.php?status=success');
        exit();
    } else {
        echo "Cập nhật thông tin thất bại!";
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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap-grid.min.css"
            integrity="sha512-i1b/nzkVo97VN5WbEtaPebBG8REvjWeqNclJ6AItj7msdVcaveKrlIIByDpvjk5nwHjXkIqGZscVxOrTb9tsMA=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="./assets/css/edit-profile.css">
        <title>Quản lý người dùng</title>
    </head>

    <body>
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
                                    <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile"
                                        role="tab" aria-controls="profile" aria-selected="true">Thông tin cá nhân</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="password-tab" data-bs-toggle="tab" href="#password"
                                        role="tab" aria-controls="password" aria-selected="false">Đổi mật khẩu</a>
                                </li>
                            </ul>
                            <hr>
                            <!-- Nội dung các tab -->
                            <div class="tab-content" id="myTabContent">
                                <!-- Tab Thông tin cá nhân -->
                                <div class="tab-pane fade show active" id="profile" role="tabpanel"
                                    aria-labelledby="profile-tab">
                                    <form action="" method="POST" enctype="multipart/form-data" class="w-50 mx-auto">
                                        <div class="form-group mb-3">
                                            <label for="name">Họ và tên</label>
                                            <input type="text" class="form-control" id="name" name="fullName"
                                                value="<?= htmlspecialchars($user_data['ho_ten'] ?? '') ?>">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="<?= htmlspecialchars($user_data['email'] ?? '') ?>">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="phone">Số điện thoại</label>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                value="<?= htmlspecialchars($user_data['so_dien_thoai'] ?? '') ?>">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="birthYear">Năm sinh</label>
                                            <input type="date" class="form-control" id="birthYear" name="birthYear"
                                                value="<?= htmlspecialchars($user_data['ngay_sinh'] ?? '') ?>">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="gender">Giới tính</label>
                                            <select name="gender" class="form-control form-select" required>
                                                <option value="male"
                                                    <?= isset($user_data['gioi_tinh']) && $user_data['gioi_tinh'] == 'Nam' ? 'selected' : '' ?>>
                                                    Nam</option>
                                                <option value="female"
                                                    <?= isset($user_data['gioi_tinh']) && $user_data['gioi_tinh'] == 'Nữ' ? 'selected' : '' ?>>
                                                    Nữ</option>
                                                <option value="other"
                                                    <?= isset($user_data['gioi_tinh']) && $user_data['gioi_tinh'] == 'Khác' ? 'selected' : '' ?>>
                                                    Khác</option>
                                            </select>
                                        </div>
                                        <!-- <div class="form-group mb-3">
                                            <label for="address">Địa chỉ</label>
                                            <input type="text" class="form-control" id="address" name="address"
                                                value="<?= htmlspecialchars($user_data['dia_chi'] ?? '') ?>">
                                        </div> -->
                                        <div class="form-group mb-3">
                                            <label for="idNumber">Số CCCD</label>
                                            <input type="text" class="form-control" id="idNumber" name="idNumber"
                                                value="<?= htmlspecialchars($user_data['so_cmnd'] ?? '') ?>">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="hometown">Quê quán</label>
                                            <input type="text" class="form-control" id="hometown" name="hometown"
                                                value="<?= htmlspecialchars($user_data['que_quan'] ?? '') ?>">
                                        </div>

                                        <div class="form-group mb-3">
                                            <div class="d-flex">
                                                <label for="avatar">Ảnh đại diện hiện tại</label>
                                                <!-- Thẻ img để hiển thị ảnh mới chọn -->
                                                <?php if (!empty($user_data['avatar'])): ?>
                                                <img src="./assets/images/<?php echo htmlspecialchars($user_data['avatar']); ?>"
                                                    alt="Avatar hiện tại" id="currentAvatar" class="current-user-image">
                                                <?php else: ?>
                                                <!-- If no avatar is available, use a default image -->
                                                <img src="./assets/images/student_profile.jpg" alt="Avatar hiện tại"
                                                    id="currentAvatar" class="current-user-image">
                                                <?php endif; ?>
                                            </div>
                                            <!-- Input để chọn file ảnh -->
                                            <input type="file" class="form-control mt-3" id="avatar" name="avatar"
                                                accept="image/*">
                                        </div>
                                        <div class="form-group mb-3 mt-4">
                                            <button type="submit" class="btn btn-success">Cập nhật thông tin cá
                                                nhân</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- Tab Đổi mật khẩu -->
                                <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                                    <form action="#" method="POST" class="w-50 mx-auto">
                                        <div class="form-group mb-3 position-relative">
                                            <label for="current-password">Mật khẩu hiện tại</label>
                                            <input type="password" class="form-control" id="current-password"
                                                name="current-password">
                                            <span class="show-pass-word">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                        </div>
                                        <div class="form-group mb-3 position-relative">
                                            <label for="new-password">Mật khẩu mới</label>
                                            <input type="password" class="form-control" id="new-password"
                                                name="new-password">
                                            <span class="show-pass-word">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                        </div>
                                        <div class="form-group mb-3 position-relative">
                                            <label for="confirm-password">Xác nhận mật khẩu mới</label>
                                            <input type="password" class="form-control" id="confirm-password"
                                                name="new-password_confirmation">
                                            <span class="show-pass-word">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                        </div>
                                        <div class="form-group mb-3 mt-4">
                                            <button type="submit" class="btn btn-success">Đổi mật khẩu</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
            integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
            integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
        </script>
        <script src="./assets/js/app.js"></script>

    </body>

</html>