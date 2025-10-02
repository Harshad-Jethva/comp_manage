<?php
session_start();
require_once '../dbconfig.php';
require_once 'admin_functions.php';

// Check admin login
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_email'])) {
    header('Location: login.php');
    exit();
}

// Fetch data for dashboard
$stats = getDashboardStats($conn);
$recentComplaints = getRecentComplaints($conn, 5);
$sectorStats = getSectorStats($conn);
$statusStats = getStatusStats($conn);
$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM user"))['total'];
$totalAdmins = getTotalAdmins($conn);

// Get monthly trend data
$monthlyQuery = "SELECT 
    DATE_FORMAT(created_at, '%b') as month,
    COUNT(*) as count 
    FROM complain 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY created_at";
$monthlyResult = mysqli_query($conn, $monthlyQuery);
$monthlyData = [];
while ($row = mysqli_fetch_assoc($monthlyResult)) {
    $monthlyData[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en" class="<?php echo isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'true' ? 'dark' : ''; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stats-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        .table-striped tbody tr:nth-child(odd) {
            background-color: #f9fafb;
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
        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="stats-card bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 text-white p-6 rounded-lg shadow-md">
                    <h2 class="text-lg">Total Complaints</h2>
                    <p class="text-3xl font-bold"><?php echo $stats['total_complaints']; ?></p>
                </div>
                <div class="stats-card bg-gradient-to-br from-green-500 via-green-600 to-green-700 text-white p-6 rounded-lg shadow-md">
                    <h2 class="text-lg">Resolved Complaints</h2>
                    <p class="text-3xl font-bold"><?php echo $stats['resolved_count']; ?></p>
                </div>
                <div class="stats-card bg-gradient-to-br from-yellow-500 via-yellow-600 to-yellow-700 text-white p-6 rounded-lg shadow-md">
                    <h2 class="text-lg">Pending Complaints</h2>
                    <p class="text-3xl font-bold"><?php echo $stats['pending_count']; ?></p>
                </div>
                <div class="stats-card bg-gradient-to-br from-red-500 via-red-600 to-red-700 text-white p-6 rounded-lg shadow-md">
                    <h2 class="text-lg">Overdue Complaints</h2>
                    <p class="text-3xl font-bold"><?php echo $stats['overdue_count']; ?></p>
                </div>
                <div class="stats-card bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 text-white p-6 rounded-lg shadow-md">
                    <h2 class="text-lg">Total Users</h2>
                    <p class="text-3xl font-bold"><?php echo $totalUsers; ?></p>
                </div>
                <div class="stats-card bg-gradient-to-br from-indigo-500 via-indigo-600 to-indigo-700 text-white p-6 rounded-lg shadow-md">
                    <h2 class="text-lg">Total Admins</h2>
                    <p class="text-3xl font-bold"><?php echo $totalAdmins; ?></p>
                </div>
            </div>

            <!-- Recent Complaints -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h2 class="text-xl font-bold mb-4">Recent Complaints</h2>
                <table class="w-full border-collapse border border-gray-300 table-striped">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 p-2">ID</th>
                            <th class="border border-gray-300 p-2">Title</th>
                            <th class="border border-gray-300 p-2">Sector</th>
                            <th class="border border-gray-300 p-2">Status</th>
                            <th class="border border-gray-300 p-2">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentComplaints as $complaint): ?>
                        <tr>
                            <td class="border border-gray-300 p-2 text-center">FB<?php echo str_pad($complaint['id'], 6, '0', STR_PAD_LEFT); ?></td>
                            <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($complaint['title']); ?></td>
                            <td class="border border-gray-300 p-2 text-center"><?php echo htmlspecialchars($complaint['complaint_sector']); ?></td>
                            <td class="border border-gray-300 p-2 text-center"><?php echo htmlspecialchars($complaint['status']); ?></td>
                            <td class="border border-gray-300 p-2 text-center"><?php echo date('Y-m-d', strtotime($complaint['created_at'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold mb-4">Complaints by Sector</h2>
                    <canvas id="sectorChart"></canvas>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold mb-4">Complaints by Status</h2>
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- Monthly Trend Chart -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h2 class="text-xl font-bold mb-4">Monthly Complaint Trend (Last 6 Months)</h2>
                <canvas id="monthlyTrendChart"></canvas>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="complaints.php" class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">View All Complaints</h3>
                            <p class="text-sm opacity-90">Manage and track complaints</p>
                        </div>
                        <i class="fas fa-arrow-right text-2xl"></i>
                    </div>
                </a>
                <a href="users.php" class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Manage Users</h3>
                            <p class="text-sm opacity-90">View and edit user accounts</p>
                        </div>
                        <i class="fas fa-arrow-right text-2xl"></i>
                    </div>
                </a>
                <a href="reports.php" class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Generate Reports</h3>
                            <p class="text-sm opacity-90">Export and analyze data</p>
                        </div>
                        <i class="fas fa-arrow-right text-2xl"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Sector Chart
        const sectorCtx = document.getElementById('sectorChart').getContext('2d');
        new Chart(sectorCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_keys($sectorStats)); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_values($sectorStats)); ?>,
                    backgroundColor: ['#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_keys($statusStats)); ?>,
                datasets: [{
                    label: 'Number of Complaints',
                    data: <?php echo json_encode(array_values($statusStats)); ?>,
                    backgroundColor: ['#3b82f6', '#f59e0b', '#10b981', '#ef4444'],
                    borderRadius: 8,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Monthly Trend Chart
        const monthlyTrendCtx = document.getElementById('monthlyTrendChart').getContext('2d');
        new Chart(monthlyTrendCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($monthlyData, 'month')); ?>,
                datasets: [{
                    label: 'Complaints',
                    data: <?php echo json_encode(array_column($monthlyData, 'count')); ?>,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Auto-refresh dashboard every 30 seconds
        setInterval(() => {
            location.reload();
        }, 30000);
    </script>
</body>
</html>