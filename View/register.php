<?php
session_start();

require_once '../Controller/RegisterController.php'; // Include RegisterController file
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $conn = $database->connect();
    if ($conn === null) {
        die('Database connection failed!');
    }
    $registerController = new RegisterController();
    $registerController->register($conn);
}
// Check if there are errors in the session and display them
if (isset($_SESSION['errors'])) {
    // Display the errors for debugging (var_dump)
    // echo "<pre>";
    // var_dump($_SESSION['errors']);
    // echo "</pre>";

    // Display the errors in the form
    $errors = $_SESSION['errors'];
} else {
    $errors = [];
}
unset($_SESSION['errors']);
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./assets/css/login.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"
            integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
            integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap-grid.min.css"
            integrity="sha512-i1b/nzkVo97VN5WbEtaPebBG8REvjWeqNclJ6AItj7msdVcaveKrlIIByDpvjk5nwHjXkIqGZscVxOrTb9tsMA=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <title>Document</title>
    </head>

    <body>
        <div class="top">
            <div class="login tab-box">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 form-section">
                            <div class="login-inner-form">
                                <div class="details">
                                    <a href="index.php">
                                        <img src="./assets/images/logo.webp" alt="logo">
                                    </a>
                                    <h3>Đăng Ký Tài Khoản</h3>
                                    <form method="POST" action="">
                                        <div class="form-group form-box">
                                            <input type="text" name="username" class="form-control"
                                                placeholder="Họ Và Tên"
                                                value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>"
                                                required>
                                            <?php if (isset($errors['username'])): ?>
                                            <div class="text-danger"><?php echo $errors['username']; ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group form-box">
                                            <input type="email" name="email" class="form-control"
                                                placeholder="Địa Chỉ Email"
                                                value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>"
                                                required>
                                            <?php if (isset($errors['email'])): ?>
                                            <div class="text-danger"><?php echo $errors['email']; ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group form-box position-relative password-form">
                                            <input type="password" name="password" class="form-control"
                                                placeholder="Mật Khẩu"
                                                value="<?php echo isset($_POST['password']) ? $_POST['password'] : ''; ?>"
                                                required autocomplete="new-password">
                                            <span
                                                class="position-absolute top-50 end-0 translate-middle-y cursor-pointer toggle-password"
                                                style="z-index: 10;">
                                                <i class="see-password fa fa-eye"></i>
                                            </span>
                                            <?php if (isset($errors['password'])): ?>
                                            <div class="text-danger"><?php echo $errors['password']; ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group form-box position-relative password-form">
                                            <input type="password" name="confirm_password" class="form-control"
                                                placeholder="Xác Nhận Mật Khẩu"
                                                value="<?php echo isset($_POST['confirm_password']) ? $_POST['confirm_password'] : ''; ?>"
                                                required autocomplete="new-password">
                                            <span
                                                class="position-absolute top-50 end-0 translate-middle-y cursor-pointer toggle-password"
                                                style="z-index: 10;">
                                                <i class="see-password fa fa-eye"></i>
                                            </span>
                                            <?php if (isset($errors['confirm_password'])): ?>
                                            <div class="text-danger"><?php echo $errors['confirm_password']; ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group form-box">
                                            <input type="text" name="phone" class="form-control"
                                                placeholder="Số Điện Thoại"
                                                value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : ''; ?>"
                                                required>
                                            <?php if (isset($errors['phone'])): ?>
                                            <div class="text-danger"><?php echo $errors['phone']; ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group form-box text-left">
                                            <select name="gender" class="form-control form-select" required>
                                                <option class="form-control" value="" disabled selected>Giới Tính
                                                </option>
                                                <option class="form-control" value="Nam"
                                                    <?php echo isset($_POST['gender']) && $_POST['gender'] == 'Nam' ? 'selected' : ''; ?>>
                                                    Nam</option>
                                                <option class="form-control" value="Nữ"
                                                    <?php echo isset($_POST['gender']) && $_POST['gender'] == 'Nữ' ? 'selected' : ''; ?>>
                                                    Nữ</option>
                                                <option class="form-control" value="Khác"
                                                    <?php echo isset($_POST['gender']) && $_POST['gender'] == 'Khác' ? 'selected' : ''; ?>>
                                                    Khác</option>
                                            </select>
                                            <?php if (isset($errors['gender'])): ?>
                                            <div class="text-danger"><?php echo $errors['gender']; ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group form-box text-left">
                                            <label for="birth" class="form-label">Ngày Sinh:</label>
                                            <input type="date" name="birth" class="form-control"
                                                value="<?php echo isset($_POST['birth']) ? $_POST['birth'] : ''; ?>"
                                                required>
                                            <?php if (isset($errors['birth'])): ?>
                                            <div class="text-danger"><?php echo $errors['birth']; ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group form-box checkbox clearfix">
                                            <div class="form-check checkbox-theme">
                                                <input class="form-check-input" type="checkbox" value="1" id="agree"
                                                    name="agree"
                                                    <?php echo isset($_POST['agree']) && $_POST['agree'] == '1' ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="agree">
                                                    Tôi đồng ý với các điều khoản dịch vụ
                                                </label>
                                            </div>
                                            <?php if (isset($errors['agree'])): ?>
                                            <div class="text-danger"><?php echo $errors['agree']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn-md btn-theme w-100">Đăng Ký</button>
                                        </div>

                                        <p>Bạn Có Sẵn Tài Khoản? <a href="login.php">Đăng Nhập Tại Đây</a></p>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 bg-img">
                            <div class="information">
                                <div class="btn-section">
                                    <a href="login.php" class="active link-btn">Đăng Nhập</a>
                                    <a href="index.php" class="link-btn">Trang Chủ</a>
                                </div>
                                <h1>ĐẠI HỌC SƯ PHẠM KỸ THUẬT VINH</h1>
                                <p>Ký túc xá Trường Đại học Sư phạm Kỹ thuật Vinh cung cấp nơi ở tiện nghi và an toàn,
                                    hỗ trợ sinh viên yên tâm học tập và phát triển trong môi trường hiện đại.</p>

                                <div class="social-list">
                                    <a href="#" class="facebook-bg">
                                        <i class="fa-brands fa-facebook"></i>
                                    </a>
                                    <a href="#" class="twitter-bg">
                                        <i class="fa-brands fa-twitter"></i>
                                    </a>
                                    <a href="#" class="google-bg">
                                        <i class="fa-brands fa-google"></i>
                                    </a>
                                    <a href="#" class="linkedin-bg">
                                        <i class="fa-brands fa-linkedin-in"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../View/assets/js/app.js"></script>
    </body>

</html>