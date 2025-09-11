<?php
// Secure photo viewer for complaint photos
session_start();

// Check if user is logged in (optional - you can remove this if photos should be publicly viewable)
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // For now, allow public access to photos
    // You can uncomment the lines below to require login
    // http_response_code(403);
    // die('Access denied. Please login to view photos.');
}

// Get the photo path from URL parameter
$photoPath = isset($_GET['path']) ? $_GET['path'] : '';

// Validate photo path
if (empty($photoPath) || !file_exists($photoPath)) {
    http_response_code(404);
    die('Photo not found.');
}

// Ensure the photo is in the uploads directory
$realPath = realpath($photoPath);
$uploadsPath = realpath('uploads/');

if (!$realPath || strpos($realPath, $uploadsPath) !== 0) {
    http_response_code(403);
    die('Access denied.');
}

// Get file info
$fileInfo = pathinfo($photoPath);
$extension = strtolower($fileInfo['extension']);

// Validate file extension
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
if (!in_array($extension, $allowedExtensions)) {
    http_response_code(403);
    die('Invalid file type.');
}

// Set appropriate content type
$contentTypes = [
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif'
];

header('Content-Type: ' . $contentTypes[$extension]);
header('Content-Length: ' . filesize($photoPath));
header('Cache-Control: public, max-age=31536000'); // Cache for 1 year
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');

// Output the file
readfile($photoPath);
exit;
?>
