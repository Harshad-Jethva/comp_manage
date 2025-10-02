<?php
session_start();
require_once '../dbconfig.php';
require_once 'admin_functions.php';

// Check admin login
checkAdminLogin();

// Handle profile updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'update_profile':
            $admin_name = mysqli_real_escape_string($conn, $_POST['admin_name']);
            $admin_email = mysqli_real_escape_string($conn, $_POST['admin_email']);
            $admin_phone = mysqli_real_escape_string($conn, $_POST['admin_phone']);
            $admin_bio = mysqli_real_escape_string($conn, $_POST['admin_bio']);
            
            // Update session
            $_SESSION['admin_name'] = $admin_name;
            $_SESSION['admin_email'] = $admin_email;
            $_SESSION['admin_phone'] = $admin_phone;
            $_SESSION['admin_bio'] = $admin_bio;
            
            $success_message = "Profile updated successfully!";
            break;
            
        case 'update_avatar':
            $selected_avatar = $_POST['selected_avatar'] ?? '';
            $_SESSION['admin_avatar'] = $selected_avatar;
            $success_message = "Avatar updated successfully!";
            break;
            
        case 'change_password':
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];
            
            if ($new_password === $confirm_password && strlen($new_password) >= 6) {
                // In a real implementation, verify current password and update
                $success_message = "Password changed successfully!";
            } else {
                $error_message = "Password requirements not met or passwords don't match!";
            }
            break;
    }
}

// Avatar options
$avatar_options = [
    'gradient-1' => 'bg-gradient-to-br from-blue-500 to-purple-600',
    'gradient-2' => 'bg-gradient-to-br from-green-500 to-blue-500',
    'gradient-3' => 'bg-gradient-to-br from-purple-500 to-pink-500',
    'gradient-4' => 'bg-gradient-to-br from-yellow-500 to-red-500',
    'gradient-5' => 'bg-gradient-to-br from-indigo-500 to-purple-500',
    'gradient-6' => 'bg-gradient-to-br from-pink-500 to-rose-500',
    'gradient-7' => 'bg-gradient-to-br from-cyan-500 to-blue-500',
    'gradient-8' => 'bg-gradient-to-br from-emerald-500 to-teal-500'
];

