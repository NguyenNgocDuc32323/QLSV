<?php
// session_start();
require_once '../Model/User.php';  // Include User class
require_once '../config/database.php';

class RegisterController {

    // Method to handle user registration
    public function register($conn) {

        // Initialize error messages array
        $errors = [];

        // Check if POST request is made
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Get form data and sanitize inputs
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $phone = trim($_POST['phone']);
            $gender = $_POST['gender'];
            $birth = $_POST['birth'];
            $agree = isset($_POST['agree']) ? 1 : 0; // Check if user agrees to the terms

            // Validate inputs and store error messages in an array
            if (empty($username)) {
                $errors['username'] = "Họ và tên không được để trống.";
            }

            if (empty($email)) {
                $errors['email'] = "Địa chỉ email không được để trống.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Địa chỉ email không hợp lệ.";
            }

            if (empty($password)) {
                $errors['password'] = "Mật khẩu không được để trống.";
            } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", $password)) {
                $errors['password'] = "Mật khẩu phải chứa ít nhất một chữ cái viết hoa, một chữ cái viết thường, một số, một ký tự đặc biệt và có ít nhất 8 ký tự.";
            }

            if ($password !== $confirm_password) {
                $errors['confirm_password'] = "Mật khẩu xác nhận không khớp.";
            }

            if (empty($phone)) {
                $errors['phone'] = "Số điện thoại không được để trống.";
            } elseif (!preg_match("/^0\d{9}$/", $phone)) {
                $errors['phone'] = "Số điện thoại phải bắt đầu bằng số 0 và có 10 chữ số.";
            }

            if (empty($gender)) {
                $errors['gender'] = "Vui lòng chọn giới tính.";
            }

            if (empty($birth)) {
                $errors['birth'] = "Vui lòng chọn ngày sinh.";
            }

            // Check if the checkbox was checked
            if (empty($_POST['agree'])) {
                $errors['agree'] = "Bạn phải đồng ý với các điều khoản dịch vụ.";
            }

            // If there are no errors, proceed with registration
            if (empty($errors)) {
                // Create User model
                $userModel = new User($conn);

                // Check if email already exists in the database
                if ($userModel->checkEmailExists($email)) {
                    $errors['email'] = "Email đã tồn tại.";
                } else {
                    // Hash password for security
                    $hashed_password = password_hash($password, PASSWORD_BCRYPT);  // Use bcrypt for password hashing

                    // Register the new user by calling the registerUser method in the model
                    if ($userModel->registerUser($username, $email, $hashed_password, $phone, $gender, $birth)) {
                        $_SESSION['success'] = "Đăng ký thành công! Vui lòng đăng nhập.";
                        header("Location: login.php");
                        exit();
                    } else {
                        $errors['general'] = "Có lỗi xảy ra trong quá trình đăng ký. Vui lòng thử lại.";
                    }
                }
            }

            // If there are errors, pass them to the view
            $_SESSION['errors'] = $errors;
            header("Location: register.php");
            exit();
        }
    }
}
?>