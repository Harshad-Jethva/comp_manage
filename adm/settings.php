<?php
session_start();
require_once '../dbconfig.php';
require_once 'admin_functions.php';

// Check admin login
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_email'])) {
    header('Location: login.php');
    exit();
}

// Fetch current admin details
$admin_id = $_SESSION['admin_id'];
$query = "SELECT * FROM admin WHERE id = $admin_id";
$result = mysqli_query($conn, $query);
$admin = mysqli_fetch_assoc($result);

// Handle settings updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'update_profile':
            $admin_name = mysqli_real_escape_string($conn, $_POST['admin_name']);
            $admin_email = mysqli_real_escape_string($conn, $_POST['admin_email']);

            $update_query = "UPDATE admin SET email = '$admin_email', role = '$admin_name' WHERE id = $admin_id";
            if (mysqli_query($conn, $update_query)) {
                // Update session
                $_SESSION['admin_name'] = $admin_name;
                $_SESSION['admin_email'] = $admin_email;
                $success_message = "Profile updated successfully!";
            } else {
                $error_message = "Failed to update profile.";
            }
            break;

        case 'change_password':
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if ($new_password === $confirm_password) {
                $password_query = "SELECT pass FROM admin WHERE id = $admin_id";
                $password_result = mysqli_query($conn, $password_query);
                $password_data = mysqli_fetch_assoc($password_result);

                if ($current_password === $password_data['pass']) {
                    $hashed_password = mysqli_real_escape_string($conn, $new_password);
                    $update_password_query = "UPDATE admin SET pass = '$hashed_password' WHERE id = $admin_id";

                    if (mysqli_query($conn, $update_password_query)) {
                        $success_message = "Password changed successfully!";
                    } else {
                        $error_message = "Failed to change password.";
                    }
                } else {
                    $error_message = "Current password is incorrect.";
                }
            } else {
                $error_message = "New passwords do not match!";
            }
            break;

        case 'update_system_settings':
            $dark_mode = isset($_POST['dark_mode']) ? 'true' : 'false';
            $email_notifications = isset($_POST['email_notifications']) ? 'true' : 'false';
            $auto_refresh = intval($_POST['auto_refresh'] ?? 60);
            $items_per_page = intval($_POST['items_per_page'] ?? 20);

            setcookie('darkMode', $dark_mode, time() + (86400 * 30), '/');
            setcookie('emailNotifications', $email_notifications, time() + (86400 * 30), '/');
            setcookie('autoRefresh', $auto_refresh, time() + (86400 * 30), '/');
            setcookie('itemsPerPage', $items_per_page, time() + (86400 * 30), '/');

            // Update cookie values immediately for current request
            $_COOKIE['darkMode'] = $dark_mode;
            $_COOKIE['emailNotifications'] = $email_notifications;
            $_COOKIE['autoRefresh'] = $auto_refresh;
            $_COOKIE['itemsPerPage'] = $items_per_page;

            $success_message = "System settings updated successfully!";
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="<?php echo isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'true' ? 'dark' : ''; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - FIXBOT Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 dark:bg-slate-900">
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="lg:ml-64 min-h-screen">
        <div class="p-6">
            <!-- Settings Header -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg mb-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Settings</h1>
                <p class="text-gray-600 dark:text-gray-400">Manage your admin panel preferences and account settings</p>
            </div>

            <?php if (isset($success_message)): ?>
            <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded-xl mb-6 flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                <span><?php echo $success_message; ?></span>
            </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
            <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 px-4 py-3 rounded-xl mb-6 flex items-center">
                <i class="fas fa-exclamation-circle mr-3"></i>
                <span><?php echo $error_message; ?></span>
            </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Profile Settings -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Profile Settings</h3>
                    
                    <form method="POST">
                        <input type="hidden" name="action" value="update_profile">
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Admin Name</label>
                                <input type="text" name="admin_name" value="<?php echo htmlspecialchars($admin['role'] ?? 'Admin'); ?>" 
                                       class="w-full bg-gray-100 dark:bg-slate-700 dark:text-white border-0 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                <input type="email" name="admin_email" value="<?php echo htmlspecialchars($admin['email'] ?? ''); ?>" 
                                       class="w-full bg-gray-100 dark:bg-slate-700 dark:text-white border-0 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700">
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Password Settings -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Change Password</h3>
                    
                    <form method="POST">
                        <input type="hidden" name="action" value="change_password">
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Password</label>
                                <input type="password" name="current_password" required 
                                       class="w-full bg-gray-100 dark:bg-slate-700 dark:text-white border-0 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Password</label>
                                <input type="password" name="new_password" required 
                                       class="w-full bg-gray-100 dark:bg-slate-700 dark:text-white border-0 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm New Password</label>
                                <input type="password" name="confirm_password" required 
                                       class="w-full bg-gray-100 dark:bg-slate-700 dark:text-white border-0 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-500">
                            </div>
                            
                            <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-700">
                                Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
