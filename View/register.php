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
                            <h3>Đăng Ký Tài Khoản</h3>
                            <form method="POST" action="{{route('register-post')}}">
                                <div class="form-group form-box">
                                    <input type="text" name="student_code" class="form-control" placeholder="Mã Học Sinh" required>
                                </div>
                                <div class="form-group form-box">
                                    <input type="text" name="username" class="form-control" placeholder="Họ Và Tên" required>
                                </div>
                                <div class="form-group form-box">
                                    <input type="email" name="email" class="form-control" placeholder="Địa Chỉ Email" required>
                                </div>
                                <div class="form-group form-box position-relative password-form">
                                    <input type="password" name="password" class="form-control" placeholder="Mật Khẩu" required>
                                    <span class="position-absolute top-50 end-0 translate-middle-y cursor-pointer toggle-password" style="z-index: 10;">
                                        <i class="see-password fa fa-eye"></i>
                                    </span>
                                </div>
                                <div class="form-group form-box position-relative password-form">
                                    <input type="password" name="confirm_password" class="form-control" placeholder="Xác Nhận Mật Khẩu" required>
                                    <span class="position-absolute top-50 end-0 translate-middle-y cursor-pointer toggle-password" style="z-index: 10;">
                                        <i class="see-password fa fa-eye"></i>
                                    </span>
                                </div>
                                <div class="form-group form-box">
                                    <input type="text" name="email" class="form-control" placeholder="Căn Cước Công Dân" required>
                                </div>
                                <div class="form-group form-box">
                                    <input type="text" name="phone" class="form-control" placeholder="Số Điện Thoại" required>
                                </div>
                                <div class="form-group form-box text-left">
                                    <label for="gender" class="form-label">Ngày Sinh:</label>
                                    <input type="datetime-local" name="birth" class="form-control" placeholder="Ngày Sinh" required>
                                </div>
                                <div class="form-group form-box text-left">
                                    <label for="gender" class="form-label">Giới Tính:</label>
                                    <select name="gender" class="form-control form-select" required>
                                        <option class="form-control" value="Nam">Nam</option>
                                        <option class="form-control" value="Nữ">Nữ</option>
                                        <option class="form-control" value="Khác">Khác</option>
                                    </select>
                                </div>
                                <div class="form-group form-box checkbox clearfix">
                                    <div class="form-check checkbox-theme">
                                        <input class="form-check-input" type="checkbox" value="1" id="agree" name="agree">
                                        <label class="form-check-label" for="agree">
                                            Tôi đồng ý với các điều khoản dịch vụ
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn-md btn-theme w-100">Đăng Ký</button>
                                </div>
                                <p>Bạn Có Sẵn Tài Khoản?<a href="login.php">Đăng Nhập Tại Đây</a></p>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 bg-img">
                    <div class="information">
                        <div class="btn-section">
                            <a href="register.php" class="active link-btn">Đăng Ký</a>
                            <a href="login.php" class="link-btn">Đăng Nhập</a>
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