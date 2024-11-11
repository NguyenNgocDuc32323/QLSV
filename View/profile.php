<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';
require_once '../Controller/ProfileController.php';
$database = new Database();
$conn = $database->connect();
$profileController = new ProfileController($conn);
$result = $profileController->showProfile(); 
// var_dump($result);

if ($result) {
     $user_data = $_SESSION['user_data'] = $result;
} else {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="assets/css/profile.css">
        <title>Profile</title>
    </head>

    <body>
        <div class="std-infor">
            <div class="border rounded-5">
                <section class="custom-section">
                    <div class="row d-flex justify-content-center">
                        <div class="col col-lg-7 mb-4 mb-lg-0">
                            <div class="card custom-card">
                                <div class="row g-0">
                                    <div class="col-md-4 gradient-custom text-center text-white custom-gradient-column">
                                        <?php if (!empty($user_data['avatar'])): ?>
                                        <img src="./assets/images/avatar/<?php echo htmlspecialchars($user_data['avatar']); ?>"
                                            alt="avatar" class="img-fluid mt-5 mb-4">
                                        <?php else: ?>
                                        <img src="./assets/images/student_profile.jpg" alt="avatar"
                                            class="img-fluid mt-5 mb-4">
                                        <?php endif; ?>
                                        <h5><?php echo htmlspecialchars($user_data['ho_ten']); ?></h5>
                                        <a href="edit-profile.php" class="text-white"><i
                                                class="far fa-edit mb-5"></i></a>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-center infor-block">
                                                <h6>Thông Tin Học Sinh</h6>
                                                <a href="edit-profile.php" class="ms-auto">
                                                    <i class="far fa-edit"></i>
                                                </a>

                                            </div>
                                            <hr class="mt-0 mb-4">
                                            <div class="row pt-1">
                                                <div class="col-6 mb-3">
                                                    <h6>Họ Và Tên</h6>
                                                    <p class="text-muted">
                                                        <?php echo htmlspecialchars($user_data['ho_ten']); ?></p>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <h6>Mã Học Sinh</h6>
                                                    <p class="text-muted">
                                                        <?php echo htmlspecialchars($user_data['ma_sinh_vien']); ?>
                                                    </p>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <h6>Năm Sinh</h6>
                                                    <p class="text-muted">
                                                        <?php echo htmlspecialchars($user_data['ngay_sinh']); ?>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <h6>Căn Cước Công Dân</h6>
                                                    <p class="text-muted">
                                                        <?php echo htmlspecialchars($user_data['so_cmnd']); ?></p>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <h6>Giới Tính</h6>
                                                    <p class="text-muted">
                                                        <?php echo htmlspecialchars($user_data['gioi_tinh']); ?></p>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <h6>Email</h6>
                                                    <p class="text-muted">
                                                        <?php echo htmlspecialchars($user_data['email']); ?></p>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <h6>Số Điện Thoại</h6>
                                                    <p class="text-muted">
                                                        <?php echo htmlspecialchars($user_data['so_dien_thoai']); ?></p>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <h6>Quê Quán</h6>
                                                    <p class="text-muted">
                                                        <?php echo htmlspecialchars($user_data['que_quan']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </body>

</html>