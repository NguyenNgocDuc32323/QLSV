<?php
require_once '../Model/User.php';

class LoginController {
    public function login($conn) {
        if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
            header('Location: index.php');
            exit();
        }

        $errors = [];

        if (isset($_COOKIE['ten_dang_nhap']) && isset($_COOKIE['mat_khau'])) {
            $username = $_COOKIE['ten_dang_nhap'];
            $password = $_COOKIE['mat_khau'];
            $remember = true; 
            $userModel = new User($conn);
            $result = $userModel->checkLogin($username, $password);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $_SESSION['ten_dang_nhap'] = $username;
                $_SESSION['login'] = $row['id'];
                $_SESSION['is_logged_in'] = true;

                header('Location: index.php');
                exit();
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['ten_dang_nhap'] ?? '';
            $password = $_POST['mat_khau'] ?? '';
            $remember = isset($_POST['remember']) ? $_POST['remember'] : null; 
            if (empty($username)) $errors['ten_dang_nhap'] = 'Tên đăng nhập là bắt buộc!';
            if (empty($password)) $errors['mat_khau'] = 'Mật khẩu là bắt buộc!';
            if (empty($errors)) {
                $userModel = new User($conn);
                $result = $userModel->checkLogin($username, $password);

                if ($result && $result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $_SESSION['ten_dang_nhap'] = $username;
                    $_SESSION['login'] = $row['id'];
                    $_SESSION['is_logged_in'] = true;
                    if ($remember) {
                        setcookie('ten_dang_nhap', $username, time() + (86400 * 30), "/", "", isset($_SERVER["HTTPS"]), true);
                        setcookie('mat_khau', $password, time() + (86400 * 30), "/", "", isset($_SERVER["HTTPS"]), true);
                    } else {
                        setcookie('ten_dang_nhap', '', time() - 3600, "/");
                        setcookie('mat_khau', '', time() - 3600, "/");
                    }
                    header('Location: index.php');
                    exit();
                } else {
                    $_SESSION['errors'] = ['Thông tin đăng nhập không đúng.'];
                    header('Location: login.php');
                    exit();
                }
            } else {
                $_SESSION['errors'] = $errors;
                header('Location: login.php');
                exit();
            }
        }
    }
}
?>