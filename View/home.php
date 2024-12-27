<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../Controller/RoomController.php';
$database = new Database();
$conn = $database->connect();
$roomController = new RoomController($conn);
$getallRoom = $roomController->getAllRoom();
?>

<!DOCTYPE html>
<html lang="vi">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Trang Sinh Viên Thuê KTX</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
            integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            background-color: #f8f9fa;
        }

        header h1 {
            font-weight: 700;
            font-size: 2.5rem;
            color: #45BF55;
            margin: 0;
        }

        header ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        header ul li a {
            text-decoration: none;
            color: #45BF55;
            /* 
            font-weight: 700; */
            font-size: 1.5rem;
        }

        header ul li a:hover {
            color: #45BF55;
        }

        .btn-primary {
            background-color: #45BF55;
            border-color: #45BF55;
        }

        /* .content {
            background: url('assets/images/home_banner.jpg');
        } */

        .container {
            width: 100%;


            margin: 2rem auto;
        }

        .room {
            width: calc(33.33% - 20px);
            /* Trừ đi tổng margin để vừa với hàng */
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            text-align: center;
            margin: 10px;
        }




        .room img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 400px;
            max-width: 90%;
            z-index: 1000;
            padding: 1.5rem;
            display: none;
        }

        .popup h5 {
            margin-top: 0;
        }

        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }

        .popup .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #000;
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
        </style>
    </head>

    <body>
        <header class="d-flex justify-content-between align-items-center px-4">
            <h1>Quản Lý Thuê KTX</h1>
            <ul class="d-flex align-items-center gap-3 m-0">
                <li class="fw-medium">
                    <a href="index.php" class="d-flex align-items-center gap-1 hover-text-primary">
                        <i class="fa-solid fa-house"></i>
                        Trang Chủ
                    </a>
                </li>
            </ul>
        </header>

        <div class="content">
            <div class="container">
                <section>
                    <h2>Danh Sách Phòng KTX</h2>
                    <div class="room-list row g-3">
                        <?php if (!empty($getallRoom)) : ?>
                        <?php foreach ($getallRoom as $room) : ?>
                        <div class="room col-md-4">

                            <img src="<?php echo 'assets/images/room/' . htmlspecialchars($room['anh_phong']); ?>"
                                alt="Product Image" />
                            <h3><?php echo htmlspecialchars($room['ma_phong']); ?></h3>
                            <p>Tầng: <?php echo htmlspecialchars($room['tang']); ?></p>
                            <p>Diện tích: <?php echo htmlspecialchars($room['dien_tich']); ?> m²</p>
                            <p>Sức chứa tối đa: <?php echo htmlspecialchars($room['suc_chua_toi_da']); ?> người</p>
                            <p>Mô tả: <?php echo htmlspecialchars($room['mo_ta']); ?></p>
                            <p>Trạng thái: <strong><?php echo htmlspecialchars($room['trang_thai_phong']); ?></strong>
                            </p>
                            <?php if ($room['trang_thai_phong'] === 'Còn chỗ') : ?>
                            <button class="btn btn-primary"
                                data-room="<?php echo htmlspecialchars($room['ma_phong']); ?>">Thuê ngay</button>
                            <?php else : ?>
                            <button class="btn btn-secondary" disabled>Không khả dụng</button>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                        <?php else : ?>
                        <p>Hiện tại không có phòng nào.</p>
                        <?php endif; ?>

                    </div>
                </section>
            </div>
        </div>

        <!-- Popup -->
        <div class="popup-overlay"></div>
        <div class="popup">
            <button class="close-btn">&times;</button>
            <h5>Yêu Cầu Thuê Phòng</h5>
            <form id="bookingForm">
                <input type="hidden" id="selectedRoom">
                <div class="mb-3">
                    <label for="studentName" class="form-label">Tên Sinh Viên</label>
                    <input type="text" class="form-control" id="studentName" placeholder="Nhập tên" required>
                </div>
                <div class="mb-3">
                    <label for="studentEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="studentEmail" placeholder="Nhập email" required>
                </div>
                <div class="mb-3">
                    <label for="studentPhone" class="form-label">Số Điện Thoại</label>
                    <input type="tel" class="form-control" id="studentPhone" placeholder="Nhập số điện thoại" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Gửi Yêu Cầu</button>
            </form>
        </div>

        <script>
        const overlay = document.querySelector('.popup-overlay');
        const popup = document.querySelector('.popup');
        const closeBtn = document.querySelector('.close-btn');

        document.querySelectorAll('.btn-primary').forEach(button => {
            button.addEventListener('click', () => {
                const room = button.getAttribute('data-room');
                document.getElementById('selectedRoom').value = room;

                overlay.style.display = 'block';
                popup.style.display = 'block';
            });
        });

        closeBtn.addEventListener('click', () => {
            overlay.style.display = 'none';
            popup.style.display = 'none';
        });

        overlay.addEventListener('click', () => {
            overlay.style.display = 'none';
            popup.style.display = 'none';
        });

        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const name = document.getElementById('studentName').value;
            const email = document.getElementById('studentEmail').value;
            const phone = document.getElementById('studentPhone').value;
            const room = document.getElementById('selectedRoom').value;

            alert(`Yêu cầu thuê phòng thành công!\n\nTên: ${name}\nEmail: ${email}\nPhòng: ${room}`);
            document.getElementById('bookingForm').reset();

            overlay.style.display = 'none';
            popup.style.display = 'none';
        });
        </script>
    </body>

</html>