<?php
session_start();
require_once '../dbconfig.php';

// Check admin login
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_email'])) {
    header('Location: login.php');
    exit();
}

// Fetch distinct sectors for the sector filter dropdown
$allSectors = [];
$query = "SELECT DISTINCT complaint_sector FROM complain";
$result = $conn->query($query);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $allSectors[] = $row['complaint_sector'];
    }
}

// Fetch all admin users for assignment dropdown
$adminUsers = [];
$query = "SELECT id, email FROM admin";
$result = $conn->query($query);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $adminUsers[] = $row;
    }
}


// Handle status update if the form is submitted


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $complaintId = intval($_POST['complaint_id']);
    $query1 = "SELECT * FROM complaint_status_summary WHERE compid = $complaintId";
    $result1 = $conn->query($query1);

    // Check if the query was successful and if any rows were returned
    if ($result1 && $result1->num_rows > 0) {
        $row = $result1->fetch_assoc(); // Fetch the row to get the id
        $newStatus = mysqli_real_escape_string($conn, $_POST['update_status']);

        // Use the fetched id from the result
        $query = "UPDATE complaint_status_summary SET status = '$newStatus' WHERE id = " . intval($row['id']);
        $conn->query($query);
    }

    // Redirect to avoid form resubmission
    header('Location: complaints.php');
    exit();
}

// Handle complaint deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_complaint_id'])) {
    $complaintId = intval($_POST['delete_complaint_id']);
    $query = "DELETE FROM complain WHERE id = $complaintId";
    $conn->query($query);
     $query = "DELETE FROM complaint_status_summary WHERE compid = $complaintId";
    $conn->query($query);

    // Redirect to avoid form resubmission
    header('Location: complaints.php');
    exit();
}

// Fetch complaints based on filters
$status = isset($_POST['status']) ? $_POST['status'] : (isset($_GET['status']) ? $_GET['status'] : '');
$sector = isset($_POST['sector']) ? $_POST['sector'] : (isset($_GET['sector']) ? $_GET['sector'] : '');
$priority = isset($_POST['priority']) ? $_POST['priority'] : (isset($_GET['priority']) ? $_GET['priority'] : '');
$date = isset($_POST['date']) ? $_POST['date'] : (isset($_GET['date']) ? $_GET['date'] : '');
$search = isset($_POST['search']) ? $_POST['search'] : (isset($_GET['search']) ? $_GET['search'] : '');

$query = "SELECT c.*, a.email AS assigned_to, css.status AS complaint_status FROM complain c LEFT JOIN admin a ON c.assigned_to = a.id LEFT JOIN complaint_status_summary css ON c.id = css.compid WHERE 1=1";
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

$result = $conn->query($query);
$complaints = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $complaints[] = $row;
    }
}



