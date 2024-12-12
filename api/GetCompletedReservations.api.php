<?php
require_once '../classes/venue.class.php';
require_once '../includes/database.php';

header('Content-Type: application/json');

$venueObj = new Venue();
$completedReservations = $venueObj->getCompletedReservations();

if ($completedReservations) {
    echo json_encode([
        'status' => 'success',
        'data' => $completedReservations
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'No completed reservations found'
    ]);
}
?> 