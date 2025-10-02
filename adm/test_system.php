<?php
session_start();
require_once '../dbconfig.php';
require_once 'admin_functions.php';

// Check admin login
checkAdminLogin();

// Test all CRUD operations
$testResults = [];

// Test 1: Dashboard Stats
try {
    $stats = getDashboardStats($conn);
    $testResults['dashboard_stats'] = [
        'status' => 'PASS',
        'message' => 'Dashboard statistics loaded successfully',
        'data' => $stats
    ];
} catch (Exception $e) {
    $testResults['dashboard_stats'] = [
        'status' => 'FAIL',
        'message' => 'Failed to load dashboard statistics: ' . $e->getMessage()
    ];
}

// Test 2: Get All Sectors
try {
    $sectors = getAllSectors($conn);
    $testResults['get_sectors'] = [
        'status' => 'PASS',
        'message' => 'Sectors loaded successfully',
        'count' => count($sectors)
    ];
} catch (Exception $e) {
    $testResults['get_sectors'] = [
        'status' => 'FAIL',
        'message' => 'Failed to load sectors: ' . $e->getMessage()
    ];
}

// Test 3: Get Admin Users
try {
    $admins = getAdminUsers($conn);
    $testResults['get_admin_users'] = [
        'status' => 'PASS',
        'message' => 'Admin users loaded successfully',
        'count' => count($admins)
    ];
} catch (Exception $e) {
    $testResults['get_admin_users'] = [
        'status' => 'FAIL',
        'message' => 'Failed to load admin users: ' . $e->getMessage()
    ];
}

// Test 4: Get Recent Complaints
try {
    $complaints = getRecentComplaints($conn, 5);
    $testResults['get_recent_complaints'] = [
        'status' => 'PASS',
        'message' => 'Recent complaints loaded successfully',
        'count' => count($complaints)
    ];
} catch (Exception $e) {
    $testResults['get_recent_complaints'] = [
        'status' => 'FAIL',
        'message' => 'Failed to load recent complaints: ' . $e->getMessage()
    ];
}

// Test 5: Test API Endpoints
$apiTests = [
    'getComplaints' => 'api.php?action=getComplaints',
    'getUsers' => 'api.php?action=getUsers',
    'getSectors' => 'api.php?action=getSectors',
    'getAdminUsers' => 'api.php?action=getAdminUsers',
    'getDashboardStats' => 'api.php?action=getDashboardStats'
];

foreach ($apiTests as $testName => $endpoint) {
    try {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/' . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Cookie: ' . session_name() . '=' . session_id()
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200) {
            $data = json_decode($response, true);
            $testResults['api_' . $testName] = [
                'status' => $data['success'] ? 'PASS' : 'FAIL',
                'message' => $data['success'] ? 'API endpoint working correctly' : 'API returned error: ' . ($data['error'] ?? 'Unknown error'),
                'http_code' => $httpCode
            ];
        } else {
            $testResults['api_' . $testName] = [
                'status' => 'FAIL',
                'message' => 'API endpoint returned HTTP ' . $httpCode,
                'http_code' => $httpCode
            ];
        }
    } catch (Exception $e) {
        $testResults['api_' . $testName] = [
            'status' => 'FAIL',
            'message' => 'API test failed: ' . $e->getMessage()
        ];
    }
}

// Test 6: Database Connection
try {
    if ($conn && mysqli_ping($conn)) {
        $testResults['database_connection'] = [
            'status' => 'PASS',
            'message' => 'Database connection is active'
        ];
    } else {
        $testResults['database_connection'] = [
            'status' => 'FAIL',
            'message' => 'Database connection is not active'
        ];
    }
} catch (Exception $e) {
    $testResults['database_connection'] = [
        'status' => 'FAIL',
        'message' => 'Database connection test failed: ' . $e->getMessage()
    ];
}

// Test 7: Session Management
try {
    if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_name'])) {
        $testResults['session_management'] = [
            'status' => 'PASS',
            'message' => 'Admin session is active',
            'admin_id' => $_SESSION['admin_id'],
            'admin_name' => $_SESSION['admin_name']
        ];
    } else {
        $testResults['session_management'] = [
            'status' => 'FAIL',
            'message' => 'Admin session is not active'
        ];
    }
} catch (Exception $e) {
    $testResults['session_management'] = [
        'status' => 'FAIL',
        'message' => 'Session management test failed: ' . $e->getMessage()
    ];
}

