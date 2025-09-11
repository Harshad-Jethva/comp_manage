<?php include("header.php"); ?>


<div id="historyPage" class="page">
            <div class="container mx-auto px-4 py-12">
                <div class="text-center mb-12 slide-in-bottom">
                    <h1 class="text-5xl font-bold text-gradient mb-6" data-translate="complaint_history">Complaint
                        History & Tracking</h1>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                        Monitor all your complaints in one place with real-time status updates, detailed progress
                        tracking,
                        and direct communication with handling authorities.
                    </p>
                </div>

                <div class="max-w-7xl mx-auto">
                    <!-- Advanced Filter Options -->
                    <div class="bg-white rounded-3xl shadow-xl p-8 mb-8 fade-in-scale">
                        <h3 class="text-2xl font-bold mb-6 text-center">Advanced Filters & Search</h3>
                        <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                                <select id="statusFilter"
                                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                                    <option value="">All Status</option>
                                    <option value="pending">🟡 Pending</option>
                                    <option value="in-progress">🔵 In Progress</option>
                                    <option value="resolved">🟢 Resolved</option>
                                    <option value="closed">⚫ Closed</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Sector</label>
                                <select id="sectorFilter"
                                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                                    <option value="">All Sectors</option>
                                    <option value="college">🎓 College</option>
                                    <option value="police">🚔 Police</option>
                                    <option value="municipal">🏛️ Municipal</option>
                                    <option value="healthcare">🏥 Healthcare</option>
                                    <option value="transport">🚌 Transport</option>
                                    <option value="electricity">⚡ Electricity</option>
                                    <option value="water">💧 Water</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Priority</label>
                                <select id="priorityFilter"
                                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                                    <option value="">All Priorities</option>
                                    <option value="low">🟢 Low</option>
                                    <option value="medium">🟡 Medium</option>
                                    <option value="high">🟠 High</option>
                                    <option value="urgent">🔴 Urgent</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Date Range</label>
                                <input type="date" id="dateFilter"
                                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                            </div>
                            <div class="flex items-end">
                                <button onclick="filterComplaints()"
                                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105">
                                    <i class="fas fa-filter mr-2"></i>Apply Filters
                                </button>
                            </div>
                        </div>

                        <!-- Search Bar -->
                        <div class="mt-6">
                            <div class="relative">
                                <input type="text" id="searchInput"
                                    placeholder="Search complaints by title, ID, or description..."
                                    class="w-full border-2 border-gray-200 rounded-xl px-6 py-4 pl-12 focus:ring-2 focus:ring-blue-500 transition-all duration-300 text-lg">
                                <i
                                    class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Dashboard -->
                    <div class="grid md:grid-cols-4 gap-6 mb-8 fade-in-scale">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-3xl shadow-xl">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-sm font-medium">Total Complaints</p>
                                    <p class="text-3xl font-bold" id="totalComplaints">0</p>
                                </div>
                                <i class="fas fa-file-alt text-4xl text-blue-200"></i>
                            </div>
                        </div>
                        <div
                            class="bg-gradient-to-br from-yellow-500 to-orange-500 text-white p-6 rounded-3xl shadow-xl">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-yellow-100 text-sm font-medium">Pending</p>
                                    <p class="text-3xl font-bold" id="pendingComplaints">0</p>
                                </div>
                                <i class="fas fa-clock text-4xl text-yellow-200"></i>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-3xl shadow-xl">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-100 text-sm font-medium">Resolved</p>
                                    <p class="text-3xl font-bold" id="resolvedComplaints">0</p>
                                </div>
                                <i class="fas fa-check-circle text-4xl text-green-200"></i>
                            </div>
                        </div>
                        <div
                            class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-3xl shadow-xl">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100 text-sm font-medium">Avg. Resolution</p>
                                    <p class="text-3xl font-bold">5.2<span class="text-lg">days</span></p>
                                </div>
                                <i class="fas fa-chart-line text-4xl text-purple-200"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Complaints List -->
                    <div id="complaintsList" class="space-y-6">
                        <!-- Complaints will be populated here -->
                    </div>
                </div>
            </div>
        </div>

            <!-- Login Modal -->
    <?php include("login.php"); ?>
    

    <!-- Signup Modal -->
    <?php include("signup.php"); ?>

<?php
// Fetch complaints based on filters
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'filterComplaints') {
    include("dbconfig.php");

    $status = $_POST['status'] ?? '';
    $sector = $_POST['sector'] ?? '';
    $priority = $_POST['priority'] ?? '';
    $date = $_POST['date'] ?? '';
    $search = $_POST['search'] ?? '';

    $query = "SELECT c.*, p.path AS photo_path FROM complain c LEFT JOIN photo p ON c.id = p.c_id WHERE 1=1";

    if (!empty($status)) {
        $query .= " AND c.status = '" . mysqli_real_escape_string($conn, $status) . "'";
    }
    if (!empty($sector)) {
        $query .= " AND c.complaint_sector = '" . mysqli_real_escape_string($conn, $sector) . "'";
    }
    if (!empty($priority)) {
        $query .= " AND c.priority = '" . mysqli_real_escape_string($conn, $priority) . "'";
    }
    if (!empty($date)) {
        $query .= " AND DATE(c.created_at) = '" . mysqli_real_escape_string($conn, $date) . "'";
    }
    if (!empty($search)) {
        $query .= " AND (c.title LIKE '%" . mysqli_real_escape_string($conn, $search) . "%' OR c.description LIKE '%" . mysqli_real_escape_string($conn, $search) . "%')";
    }

    $result = mysqli_query($conn, $query);

    $complaints = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $complaints[$row['id']]['details'] = $row;
        if (!empty($row['photo_path'])) {
            $complaints[$row['id']]['photos'][] = $row['photo_path'];
        }
    }

    echo json_encode(array_values($complaints));
    exit;
}
?>