// Fetch complaint status summary for reference
$query1 = "SELECT * FROM complaint_status_summary";
$result1 = $conn->query($query1);
$complaints1 = [];
if ($result1 && $result1->num_rows > 0) {
    while ($row = $result1->fetch_assoc()) {
        $complaints1[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="<?php echo isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'true' ? 'dark' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIXBOT Admin Panel - Complaints</title>
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
                            <button id="sidebarToggle"
                                class="lg:hidden text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                                <i class="fas fa-bars text-xl"></i>
                            </button>
                            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Complaint Management</h2>
                        </div>

                        <div class="flex items-center space-x-3">
                            <button onclick="exportComplaints()" class="bg-green-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-green-700 transition-colors">
                                <i class="fas fa-download mr-2"></i>Export CSV
                            </button>
                            <button onclick="refreshComplaints()" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-blue-700 transition-colors">
                                <i class="fas fa-sync-alt mr-2"></i>Refresh
                            </button>
                        </div>
                    </div>
                </header>

                <div class="p-6">

               

                    <!-- Add Filters Form -->
                    <form method="POST" class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6 mb-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex flex-col sm:flex-row gap-4">
                                <select name="status" class="bg-gray-100 dark:bg-slate-700 border-0 rounded-xl px-4 py-2 text-sm">
                                    <option value="">All Status</option>
                                    <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="in_progress" <?php echo $status === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                    <option value="resolved" <?php echo $status === 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                                    <option value="closed" <?php echo $status === 'closed' ? 'selected' : ''; ?>>Closed</option>
                                </select>

                                <select name="sector" class="bg-gray-100 dark:bg-slate-700 border-0 rounded-xl px-4 py-2 text-sm">
                                    <option value="">All Sectors</option>
                                    <?php foreach ($allSectors as $sectorOption): ?>
                                        <option value="<?php echo htmlspecialchars($sectorOption); ?>" <?php echo $sector === $sectorOption ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($sectorOption); ?></option>
                                    <?php endforeach; ?>
                                </select>

                                <select name="priority" class="bg-gray-100 dark:bg-slate-700 border-0 rounded-xl px-4 py-2 text-sm">
                                    <option value="">All Priorities</option>
                                    <option value="low" <?php echo $priority === 'low' ? 'selected' : ''; ?>>Low</option>
                                    <option value="medium" <?php echo $priority === 'medium' ? 'selected' : ''; ?>>Medium</option>
                                    <option value="high" <?php echo $priority === 'high' ? 'selected' : ''; ?>>High</option>
                                </select>

                                <input type="date" name="date" value="<?php echo htmlspecialchars($date); ?>" class="bg-gray-100 dark:bg-slate-700 border-0 rounded-xl px-4 py-2 text-sm">
                            </div>

                            <div class="flex gap-3">
                                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search complaints..."
                                    class="bg-gray-100 dark:bg-slate-700 border-0 rounded-xl px-4 py-2 text-sm">
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-blue-700">
                                    <i class="fas fa-filter mr-2"></i>Apply Filters
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Complaints Table -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-slate-700">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Filtered Complaints</h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 dark:bg-slate-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Complaint</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Sector</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Priority</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Complaint Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                                    <?php if (empty($complaints)): ?>
                                        <tr>
                                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                                No complaints found.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($complaints as $complaint): ?>
                                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700">
                                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                                    FB<?php echo str_pad($complaint['id'], 6, '0', STR_PAD_LEFT); ?>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <p class="text-sm text-gray-900 dark:text-white font-medium">
                                                        <?php echo htmlspecialchars($complaint['title']); ?></p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        <?php echo htmlspecialchars($complaint['description']); ?></p>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                                    <?php echo htmlspecialchars($complaint['complaint_sector']); ?>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                                    <?php echo htmlspecialchars($complaint['priority']); ?>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                                    <?php echo htmlspecialchars($complaint['status']); ?>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <?php 
                                                    $complaintStatus = $complaint['complaint_status'] ?? 'Pending';
                                                    $statusColors = [
                                                        'Pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                                        'in_progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                        'resolved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                        'closed' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
                                                    ];
                                                    $statusColor = $statusColors[$complaintStatus] ?? 'bg-gray-100 text-gray-800';
                                                    $statusLabel = ucfirst(str_replace('_', ' ', $complaintStatus));
                                                    ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $statusColor; ?>">
                                                        <?php echo htmlspecialchars($statusLabel); ?>
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                    <?php echo htmlspecialchars($complaint['created_at']); ?>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex gap-2">
                                                        <button onclick="viewComplaintDetails(<?php echo $complaint['id']; ?>)" 
                                                            class="bg-blue-600 text-white px-3 py-1 rounded-lg text-xs hover:bg-blue-700 transition-colors"
                                                            title="View Details">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button onclick="openStatusModal(<?php echo $complaint['id']; ?>, '<?php echo htmlspecialchars($complaint['status']); ?>')" 
                                                            class="bg-green-600 text-white px-3 py-1 rounded-lg text-xs hover:bg-green-700 transition-colors"
                                                            title="Update Status">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this complaint?');" class="inline">
                                                            <input type="hidden" name="delete_complaint_id" value="<?php echo $complaint['id']; ?>">
                                                            <button type="submit" 
                                                                class="bg-red-600 text-white px-3 py-1 rounded-lg text-xs hover:bg-red-700 transition-colors"
                                                                title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div id="pagination" class="p-6 border-t border-gray-200 dark:border-slate-700">
                            <!-- Pagination will be loaded here -->
                        </div>
                    </div>
                </div>
            </main>

            <!-- View Complaint Details Modal -->
            <div id="viewComplaintModal" class="modal">
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 w-full max-w-3xl mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Complaint Details</h3>
                        <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div id="complaintDetailsContent" class="space-y-4">
                        <!-- Content will be loaded dynamically -->
                    </div>
                </div>
            </div>

            <!-- Update Status Modal -->
            <div id="statusModal" class="modal">
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 w-full max-w-md mx-4">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Update Status</h3>
                        <button onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <form method="POST">
                        <input type="hidden" id="statusComplaintId" name="complaint_id">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Status</label>
                            <select id="statusSelect" name="update_status" class="w-full bg-gray-100 dark:bg-slate-700 border-0 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="Pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="resolved">Resolved</option>
                                <option value="closed">Closed</option>
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

            <!-- Photo Viewer Modal -->
            <div id="photoModal" class="modal" style="background-color: rgba(0, 0, 0, 0.9);">
                <div class="relative w-full h-full flex items-center justify-center p-4">
                    <button onclick="closePhotoModal()" class="absolute top-4 right-4 text-white bg-red-600 hover:bg-red-700 rounded-full w-12 h-12 flex items-center justify-center z-10 transition-colors">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                    <img id="photoModalImage" src="" alt="Full Size Photo" class="max-w-full max-h-[90vh] rounded-lg shadow-2xl object-contain">
                </div>
            </div>
        </div>
    </div>

    <script>
        // Export complaints to CSV
        function exportComplaints() {
            window.location.href = 'api.php?action=exportReport&type=csv';
        }

        // Refresh complaints page
        function refreshComplaints() {
            location.reload();
        }

        // View complaint details
        function viewComplaintDetails(complaintId) {
            fetch(`api.php?action=getComplaint&id=${complaintId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const complaint = data.complaint;
                        const content = `
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 dark:bg-slate-700 p-4 rounded-xl">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Complaint ID</p>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white">FB${String(complaint.id).padStart(6, '0')}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-slate-700 p-4 rounded-xl">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Status</p>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white">${complaint.status}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-slate-700 p-4 rounded-xl">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Name</p>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white">${complaint.name || 'N/A'}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-slate-700 p-4 rounded-xl">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Email</p>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white">${complaint.email || 'N/A'}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-slate-700 p-4 rounded-xl">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Phone</p>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white">${complaint.phone || 'N/A'}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-slate-700 p-4 rounded-xl">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Sector</p>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white">${complaint.complaint_sector}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-slate-700 p-4 rounded-xl">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Priority</p>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white">${complaint.priority}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-slate-700 p-4 rounded-xl">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Date</p>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white">${complaint.created_at}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-slate-700 p-4 rounded-xl md:col-span-2">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Address</p>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white">${complaint.address || 'N/A'}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-slate-700 p-4 rounded-xl md:col-span-2">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Title</p>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white">${complaint.title}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-slate-700 p-4 rounded-xl md:col-span-2">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Description</p>
                                    <p class="text-sm text-gray-800 dark:text-white">${complaint.description}</p>
                                </div>
                                ${complaint.photos && complaint.photos.length > 0 ? `
                                <div class="bg-gray-50 dark:bg-slate-700 p-4 rounded-xl md:col-span-2">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Attached Photos (${complaint.photos.length})</p>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                        ${complaint.photos.map(photo => `
                                            <div class="relative group cursor-pointer" onclick="openPhotoModal('${photo}')">
                                                <img src="../${photo}" alt="Complaint Photo" 
                                                     class="w-full h-32 object-cover rounded-lg border-2 border-gray-300 dark:border-slate-600 hover:border-blue-500 transition-all duration-300">
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-search-plus text-white text-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                                                </div>
                                            </div>
                                        `).join('')}
                                    </div>
                                </div>
                                ` : ''}
                            </div>
                        `;
                        document.getElementById('complaintDetailsContent').innerHTML = content;
                        document.getElementById('viewComplaintModal').classList.add('active');
                    } else {
                        alert('Failed to load complaint details');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading complaint details');
                });
        }

        // Close view modal
        function closeViewModal() {
            document.getElementById('viewComplaintModal').classList.remove('active');
        }

        // Open status update modal
        function openStatusModal(complaintId, currentStatus) {
            document.getElementById('statusComplaintId').value = complaintId;
            document.getElementById('statusSelect').value = currentStatus;
            document.getElementById('statusModal').classList.add('active');
        }

        // Close status modal
        function closeStatusModal() {
            document.getElementById('statusModal').classList.remove('active');
        }

        // Open photo modal
        function openPhotoModal(photoPath) {
            document.getElementById('photoModalImage').src = '../' + photoPath;
            document.getElementById('photoModal').classList.add('active');
        }

        // Close photo modal
        function closePhotoModal() {
            document.getElementById('photoModal').classList.remove('active');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const viewModal = document.getElementById('viewComplaintModal');
            const statusModal = document.getElementById('statusModal');
            const photoModal = document.getElementById('photoModal');
            
            if (event.target === viewModal) {
                closeViewModal();
            }
            if (event.target === statusModal) {
                closeStatusModal();
            }
            if (event.target === photoModal) {
                closePhotoModal();
            }
        }

        // Initialize sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('-translate-x-full');
                });
            }
        });
    </script>
</body>

</html>