// Calculate overall test results
$totalTests = count($testResults);
$passedTests = count(array_filter($testResults, function($test) {
    return $test['status'] === 'PASS';
}));
$failedTests = $totalTests - $passedTests;
$successRate = $totalTests > 0 ? round(($passedTests / $totalTests) * 100, 1) : 0;
?>
<!DOCTYPE html>
<html lang="en" class="<?php echo isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'true' ? 'dark' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIXBOT Admin Panel - System Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
            width: 280px;
            position: fixed;
            height: 100vh;
            z-index: 50;
        }

        .sidebar.open {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
            transition: margin-left 0.3s ease-in-out;
            min-height: 100vh;
            padding: 0;
        }

        @media (min-width: 1024px) {
            .sidebar {
                transform: translateX(0);
                position: relative;
            }
            .main-content {
                margin-left: 280px;
            }
        }

        @media (max-width: 1023px) {
            .main-content {
                margin-left: 0 !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-slate-900 font-sans">
    <!-- Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar fixed left-0 top-0 z-50 h-full w-64 bg-white dark:bg-slate-800 shadow-xl lg:relative lg:translate-x-0">
        <div class="flex h-full flex-col">
            <!-- Logo -->
            <div class="flex items-center justify-center p-6 border-b border-gray-200 dark:border-slate-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-robot text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800 dark:text-white">FIXBOT</h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Admin Panel</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2">
                <a href="dashboard.php" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors duration-200">
                    <i class="fas fa-chart-pie w-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="complaints.php" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors duration-200">
                    <i class="fas fa-exclamation-triangle w-5"></i>
                    <span>Complaints</span>
                </a>

                <a href="users.php" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors duration-200">
                    <i class="fas fa-users w-5"></i>
                    <span>Users</span>
                </a>

                <a href="analytics.php" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors duration-200">
                    <i class="fas fa-chart-bar w-5"></i>
                    <span>Analytics</span>
                </a>

                <a href="sectors.php" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors duration-200">
                    <i class="fas fa-building w-5"></i>
                    <span>Sectors</span>
                </a>

                <a href="reports.php" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors duration-200">
                    <i class="fas fa-file-alt w-5"></i>
                    <span>Reports</span>
                </a>
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-gray-200 dark:border-slate-700">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                        <?php echo strtoupper(substr($_SESSION['admin_name'], 0, 2)); ?>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800 dark:text-white"><?php echo htmlspecialchars($_SESSION['admin_name']); ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo htmlspecialchars($_SESSION['admin_role']); ?></p>
                    </div>
                </div>
                <a href="logout.php" class="w-full bg-red-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-red-700 transition-colors duration-200 flex items-center justify-center">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main id="mainContent" class="main-content min-h-screen">
        <!-- Header -->
        <header class="bg-white dark:bg-slate-800 shadow-sm border-b border-gray-200 dark:border-slate-700">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center space-x-4">
                    <button id="sidebarToggle" class="lg:hidden text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">System Test Results</h2>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Refresh Button -->
                    <button onclick="location.reload()" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-blue-700">
                        <i class="fas fa-refresh mr-2"></i>Refresh Tests
                    </button>

                    <!-- Theme Toggle -->
                    <button id="themeToggle" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                        <i class="fas fa-moon text-xl dark:hidden"></i>
                        <i class="fas fa-sun text-xl hidden dark:inline"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="p-6">
            <!-- Test Summary -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm">Total Tests</p>
                            <p class="text-3xl font-bold"><?php echo $totalTests; ?></p>
                        </div>
                        <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                            <i class="fas fa-vial text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm">Passed</p>
                            <p class="text-3xl font-bold"><?php echo $passedTests; ?></p>
                        </div>
                        <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-2xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-100 text-sm">Failed</p>
                            <p class="text-3xl font-bold"><?php echo $failedTests; ?></p>
                        </div>
                        <div class="bg-red-400 bg-opacity-30 rounded-full p-3">
                            <i class="fas fa-times-circle text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm">Success Rate</p>
                            <p class="text-3xl font-bold"><?php echo $successRate; ?>%</p>
                        </div>
                        <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                            <i class="fas fa-percentage text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Test Results -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Detailed Test Results</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-slate-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Test Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Message</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Details</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                            <?php foreach ($testResults as $testName => $result): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                    <?php echo ucwords(str_replace('_', ' ', $testName)); ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $result['status'] === 'PASS' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'; ?>">
                                        <i class="fas fa-<?php echo $result['status'] === 'PASS' ? 'check' : 'times'; ?> mr-1"></i>
                                        <?php echo $result['status']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    <?php echo htmlspecialchars($result['message']); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    <?php if (isset($result['count'])): ?>
                                        Count: <?php echo $result['count']; ?>
                                    <?php elseif (isset($result['http_code'])): ?>
                                        HTTP: <?php echo $result['http_code']; ?>
                                    <?php elseif (isset($result['admin_id'])): ?>
                                        ID: <?php echo $result['admin_id']; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- System Information -->
            <div class="mt-8 bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">System Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Server Information</h4>
                        <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                            <li>PHP Version: <?php echo PHP_VERSION; ?></li>
                            <li>Server: <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></li>
                            <li>Document Root: <?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown'; ?></li>
                            <li>Current Time: <?php echo date('Y-m-d H:i:s'); ?></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Database Information</h4>
                        <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                            <li>MySQL Version: <?php echo mysqli_get_server_info($conn); ?></li>
                            <li>Database: <?php echo mysqli_get_server_info($conn); ?></li>
                            <li>Connection Status: <?php echo mysqli_ping($conn) ? 'Active' : 'Inactive'; ?></li>
                            <li>Charset: <?php echo mysqli_character_set_name($conn); ?></li>
                        </ul>
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
        });

        // Theme Management
        function initializeTheme() {
            document.getElementById('themeToggle').addEventListener('click', function () {
                const isDark = document.documentElement.classList.contains('dark');
                document.documentElement.classList.toggle('dark');
                document.cookie = `darkMode=${!isDark}; path=/; max-age=31536000`;
            });
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
    </script>
</body>
</html>
