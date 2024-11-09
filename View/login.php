<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/login.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js" integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap-grid.min.css" integrity="sha512-i1b/nzkVo97VN5WbEtaPebBG8REvjWeqNclJ6AItj7msdVcaveKrlIIByDpvjk5nwHjXkIqGZscVxOrTb9tsMA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                            <h3>Đăng Nhập Tài Khoản</h3>
                            <form method="POST" action="{{ route('login-post') }}" >
                                <div class="form-group form-box">
                                    <input type="email" name="email" class="form-control" placeholder="Địa Chỉ Email" required autocomplete="email">
                                </div>
                                <div class="form-group form-box position-relative">
                                    <input type="password" name="password" class="form-control" placeholder="Mật Khẩu" required autocomplete="password">
                                    <span class="position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer toggle-password" style="z-index: 10;">
                                        <i class="fa fa-eye see-password"></i>
                                    </span>
                                </div>
                                <div class="form-group form-box checkbox clearfix">
                                    <div class="form-check checkbox-theme">
                                        <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">
                                            Nhớ Mật Khẩu
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn-md btn-theme w-100">Đăng Nhập</button>
                                </div>
                                <p>Bạn Chưa Có Tài Khoản?<a href="register.php" class="text-route">Đăng Ký Tại Đây</a></p>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 bg-img">
                    <div class="information">
                        <div class="btn-section">
                            <a href="login.php" class="active link-btn">Đăng Nhập</a>
                            <a href="register.php" class="link-btn">Đăng Ký</a>
                        </div>
                        <h1>ĐẠI HỌC SƯ PHẠM KỸ THUẬT VINH</h1>
                        <p>Ký túc xá Trường Đại học Sư phạm Kỹ thuật Vinh cung cấp nơi ở tiện nghi và an toàn, hỗ trợ sinh viên yên tâm học tập và phát triển trong môi trường hiện đại.</p>

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