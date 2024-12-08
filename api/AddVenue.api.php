<?php

require_once '../classes/venue.class.php';
require_once '../sanitize.php';

$venueObj = new Venue();

$name = $description = $location = $price = $capacity = $amenities = $entrance = $cleaning = "";
$nameErr = $descriptionErr = $locationErr = $priceErr = $capacityErr = $amenitiesErr = $entranceErr = $cleaningErr = $imageErr = "";

$uploadDir = '/venue_image_uploads/';
$allowedType = ['jpg', 'jpeg', 'png'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $name = clean_input($_POST['name']);
    $description = clean_input($_POST['description']);
    $location = clean_input($_POST['location']);
    $price = clean_input($_POST['price']);
    $capacity = clean_input($_POST['capacity']);
    $amenities = clean_input($_POST['amenities']);
    $entrance = clean_input($_POST['entrance']);
    $cleaning = clean_input($_POST['cleaning']);

    // Validation for required fields
    if (empty($name))
        $nameErr = "Name is required";
    if (empty($description))
        $descriptionErr = "Description is required";
    if (empty($location))
        $locationErr = "Location is required";
    if (empty($price))
        $priceErr = "Price is required";
    if (empty($capacity))
        $capacityErr = "Capacity is required";
    if (empty($amenities))
        $amenitiesErr = "Amenities are required";
    if (empty($entrance))
        $entranceErr = "Entrance field is required";
    if (empty($cleaning))
        $cleaningErr = "Cleaning field is required";

    // Handle multiple image uploads
    $imageErr = [];
    $uploadedImages = [];

    if (empty($_FILES['venue_images']['name'][0])) {
        $imageErr[] = 'At least one image is required.';
    } else {
        foreach ($_FILES['venue_images']['name'] as $key => $image) {
            $imageFileType = strtolower(pathinfo($image, PATHINFO_EXTENSION));

            // Validate each image
            if (!in_array($imageFileType, $allowedType)) {
                $imageErr[] = "File " . $_FILES['venue_images']['name'][$key] . " has an invalid format. Only jpg, jpeg, and png are allowed.";
            } else {
                // Generate a unique target path for each image
                $targetImage = $uploadDir . uniqid() . '.' . $imageFileType;

                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES['venue_images']['tmp_name'][$key], '..' . $targetImage)) {
                    $uploadedImages[] = $targetImage;
                } else {
                    $imageErr[] = "Failed to upload image: " . $_FILES['venue_images']['name'][$key];
                }
            }
        }
    }

    // Prepare amenities JSON
    $amenitiesArray = explode(',', $amenities);
    $amenitiesJson = json_encode(array_map('trim', $amenitiesArray));


    // Proceed if no errors
    if (
        empty($nameErr) && empty($descriptionErr) && empty($locationErr) && empty($priceErr) && empty($capacityErr) && empty($amenitiesErr)
        && empty($imageErr)
    ) {
        // Set venue object data
        $venueObj->name = $name;
        $venueObj->description = $description;
        $venueObj->location = $location;
        $venueObj->price = $price;
        $venueObj->capacity = $capacity;
        $venueObj->amenities = $amenitiesJson;
        $venueObj->entrance = $entrance;
        $venueObj->cleaning = $cleaning;
        $venueObj->image_url = json_encode($uploadedImages); // Save multiple image paths as JSON

        // Add venue with image URLs to the database
        $result = $venueObj->addVenue();

        if ($result['status'] === 'success') {
            echo json_encode(['status' => 'success', 'message' => $result['message']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => $result['message']]);
        }
    } else {
        // Return validation errors as JSON
        echo json_encode([
            'status' => 'error',
            'errors' => implode('<br>', array_filter([
                $nameErr,
                $descriptionErr,
                $locationErr,
                $priceErr,
                $capacityErr,
                $amenitiesErr,
                $entranceErr,
                $cleaningErr,
                $imageErr,
            ]))
        ]);
    }
    exit;
}
