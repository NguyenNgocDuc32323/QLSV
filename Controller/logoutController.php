<?php
session_start(); 
ob_start(); 
session_unset();  
session_destroy();  
setcookie('ten_dang_nhap', '', time() - 3600, "/");  
setcookie('mat_khau', '', time() - 3600, "/");  
header('Location: /qlsv/View/login.php');
exit();
?>