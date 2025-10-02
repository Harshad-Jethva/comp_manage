<!-- Sidebar -->
<!-- KEY CHANGES: The 'fixed' class now applies to all screen sizes. It's hidden off-screen on mobile by default -->
<!-- and becomes visible on larger screens (`lg:translate-x-0`). -->
<aside id="sidebar" class="sidebar bg-slate-800 text-white w-64 fixed inset-y-0 left-0 z-50 
                           transform -translate-x-full lg:translate-x-0 
                           transition-transform duration-300 ease-in-out">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 bg-slate-900 border-b border-slate-700 shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-robot text-white text-lg"></i>
                </div>
                <span class="text-xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                    FIXBOT
                </span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
            <a href="dashboard.php" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-slate-700 hover:text-white transition-all duration-200 group">
                <i class="fas fa-tachometer-alt w-5 text-center group-hover:scale-110 transition-transform"></i>
                <span>Dashboard</span>
            </a>
            <a href="complaints.php" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-slate-700 hover:text-white transition-all duration-200 group">
                <i class="fas fa-exclamation-triangle w-5 text-center group-hover:scale-110 transition-transform"></i>
                <span>Complaints</span>
            </a>
            <a href="users.php" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-slate-700 hover:text-white transition-all duration-200 group">
                <i class="fas fa-users w-5 text-center group-hover:scale-110 transition-transform"></i>
                <span>Users</span>
            </a>
            <a href="analytics.php" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-slate-700 hover:text-white transition-all duration-200 group">
                <i class="fas fa-chart-bar w-5 text-center group-hover:scale-110 transition-transform"></i>
                <span>Analytics</span>
            </a>
            <a href="reports.php" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-slate-700 hover:text-white transition-all duration-200 group">
                <i class="fas fa-file-alt w-5 text-center group-hover:scale-110 transition-transform"></i>
                <span>Reports</span>
            </a>
            <a href="settings.php" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-slate-700 hover:text-white transition-all duration-200 group">
                <i class="fas fa-cog w-5 text-center group-hover:scale-110 transition-transform"></i>
                <span>Settings</span>
            </a>
        </nav>

        <!-- User Profile -->
        <div class="p-4 border-t border-slate-700 shrink-0">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-blue-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-shield text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-white"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></p>
                    <p class="text-xs text-gray-400">Administrator</p>
                </div>
            </div>
            <a href="logout.php" class="w-full bg-red-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-red-700 transition-colors duration-200 flex items-center justify-center">
                <i class="fas fa-sign-out-alt mr-2"></i>Logout
            </a>
        </div>
    </div>
</aside>

<!-- This script and style block should ideally be in your main layout file, but is kept here for component encapsulation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle'); // This button is in header.php
    const sidebarOverlay = document.getElementById('sidebarOverlay'); // This is in header.php

    // Function to open/close sidebar
    const toggleSidebar = () => {
        sidebar.classList.toggle('-translate-x-full');
        if (sidebarOverlay) {
            sidebarOverlay.classList.toggle('hidden');
        }
        // Prevent body scroll when sidebar is open on mobile
        if (window.innerWidth < 1024) {
             document.body.classList.toggle('overflow-hidden', !sidebar.classList.contains('-translate-x-full'));
        }
    };
    
    // Event listeners
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', toggleSidebar);
    }

    // Highlight active page
    const currentPage = window.location.pathname.split('/').pop() || 'dashboard.php';
    document.querySelectorAll('.nav-item').forEach(item => {
        if (item.getAttribute('href') === currentPage) {
            item.classList.add('bg-slate-700', 'text-white');
            item.classList.remove('text-gray-300');
        }
    });
});
</script>
