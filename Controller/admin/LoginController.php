<?php
require_once '../../Model/admin/User.php';

class LoginController {
    public function login($conn) {
        // Kiểm tra nếu người dùng đã đăng nhập
        if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
            header('Location: dashboard.php');
            exit();
        }

        $errors = [];

        // Kiểm tra nếu có cookie lưu thông tin đăng nhập
        if (isset($_COOKIE['email']) && isset($_COOKIE['mat_khau'])) {
            $email = $_COOKIE['email'];
            $password = $_COOKIE['mat_khau'];
            $remember = true;
            $userModel = new User($conn);
            $result = $userModel->checkLogin($email, $password);

            // Kiểm tra kết quả trả về từ việc đăng nhập
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Kiểm tra vai trò là 'Quan Tri Vien'
                if ($row['vai_tro'] === 'Quan Tri Vien') {
                    // Lưu thông tin người dùng vào session
                    $_SESSION['email'] = $email;
                    $_SESSION['login'] = $row['id'];
                    $_SESSION['is_logged_in'] = true;
                    $_SESSION['vai_tro'] = $row['vai_tro'];  // Lưu vai trò vào session
                    
                    // Chuyển hướng tới trang dashboard
                    header('Location: dashboard.php');
                    exit();
                } else {
                    $_SESSION['login_failure'] = 'Bạn không có quyền truy cập. Vai trò không hợp lệ.';
                    header('Location: login.php');
                    exit();
                }
            }
        }

        // Xử lý khi form được gửi qua POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['mat_khau'] ?? '';
            $remember = isset($_POST['remember']) ? $_POST['remember'] : null;

            // Kiểm tra các trường nhập liệu
            if (empty($email)) $errors['email'] = 'Email là bắt buộc!';
            if (empty($password)) $errors['mat_khau'] = 'Mật khẩu là bắt buộc!';

            // Nếu không có lỗi, tiến hành đăng nhập
            if (empty($errors)) {
                $userModel = new User($conn);
                $result = $userModel->checkLogin($email, $password);

                // Kiểm tra kết quả trả về từ việc đăng nhập
                if ($result && $result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    
                    // Kiểm tra vai trò là 'Quan Tri Vien'
                    if ($row['vai_tro'] === 'Quan Tri Vien') {
                        // Lưu thông tin người dùng vào session
                        $_SESSION['email'] = $email;
                        $_SESSION['login'] = $row['id'];
                        $_SESSION['is_logged_in'] = true;
                        $_SESSION['vai_tro'] = $row['vai_tro'];  // Lưu vai trò vào session

                        // Lưu thông tin đăng nhập nếu người dùng chọn nhớ mật khẩu
                        if ($remember) {
                            setcookie('email', $email, time() + (86400 * 30), "/", "", isset($_SERVER["HTTPS"]), true);
                            setcookie('mat_khau', $password, time() + (86400 * 30), "/", "", isset($_SERVER["HTTPS"]), true);
                        } else {
                            setcookie('email', '', time() - 3600, "/");
                            setcookie('mat_khau', '', time() - 3600, "/");
                        }

                        // Chuyển hướng đến trang chính (dashboard hoặc trang chủ)
                        $_SESSION['login_success'] = "Đăng Nhập Thành Công!";
                        header('Location: dashboard.php');
                        exit();
                    } else {
                        $_SESSION['login_failure'] = 'Bạn không có quyền truy cập. Vai trò không hợp lệ.';
                        header('Location: login.php');
                        exit();
                    }
                } else {
                    $_SESSION['login_failure'] = 'Thông tin đăng nhập không đúng.';
                    header('Location: login.php');
                    exit();
                }
            } else {
                // Nếu có lỗi, lưu lỗi vào session và chuyển hướng lại về trang đăng nhập
                $_SESSION['errors'] = $errors;
                header('Location: login.php');
                exit();
            }
        }
    }
}
?>