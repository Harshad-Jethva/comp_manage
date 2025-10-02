<?php
session_start();
require_once '../dbconfig.php';
require_once 'admin_functions.php';

// Check admin login
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_email'])) {
    header('Location: login.php');
    exit();
}

// Generate report data
$report_type = $_GET['type'] ?? 'summary';
// Default to last 90 days to capture existing data
$date_from = $_GET['date_from'] ?? date('Y-m-d', strtotime('-90 days'));
$date_to = $_GET['date_to'] ?? date('Y-m-d');

// Summary Report Data
// Note: The 'status' field in complain table contains priority values (immediate/week/month)
// We'll use complaint_status_summary table if it has data, otherwise show all as pending
$summary_query = "SELECT 
    COUNT(*) as total_complaints,
    SUM(CASE WHEN css.status = 'resolved' THEN 1 ELSE 0 END) as resolved_count,
    SUM(CASE WHEN css.status = 'Pending' OR css.status IS NULL THEN 1 ELSE 0 END) as pending_count,
    SUM(CASE WHEN css.status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_count
FROM complain c
LEFT JOIN complaint_status_summary css ON c.id = css.compid
WHERE DATE(c.created_at) BETWEEN '$date_from' AND '$date_to'";
$summary_result = mysqli_query($conn, $summary_query);
if (!$summary_result) {
    die("Query failed: " . mysqli_error($conn));
}
$summary_stats = mysqli_fetch_assoc($summary_result);
$summary_stats['total_count'] = $summary_stats['total_complaints'] ?? 0;
$summary_stats['resolved_count'] = $summary_stats['resolved_count'] ?? 0;
$summary_stats['pending_count'] = $summary_stats['pending_count'] ?? 0;
$summary_stats['in_progress_count'] = $summary_stats['in_progress_count'] ?? 0;
$summary_stats['resolution_rate'] = $summary_stats['total_count'] > 0 
    ? round(($summary_stats['resolved_count'] / $summary_stats['total_count']) * 100, 1) 
    : 0;

// Detailed Reports
$detailed_query = "SELECT 
    c.id, c.title, c.complaint_sector, c.status as priority,
    COALESCE(LOWER(css.status), 'pending') as status,
    DATE(c.created_at) as date_created,
    DATEDIFF(COALESCE(c.updated_at, NOW()), c.created_at) as days_open
FROM complain c
LEFT JOIN complaint_status_summary css ON c.id = css.compid
WHERE DATE(c.created_at) BETWEEN '$date_from' AND '$date_to'
ORDER BY c.created_at DESC";
$detailed_result = mysqli_query($conn, $detailed_query);
if (!$detailed_result) {
    die("Query failed: " . mysqli_error($conn));
}
$detailed_complaints = [];
while ($row = mysqli_fetch_assoc($detailed_result)) {
    $detailed_complaints[] = $row;
}

// Sector Performance
$sector_performance_query = "SELECT 
    c.complaint_sector AS sector,
    COUNT(*) as total_complaints,
    SUM(CASE WHEN css.status = 'resolved' THEN 1 ELSE 0 END) as resolved_complaints,
    AVG(CASE WHEN css.status = 'resolved' THEN DATEDIFF(c.updated_at, c.created_at) ELSE NULL END) as avg_resolution_days
FROM complain c
LEFT JOIN complaint_status_summary css ON c.id = css.compid
WHERE DATE(c.created_at) BETWEEN '$date_from' AND '$date_to'
AND c.complaint_sector IS NOT NULL AND c.complaint_sector != ''
GROUP BY c.complaint_sector
ORDER BY total_complaints DESC";
$sector_performance_result = mysqli_query($conn, $sector_performance_query);
if (!$sector_performance_result) {
    die("Query failed: " . mysqli_error($conn));
}
$sector_performance = [];
while ($row = mysqli_fetch_assoc($sector_performance_result)) {
    $sector_performance[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en" class="<?php echo isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'true' ? 'dark' : ''; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - FIXBOT Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
        }
        .hover-lift {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
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

    <main class="min-h-screen">
        <div class="p-6">
            <!-- Report Header -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Reports & Analytics</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Generate comprehensive reports and insights</p>
                    </div>
                    
                    <div class="flex gap-3 no-print">
                        <button onclick="exportToCSV()" class="bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-700 transition-colors">
                            <i class="fas fa-file-csv mr-2"></i>Export CSV
                        </button>
                        <button onclick="exportToExcel()" class="bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700 transition-colors">
                            <i class="fas fa-file-excel mr-2"></i>Export Excel
                        </button>
                        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="fas fa-print mr-2"></i>Print
                        </button>
                        <button onclick="refreshReport()" class="bg-purple-600 text-white px-4 py-2 rounded-xl hover:bg-purple-700 transition-colors">
                            <i class="fas fa-sync-alt mr-2"></i>Refresh
                        </button>
                    </div>
                </div>
            </div>

            <!-- Report Filters -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg mb-6 no-print">
                <form method="GET" id="reportForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Report Type</label>
                        <select name="type" id="reportType" class="w-full bg-gray-100 dark:bg-slate-700 border-0 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="summary" <?php echo $report_type === 'summary' ? 'selected' : ''; ?>>Summary Report</option>
                            <option value="detailed" <?php echo $report_type === 'detailed' ? 'selected' : ''; ?>>Detailed Report</option>
                            <option value="performance" <?php echo $report_type === 'performance' ? 'selected' : ''; ?>>Performance Report</option>
                            <option value="user" <?php echo $report_type === 'user' ? 'selected' : ''; ?>>User Activity Report</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Date</label>
                        <input type="date" name="date_from" id="dateFrom" value="<?php echo $date_from; ?>" 
                               class="w-full bg-gray-100 dark:bg-slate-700 border-0 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To Date</label>
                        <input type="date" name="date_to" id="dateTo" value="<?php echo $date_to; ?>" 
                               class="w-full bg-gray-100 dark:bg-slate-700 border-0 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700">
                            <i class="fas fa-search mr-2"></i>Generate Report
                        </button>
                    </div>
                </form>
            </div>

            <?php if ($report_type === 'summary'): ?>
            <!-- Summary Report -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Report Period Info -->
                <div class="col-span-full bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl p-6 text-white mb-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Report Period</h3>
                            <p class="text-blue-100 mt-1"><?php echo date('M j, Y', strtotime($date_from)); ?> - <?php echo date('M j, Y', strtotime($date_to)); ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-blue-100">Generated on</p>
                            <p class="font-semibold"><?php echo date('M j, Y g:i A'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Complaints</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white"><?php echo $summary_stats['total_count'] ?? 0; ?></p>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                            <i class="fas fa-exclamation-triangle text-blue-600 dark:text-blue-400 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg hover-lift">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Resolved</p>
                            <p class="text-3xl font-bold text-green-600"><?php echo $summary_stats['resolved_count']; ?></p>
                        </div>
                        <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                            <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg hover-lift">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
                            <p class="text-3xl font-bold text-yellow-600"><?php echo $summary_stats['pending_count']; ?></p>
                        </div>
                        <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                            <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg hover-lift">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Resolution Rate</p>
                            <p class="text-3xl font-bold text-purple-600">
                                <?php echo $summary_stats['total_count'] > 0 
                                    ? round(($summary_stats['resolved_count'] / $summary_stats['total_count']) * 100, 1) 
                                    : 0; ?>%
                            </p>
                        </div>
                        <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                            <i class="fas fa-chart-line text-purple-600 dark:text-purple-400 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($report_type === 'detailed'): ?>
            <!-- Detailed Report -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg overflow-hidden mb-6 hover-lift">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Detailed Complaint Report</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Period: <?php echo date('M j, Y', strtotime($date_from)); ?> - <?php echo date('M j, Y', strtotime($date_to)); ?></p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-slate-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Sector</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Days Open</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date Created</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                            <?php if (empty($detailed_complaints)): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-inbox text-4xl mb-3 opacity-50"></i>
                                    <p>No complaints found for the selected period</p>
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($detailed_complaints as $complaint): ?>
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                    FB<?php echo str_pad($complaint['id'], 6, '0', STR_PAD_LEFT); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    <?php echo htmlspecialchars(substr($complaint['title'], 0, 40)) . '...'; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    <?php echo htmlspecialchars($complaint['complaint_sector'] ?? 'N/A'); ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                        'in_progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                        'resolved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'closed' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                        'immediate' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                        'week' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
                                        'month' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200'
                                    ];
                                    $status = strtolower($complaint['status'] ?? 'pending');
                                    $statusClass = $statusColors[$status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $statusClass; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $status)); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    <?php echo $complaint['days_open']; ?> days
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    <?php echo date('M j, Y', strtotime($complaint['date_created'])); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($report_type === 'performance'): ?>
            <!-- Performance Report -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg overflow-hidden mb-6 hover-lift">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Sector Performance Report</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Performance metrics by sector</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-slate-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Sector</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total Complaints</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Resolved</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Resolution Rate</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Avg Resolution Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                            <?php if (empty($sector_performance)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-chart-bar text-4xl mb-3 opacity-50"></i>
                                    <p>No performance data available for the selected period</p>
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($sector_performance as $sector): ?>
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                    <?php echo htmlspecialchars($sector['sector']); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    <?php echo $sector['total_complaints']; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    <?php echo $sector['resolved_complaints']; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php 
                                    $resolution_rate = $sector['total_complaints'] > 0 ? 
                                        round(($sector['resolved_complaints'] / $sector['total_complaints']) * 100, 1) : 0;
                                    $rate_color = $resolution_rate >= 80 ? 'text-green-600' : 
                                                 ($resolution_rate >= 60 ? 'text-yellow-600' : 'text-red-600');
                                    ?>
                                    <span class="text-sm font-medium <?php echo $rate_color; ?>">
                                        <?php echo $resolution_rate; ?>%
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    <?php echo $sector['avg_resolution_days'] ? round($sector['avg_resolution_days'], 1) . ' days' : 'N/A'; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($report_type === 'user'): ?>
            <!-- User Activity Report -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg overflow-hidden mb-6 hover-lift">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">User Activity Report</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">User registration and activity metrics</p>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php
                        $userCountQuery = "SELECT COUNT(*) as total FROM user";
                        $userCountResult = $conn->query($userCountQuery);
                        $totalUsers = $userCountResult->fetch_assoc()['total'];
                        
                        // Note: user table doesn't have created_at field, so we can't filter by date
                        $newUsersQuery = "SELECT COUNT(*) as new_users FROM user";
                        $newUsersResult = $conn->query($newUsersQuery);
                        $newUsers = $newUsersResult ? $newUsersResult->fetch_assoc()['new_users'] : 0;
                        
                        // Count unique users who filed complaints (by email)
                        $activeUsersQuery = "SELECT COUNT(DISTINCT c.email) as active FROM complain c WHERE DATE(c.created_at) BETWEEN '$date_from' AND '$date_to' AND c.email != ''";
                        $activeUsersResult = $conn->query($activeUsersQuery);
                        $activeUsers = $activeUsersResult ? $activeUsersResult->fetch_assoc()['active'] : 0;
                        ?>
                        
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-sm">Total Users</p>
                                    <p class="text-3xl font-bold"><?php echo number_format($totalUsers); ?></p>
                                </div>
                                <i class="fas fa-users text-4xl opacity-50"></i>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-100 text-sm">New Users (Period)</p>
                                    <p class="text-3xl font-bold"><?php echo number_format($newUsers); ?></p>
                                </div>
                                <i class="fas fa-user-plus text-4xl opacity-50"></i>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100 text-sm">Active Users (Period)</p>
                                    <p class="text-3xl font-bold"><?php echo number_format($activeUsers); ?></p>
                                </div>
                                <i class="fas fa-user-check text-4xl opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
    </div>
    </div>

    <script>
        // Export to CSV
        function exportToCSV() {
            showNotification('Preparing CSV export...', 'info');
            
            const reportType = '<?php echo $report_type; ?>';
            const dateFrom = '<?php echo $date_from; ?>';
            const dateTo = '<?php echo $date_to; ?>';
            
            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "FIXBOT Report\n";
            csvContent += "Report Type: " + reportType.toUpperCase() + "\n";
            csvContent += "Period: " + dateFrom + " to " + dateTo + "\n";
            csvContent += "Generated: " + new Date().toLocaleString() + "\n\n";
            
            <?php if ($report_type === 'summary'): ?>
            csvContent += "Summary Statistics\n";
            csvContent += "Metric,Value\n";
            csvContent += "Total Complaints,<?php echo $summary_stats['total_count']; ?>\n";
            csvContent += "Resolved,<?php echo $summary_stats['resolved_count']; ?>\n";
            csvContent += "Pending,<?php echo $summary_stats['pending_count']; ?>\n";
            csvContent += "Resolution Rate,<?php echo $summary_stats['resolution_rate']; ?>%\n";
            <?php elseif ($report_type === 'detailed'): ?>
            csvContent += "Detailed Complaints\n";
            csvContent += "ID,Title,Sector,Status,Days Open,Date Created\n";
            <?php foreach ($detailed_complaints as $c): ?>
            csvContent += "FB<?php echo str_pad($c['id'], 6, '0', STR_PAD_LEFT); ?>,<?php echo addslashes($c['title']); ?>,<?php echo addslashes($c['complaint_sector']); ?>,<?php echo $c['status'] ?? 'pending'; ?>,<?php echo $c['days_open']; ?>,<?php echo $c['date_created']; ?>\n";
            <?php endforeach; ?>
            <?php elseif ($report_type === 'performance'): ?>
            csvContent += "Sector Performance\n";
            csvContent += "Sector,Total Complaints,Resolved,Resolution Rate,Avg Resolution Time\n";
            <?php foreach ($sector_performance as $s): ?>
            csvContent += "<?php echo addslashes($s['sector']); ?>,<?php echo $s['total_complaints']; ?>,<?php echo $s['resolved_complaints']; ?>,<?php echo round(($s['resolved_complaints'] / $s['total_complaints']) * 100, 1); ?>%,<?php echo $s['avg_resolution_days'] ? round($s['avg_resolution_days'], 1) : 'N/A'; ?>\n";
            <?php endforeach; ?>
            <?php endif; ?>
            
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "fixbot_report_" + reportType + "_" + new Date().toISOString().split('T')[0] + ".csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            showNotification('Report exported successfully!', 'success');
        }
        
        // Export to Excel (HTML table format)
        function exportToExcel() {
            showNotification('Preparing Excel export...', 'info');
            
            const table = document.querySelector('table');
            if (!table) {
                showNotification('No table data to export', 'error');
                return;
            }
            
            const html = table.outerHTML;
            const url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
            const link = document.createElement("a");
            link.setAttribute("href", url);
            link.setAttribute("download", "fixbot_report_" + new Date().toISOString().split('T')[0] + ".xls");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            showNotification('Report exported successfully!', 'success');
        }
        
        // Refresh report
        function refreshReport() {
            showNotification('Refreshing report...', 'info');
            setTimeout(() => {
                location.reload();
            }, 500);
        }
        
        // Show notification
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
