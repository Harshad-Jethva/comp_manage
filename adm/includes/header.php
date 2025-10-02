<?php
// Ensure database connection is available
if (!isset($conn)) {
    require_once '../dbconfig.php';
}
?>
<!-- Sidebar Overlay for mobile -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

<!-- Header -->
<!-- The 'sticky top-0' class ensures this header stays fixed to the top of its parent container. -->
<header class="bg-white dark:bg-slate-800 shadow-sm border-b border-gray-200 dark:border-slate-700 sticky top-0 z-30">
    <div class="flex items-center justify-between px-6 py-3">
        <!-- Left Side: Toggle and Title -->
        <div class="flex items-center space-x-4">
            <!-- Mobile Sidebar Toggle -->
            <button id="sidebarToggle" class="lg:hidden text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-all duration-200">
                <i class="fas fa-bars text-lg"></i>
            </button>
            
            <!-- Page Title -->
            <h2 id="pageTitle" class="hidden sm:block text-xl font-bold text-gray-800 dark:text-white">
                <?php 
                // Dynamically set page title from the filename
                $page = basename($_SERVER['PHP_SELF'], '.php');
                if ($page === 'layout_example') $page = 'dashboard';
                echo ucfirst(str_replace('_', ' ', $page));
                ?>
            </h2>
        </div>

        <!-- Right Side: Search, Actions, Profile -->
        <div class="flex items-center space-x-3">
            <!-- Search -->
            <div class="hidden md:flex relative">
                <input type="text" placeholder="Quick search..." 
                       class="bg-gray-100 dark:bg-slate-700 border-0 rounded-lg pl-10 pr-4 py-2 text-sm w-64 focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
            </div>

            <!-- Notifications -->
            <div class="relative">
                <button id="notificationBtn" class="relative p-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-all duration-200">
                    <i class="fas fa-bell text-lg"></i>
                    <?php
                    $notifQuery = "SELECT COUNT(*) as count FROM complain WHERE status = 'pending' AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)";
                    $notifResult = mysqli_query($conn, $notifQuery);
                    $notifCount = mysqli_fetch_assoc($notifResult)['count'];
                    if ($notifCount > 0):
                    ?>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse"><?php echo $notifCount; ?></span>
                    <?php endif; ?>
                </button>
                <div id="notificationDropdown" class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-gray-200 dark:border-slate-700 hidden z-50">
                    <div class="p-4 border-b border-gray-200 dark:border-slate-700">
                        <h3 class="font-semibold text-gray-800 dark:text-white">Notifications</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <?php
                        $recentNotifs = mysqli_query($conn, "SELECT * FROM complain WHERE status = 'pending' ORDER BY created_at DESC LIMIT 5");
                        if (mysqli_num_rows($recentNotifs) > 0):
                            while ($notif = mysqli_fetch_assoc($recentNotifs)):
                        ?>
                        <a href="complaints.php?id=<?php echo $notif['id']; ?>" class="block p-4 hover:bg-gray-50 dark:hover:bg-slate-700 border-b border-gray-100 dark:border-slate-700">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                                        <i class="fas fa-exclamation text-yellow-600 dark:text-yellow-400"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        New Complaint: <?php echo htmlspecialchars($notif['title']); ?>
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        <?php echo date('M d, Y h:i A', strtotime($notif['created_at'])); ?>
                                    </p>
                                </div>
                            </div>
                        </a>
                        <?php 
                            endwhile;
                        else:
                        ?>
                        <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                            <i class="fas fa-check-circle text-3xl mb-2"></i>
                            <p>No new notifications</p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-3 border-t border-gray-200 dark:border-slate-700">
                        <a href="complaints.php" class="block text-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                            View All Complaints
                        </a>
                    </div>
                </div>
            </div>

            <!-- Dark Mode Toggle -->
            <button id="darkModeToggle" class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-all duration-200">
                <i class="fas fa-moon dark:hidden text-lg"></i>
                <i class="fas fa-sun hidden dark:block text-lg"></i>
            </button>

            <!-- Profile Dropdown -->
            <div class="relative">
                <button id="profileBtn" class="flex items-center space-x-3 p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-all duration-200">
                    <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center ring-2 ring-blue-200 dark:ring-blue-800">
                        <span class="text-white font-semibold text-sm">
                            <?php echo strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)); ?>
                        </span>
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?>
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Administrator</p>
                    </div>
                    <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200" id="profileChevron"></i>
                </button>
                <div id="profileDropdown" class="absolute right-0 mt-2 w-64 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-gray-200 dark:border-slate-700 hidden z-50">
                    <div class="p-4 border-b border-gray-200 dark:border-slate-700">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?>
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            <?php echo htmlspecialchars($_SESSION['admin_email'] ?? ''); ?>
                        </p>
                    </div>
                    <div class="py-2">
                        <a href="profile.php" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700">
                            <i class="fas fa-user w-5 mr-3"></i>
                            My Profile
                        </a>
                        <a href="settings.php" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700">
                            <i class="fas fa-cog w-5 mr-3"></i>
                            Settings
                        </a>
                        <a href="reports.php" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700">
                            <i class="fas fa-file-alt w-5 mr-3"></i>
                            Reports
                        </a>
                    </div>
                    <div class="border-t border-gray-200 dark:border-slate-700 py-2">
                        <a href="logout.php" class="flex items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                            <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
// Header functionality
document.addEventListener('DOMContentLoaded', function() {
    const darkModeToggle = document.getElementById('darkModeToggle');
    const html = document.documentElement;

    // Helper to toggle dropdown visibility
    const toggleDropdown = (button, dropdown, chevron) => {
        // Close all other dropdowns first
        document.querySelectorAll('.dropdown-menu').forEach(d => {
            if (d !== dropdown) d.classList.add('hidden');
        });
        if (chevron) {
             document.querySelectorAll('.dropdown-chevron').forEach(c => {
                if (c !== chevron) c.style.transform = 'rotate(0deg)';
             });
        }
        
        dropdown.classList.toggle('hidden');
        if (chevron) {
            chevron.style.transform = dropdown.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        }
    };
    
    // Dropdown toggles
    const profileBtn = document.getElementById('profileBtn');
    const profileDropdown = document.getElementById('profileDropdown');
    const profileChevron = document.getElementById('profileChevron');
    if (profileBtn) {
        profileDropdown.classList.add('dropdown-menu');
        profileChevron.classList.add('dropdown-chevron');
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleDropdown(profileBtn, profileDropdown, profileChevron);
        });
    }

    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');
    if (notificationBtn) {
        notificationDropdown.classList.add('dropdown-menu');
         notificationBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleDropdown(notificationBtn, notificationDropdown);
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown-menu').forEach(d => d.classList.add('hidden'));
        document.querySelectorAll('.dropdown-chevron').forEach(c => c.style.transform = 'rotate(0deg)');
    });

    // Dark Mode
    const applyDarkMode = (isDark) => {
        if (isDark) {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }
    };

    const savedMode = localStorage.getItem('darkMode') === 'true';
    applyDarkMode(savedMode);

    darkModeToggle.addEventListener('click', () => {
        const isDark = !html.classList.contains('dark');
        applyDarkMode(isDark);
        localStorage.setItem('darkMode', isDark);
    });
});
</script>
