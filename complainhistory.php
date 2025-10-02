<?php
include("header.php");
include("dbconfig.php");

// Fetch complaints based on filters
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
?>

<div id="historyPage" class="page">
    <div class="container mx-auto px-4 py-12">
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-gradient mb-6">Complaint History & Tracking</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Monitor all your complaints in one place with real-time status updates, detailed progress tracking,
                and direct communication with handling authorities.
            </p>
        </div>

        <form method="POST" class="bg-white rounded-3xl shadow-xl p-8 mb-8">
            <h3 class="text-2xl font-bold mb-6 text-center">Advanced Filters & Search</h3>
            <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="in-progress">In Progress</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sector</label>
                    <select name="sector" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                        <option value="">All Sectors</option>
                        <option value="college">College</option>
                        <option value="police">Police</option>
                        <option value="municipal">Municipal</option>
                        <option value="healthcare">Healthcare</option>
                        <option value="transport">Transport</option>
                        <option value="electricity">Electricity</option>
                        <option value="water">Water</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Priority</label>
                    <select name="priority" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                        <option value="">All Priorities</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date</label>
                    <input type="date" name="date" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold">
                        Apply Filters
                    </button>
                </div>
            </div>
            <div class="mt-6">
                <input type="text" name="search" placeholder="Search complaints by title, ID, or description..." class="w-full border-2 border-gray-200 rounded-xl px-6 py-4">
            </div>
        </form>

        <div id="complaintsList" class="space-y-6">
            <?php if (empty($complaints)): ?>
                <div class="bg-white rounded-3xl shadow-xl p-8 text-center">
                    <i class="fas fa-inbox text-5xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">No complaints found</h3>
                    <p class="text-gray-600 mb-6">Try adjusting your filters or file a new complaint.</p>
                </div>
            <?php else: ?>
                <?php foreach ($complaints as $complaint): ?>
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($complaint['details']['title']); ?></h3>
                            <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($complaint['details']['description']); ?></p>
                            <?php if (!empty($complaint['photos'])): ?>
                                <div class="grid grid-cols-3 gap-4">
                                    <?php foreach ($complaint['photos'] as $photo): ?>
                                        <img src="<?php echo htmlspecialchars($photo); ?>" alt="Complaint Photo" class="w-full h-48 object-cover rounded-lg">
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <div class="flex justify-between items-center mt-4">
                                <span class="text-sm font-medium">ID: <?php echo htmlspecialchars($complaint['details']['id']); ?></span>
                                <span class="text-sm font-medium">Status: <?php echo htmlspecialchars($complaint['details']['status']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>