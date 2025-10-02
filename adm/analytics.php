<?php
session_start();
require_once '../dbconfig.php';
require_once 'admin_functions.php';

// Check admin login
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_email'])) {
    header('Location: login.php');
    exit();
}

// Get analytics data
$stats = getDashboardStats($conn);
$sectorStats = getSectorStats($conn);
$statusStats = getStatusStats($conn);

// Get total users count
$userQuery = "SELECT COUNT(*) as total_users FROM user";
$userResult = $conn->query($userQuery);
$totalUsers = $userResult ? $userResult->fetch_assoc()['total_users'] : 0;

// Get active admins count
$adminQuery = "SELECT COUNT(*) as total_admins FROM admin WHERE status = '1'";
$adminResult = $conn->query($adminQuery);
$totalAdmins = $adminResult ? $adminResult->fetch_assoc()['total_admins'] : 0;
?>
<!DOCTYPE html>
<html lang="en" class="<?php echo isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'true' ? 'dark' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIXBOT Admin Panel - Analytics</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .hover-lift {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .main-content {
            margin-left: 0;
            transition: margin-left 0.3s ease-in-out;
            min-height: 100vh;
            padding: 0;
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-slate-900">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main Content Wrapper -->
        <div class="flex flex-col lg:pl-64 flex-1">
            <!-- Include Header -->
            <?php include 'includes/header.php'; ?>

    <!-- Main Content -->
    <main id="mainContent" class="main-content min-h-screen">
        <!-- Header -->
        <header class="bg-white dark:bg-slate-800 shadow-sm border-b border-gray-200 dark:border-slate-700">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center space-x-4">
                    <button id="sidebarToggle" class="lg:hidden text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Analytics & Reports</h2>
                </div>

                <div class="flex items-center space-x-3">
                    <!-- Date Range Selector -->
                    <select id="dateRange" class="bg-gray-100 dark:bg-slate-700 border-0 rounded-xl px-4 py-2 text-sm">
                        <option value="7">Last 7 days</option>
                        <option value="30" selected>Last 30 days</option>
                        <option value="90">Last 90 days</option>
                        <option value="365">Last year</option>
                    </select>

                    <!-- Export Button -->
                    <button onclick="exportReport()" class="bg-green-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-green-700 transition-colors">
                        <i class="fas fa-download mr-2"></i>Export Report
                    </button>

                    <!-- Refresh Button -->
                    <button onclick="refreshAnalytics()" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-blue-700 transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </button>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="p-6">
            <!-- Key Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-6 text-white hover-lift">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm">Total Complaints</p>
                            <p class="text-3xl font-bold"><?php echo number_format($stats['total_complaints']); ?></p>
                        </div>
                        <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                            <i class="fas fa-exclamation-triangle text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <i class="fas fa-arrow-up text-sm mr-1"></i>
                        <span class="text-sm">+12% from last month</span>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white hover-lift">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm">Resolution Rate</p>
                            <p class="text-3xl font-bold"><?php echo $stats['total_complaints'] > 0 ? round(($stats['resolved_count'] / $stats['total_complaints']) * 100, 1) : 0; ?>%</p>
                        </div>
                        <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <i class="fas fa-arrow-up text-sm mr-1"></i>
                        <span class="text-sm">+5% from last month</span>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-2xl p-6 text-white hover-lift">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm">Avg Response Time</p>
                            <p class="text-3xl font-bold">2.4h</p>
                        </div>
                        <div class="bg-yellow-400 bg-opacity-30 rounded-full p-3">
                            <i class="fas fa-clock text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <i class="fas fa-arrow-down text-sm mr-1"></i>
                        <span class="text-sm">-15% from last month</span>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl p-6 text-white hover-lift">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm">Satisfaction Score</p>
                            <p class="text-3xl font-bold">4.2/5</p>
                        </div>
                        <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                            <i class="fas fa-star text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <i class="fas fa-arrow-up text-sm mr-1"></i>
                        <span class="text-sm">+0.3 from last month</span>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid lg:grid-cols-2 gap-6 mb-8">
                <!-- Sector Distribution Chart -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg hover-lift">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Complaints by Sector</h3>
                    <div class="h-80">
                        <canvas id="sectorChart"></canvas>
                    </div>
                </div>

                <!-- Status Distribution Chart -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg hover-lift">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Status Distribution</h3>
                    <div class="h-80">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Trends and Performance -->
            <div class="grid lg:grid-cols-2 gap-6 mb-8">
                <!-- Monthly Trends -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg hover-lift">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Monthly Trends</h3>
                    <div class="h-80">
                        <canvas id="trendsChart"></canvas>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg hover-lift">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Performance Metrics</h3>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-700 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                    <i class="fas fa-clock text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">Avg Resolution Time</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Time to resolve complaints</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-800 dark:text-white">4.2 days</p>
                                <p class="text-xs text-green-600 dark:text-green-400">↓ 12%</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-700 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                    <i class="fas fa-thumbs-up text-green-600 dark:text-green-400"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">Satisfaction Rate</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">User satisfaction score</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-800 dark:text-white">87%</p>
                                <p class="text-xs text-green-600 dark:text-green-400">↑ 5%</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-700 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                                    <i class="fas fa-users text-yellow-600 dark:text-yellow-400"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">Total Users</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Registered users</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-800 dark:text-white"><?php echo number_format($totalUsers); ?></p>
                                <p class="text-xs text-blue-600 dark:text-blue-400"><i class="fas fa-user"></i></p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-700 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-shield text-purple-600 dark:text-purple-400"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">Active Admins</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Admin users</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-800 dark:text-white"><?php echo number_format($totalAdmins); ?></p>
                                <p class="text-xs text-purple-600 dark:text-purple-400"><i class="fas fa-shield-alt"></i></p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-700 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">Overdue Complaints</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Complaints past SLA</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-800 dark:text-white"><?php echo $stats['overdue_count']; ?></p>
                                <p class="text-xs text-red-600 dark:text-red-400">↑ 3%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Performers and Insights -->
            <div class="grid lg:grid-cols-2 gap-6">
                <!-- Top Performing Sectors -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg hover-lift">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Top Performing Sectors</h3>
                    <div class="space-y-4">
                        <?php 
                        $sortedSectors = $sectorStats;
                        arsort($sortedSectors);
                        $topSectors = array_slice($sortedSectors, 0, 5, true);
                        foreach ($topSectors as $sector => $count): 
                        ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-700 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-bold text-blue-600 dark:text-blue-400"><?php echo array_search($sector, array_keys($sortedSectors)) + 1; ?></span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white"><?php echo htmlspecialchars($sector); ?></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo $count; ?> complaints</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-800 dark:text-white"><?php echo $stats['total_complaints'] > 0 ? round(($count / $stats['total_complaints']) * 100, 1) : 0; ?>%</p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Recent Insights -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg hover-lift">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Recent Insights</h3>
                    <div class="space-y-4">
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border-l-4 border-blue-500">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-lightbulb text-blue-600 dark:text-blue-400 mt-1"></i>
                                <div>
                                    <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Peak Complaint Hours</p>
                                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">Most complaints are submitted between 9-11 AM and 2-4 PM</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-xl border-l-4 border-green-500">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-trending-up text-green-600 dark:text-green-400 mt-1"></i>
                                <div>
                                    <p class="text-sm font-medium text-green-800 dark:text-green-200">Resolution Improvement</p>
                                    <p class="text-xs text-green-600 dark:text-green-400 mt-1">Average resolution time decreased by 12% this month</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl border-l-4 border-yellow-500">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 mt-1"></i>
                                <div>
                                    <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Attention Needed</p>
                                    <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">Infrastructure sector has highest pending complaints</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl border-l-4 border-purple-500">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-star text-purple-600 dark:text-purple-400 mt-1"></i>
                                <div>
                                    <p class="text-sm font-medium text-purple-800 dark:text-purple-200">User Satisfaction</p>
                                    <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">87% of users rate the service as excellent or good</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Initialize the application
        document.addEventListener('DOMContentLoaded', function () {
            initializeTheme();
            initializeSidebar();
            initializeCharts();
        });

        // Theme Management
        function initializeTheme() {
            // Theme is managed by header.php, just reinitialize charts on theme change
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        setTimeout(() => initializeCharts(), 100);
                    }
                });
            });
            observer.observe(document.documentElement, { attributes: true });
        }

        // Sidebar Management
        function initializeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function () {
                    sidebar.classList.toggle('open');
                    if (window.innerWidth <= 1024) {
                        sidebarOverlay.classList.toggle('active');
                        document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : 'auto';
                    }
                });
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function () {
                    sidebar.classList.remove('open');
                    sidebarOverlay.classList.remove('active');
                    document.body.style.overflow = 'auto';
                });
            }

            window.addEventListener('resize', function () {
                if (window.innerWidth > 1024) {
                    sidebar.classList.remove('open');
                    if (sidebarOverlay) sidebarOverlay.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });
        }

        // Chart Initialization
        function initializeCharts() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#f1f5f9' : '#374151';
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

            // Destroy existing charts
            Chart.helpers.each(Chart.instances, function (instance) {
                instance.destroy();
            });

            // Sector Chart
            const sectorCtx = document.getElementById('sectorChart');
            if (sectorCtx) {
                new Chart(sectorCtx, {
                    type: 'doughnut',
                    data: {
                        labels: <?php echo json_encode(array_keys($sectorStats)); ?>,
                        datasets: [{
                            data: <?php echo json_encode(array_values($sectorStats)); ?>,
                            backgroundColor: ['#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { 
                                    color: textColor, 
                                    usePointStyle: true, 
                                    padding: 15,
                                    font: { size: 12 }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Status Chart
            const statusCtx = document.getElementById('statusChart');
            if (statusCtx) {
                new Chart(statusCtx, {
                    type: 'bar',
                    data: {
                        labels: <?php echo json_encode(array_keys($statusStats)); ?>,
                        datasets: [{
                            data: <?php echo json_encode(array_values($statusStats)); ?>,
                            backgroundColor: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444'],
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `Count: ${context.parsed.y}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                ticks: { 
                                    color: textColor,
                                    stepSize: 1
                                },
                                grid: { color: gridColor }
                            },
                            x: { 
                                ticks: { color: textColor },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            // Trends Chart
            const trendsCtx = document.getElementById('trendsChart');
            if (trendsCtx) {
                new Chart(trendsCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Complaints',
                            data: [12, 19, 15, 25, 22, 30, 28, 35, 32, 40, 38, 45],
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        }, {
                            label: 'Resolved',
                            data: [8, 15, 12, 20, 18, 25, 22, 28, 26, 32, 30, 38],
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                labels: { 
                                    color: textColor,
                                    usePointStyle: true,
                                    padding: 15
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                ticks: { 
                                    color: textColor,
                                    stepSize: 5
                                },
                                grid: { color: gridColor }
                            },
                            x: { 
                                ticks: { color: textColor },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }
        }

        function exportReport() {
            showNotification('Preparing report...', 'info');
            
            // Create a comprehensive CSV export
            let csvContent = "data:text/csv;charset=utf-8,";
            
            // Add header
            csvContent += "FIXBOT Analytics Report\n";
            csvContent += "Generated: " + new Date().toLocaleString() + "\n\n";
            
            // Add key metrics
            csvContent += "Key Metrics\n";
            csvContent += "Metric,Value\n";
            csvContent += "Total Complaints,<?php echo $stats['total_complaints']; ?>\n";
            csvContent += "Resolved Complaints,<?php echo $stats['resolved_count']; ?>\n";
            csvContent += "Pending Complaints,<?php echo $stats['pending_count']; ?>\n";
            csvContent += "Overdue Complaints,<?php echo $stats['overdue_count']; ?>\n";
            csvContent += "Resolution Rate,<?php echo $stats['total_complaints'] > 0 ? round(($stats['resolved_count'] / $stats['total_complaints']) * 100, 1) : 0; ?>%\n";
            csvContent += "Total Users,<?php echo $totalUsers; ?>\n";
            csvContent += "Active Admins,<?php echo $totalAdmins; ?>\n\n";
            
            // Add sector distribution
            csvContent += "Complaints by Sector\n";
            csvContent += "Sector,Count\n";
            <?php foreach ($sectorStats as $sector => $count): ?>
            csvContent += "<?php echo addslashes($sector); ?>,<?php echo $count; ?>\n";
            <?php endforeach; ?>
            csvContent += "\n";
            
            // Add status distribution
            csvContent += "Status Distribution\n";
            csvContent += "Status,Count\n";
            <?php foreach ($statusStats as $status => $count): ?>
            csvContent += "<?php echo addslashes($status); ?>,<?php echo $count; ?>\n";
            <?php endforeach; ?>
            
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "analytics_report_" + new Date().toISOString().split('T')[0] + ".csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Show success notification
            showNotification('Report exported successfully!', 'success');
        }
        
        function refreshAnalytics() {
            showNotification('Refreshing analytics...', 'info');
            setTimeout(() => {
                location.reload();
            }, 500);
        }
        
        // Date range change handler
        document.getElementById('dateRange')?.addEventListener('change', function() {
            showNotification('Date range filter coming soon!', 'info');
            // TODO: Implement date range filtering
        });
        
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>
</body>
</html>