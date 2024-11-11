document.addEventListener('DOMContentLoaded', function () {
    var inputs = document.querySelectorAll('input[type="text"], input[type="email"],input[type="password"],input[type="number"], textarea');
    inputs.forEach(function (input) {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/<script[^>]*>.*<\/script>/gi, '');
        });
    });

    var togglePasswordIcons = document.querySelectorAll('.toggle-password');
    var togglePasswordIconsEdit = document.querySelectorAll('.show-pass-word');
    togglePasswordIcons.forEach(function (icon) {
        icon.addEventListener('click', function () {
            var passwordInput = icon.previousElementSibling;
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.innerHTML = '<i class="see-password fa fa-eye-slash"></i>';
            } else {
                passwordInput.type = 'password';
                icon.innerHTML = '<i class="fa fa-eye see-password"></i>';
            }
        });
    });
    togglePasswordIconsEdit.forEach(function (icon) {
        icon.addEventListener('click', function () {
            var passwordInput = this.previousElementSibling;
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.innerHTML = '<i class="fa fa-eye-slash"></i>';
            } else {
                passwordInput.type = 'password';
                this.innerHTML = '<i class="fa fa-eye"></i>';
            }
        });
    });
});
// Lấy phần tử img và input file
const avatarInput = document.getElementById('avatar');
const currentAvatar = document.getElementById('currentAvatar');

// Lắng nghe sự kiện change của input file
avatarInput.addEventListener('change', function (event) {
    const file = event.target.files[0]; // Lấy file ảnh được chọn

    // Kiểm tra nếu có file được chọn
    if (file) {
        // Tạo URL tạm thời cho ảnh
        const imageUrl = URL.createObjectURL(file);

        // Cập nhật src của ảnh
        currentAvatar.src = imageUrl;
    } else {
        // Nếu không có ảnh nào được chọn, giữ nguyên ảnh hiện tại
        avatarInput.value = ""; // Đặt lại input file để không xóa tên file hiện tại
    }
});

