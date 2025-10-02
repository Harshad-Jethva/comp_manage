<?php
session_start();
require_once '../dbconfig.php';
require_once 'admin_functions.php';

// Check admin login
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_email'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" class="<?php echo isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'true' ? 'dark' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIXBOT Admin Panel - Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
     
        .main-content {
            margin-left: 0;
            transition: margin-left 0.3s ease-in-out;
            min-height: 100vh;
            padding: 0;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hover-lift {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        @media (min-width: 1024px) {
            .sidebar {
                transform: translateX(0);
                position: relative;
            }
          
        }

        @media (max-width: 1023px) {
            .main-content {
                margin-left: 0 !important;
            }
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
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">User Management</h2>
                </div>

                <div class="flex items-center space-x-3">
                    <button onclick="exportUsers()" class="bg-green-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-green-700 transition-colors">
                        <i class="fas fa-download mr-2"></i>Export CSV
                    </button>
                    <button onclick="refreshUsers()" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-blue-700 transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </button>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="p-6">
            <!-- Filters and Actions -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <select id="statusFilter" class="bg-gray-100 dark:bg-slate-700 border-0 rounded-xl px-4 py-2 text-sm">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                    <div class="relative hidden md:block">
                        <input type="text" id="searchInput" placeholder="Search users..." class="bg-gray-100 dark:bg-slate-700 border-0 rounded-xl pl-10 pr-4 py-2 w-64 focus:ring-2 focus:ring-blue-500" onkeypress="if(event.key === 'Enter') applyFilters()">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="applyFilters()" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-blue-700">
                            <i class="fas fa-filter mr-2"></i>Apply Filters
                        </button>
                        <button onclick="showUserModal()" class="bg-green-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-green-700">
                            <i class="fas fa-plus mr-2"></i>Add User
                        </button>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">All Users</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-slate-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Phone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Joined</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody" class="divide-y divide-gray-200 dark:divide-slate-700">
                            <!-- Data will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- View User Details Modal -->
    <div id="viewUserModal" class="modal">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 w-full max-w-3xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">User Details</h3>
                <button onclick="closeViewUserModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="userDetailsContent" class="space-y-4">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>

    <!-- User Modal -->
    <div id="userModal" class="modal">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h3 id="userModalTitle" class="text-xl font-semibold text-gray-800 dark:text-white">Add New User</h3>
                <button onclick="closeUserModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="userForm" onsubmit="submitUser(event)">
                <input type="hidden" id="userId" name="id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name *</label>
                        <input type="text" id="userName" name="name" required class="w-full bg-gray-100 dark:bg-slate-700 border-0 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
                        <input type="email" id="userEmail" name="email" required class="w-full bg-gray-100 dark:bg-slate-700 border-0 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone *</label>
                        <input type="tel" id="userPhone" name="phone" required class="w-full bg-gray-100 dark:bg-slate-700 border-0 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select id="userStatus" name="status" class="w-full bg-gray-100 dark:bg-slate-700 border-0 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Address *</label>
                    <textarea id="userAddress" name="address" required rows="3" class="w-full bg-gray-100 dark:bg-slate-700 border-0 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="mb-6" id="passwordField">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password *</label>
                    <input type="password" id="userPassword" name="password" class="w-full bg-gray-100 dark:bg-slate-700 border-0 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Leave blank to keep current password when editing</p>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeUserModal()" class="px-6 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-xl hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i>Save User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 w-full max-w-md mx-4">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Delete User</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Are you sure you want to delete this user? This action cannot be undone.</p>
                <div class="flex justify-center space-x-3">
                    <button onclick="closeDeleteModal()" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                        Cancel
                    </button>
                    <button onclick="confirmDelete()" class="bg-red-600 text-white px-4 py-2 rounded-xl hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div id="statusModal" class="modal">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 w-full max-w-md mx-4">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Update User Status</h3>
                <button onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="statusForm" onsubmit="submitStatusUpdate(event)">
                <input type="hidden" id="statusUserId" name="userId">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Status</label>
                    <select id="userStatusSelect" name="status" class="w-full bg-gray-100 dark:bg-slate-700 border-0 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeStatusModal()" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i>Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let deleteUserId = null;
        let statusUserId = null;
        let isViewMode = false;
        let usersCache = []; // Cache users data to reduce API calls
        let currentFilters = {}; // Track current filters

        // Initialize the application
        document.addEventListener('DOMContentLoaded', function () {
            initializeTheme();
            initializeSidebar();
            loadUsers();
        });

        // Theme Management
        function initializeTheme() {
            const themeToggle = document.getElementById('themeToggle');
            if (themeToggle) {
                themeToggle.addEventListener('click', function () {
                    const isDark = document.documentElement.classList.contains('dark');
                    document.documentElement.classList.toggle('dark');
                    document.cookie = `darkMode=${!isDark}; path=/; max-age=31536000`;
                });
            }
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

        // Load users
        function loadUsers(filters = {}, forceRefresh = false) {
            currentFilters = filters;
            
            // Use cache if available and not forcing refresh
            if (usersCache.length > 0 && !forceRefresh) {
                displayUsers(filterUsersLocally(usersCache, filters));
                return;
            }
            
            let url = 'api.php?action=getUsers';
            
            // Only fetch all users once, then filter locally
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        usersCache = data.users; // Cache the data
                        displayUsers(filterUsersLocally(usersCache, filters));
                    } else {
                        console.error('API Error:', data.message);
                        showNotification(data.message || 'Failed to load users', 'error');
                        displayUsers([]);
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    showNotification('Error loading users: ' + error.message, 'error');
                    displayUsers([]);
                });
        }

        // Filter users locally without API call
        function filterUsersLocally(users, filters) {
            let filtered = [...users];
            
            // Apply status filter
            if (filters.status) {
                filtered = filtered.filter(user => 
                    (user.status || 'active').toLowerCase() === filters.status.toLowerCase()
                );
            }
            
            // Apply search filter
            if (filters.search) {
                const searchLower = filters.search.toLowerCase();
                filtered = filtered.filter(user => 
                    user.name.toLowerCase().includes(searchLower) ||
                    user.email.toLowerCase().includes(searchLower) ||
                    user.phone.toLowerCase().includes(searchLower)
                );
            }
            
            return filtered;
        }

        function displayUsers(users) {
            const tbody = document.getElementById('usersTableBody');
            
            if (!users || users.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-users text-4xl mb-3 opacity-50"></i>
                            <p>No users found</p>
                        </td>
                    </tr>
                `;
                return;
            }
            
            tbody.innerHTML = users.map(user => `
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                        ${user.id}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">${user.name}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">${user.email}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">${user.phone}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusColor(user.status)}">
                            ${getStatusIcon(user.status)}
                            ${user.status ? user.status.charAt(0).toUpperCase() + user.status.slice(1) : 'Active'}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">${new Date(user.created_at).toLocaleDateString()}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button onclick="viewUserDetails(${user.id})" 
                                class="bg-blue-600 text-white px-3 py-1 rounded-lg text-xs hover:bg-blue-700 transition-colors"
                                title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="editUser(${user.id})" 
                                class="bg-green-600 text-white px-3 py-1 rounded-lg text-xs hover:bg-green-700 transition-colors"
                                title="Edit User">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="openStatusModal(${user.id}, '${user.status || 'active'}')" 
                                class="bg-purple-600 text-white px-3 py-1 rounded-lg text-xs hover:bg-purple-700 transition-colors"
                                title="Update Status">
                                <i class="fas fa-toggle-on"></i>
                            </button>
                            <button onclick="deleteUser(${user.id})" 
                                class="bg-red-600 text-white px-3 py-1 rounded-lg text-xs hover:bg-red-700 transition-colors"
                                title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function getStatusColor(status) {
            const colors = {
                'active': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 border border-green-300',
                'inactive': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 border border-yellow-300',
                'suspended': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 border border-red-300'
            };
            return colors[status?.toLowerCase()] || colors['active'];
        }

        function getStatusIcon(status) {
            const icons = {
                'active': '<i class="fas fa-check-circle mr-1"></i>',
                'inactive': '<i class="fas fa-pause-circle mr-1"></i>',
                'suspended': '<i class="fas fa-ban mr-1"></i>'
            };
            return icons[status?.toLowerCase()] || icons['active'];
        }

        function applyFilters() {
            const filters = {
                status: document.getElementById('statusFilter').value,
                search: document.getElementById('searchInput').value
            };
            // Use cached data, no API call needed
            loadUsers(filters, false);
        }

        // Export users to CSV
        function exportUsers() {
            window.location.href = 'export_users.php';
        }

        // Refresh users - force API call
        function refreshUsers() {
            usersCache = []; // Clear cache
            loadUsers(currentFilters, true);
        }

        // View user details - use cached data
        function viewUserDetails(userId) {
            console.log('Viewing user details for ID:', userId);
            
            // Find user in cache
            const user = usersCache.find(u => u.id === userId);
            
            if (user) {
                const content = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-slate-700 p-4 rounded-xl">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">User ID</p>
                            <p class="text-sm font-semibold text-gray-800 dark:text-white">#${user.id}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-700 p-4 rounded-xl">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Status</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusColor(user.status || 'active')}">
                                ${getStatusIcon(user.status || 'active')}
                                ${user.status ? user.status.charAt(0).toUpperCase() + user.status.slice(1) : 'Active'}
                            </span>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-700 p-4 rounded-xl">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Full Name</p>
                            <p class="text-sm font-semibold text-gray-800 dark:text-white">${user.name || 'N/A'}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-700 p-4 rounded-xl">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Email Address</p>
                            <p class="text-sm font-semibold text-gray-800 dark:text-white">${user.email || 'N/A'}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-700 p-4 rounded-xl">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Phone Number</p>
                            <p class="text-sm font-semibold text-gray-800 dark:text-white">${user.phone || 'N/A'}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-700 p-4 rounded-xl">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Joined Date</p>
                            <p class="text-sm font-semibold text-gray-800 dark:text-white">${user.created_at ? new Date(user.created_at).toLocaleDateString() : 'N/A'}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-700 p-4 rounded-xl md:col-span-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Address</p>
                            <p class="text-sm text-gray-800 dark:text-white">${user.address || 'N/A'}</p>
                        </div>
                    </div>
                `;
                document.getElementById('userDetailsContent').innerHTML = content;
                document.getElementById('viewUserModal').classList.add('active');
            } else {
                showNotification('User not found in cache', 'error');
            }
        }

        // Close view user modal
        function closeViewUserModal() {
            document.getElementById('viewUserModal').classList.remove('active');
        }

        // User Modal Functions
        function showUserModal(id = null) {
            const modal = document.getElementById('userModal');
            const title = document.getElementById('userModalTitle');
            const form = document.getElementById('userForm');
            const passwordField = document.getElementById('passwordField');
            
            isViewMode = false;
            
            if (id) {
                title.textContent = 'Edit User';
                passwordField.style.display = 'block';
                document.getElementById('userPassword').required = false;
                loadUserData(id);
            } else {
                title.textContent = 'Add New User';
                passwordField.style.display = 'block';
                document.getElementById('userPassword').required = true;
                form.reset();
                document.getElementById('userId').value = '';
            }
            
            modal.classList.add('active');
        }

        function closeUserModal() {
            document.getElementById('userModal').classList.remove('active');
            isViewMode = false;
        }

        function loadUserData(id) {
            // Use cached data instead of API call
            const user = usersCache.find(u => u.id === id);
            if (user) {
                document.getElementById('userId').value = user.id;
                document.getElementById('userName').value = user.name;
                document.getElementById('userEmail').value = user.email;
                document.getElementById('userPhone').value = user.phone;
                document.getElementById('userAddress').value = user.address || '';
                document.getElementById('userStatus').value = user.status || 'active';
            }
        }

        function submitUser(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const action = document.getElementById('userId').value ? 'updateUser' : 'createUser';
            formData.append('action', action);
            
            fetch('api.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    closeUserModal();
                    usersCache = []; // Clear cache to refresh
                    loadUsers(currentFilters, true);
                } else {
                    showNotification(data.message || 'An error occurred', 'error');
                }
            });
        }

        function editUser(id) {
            showUserModal(id);
        }

        function deleteUser(id) {
            deleteUserId = id;
            document.getElementById('deleteModal').classList.add('active');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
            deleteUserId = null;
        }

        function confirmDelete() {
            if (deleteUserId) {
                const formData = new FormData();
                formData.append('action', 'deleteUser');
                formData.append('id', deleteUserId);
                
                fetch('api.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        closeDeleteModal();
                        usersCache = []; // Clear cache to refresh
                        loadUsers(currentFilters, true);
                    } else {
                        showNotification(data.message || 'An error occurred', 'error');
                    }
                });
            }
        }

        // Status Modal Functions
        function openStatusModal(userId, currentStatus) {
            statusUserId = userId;
            document.getElementById('statusUserId').value = userId;
            document.getElementById('userStatusSelect').value = currentStatus || 'active';
            document.getElementById('statusModal').classList.add('active');
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.remove('active');
            statusUserId = null;
        }

        function submitStatusUpdate(event) {
            event.preventDefault();
            
            const formData = new FormData();
            formData.append('action', 'updateUserStatus');
            formData.append('id', document.getElementById('statusUserId').value);
            formData.append('status', document.getElementById('userStatusSelect').value);
            
            fetch('api.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    closeStatusModal();
                    
                    // Update cache locally instead of full refresh
                    const userIndex = usersCache.findIndex(u => u.id === parseInt(document.getElementById('statusUserId').value));
                    if (userIndex !== -1) {
                        usersCache[userIndex].status = document.getElementById('userStatusSelect').value;
                    }
                    
                    // Redisplay with current filters
                    displayUsers(filterUsersLocally(usersCache, currentFilters));
                } else {
                    showNotification(data.message || 'An error occurred', 'error');
                }
            })
            .catch(error => {
                showNotification('Error updating status: ' + error.message, 'error');
            });
        }

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

        // Close modals when clicking outside
        window.onclick = function(event) {
            const viewUserModal = document.getElementById('viewUserModal');
            const userModal = document.getElementById('userModal');
            const deleteModal = document.getElementById('deleteModal');
            const statusModal = document.getElementById('statusModal');
            
            if (event.target === viewUserModal) {
                closeViewUserModal();
            }
            if (event.target === userModal) {
                closeUserModal();
            }
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
            if (event.target === statusModal) {
                closeStatusModal();
            }
        }
    </script>
</body>
</html>