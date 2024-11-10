document.addEventListener("DOMContentLoaded", function() {
    // Lắng nghe sự kiện click vào các tab
    var tabs = document.querySelectorAll(".nav-item");
    var contentTabs = document.querySelectorAll(".content-tab");

    tabs.forEach(function(tab) {
        tab.addEventListener("click", function() {
            // Loại bỏ class 'active' ở tất cả các tab
            tabs.forEach(function(tab) {
                tab.classList.remove("active");
            });

            // Thêm class 'active' vào tab hiện tại
            tab.classList.add("active");

            // Ẩn tất cả các tab nội dung
            contentTabs.forEach(function(tab) {
                tab.style.display = "none";
            });

            // Lấy id của tab hiện tại và hiển thị nội dung tương ứng
            var contentId = "content-" + tab.id.split('-')[1];
            document.getElementById(contentId).style.display = "block";
        });
    });

    // Mặc định hiển thị nội dung tab "Sản Phẩm" và đánh dấu 'active'
    document.getElementById("tab-product").classList.add("active");
    document.getElementById("content-product").style.display = "block";
});
