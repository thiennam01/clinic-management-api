document.addEventListener('DOMContentLoaded', () => {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    const openSidebar = () => {
        sidebar?.classList.remove('-translate-x-full');
        sidebarOverlay?.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };

    const closeSidebar = () => {
        sidebar?.classList.add('-translate-x-full');
        sidebarOverlay?.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    sidebarToggle?.addEventListener('click', openSidebar);
    sidebarOverlay?.addEventListener('click', closeSidebar);

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            closeSidebar();
        }
    });
});