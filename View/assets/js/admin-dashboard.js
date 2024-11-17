document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab');
    const contentTabs = document.querySelectorAll('.content-tab');
    contentTabs.forEach(content => {
        content.style.display = "none";
    });
    const sidebarTabs = document.querySelectorAll('.nav-item');
    sidebarTabs.forEach(tab => {
        tab.classList.remove('bg-dark');
    });
    if (tab) {
        const contentTab = document.getElementById('content-' + tab);
        if (contentTab) {
            contentTab.style.display = "block";
        }
        const sidebarTab = document.getElementById('tab-' + tab);
        if (sidebarTab) {
            sidebarTab.classList.add('bg-dark');
        }
    } else {
        const firstTab = document.getElementById('content-product');
        if (firstTab) {
            firstTab.style.display = "block";
        }

        const firstSidebarTab = document.getElementById('tab-product');
        if (firstSidebarTab) {
            firstSidebarTab.classList.add('bg-dark');
        }
    }
});