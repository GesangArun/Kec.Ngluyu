<?php
header('Content-Type: application/json');

$response = [
    'success' => true,
    'message' => 'PHP berfungsi dengan baik',
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION
];

echo json_encode($response);
?>
