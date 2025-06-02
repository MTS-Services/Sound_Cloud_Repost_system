lucide.createIcons();
// Mobile menu functionality
const mobileMenuBtn = document.getElementById('mobile-menu-btn');
const closeMenu = document.getElementById('close_menu');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');

mobileMenuBtn.addEventListener('click', () => {
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
    mobileMenuBtn.classList.remove('block')
    mobileMenuBtn.classList.add('hidden')
});
closeMenu.addEventListener('click', () => {
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
    mobileMenuBtn.classList.add('block',)
    mobileMenuBtn.classList.remove('hidden')
});


overlay.addEventListener('click', () => {
    sidebar.classList.add('-translate-x-full');
    overlay.classList.add('hidden');
});

// Sidebar navigation with proper ul/li structure
const sidebarItems = document.querySelectorAll('.sidebar-item');

// Function to set active sidebar item
function setActiveSidebarItem(targetPage) {
    // Remove active class from all items
    sidebarItems.forEach(item => {
        item.classList.remove('active');
        item.classList.add('text-gray-700');
    });

    // Add active class to clicked item
    const activeItem = document.querySelector(`[data-page="${targetPage}"]`);
    if (activeItem) {
        activeItem.classList.add('active');
        activeItem.classList.remove('text-gray-700');
    }
}