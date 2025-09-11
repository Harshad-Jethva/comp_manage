<?php
include("dbconfig.php");

// Function to get complaints with photos
function getComplaintsWithPhotos($conn) {
    $query = "SELECT c.*, GROUP_CONCAT(p.path) as photo_paths 
              FROM complain c 
              LEFT JOIN photo p ON c.id = p.c_id 
              GROUP BY c.id 
              ORDER BY c.id DESC";
    
    $result = mysqli_query($conn, $query);
    $complaints = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $row['photo_paths'] = $row['photo_paths'] ? explode(',', $row['photo_paths']) : [];
        $complaints[] = $row;
    }
    
    return $complaints;
}

// Get complaints data
$complaints = getComplaintsWithPhotos($conn);

// Return JSON response
header('Content-Type: application/json');
echo json_encode($complaints);
?>
