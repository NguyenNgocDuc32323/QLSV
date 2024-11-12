<?php 
require_once '../../Controller/admin/DashboardController.php' ;
$database = new Database();
$conn = $database->connect();
$dashboardController = new DashboardController($conn);
$room = $dashboardController->showRoom();
$contract = $dashboardController->showContract();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../assets/css/admin-dashboard.css" />
    <link rel="stylesheet" href="../assets/css/admin_create.css" />
</head>
<body>
    <div class="d-flex align-items-center">
        <div class="sidebar text-white position-fixed vh-100 p-3">
            <a href="../../View/admin/dashboard.php" class="d-block text-center my-3">
                <img src="../assets/images/logo.webp" alt="site logo" class="img-fluid" style="max-height: 80px" />
            </a>
            <nav class="sidebar-menu-area">
                <ul class="nav flex-column" id="sidebar-menu">
                    <li class="nav-item bg-success" id="tab-product">
                        <a href="dashboard.php" class="nav-link text-white">
                            <i class="fa-solid fa-user me-2"></i>
                            Sinh Viên
                        </a>
                    </li>
                    <li class="nav-item bg-success" id="tab-category">
                        <a href="#" class="nav-link text-white">
                            <i class="fa-solid fa-list me-2"></i>
                            Phòng
                        </a>
                    </li>
                    <li class="nav-item bg-success" id="tab-account">
                        <a href="#" class="nav-link text-white">
                            <i class="fa-solid fa-bottle-water me-2"></i>
                            Hợp Đồng
                        </a>
                    </li>
                    <li class="nav-item bg-success" id="tab-transaction">
                        <a href="#" class="nav-link text-white">
                            <i class="fa-solid fa-cart-shopping me-2"></i>
                            Hóa Đơn Phòng
                        </a>
                    </li>
                    <li class="nav-item bg-success" id="tab-delivery">
                        <a href="#" class="nav-link text-white">
                            <i class="fa-solid fa-truck-fast me-2"></i>
                            Hóa Đơn Điện Nước
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="dashboard-main">
            <div class="d-flex align-items-center justify-content-between p-4">
                <i class="fa-solid fa-bars btn btn-success btn-navbar"></i>
                <div class="user-infor">
                    <img src="./asset/images/admin.png" alt="" class="img-fluid admin-logo" />
                    <div class="dropdown">
                        <button class="d-flex justify-content-center align-items-center rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="../assets/images/admin/admin.png" alt="image" class="user-image object-fit-cover rounded-circle">
                        </button>
                        <div class="dropdown-menu to-top dropdown-menu-sm" aria-labelledby="dropdownMenuButton">
                            <div class="drop-header d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="drop-user-name">Nguyen Ngoc Duc</h6>
                                    <span class="drop-user-role">admin</span>
                                </div>
                            </div>
                            <ul class="to-top-list">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="../../View/index.php">
                                        <i class="icon-user-item fa-regular fa-user"></i> <span>Trang Người Dùng</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="../../Controller/logoutController.php">
                                        <i class="icon-user-item fa-solid fa-power-off"></i><span>Đăng Xuất</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-wrapper">
            <div class="container-fluid flex-grow-1 container-p-y">
                            <div class="dashboard-main-body">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
                    <h1 class="fw-semibold mb-0 body-title">Create Product</h1>
                </div>
                <div class="row justify-content-center align-items-center manage-block">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="http://127.0.0.1:8000/admin/create-product" method="POST" enctype="multipart/form-data" class="w-75 mx-auto">
                                    <input type="hidden" name="_token" value="l69Lmm55svkK5Ulryd69mB3Vu8SQDrWgBR1NoMQl" autocomplete="off">                            <!-- Product Details -->
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="" fdprocessedid="tqyvp">
                                                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="price">Price</label>
                                        <input type="text" class="form-control" id="price" name="price" value="" fdprocessedid="4o0gn9">
                                                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="quantity">Quantity</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" value="" fdprocessedid="92r49o">
                                                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="category_id">Category</label>
                                        <select class="form-control" id="category_id" name="category_id" fdprocessedid="g8k95i">
                                                                                    <option value="1">PC</option>
                                                                                    <option value="2">PP</option>
                                                                                    <option value="3">PET</option>
                                                                            </select>
                                                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="label_id">Label</label>
                                        <select class="form-control" id="label_id" name="label_id" fdprocessedid="b6rdd6">
                                                                                    <option value="1">Hot</option>
                                                                                    <option value="2">New</option>
                                                                                    <option value="3">Sale</option>
                                                                            </select>
                                                                    </div>
                                
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="large_images">Large Images</label>
                                        <div class="row">
                                                                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                    <input type="file" class="form-control" name="large_images[]">
                                                                                            </div>
                                                                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                    <input type="file" class="form-control" name="large_images[]">
                                                                                            </div>
                                                                            </div>
                                    </div>
                                    <!-- Small Images -->
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="small_images">Small Images</label>
                                        <div id="small_images" class="d-flex flex-wrap row">
                                                                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                    <input type="file" class="form-control" name="small_images[]">
                                                                                            </div>
                                                                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                    <input type="file" class="form-control" name="small_images[]">
                                                                                            </div>
                                                                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                    <input type="file" class="form-control" name="small_images[]">
                                                                                            </div>
                                                                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                    <input type="file" class="form-control" name="small_images[]">
                                                                                            </div>
                                                                            </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <button type="submit" class="btn btn-success badge btn-add-prd" fdprocessedid="pij2cc">Create Product</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            </div>
</div>
            
            
        <script src="../assets/js/admin-dashboard.js"></script>
</body>

</html>