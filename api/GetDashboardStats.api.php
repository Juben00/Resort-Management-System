<?php
require_once '../classes/venue.class.php';
session_start();

$venueObj = new Venue();

// Get user ID from session
$userId = $_SESSION['user']['id'];

try {
    // Get all bookings for the user
    $stats = $venueObj->getDashboardStats($userId);
    
    echo json_encode([
        'status' => 'success',
        'data' => $stats
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} 