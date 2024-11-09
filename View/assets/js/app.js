document.addEventListener('DOMContentLoaded', function () {
    var inputs = document.querySelectorAll('input[type="text"], input[type="email"],input[type="password"],input[type="number"], textarea');
    inputs.forEach(function (input) {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/<script[^>]*>.*<\/script>/gi, '');
        });
    });

    var togglePasswordIcons = document.querySelectorAll('.toggle-password');
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
});