<?php 
$database = new Database();
$conn = $database->connect();
$dashboardController = new DashboardController($conn);
$userId = $_SESSION['login']; 
$logged_user = $dashboardController->getAdmin($userId);
?>
<div class="d-flex align-items-center justify-content-between p-4">
                 <div></div>
                <div class="user-infor">
                    <div class="dropdown">
                        <button class="d-flex justify-content-center align-items-center rounded-circle" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="../assets/images/admin/<?php echo $logged_user['avatar']?>" alt="image"
                                class="user-image object-fit-cover rounded-circle">
                        </button>
                        <div class="dropdown-menu to-top dropdown-menu-sm" aria-labelledby="dropdownMenuButton">
                            <div class="drop-header d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="drop-user-name"><?php  echo($logged_user['ho_ten'])?></h6>
                                    <span class="drop-user-role"><?php  echo($logged_user['vai_tro'])?></span>
                                </div>
                            </div>
                            <ul class="to-top-list">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="../../View/index.php">
                                        <i class="icon-user-item fa-regular fa-user"></i> <span>Trang Người Dùng</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center"
                                        href="../../Controller/logoutController.php">
                                        <i class="icon-user-item fa-solid fa-power-off"></i><span>Đăng Xuất</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

</div>