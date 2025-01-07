<?php
require_once '../classes/venue.class.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $venueObj = new Venue();
    $result = $venueObj->rejectReservation($_POST['booking_id']);
    echo json_encode($result);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}