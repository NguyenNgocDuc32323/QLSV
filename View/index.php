<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js" integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap-grid.min.css" integrity="sha512-i1b/nzkVo97VN5WbEtaPebBG8REvjWeqNclJ6AItj7msdVcaveKrlIIByDpvjk5nwHjXkIqGZscVxOrTb9tsMA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Quản Lý Sinh Viên</title>
    <link rel="stylesheet" href="./assets/css/index.css">
</head>
<body>
    <div class="main-background">
        <div class="main-container">
            <div class="text-main">
                <span id="text-field">Tham quan online</span>
                <p class="text-main-prd">
                    TRUNG TÂM QUẢN LÝ KÝ TÚC XÁ
                    <br>
                    ĐẠI HỌC SƯ PHẠM KỸ THUẬT VINH
                </p>
                <a href="login.php" class="main-btn btn" aria-label="Đăng Nhập">
                    <span id="text-start-visit">Đăng Nhập</span>
                </a>
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
                <a href="#" class="tutorial-body-bottom-item">
                    <i class="fa-solid">
                    <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" width="23" height="23" style="transform: translateY(-10%);">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.5 1L30 15.0152V31H17.5V28.9925H28V15.869L15.5 3.78692L3 15.869V28.9925H10.5V18.4533H20V20.4607H12.5V31H1V15.0152L15.5 1Z" fill="#014689"></path>
                    </svg>
                    </i>
                    <div id="text-location">Tổng quan khu KTX</div>
                </a>
                <a href="#" class="tutorial-body-bottom-item" >
                    <i class="fa-solid">
                    <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" width="25" height="25">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.9998 3.6665C9.18832 3.6665 3.6665 9.18832 3.6665 15.9998C3.6665 22.8113 9.18832 28.3332 15.9998 28.3332C22.8113 28.3332 28.3332 22.8113 28.3332 15.9998C28.3332 9.18832 22.8113 3.6665 15.9998 3.6665ZM1.6665 15.9998C1.6665 8.08376 8.08376 1.6665 15.9998 1.6665C23.9159 1.6665 30.3332 8.08376 30.3332 15.9998C30.3332 23.9159 23.9159 30.3332 15.9998 30.3332C8.08376 30.3332 1.6665 23.9159 1.6665 15.9998Z" fill="#014689"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M1.6665 16C1.6665 15.4477 2.11422 15 2.6665 15H29.3332C29.8855 15 30.3332 15.4477 30.3332 16C30.3332 16.5523 29.8855 17 29.3332 17H2.6665C2.11422 17 1.6665 16.5523 1.6665 16Z" fill="#014689"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.9998 1.6665C16.2808 1.6665 16.5487 1.78466 16.7382 1.99208C20.2367 5.8222 22.2249 10.7927 22.333 15.979C22.3332 15.9929 22.3332 16.0068 22.333 16.0207C22.2249 21.207 20.2367 26.1775 16.7382 30.0076C16.5487 30.215 16.2808 30.3332 15.9998 30.3332C15.7189 30.3332 15.451 30.215 15.2615 30.0076C11.763 26.1775 9.77477 21.207 9.66672 16.0207C9.66643 16.0068 9.66643 15.9929 9.66672 15.979C9.77477 10.7927 11.763 5.8222 15.2615 1.99208C15.451 1.78466 15.7189 1.6665 15.9998 1.6665ZM11.6667 15.9998C11.7613 20.3159 13.2921 24.466 15.9998 27.8015C18.7075 24.466 20.2384 20.3159 20.3329 15.9998C20.2384 11.6838 18.7075 7.53364 15.9998 4.19814C13.2921 7.53364 11.7613 11.6838 11.6667 15.9998Z" fill="#014689"></path>
                    </svg>
                    </i>
                    <div id="text-guide-model3d">Chọn khu KTX</div>
                </a>
                <a href="#" class="tutorial-body-bottom-item">
                    <i class="fa-solid">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" width="20" height="20" style="transform: translateY(10%);">
                        <path d="M 15 7 L 30 0 L 15 30 L 0 0 z" stroke="#014689" stroke-width="1.7" fill="none"></path>
                    </svg>
                    </i>
                    <div id="text-location">Trở về cảnh quan trước</div>
                </a>
                </div>
          </div>
            </div>
        </div>
    </div>
</body>
</html>