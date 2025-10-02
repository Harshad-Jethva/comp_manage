<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIXBOT - Smart Complaint Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="tailwindcss.js"></script>
    <script src="main.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    
    <link rel="stylesheet" href="main.css">
</head>

<body class="bg-gray-50 overflow-x-hidden">
    <!-- Loading Screen -->
    <!-- <div id="loadingScreen"
        class="fixed inset-0 bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center z-50">
        <div class="text-center text-white">
            <div class="spinner mx-auto mb-4"></div>
            <h2 class="text-2xl font-bold mb-2">Loading FIXBOT</h2>
            <p class="loading-dots">Initializing AI systems</p>
        </div>
    </div> -->

    <!-- Notification System -->
    <div id="notification" class="notification bg-green-500 text-white px-6 py-4 shadow-2xl">
        <div class="flex items-center space-x-3">
            <i id="notificationIcon" class="fas fa-check-circle text-xl"></i>
            <span id="notificationText" class="font-medium"></span>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-white/90 backdrop-blur-md shadow-xl sticky top-0 z-40 border-b border-gray-200">
        <nav class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3 slide-in-left">
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-3 rounded-xl shadow-lg pulse-glow">
                        <i class="fas fa-robot text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gradient">FIXBOT</h1>
                        <p class="text-xs text-gray-500">Smart Complaint System</p>
                    </div>
                </div>

                <!-- Language Selector -->
                <div class="flex items-center space-x-6 slide-in-right">
                    <div class="relative">
                        <select id="languageSelector"
                            class="bg-white/80 backdrop-blur-sm border-2 border-gray-200 rounded-xl px-4 py-2 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 hover:shadow-lg">
                            <option value="en">English</option>
                            <option value="hi">Hindi</option>
                            <option value="gu">Gujarati</option>
                        </select>
                    </div>

                    <!-- Navigation Menu -->
                    <div class="hidden lg:flex items-center space-x-8">
                        <a href="index.php" 
                            class="nav-link text-gray-700 hover:text-blue-600 font-medium transition-all duration-300 relative group">
                            Home
                            <span
                                class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                        <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="complaintform.php" 
                            class="nav-link text-gray-700 hover:text-blue-600 font-medium transition-all duration-300 relative group">
                            File Complaint
                            <span
                                class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                        <a href="complainhistory.php" 
                            class="nav-link text-gray-700 hover:text-blue-600 font-medium transition-all duration-300 relative group">
                            My Complaints
                            <span
                                class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                        <?php endif; ?>
                        <a href="about.php" 
                            class="nav-link text-gray-700 hover:text-blue-600 font-medium transition-all duration-300 relative group">
                            About
                            <span
                                class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                        <a href="team.php" 
                            class="nav-link text-gray-700 hover:text-blue-600 font-medium transition-all duration-300 relative group">
                            Team
                            <span
                                class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                        <a href="contact.php" 
                            class="nav-link text-gray-700 hover:text-blue-600 font-medium transition-all duration-300 relative group">
                            Contact
                            <span
                                class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                    </div>

                    <!-- User Actions -->
                    <div id="userActions" class="flex items-center space-x-3">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <!-- Logged in user -->
                            <div class="flex items-center space-x-3">
                                <a href="profile.php" class="text-gray-700 font-medium hover:text-blue-600 transition-colors duration-300">
                                    <i class="fas fa-user-circle mr-2"></i>
                                    <?php echo htmlspecialchars($_SESSION['name']); ?>
                                </a>
                                <a href="logout.php"
                                    class="bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-2.5 rounded-xl font-semibold hover:from-red-700 hover:to-red-800 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </a>
                            </div>
                        <?php else: ?>
                            <!-- Not logged in -->
                            <button onclick="showModal('loginModal')"
                                class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-2.5 rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login
                            </button>
                            <button onclick="showModal('signupModal')"
                                class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-2.5 rounded-xl font-semibold hover:from-purple-700 hover:to-purple-800 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <i class="fas fa-user-plus mr-2"></i>Sign Up
                            </button>
                        <?php endif; ?>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button id="mobileMenuBtn"
                        class="lg:hidden text-gray-700 p-2 rounded-lg hover:bg-gray-100 transition-colors duration-300">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="lg:hidden mt-6 pb-6 border-t border-gray-200 hidden">
                <div class="flex flex-col space-y-4 mt-6">
                    <a href="index.php" 
                        class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-300 py-2">
                        <i class="fas fa-home mr-3"></i>Home
                    </a>
                    <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="complaintform.php" 
                        class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-300 py-2">
                        <i class="fas fa-plus mr-3"></i>File Complaint
                    </a>
                    <a href="complainhistory.php" 
                        class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-300 py-2">
                        <i class="fas fa-history mr-3"></i>My Complaints
                    </a>
                    <?php endif; ?>
                    <a href="about.php" 
                        class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-300 py-2">
                        <i class="fas fa-info-circle mr-3"></i>About
                    </a>
                    <a href="team.php" 
                        class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-300 py-2">
                        <i class="fas fa-users mr-3"></i>Team
                    </a>
                    <a href="contact.php" 
                        class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-300 py-2">
                        <i class="fas fa-envelope mr-3"></i>Contact
                    </a>
                    
                    <!-- Mobile User Actions -->
                    <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div class="text-gray-700 font-medium py-2 mb-3">
                            <i class="fas fa-user-circle mr-3"></i><?php echo htmlspecialchars($_SESSION['name']); ?>
                        </div>
                        <a href="profile.php" 
                            class="block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-xl font-semibold text-center hover:from-blue-700 hover:to-purple-700 transition-all duration-300 mb-3">
                            <i class="fas fa-user-edit mr-2"></i>My Profile
                        </a>
                        <a href="logout.php" 
                            class="block bg-gradient-to-r from-red-600 to-red-700 text-white px-4 py-2 rounded-xl font-semibold text-center hover:from-red-700 hover:to-red-800 transition-all duration-300">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="border-t border-gray-200 pt-4 mt-4 space-y-3">
                        <button onclick="showModal('loginModal')" 
                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-300">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </button>
                        <button onclick="showModal('signupModal')" 
                            class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white px-4 py-2 rounded-xl font-semibold hover:from-purple-700 hover:to-purple-800 transition-all duration-300">
                            <i class="fas fa-user-plus mr-2"></i>Sign Up
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>