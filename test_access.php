<?php
echo "<h2>Testing Apache Access</h2>";
echo "<p>Current directory: " . __DIR__ . "</p>";
echo "<p>Current file: " . __FILE__ . "</p>";
echo "<p>Server info: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p>Document root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Request URI: " . $_SERVER['REQUEST_URI'] . "</p>";

echo "<h3>Directory Contents:</h3>";
$files = scandir(__DIR__);
foreach($files as $file) {
    if($file != '.' && $file != '..') {
        echo "<p>$file</p>";
    }
}

echo "<h3>Admin Directory Test:</h3>";
if(is_dir(__DIR__ . '/admin')) {
    echo "<p>✓ Admin directory exists</p>";
    $adminFiles = scandir(__DIR__ . '/admin');
    foreach($adminFiles as $file) {
        if($file != '.' && $file != '..') {
            echo "<p>admin/$file</p>";
        }
    }
} else {
    echo "<p>✗ Admin directory not found</p>";
}

echo "<p><a href='admin/'>Test Admin Directory Access</a></p>";
echo "<p><a href='admin/login.php'>Direct Login Access</a></p>";
?>
