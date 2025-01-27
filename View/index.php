<?php
session_start();
$isLoggedIn = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'];
$vai_tro = isset($_SESSION['vai_tro']) ? $_SESSION['vai_tro'] : ''; 
if (isset($_SESSION['login_success']) && $_SESSION['login_success']) {
    $login_success = $_SESSION['login_success'];
    echo "<script class='text-success'>alert('{$login_success}');</script>";
    unset($_SESSION['login_success']);
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

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"
            integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
            integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap-grid.min.css"
            integrity="sha512-i1b/nzkVo97VN5WbEtaPebBG8REvjWeqNclJ6AItj7msdVcaveKrlIIByDpvjk5nwHjXkIqGZscVxOrTb9tsMA=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <title>Quản Lý Sinh Viên</title>
        <link rel="stylesheet" href="./assets/css/index.css">
    </head>

    <body>
        
        </div>
        <div class="main-background">
            <div class="main-container">
                <div class="text-main">
                    <span id="text-field">Tham quan online</span>
                    <p class="text-main-prd">
                        TRUNG TÂM QUẢN LÝ KÝ TÚC XÁ
                        <br>
                        ĐẠI HỌC SƯ PHẠM KỸ THUẬT VINH
                    </p>
                    <?php if ($isLoggedIn): ?>
                    <a href="../Controller/logoutController.php" class="main-btn btn" aria-label="Đăng Xuất">
                        <span id="text-start-visit">Đăng Xuất</span>
                    </a>
                    <?php else: ?>
                    <a href="login.php" class="main-btn btn" aria-label="Đăng Nhập">
                        <span id="text-start-visit">Đăng Nhập</span>
                    </a>
                    <?php endif; ?>
                </div>
                <div class="tutorial-main">
                    <p class="tutorial-header" id="text-guide">Nội Dung Của Ký Túc</p>
                    <div class="tutorial-body">
                        <div class="tutorial-body-top">
                            <div class="tutorial-body-top-item">
                                <i class="fa-regular fa-hand-point-up"></i>
                                <div id="text-push">Chạm/Nhấn để trải nghiệm</div>
                                <i class="fa-solid fa-computer-mouse"></i>
                            </div>
                        </div>
                        <div class="tutorial-body-bottom">
                            <?php if ($isLoggedIn): ?>
                            <?php if ($vai_tro === 'Quan Tri Vien'): ?>
                            <a href="./admin/dashboard.php" class="tutorial-body-bottom-item">
                                <i class="fa-solid fa-people-roof"></i>
                                <div id="text-guide-model3d">Quản Lý Ký Túc Xá</div>
                            </a>
                            <?php endif; ?>
                            <a href="home.php" class="tutorial-body-bottom-item">
                                <i class="far fa-edit"></i>
                                <div id="text-guide-model3d">đặt phòng</div>
                            </a>
                            <a href="profile.php" class="tutorial-body-bottom-item">
                                <i class="far fa-edit"></i>
                                <div id="text-guide-model3d">Chỉnh Sửa Cá Nhân</div>
                            </a>
                            <a href="../Controller/logoutController.php" class="tutorial-body-bottom-item">
                                <i class="fa-solid fa-right-to-bracket"></i>
                                <div id="text-guide-model3d">Đăng Xuất</div>
                            </a>


                            <?php else: ?>

                            <a href="profile.php" class="tutorial-body-bottom-item">
                                <i class="far fa-edit"></i>
                                <div id="text-guide-model3d">Chỉnh Sửa Cá Nhân</div>
                            </a>
                            <a href="login.php" class="tutorial-body-bottom-item">
                                <i class="fa-solid fa-right-to-bracket"></i>
                                <div id="text-guide-model3d">Đăng Nhập</div>
                            </a>
                            <a href="register.php" class="tutorial-body-bottom-item">
                                <i class="fa-regular fa-user"></i>
                                <div id="text-location">Đăng Ký</div>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>