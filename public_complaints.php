<?php
require_once 'dbconfig.php';

// Get all complaints for public viewing
$search = $_GET['search'] ?? '';
$sector = $_GET['sector'] ?? '';
$status = $_GET['status'] ?? '';
$page = intval($_GET['page'] ?? 1);
$limit = 12;

$where_conditions = [];
if ($search) {
    $search_term = mysqli_real_escape_string($conn, $search);
    $where_conditions[] = "(title LIKE '%$search_term%' OR description LIKE '%$search_term%' OR sector LIKE '%$search_term%')";
}
if ($sector && $sector !== 'all') {
    $where_conditions[] = "sector = '" . mysqli_real_escape_string($conn, $sector) . "'";
}
if ($status && $status !== 'all') {
    $where_conditions[] = "status = '" . mysqli_real_escape_string($conn, $status) . "'";
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';
$offset = ($page - 1) * $limit;

$query = "SELECT * FROM complain $where_clause ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
$complaints = [];
while ($row = mysqli_fetch_assoc($result)) {
    $complaints[] = $row;
}

// Get total count for pagination
$count_query = "SELECT COUNT(*) as total FROM complain $where_clause";
$count_result = mysqli_query($conn, $count_query);
$total_complaints = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_complaints / $limit);

// Get sectors for filter
$sector_query = "SELECT DISTINCT sector FROM complain WHERE sector IS NOT NULL ORDER BY sector";
$sector_result = mysqli_query($conn, $sector_query);
$sectors = [];
while ($row = mysqli_fetch_assoc($sector_result)) {
    $sectors[] = $row['sector'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Complaints - FIXBOT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-robot text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        FIXBOT
                    </span>
                </div>
                <nav class="hidden md:flex space-x-6">
                    <a href="index.php" class="text-gray-600 hover:text-blue-600">Home</a>
                    <a href="public_complaints.php" class="text-blue-600 font-medium">View Complaints</a>
                    <a href="complaintform.php" class="text-gray-600 hover:text-blue-600">Submit Complaint</a>
                    <a href="about.php" class="text-gray-600 hover:text-blue-600">About</a>
                </nav>
                <a href="admin/" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Admin Login
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Public Complaints</h1>
            <p class="text-lg text-gray-600">Track and view all submitted complaints in our system</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl p-6 shadow-sm mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                           placeholder="Search complaints..." 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sector</label>
                    <select name="sector" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Sectors</option>
                        <?php foreach ($sectors as $s): ?>
                            <option value="<?php echo htmlspecialchars($s); ?>" <?php echo $sector === $s ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($s); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="in_progress" <?php echo $status === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                        <option value="resolved" <?php echo $status === 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                        <option value="closed" <?php echo $status === 'closed' ? 'selected' : ''; ?>>Closed</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Complaints Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <?php foreach ($complaints as $complaint): ?>
            <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            <?php echo htmlspecialchars($complaint['title']); ?>
                        </h3>
                        <p class="text-sm text-gray-600 mb-3">
                            <?php echo htmlspecialchars(substr($complaint['description'], 0, 120)) . '...'; ?>
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between mb-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <?php echo htmlspecialchars($complaint['sector']); ?>
                    </span>
                    
                    <?php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'in_progress' => 'bg-blue-100 text-blue-800',
                        'resolved' => 'bg-green-100 text-green-800',
                        'closed' => 'bg-gray-100 text-gray-800'
                    ];
                    $status_display = $complaint['status'] ?? 'pending';
                    ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $statusColors[$status_display]; ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $status_display)); ?>
                    </span>
                </div>
                
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <div class="flex items-center">
                        <i class="fas fa-user mr-1"></i>
                        <?php echo htmlspecialchars($complaint['name']); ?>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-calendar mr-1"></i>
                        <?php echo date('M j, Y', strtotime($complaint['created_at'])); ?>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">
                            ID: FB<?php echo str_pad($complaint['id'], 6, '0', STR_PAD_LEFT); ?>
                        </span>
                        <button onclick="viewComplaint(<?php echo $complaint['id']; ?>)" 
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View Details
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="flex items-center justify-center space-x-2">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>&sector=<?php echo urlencode($sector); ?>&status=<?php echo urlencode($status); ?>" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-chevron-left mr-1"></i>Previous
                </a>
            <?php endif; ?>
            
            <?php for ($i = max(1, $page-2); $i <= min($total_pages, $page+2); $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&sector=<?php echo urlencode($sector); ?>&status=<?php echo urlencode($status); ?>" 
                   class="px-4 py-2 <?php echo $i === $page ? 'bg-blue-600 text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-50'; ?> rounded-lg">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
            
            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>&sector=<?php echo urlencode($sector); ?>&status=<?php echo urlencode($status); ?>" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Next<i class="fas fa-chevron-right ml-1"></i>
                </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if (empty($complaints)): ?>
        <div class="text-center py-12">
            <i class="fas fa-search text-gray-400 text-4xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No complaints found</h3>
            <p class="text-gray-600">Try adjusting your search criteria or submit a new complaint.</p>
            <a href="complaintform.php" class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Submit Complaint
            </a>
        </div>
        <?php endif; ?>
    </main>

    <!-- View Complaint Modal -->
    <div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-2xl p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Complaint Details</h2>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="complaintDetails"></div>
        </div>
    </div>

    <script>
        function viewComplaint(id) {
            fetch(`get_complaint_details.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const complaint = data.complaint;
                        document.getElementById('complaintDetails').innerHTML = `
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-semibold mb-2">Complaint Information</h3>
                                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                        <p><strong>ID:</strong> FB${String(complaint.id).padStart(6, '0')}</p>
                                        <p><strong>Title:</strong> ${complaint.title}</p>
                                        <p><strong>Description:</strong> ${complaint.description}</p>
                                        <p><strong>Sector:</strong> ${complaint.sector}</p>
                                        <p><strong>Status:</strong> <span class="capitalize">${(complaint.status || 'pending').replace('_', ' ')}</span></p>
                                        <p><strong>Priority:</strong> <span class="capitalize">${complaint.priority || 'medium'}</span></p>
                                        <p><strong>Date Submitted:</strong> ${new Date(complaint.created_at).toLocaleDateString()}</p>
                                    </div>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold mb-2">Citizen Information</h3>
                                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                        <p><strong>Name:</strong> ${complaint.name}</p>
                                        <p><strong>Email:</strong> ${complaint.email}</p>
                                        <p><strong>Phone:</strong> ${complaint.phone}</p>
                                        <p><strong>Address:</strong> ${complaint.address}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                        document.getElementById('viewModal').classList.remove('hidden');
                        document.getElementById('viewModal').classList.add('flex');
                    }
                });
        }

        function closeModal() {
            document.getElementById('viewModal').classList.add('hidden');
            document.getElementById('viewModal').classList.remove('flex');
        }

        // Close modal when clicking outside
        document.getElementById('viewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>
