<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include("dbconfig.php");

$user_id = $_SESSION['user_id'];
$successMessage = "";
$errorMessage = "";

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    // Check if email already exists for another user
    $checkEmail = "SELECT id FROM user WHERE email='$email' AND id != '$user_id'";
    $result = mysqli_query($conn, $checkEmail);
    
    if (mysqli_num_rows($result) > 0) {
        $errorMessage = "Email already exists for another user.";
    } else {
        // Update user profile
        $updateQuery = "UPDATE user SET name='$name', email='$email', phone='$phone' WHERE id='$user_id'";
        
        if (mysqli_query($conn, $updateQuery)) {
            // Update session variables
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $successMessage = "Profile updated successfully!";
        } else {
            $errorMessage = "Failed to update profile. Please try again.";
        }
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = mysqli_real_escape_string($conn, $_POST['current_password']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    
    // Verify current password
    $checkPass = "SELECT pass FROM user WHERE id='$user_id' AND pass='$current_password'";
    $result = mysqli_query($conn, $checkPass);
    
    if (mysqli_num_rows($result) == 0) {
        $errorMessage = "Current password is incorrect.";
    } elseif ($new_password !== $confirm_password) {
        $errorMessage = "New passwords do not match.";
    } elseif (strlen($new_password) < 6) {
        $errorMessage = "Password must be at least 6 characters long.";
    } else {
        // Update password
        $updatePass = "UPDATE user SET pass='$new_password' WHERE id='$user_id'";
        
        if (mysqli_query($conn, $updatePass)) {
            $_SESSION['password'] = $new_password;
            $successMessage = "Password changed successfully!";
        } else {
            $errorMessage = "Failed to change password. Please try again.";
        }
    }
}

// Fetch user data
$query = "SELECT * FROM user WHERE id='$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Get user's complaint statistics
$statsQuery = "SELECT 
    COUNT(*) as total_complaints,
    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) as in_progress,
    SUM(CASE WHEN status = 'Resolved' THEN 1 ELSE 0 END) as resolved
    FROM complain WHERE email='{$user['email']}'";
$statsResult = mysqli_query($conn, $statsQuery);
$stats = mysqli_fetch_assoc($statsResult);
?>

<?php include("header.php"); ?>

<div class="container mx-auto px-4 py-12">
    <div class="max-w-6xl mx-auto">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-gradient mb-4">My Profile</h1>
            <p class="text-xl text-gray-600">Manage your account settings and preferences</p>
        </div>

        <?php if (!empty($successMessage)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-xl mb-6 animate-fade-in" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-xl"></i>
                    <span class="font-medium"><?php echo $successMessage; ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl mb-6 animate-fade-in" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
                    <span class="font-medium"><?php echo $errorMessage; ?></span>
                </div>
            </div>
        <?php endif; ?>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- User Card -->
                <div class="bg-white rounded-3xl shadow-xl p-8 mb-6 text-center">
                    <div class="w-32 h-32 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-white text-5xl font-bold">
                            <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                        </span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($user['name']); ?></h2>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($user['email']); ?></p>
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-calendar-alt mr-2"></i>Member since <?php echo date('M Y'); ?>
                    </div>
                </div>

                <!-- Statistics Card -->
                <div class="bg-white rounded-3xl shadow-xl p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Complaint Statistics</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Complaints</span>
                            <span class="bg-blue-100 text-blue-800 px-4 py-1 rounded-full font-bold">
                                <?php echo $stats['total_complaints'] ?? 0; ?>
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Pending</span>
                            <span class="bg-yellow-100 text-yellow-800 px-4 py-1 rounded-full font-bold">
                                <?php echo $stats['pending'] ?? 0; ?>
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">In Progress</span>
                            <span class="bg-orange-100 text-orange-800 px-4 py-1 rounded-full font-bold">
                                <?php echo $stats['in_progress'] ?? 0; ?>
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Resolved</span>
                            <span class="bg-green-100 text-green-800 px-4 py-1 rounded-full font-bold">
                                <?php echo $stats['resolved'] ?? 0; ?>
                            </span>
                        </div>
                    </div>
                    <a href="complainhistory.php" class="block mt-6 text-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300">
                        <i class="fas fa-history mr-2"></i>View All Complaints
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Profile Information -->
                <div class="bg-white rounded-3xl shadow-xl p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-user-edit mr-3 text-blue-600"></i>Profile Information
                        </h3>
                    </div>
                    
                    <form method="POST" action="profile.php" class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                                <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required
                                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required
                                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                            <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" name="update_profile"
                                class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-save mr-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="bg-white rounded-3xl shadow-xl p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-lock mr-3 text-blue-600"></i>Change Password
                        </h3>
                    </div>
                    
                    <form method="POST" action="profile.php" class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Current Password *</label>
                            <input type="password" name="current_password" required
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">New Password *</label>
                                <input type="password" name="new_password" required minlength="6"
                                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                                <p class="text-xs text-gray-500 mt-1">Minimum 6 characters</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password *</label>
                                <input type="password" name="confirm_password" required minlength="6"
                                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" name="change_password"
                                class="bg-gradient-to-r from-green-600 to-blue-600 text-white px-8 py-3 rounded-xl font-semibold hover:from-green-700 hover:to-blue-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-key mr-2"></i>Change Password
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Account Actions -->
                <div class="bg-white rounded-3xl shadow-xl p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-cog mr-3 text-blue-600"></i>Account Actions
                    </h3>
                    
                    <div class="space-y-4">
                        <a href="complainhistory.php" 
                            class="flex items-center justify-between p-4 border-2 border-gray-200 rounded-xl hover:border-blue-500 transition-all duration-300 group">
                            <div class="flex items-center">
                                <i class="fas fa-history text-2xl text-blue-600 mr-4"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-800">My Complaints</h4>
                                    <p class="text-sm text-gray-600">View and track your complaints</p>
                                </div>
                            </div>
                            <i class="fas fa-arrow-right text-gray-400 group-hover:text-blue-600 transition-colors"></i>
                        </a>
                        
                        <a href="complaintform.php" 
                            class="flex items-center justify-between p-4 border-2 border-gray-200 rounded-xl hover:border-blue-500 transition-all duration-300 group">
                            <div class="flex items-center">
                                <i class="fas fa-plus-circle text-2xl text-green-600 mr-4"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-800">File New Complaint</h4>
                                    <p class="text-sm text-gray-600">Submit a new complaint</p>
                                </div>
                            </div>
                            <i class="fas fa-arrow-right text-gray-400 group-hover:text-blue-600 transition-colors"></i>
                        </a>
                        
                        <a href="logout.php" 
                            class="flex items-center justify-between p-4 border-2 border-red-200 rounded-xl hover:border-red-500 transition-all duration-300 group">
                            <div class="flex items-center">
                                <i class="fas fa-sign-out-alt text-2xl text-red-600 mr-4"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Logout</h4>
                                    <p class="text-sm text-gray-600">Sign out of your account</p>
                                </div>
                            </div>
                            <i class="fas fa-arrow-right text-gray-400 group-hover:text-red-600 transition-colors"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
