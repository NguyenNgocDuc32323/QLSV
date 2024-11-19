document.addEventListener("DOMContentLoaded", function() {
    // Lấy tham số 'tab' từ URL
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab');

    // Ẩn tất cả các tab nội dung
    const contentTabs = document.querySelectorAll('.content-tab');
    contentTabs.forEach(content => {
        content.style.display = "none";
    });

    // Ẩn tất cả các tab trong sidebar
    const sidebarTabs = document.querySelectorAll('.nav-item');
    sidebarTabs.forEach(tab => {
        tab.classList.remove('bg-dark'); // Nếu bạn dùng 'bg-dark' để đánh dấu tab đang mở
    });

    // Hiển thị tab tương ứng nếu có tham số 'tab' trong URL
    if (tab) {
        // Hiển thị nội dung của tab
        const contentTab = document.getElementById('content-' + tab);
        if (contentTab) {
            contentTab.style.display = "block";
        }

        // Đánh dấu tab đang mở trong sidebar
        const sidebarTab = document.getElementById('tab-' + tab);
        if (sidebarTab) {
            sidebarTab.classList.add('bg-dark'); // Đánh dấu tab đang mở
        }
    } else {
        // Nếu không có tham số 'tab', mở mặc định tab đầu tiên (product)
        const firstTab = document.getElementById('content-product');
        if (firstTab) {
            firstTab.style.display = "block";
        }

        const firstSidebarTab = document.getElementById('tab-product');
        if (firstSidebarTab) {
            firstSidebarTab.classList.add('bg-dark'); // Đánh dấu tab đầu tiên
        }
    }
});