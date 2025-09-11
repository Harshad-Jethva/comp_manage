<?php
require_once 'dbconfig.php';

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID parameter required']);
    exit();
}

$id = intval($_GET['id']);
$query = "SELECT * FROM complain WHERE id = $id";
$result = mysqli_query($conn, $query);

if ($complaint = mysqli_fetch_assoc($result)) {
    echo json_encode(['success' => true, 'complaint' => $complaint]);
} else {
    echo json_encode(['success' => false, 'message' => 'Complaint not found']);
}
?>
