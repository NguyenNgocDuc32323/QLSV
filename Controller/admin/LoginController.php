<?php
require_once '../../Model/admin/User.php';
class LoginController {
    public function login($conn) {
        if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
            header('Location: index.php');
            exit();
        }

        $errors = [];

        if (isset($_COOKIE['email']) && isset($_COOKIE['mat_khau'])) {
            $email = $_COOKIE['email'];
            $password = $_COOKIE['mat_khau'];
            $remember = true;
            $userModel = new User($conn);
            $result = $userModel->checkLogin($email, $password);
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $_SESSION['email'] = $email;
                $_SESSION['login'] = $row['id'];
                $_SESSION['is_logged_in'] = true;
                
                $_SESSION['login_success'] = true; 
                header('Location: index.php');
                exit();
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['mat_khau'] ?? '';
            $remember = isset($_POST['remember']) ? $_POST['remember'] : null;
            
            if (empty($email)) $errors['email'] = 'Email là bắt buộc!';
            if (empty($password)) $errors['mat_khau'] = 'Mật khẩu là bắt buộc!';
            
            if (empty($errors)) {
                $userModel = new User($conn);
                $result = $userModel->checkLogin($email, $password);

                if ($result && $result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $_SESSION['email'] = $email;
                    $_SESSION['login'] = $row['id'];
                    $_SESSION['is_logged_in'] = true;
                    $_SESSION['login_success'] = "Đăng Nhập Thành Công!";
                    if ($remember) {
                        setcookie('email', $email, time() + (86400 * 30), "/", "", isset($_SERVER["HTTPS"]), true);
                        setcookie('mat_khau', $password, time() + (86400 * 30), "/", "", isset($_SERVER["HTTPS"]), true);
                    } else {
                        setcookie('email', '', time() - 3600, "/");
                        setcookie('mat_khau', '', time() - 3600, "/");
                    }

                    $_SESSION['login_success'] = "Đăng nhập thành công"; 
                    
                    header('Location: index.php');
                    exit();
                } else {
                    $_SESSION['login_failure'] = 'Thông tin đăng nhập không đúng. '. $errors;
                    header('Location: login.php');
                    $login_success = $_SESSION['login_failure'];
                    echo "<script class='text-success'>alert('{$login_success}');</script>";
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