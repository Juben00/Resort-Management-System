<?php
require_once '../classes/venue.class.php';
session_start();

$venueObj = new Venue();

// Get user ID from session

try {
    // Get all bookings for the user
    $stats = $venueObj->getDashboardStats();
    
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