$current_avatar = $_SESSION['admin_avatar'] ?? 'gradient-1';
?>
<!DOCTYPE html>
<html lang="en" class="<?php echo isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'true' ? 'dark' : ''; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - FIXBOT Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .profile-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .avatar-option {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .avatar-option:hover {
            transform: scale(1.1);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        .avatar-option.selected {
            transform: scale(1.1);
            box-shadow: 0 0 0 3px #3b82f6;
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-slate-900">
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="lg:ml-64 min-h-screen">
        <div class="p-4">
            <?php if (isset($success_message)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 animate-pulse">
                <i class="fas fa-check-circle mr-2"></i><?php echo $success_message; ?>
            </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error_message; ?>
            </div>
            <?php endif; ?>

            <!-- Profile Header Card -->
            <div class="profile-card rounded-3xl p-8 mb-6 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                <div class="relative z-10">
                    <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
                        <div class="relative">
                            <div class="w-32 h-32 <?php echo $avatar_options[$current_avatar]; ?> rounded-full flex items-center justify-center text-4xl font-bold text-white ring-4 ring-white ring-opacity-30">
                                <?php echo strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)); ?>
                            </div>
                            <button onclick="openAvatarModal()" class="absolute bottom-2 right-2 bg-white text-gray-700 rounded-full p-2 shadow-lg hover:shadow-xl transition-all duration-200">
                                <i class="fas fa-camera text-sm"></i>
                            </button>
                        </div>
                        
                        <div class="text-center md:text-left flex-1">
                            <h1 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></h1>
                            <p class="text-xl opacity-90 mb-4">System Administrator</p>
                            <p class="opacity-80 max-w-md">
                                <?php echo htmlspecialchars($_SESSION['admin_bio'] ?? 'Dedicated administrator managing the FIXBOT complaint management system with expertise in citizen services and digital governance.'); ?>
                            </p>
                            
                            <div class="flex flex-wrap gap-4 mt-6">
                                <div class="glass-effect rounded-lg px-4 py-2">
                                    <i class="fas fa-envelope mr-2"></i>
                                    <?php echo htmlspecialchars($_SESSION['admin_email'] ?? 'admin@fixbot.com'); ?>
                                </div>
                                <div class="glass-effect rounded-lg px-4 py-2">
                                    <i class="fas fa-phone mr-2"></i>
                                    <?php echo htmlspecialchars($_SESSION['admin_phone'] ?? '+1 (555) 123-4567'); ?>
                                </div>
                                <div class="glass-effect rounded-lg px-4 py-2">
                                    <i class="fas fa-calendar mr-2"></i>
                                    Joined <?php echo date('M Y'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Complaints</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">1,247</p>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                            <i class="fas fa-exclamation-triangle text-blue-600 dark:text-blue-400"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Resolved Today</p>
                            <p class="text-2xl font-bold text-green-600">23</p>
                        </div>
                        <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                            <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Response Time</p>
                            <p class="text-2xl font-bold text-purple-600">2.4h</p>
                        </div>
                        <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                            <i class="fas fa-clock text-purple-600 dark:text-purple-400"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Satisfaction</p>
                            <p class="text-2xl font-bold text-yellow-600">94%</p>
                        </div>
                        <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                            <i class="fas fa-star text-yellow-600 dark:text-yellow-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Settings -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-6 flex items-center">
                        <i class="fas fa-user-edit mr-3 text-blue-500"></i>
                        Personal Information
                    </h3>
                    
                    <form method="POST">
                        <input type="hidden" name="action" value="update_profile">
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                                <input type="text" name="admin_name" value="<?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?>" 
                                       class="w-full bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                                <input type="email" name="admin_email" value="<?php echo htmlspecialchars($_SESSION['admin_email'] ?? 'admin@fixbot.com'); ?>" 
                                       class="w-full bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                                <input type="tel" name="admin_phone" value="<?php echo htmlspecialchars($_SESSION['admin_phone'] ?? '+1 (555) 123-4567'); ?>" 
                                       class="w-full bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bio</label>
                                <textarea name="admin_bio" rows="4" 
                                          class="w-full bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                          placeholder="Tell us about yourself..."><?php echo htmlspecialchars($_SESSION['admin_bio'] ?? ''); ?></textarea>
                            </div>
                            
                            <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-200 font-medium">
                                <i class="fas fa-save mr-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Security Settings -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-6 flex items-center">
                        <i class="fas fa-shield-alt mr-3 text-green-500"></i>
                        Security Settings
                    </h3>
                    
                    <form method="POST">
                        <input type="hidden" name="action" value="change_password">
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Password</label>
                                <input type="password" name="current_password" required 
                                       class="w-full bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Password</label>
                                <input type="password" name="new_password" required minlength="6"
                                       class="w-full bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Minimum 6 characters</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm New Password</label>
                                <input type="password" name="confirm_password" required minlength="6"
                                       class="w-full bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            </div>
                            
                            <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-teal-600 text-white px-6 py-3 rounded-lg hover:from-green-600 hover:to-teal-700 transition-all duration-200 font-medium">
                                <i class="fas fa-key mr-2"></i>Change Password
                            </button>
                        </div>
                    </form>

                    <!-- Two-Factor Authentication -->
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-slate-700">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h4 class="font-medium text-gray-800 dark:text-white">Two-Factor Authentication</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Add an extra layer of security</p>
                            </div>
                            <button class="bg-gray-200 dark:bg-slate-600 relative inline-flex h-6 w-11 items-center rounded-full transition-colors">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Avatar Selection Modal -->
    <div id="avatarModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 max-w-md w-full mx-4">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Choose Avatar</h2>
                <button onclick="closeAvatarModal()" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form method="POST">
                <input type="hidden" name="action" value="update_avatar">
                <input type="hidden" name="selected_avatar" id="selectedAvatar" value="<?php echo $current_avatar; ?>">
                
                <div class="grid grid-cols-4 gap-4 mb-6">
                    <?php foreach ($avatar_options as $key => $class): ?>
                    <div class="avatar-option w-16 h-16 <?php echo $class; ?> rounded-full flex items-center justify-center text-white font-bold text-lg <?php echo $key === $current_avatar ? 'selected' : ''; ?>" 
                         data-avatar="<?php echo $key; ?>">
                        <?php echo strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="flex space-x-3">
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        Save Avatar
                    </button>
                    <button type="button" onclick="closeAvatarModal()" class="flex-1 bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAvatarModal() {
            document.getElementById('avatarModal').classList.remove('hidden');
            document.getElementById('avatarModal').classList.add('flex');
        }

        function closeAvatarModal() {
            document.getElementById('avatarModal').classList.add('hidden');
            document.getElementById('avatarModal').classList.remove('flex');
        }

        // Avatar selection
        document.querySelectorAll('.avatar-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.avatar-option').forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                document.getElementById('selectedAvatar').value = this.dataset.avatar;
            });
        });

        // Close modal when clicking outside
        document.getElementById('avatarModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAvatarModal();
            }
        });
    </script>
</body>
</html>
