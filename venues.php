<?php
session_start();
require_once __DIR__ . '/classes/venue.class.php';
require_once __DIR__ . '/classes/account.class.php';

$venueObj = new Venue();
$accountObj = new Account();

// Check if 'id' parameter is present and valid
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

// Retrieve venue information based on 'id' parameter
$venue = $venueObj->getSingleVenue($_GET['id']);

// If no venue is found, redirect to index.php
if (empty($venue['name'])) {
    header("Location: index.php");
    exit();
}

// Retrieve the owner's information
$owner = $accountObj->getUser($venue['host_id']);
$bookedDate = $venueObj->getBookedDates($_GET['id']);

// Prepare booked dates for JavaScript
$bookedDates = [];
foreach ($bookedDate as $booking) {
    $start = new DateTime($booking['startdate']);
    $end = new DateTime($booking['enddate']);
    $interval = new DateInterval('P1D');
    $dateRange = new DatePeriod($start, $interval, $end->modify('+1 day'));

    foreach ($dateRange as $date) {
        $bookedDates[] = $date->format('Y-m-d');
    }
}

$discountStatus = $accountObj->getDiscountApplication($_SESSION['user']['id']);
$ratings = $venueObj->getRatings($_GET['id']);
$reviews = $venueObj->getReview($_GET['id']);
// var_dump($_GET['id']);
// var_dump($bookedDate);
// var_dump($bookedDate[0]['startdate'])
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venue Details - HubVenue</title>
    <link rel="icon" href="./images/icoco_black_ico.png">
    <link rel="stylesheet" href="./output.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/luxon@3.3.0/build/global/luxon.min.js"></script>
    <link rel="stylesheet" href="node_modules/flatpickr/dist/flatpickr.min.css">
    <script src="node_modules/flatpickr/dist/flatpickr.min.js"></script>
    <style>
        .flatpickr-calendar {
            z-index: 100 !important;
        }

        .flatpickr-calendar.static {
            position: absolute;
            top: 100% !important;
        }

        /* Add these new styles for image transitions */
        .venue-image {
            transition: opacity 0.5s ease-in-out;
        }

        .venue-image.fade-out {
            opacity: 0;
        }

        .venue-image.fade-in {
            opacity: 1;
        }

        #thumbnailContainer::-webkit-scrollbar {
            height: 8px;
        }

        #thumbnailContainer::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        #thumbnailContainer::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }

        #thumbnailContainer::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .modal-fade-enter {
            opacity: 0;
            transform: scale(0.9);
        }

        .modal-fade-enter-active {
            opacity: 1;
            transform: scale(1);
            transition: opacity 300ms, transform 300ms;
        }

        .modal-fade-exit {
            opacity: 1;
            transform: scale(1);
        }

        .modal-fade-exit-active {
            opacity: 0;
            transform: scale(0.9);
            transition: opacity 300ms, transform 300ms;
        }

        .split-view {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            transition: all 0.3s ease;
        }

        .venue-comparison {
            height: 100vh;
            overflow-y: auto;
            padding: 120px 4rem 2rem;
            border-left: 1px solid #e5e7eb;
            background: #f9fafb;
            position: fixed;
            right: -50%;
            top: 0;
            width: 50%;
            transition: all 0.3s ease;
            z-index: 40;
        }

        .venue-comparison.active {
            right: 0;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
        }

        .comparison-close {
            position: fixed;
            top: 2rem;
            right: 2rem;
            z-index: 50;
            background: white;
            border-radius: 50%;
            padding: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .venue-comparison .comparison-content {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .venue-comparison .bg-slate-50 {
            margin-bottom: 1.5rem;
            width: 100%;
            transition: all 0.3s ease;
        }

        .venue-comparison h2 {
            margin-bottom: 2rem;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .main-content {
            transition: all 0.3s ease;
            width: 100%;
            max-width: 1200px;
            padding: 100px 2rem 0;
            margin: 0 auto;
            /* Account for sidebar */
        }

        .main-content.shifted {
            margin-right: 50%;
            width: 50%;
            padding: 100px 0 0;
            /* Remove horizontal padding */
            margin-left: 5rem;
            max-width: none;
            height: 100vh;
            overflow-y: auto;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            justify-content: center;
            /* Center the content */
        }

        .main-content.shifted #venueDetails {
            width: 100%;
            max-width: 800px;
            padding: 0 4rem;
            margin: 0 auto;
        }

        .main-container {
            transition: all 0.3s ease;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 0 0;
            display: flex;
            justify-content: center;
        }

        .main-container.shifted {
            max-width: none;
            width: 100%;
            padding: 0;
            margin: 0;
            height: 100vh;
            overflow: hidden;
        }

        #venueDetails {
            width: 100%;
            margin: 0 auto;
            padding-bottom: 2rem;
            /* Add padding at the bottom for scrolling space */
        }

        .grid.grid-cols-3 {
            width: 100%;
            gap: 1rem;
            margin-top: 1rem;
        }

        @media (max-width: 1400px) {
            .main-content.shifted #venueDetails {
                padding: 0 2rem;
            }

            .venue-comparison {
                padding: 120px 2rem 2rem;
            }
        }

        @media (max-width: 768px) {
            .main-content.shifted #venueDetails {
                padding: 0 1rem;
            }

            .venue-comparison {
                padding: 120px 1rem 2rem;
            }
        }

        .venue-comparison .bg-slate-50 {
            transition: all 0.3s ease;
        }

        .venue-comparison .hidden {
            display: none;
        }

        /* Animation for expanding/collapsing details */
        .venue-comparison [id^="venue-details-"] {
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease-in-out;
        }

        .venue-comparison [id^="venue-details-"]:not(.hidden) {
            max-height: 2000px;
            /* Large enough to fit content */
        }

        /* Update main content shifted styles to fix rating section */
        .main-content.shifted #venueDetails {
            width: 100%;
            max-width: 800px;
            padding: 0 4rem;
            margin: 0 auto;
        }

        /* Add styles for ratings section to prevent cutoff */
        .main-content.shifted .rating-bars {
            width: 100%;
            max-width: 200px;
            /* Adjust as needed */
        }

        .main-content.shifted .reviews-section {
            width: 100%;
            overflow-x: hidden;
        }
    </style>
</head>

<body class="bg-slate-50">
    <!-- Header -->
    <?php
    if (isset($_SESSION['user'])) {
        include_once './components/navbar.logged.in.php';
    } else {
        include_once './components/navbar.html';
    }

    include_once './components/SignupForm.html';
    include_once './components/feedback.modal.html';
    include_once './components/confirm.feedback.modal.html';
    include_once './components/Menu.html';

    ?>

    <main class="max-w-7xl pt-32 mx-auto px-4 py-6 sm:px-6 lg:px-8 main-container">
        <div class="main-content">
            <div id="venueDetails">
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-3xl font-semibold"><?php echo htmlspecialchars($venue['name']) ?></h1>
                        <button id="compareButton"
                            class="flex items-center gap-2 px-4 py-2 bg-slate-50 border-2 border-gray-500 rounded-lg hover:bg-gray-50 transition duration-300">
                            <i class="fas fa-exchange-alt"></i>
                            <span>Compare</span>
                        </button>
                    </div>
                    <div class="flex items-center">
                        <span class="text-sm font-semibold">
                            <i
                                class="fas fa-star text-yellow-400 mr-1"></i><?php echo number_format($venue['rating'], 1) ?></span>
                        <span class="mx-2">·</span>
                        <span class="text-sm font-semibold"><?php echo htmlspecialchars($venue['total_reviews']) ?>
                            review/s</span>
                        <span class="mx-2">·</span>
                        <span class="text-sm font-semibold"><?php echo htmlspecialchars($venue['tag']) ?></span>
                        <span class="mx-2">·</span>
                        <span class="text-sm font-semibold"><?php echo htmlspecialchars($venue['location']) ?></span>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2 mb-8 relative">
                    <!-- Main Image (First in Array) on the Left -->
                    <div class="col-span-2">
                        <?php if (!empty($venue['image_urls'])): ?>
                            <img src="./<?= htmlspecialchars($venue['image_urls'][0]) ?>" alt="Venue Image"
                                class="venue-image w-full h-[30.5rem] object-cover rounded-lg" data-image-index="0">
                        <?php else: ?>
                            <img src="default-image.jpg" alt="Default Venue Image"
                                class="venue-image w-full h-full object-cover rounded-lg">
                        <?php endif; ?>
                    </div>

                    <!-- Small Images on the Right -->
                    <div class="space-y-2">
                        <?php if (!empty($venue['image_urls']) && count($venue['image_urls']) > 1): ?>
                            <img src="./<?= htmlspecialchars($venue['image_urls'][1]) ?>" alt="Venue Image"
                                class="venue-image w-full h-60 object-cover rounded-lg" data-image-index="1">
                        <?php else: ?>
                            <div
                                class="bg-slate-50 w-full h-60 rounded-lg shadow-lg border flex items-center justify-center">
                                <p>No more image to show</p>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($venue['image_urls']) && count($venue['image_urls']) > 2): ?>
                            <img src="./<?= htmlspecialchars($venue['image_urls'][2]) ?>" alt="Venue Image"
                                class="venue-image w-full h-60 object-cover rounded-lg" data-image-index="2">
                        <?php else: ?>
                            <div
                                class="bg-slate-50 w-full h-60 rounded-lg shadow-lg border flex items-center justify-center">
                                <p>No more image to show</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Show All Photos Button -->
                    <button id="showAllPhotosBtn" onclick="openGallery(0)"
                        class="absolute border-2 border-gray-500 bottom-4 right-4 bg-slate-50 px-4 py-2 rounded-lg font-semibold hover:bg-gray-100">
                        Show all photos
                    </button>
                </div>

                <div class="flex gap-12 flex-col md:flex-row">
                    <div class="md:w-2/3">
                        <div class="flex justify-between items-center mb-6 gap-4">
                            <div>
                                <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($venue['tag']) ?> at
                                    <?php echo htmlspecialchars($venue['location']) ?>
                                </h2>
                            </div>
                        </div>


                        <hr class="my-6">

                        <h3 class="text-xl font-semibold mb-4">Place Description</h3>
                        <p class="mb-4"><?php echo htmlspecialchars($venue['description']) ?></p>

                        <hr class="my-6">

                        <h3 class="text-xl font-semibold mb-4">Venue Capacity</h3>
                        <p class="">
                            <?php echo htmlspecialchars($venue['capacity']) ?>
                            guests
                        </p>

                        <hr class="my-6">

                        <h3 class="text-xl font-semibold mb-4">What this place offers</h3>
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <?php if (!empty($venue['amenities'])): ?>
                                <?php
                                $amenities = json_decode($venue['amenities'], true);
                                if ($amenities):
                                    ?>
                                    <ul class="list-disc pl-5 space-y-1">
                                        <?php foreach ($amenities as $amenity): ?>
                                            <li class="text-sm text-gray-800 leading-tight">
                                                <?= htmlspecialchars($amenity) ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="text-sm text-gray-500">No amenities available</p>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="text-sm text-gray-500">No amenities available</p>
                            <?php endif; ?>
                        </div>

                        <hr class="my-6">

                        <h3 class="text-xl font-semibold mb-4">Location</h3>
                        <div class="bg-gray-100 rounded-lg h-96 w-full mb-4" id="map">
                            <?php include_once './openStreetMap/autoMapping.osm.php' ?>
                        </div>

                        <hr class="my-6">

                        <h3 class="text-xl font-semibold mb-4">Ratings & Reviews</h3>
                        <div class="mb-8">
                            <div class="flex items-start gap-8">
                                <!-- Overall Rating -->
                                <div class="text-center">
                                    <div class="text-5xl font-bold mb-1">
                                        <?php echo number_format($venue['rating'], 1) ?>
                                    </div>
                                    <div class="flex items-center justify-center text-yellow-400 mb-1">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <?php echo htmlspecialchars($venue['total_reviews']) ?> reviews
                                    </div>
                                </div>

                                <!-- Rating Bars -->
                                <div class="flex-grow">
                                    <div class="space-y-2">
                                        <?php
                                        $totalReviews = isset($ratings['total']) ? (int) $ratings['total'] : 0;
                                        $rate = [
                                            5 => isset($ratings['rating_5']) ? (int) $ratings['rating_5'] : 0,
                                            4 => isset($ratings['rating_4']) ? (int) $ratings['rating_4'] : 0,
                                            3 => isset($ratings['rating_3']) ? (int) $ratings['rating_3'] : 0,
                                            2 => isset($ratings['rating_2']) ? (int) $ratings['rating_2'] : 0,
                                            1 => isset($ratings['rating_1']) ? (int) $ratings['rating_1'] : 0,
                                        ];

                                        // Find the maximum review count to normalize widths
                                        $maxReviewCount = max($rate);

                                        for ($i = 5; $i >= 1; $i--):
                                            $count = isset($rate[$i]) ? $rate[$i] : 0; // Count of reviews for the current star rating
                                            // Normalize percentage based on $maxReviewCount
                                            $normalizedPercentage = $maxReviewCount > 0 ? (($count) / $ratings['total']) * 100 : 0;
                                            ?>
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm w-16"><?php echo $i; ?> stars</span>
                                                <!-- Set explicit max width -->
                                                <div class="flex-grow h-2 bg-gray-200 rounded max-w-[500px]">
                                                    <!-- Dynamically set the width based on normalized percentage -->
                                                    <div class="h-full bg-yellow-400 rounded"
                                                        style="width: <?php echo $normalizedPercentage; ?>%;"></div>
                                                </div>
                                                <span class="text-sm w-8"><?php echo $count; ?></span>
                                            </div>
                                        <?php endfor; ?>

                                    </div>
                                </div>
                            </div>

                            <!-- Individual Reviews -->
                            <div class="mt-8 space-y-6" id="reviewContainer">
                                <?php foreach ($reviews as $index => $review): ?>
                                    <div class="border-b pb-6 review" data-index="<?php echo $index; ?>"
                                        style="<?php echo $index === 0 ? '' : 'display: none;'; ?>">
                                        <div class="flex items-center gap-4 mb-4">
                                            <?php
                                            if ($review['profile_pic'] == null) {
                                                echo '<div class="w-12 h-12 bg-black text-white rounded-full flex items-center justify-center font-bold">';
                                                echo htmlspecialchars($review['user_name'][0]); // Display the first letter of the user's first name
                                                echo '</div>';
                                            } else {
                                                echo '<img class="w-12 h-12 bg-gray-200 rounded-full" src="./' . htmlspecialchars($review['profile_pic']) . '" alt="Profile Picture">';
                                            }
                                            ?>

                                            <div>
                                                <a href="user-page.php"
                                                    class="font-semibold hover:underline"><?php echo htmlspecialchars($review['user_name']); ?></a>
                                                <p class="text-sm text-gray-500">
                                                    <?php
                                                    $originalDate = $review['date'];
                                                    $formattedDate = date('F j, Y \a\t g:i A', strtotime($originalDate)); // Format the date
                                                    echo htmlspecialchars($formattedDate);
                                                    ?>
                                                </p>

                                            </div>
                                        </div>
                                        <div class="flex text-yellow-400 mb-2">
                                            <?php for ($i = 0; $i < $review['rating']; $i++): ?>
                                                <i class="fas fa-star"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <p class="text-gray-700"><?php echo htmlspecialchars($review['review']); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Pagination -->
                            <div class="flex items-center justify-center gap-2 mt-6">
                                <button id="prevReview"
                                    class="px-4 py-2 text-sm border w-24 bg-neutral-200 transition-all duration-150 text-gray-600 hover:bg-gray-100 rounded">Previous</button>
                                <button id="nextReview"
                                    class="px-4 py-2 text-sm border w-24 bg-neutral-200 transition-all duration-150 text-gray-600 hover:bg-gray-100 rounded">Next</button>
                            </div>



                        </div>

                        <hr class="my-6">

                        <!-- New Section: Things You Should Know -->
                        <h3 class="text-xl font-semibold mb-4">Things You Should Know</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- House Rules -->
                            <div>
                                <h4 class="font-semibold text-lg mb-3">House Rules</h4>
                                <ul class="space-y-2">
                                    <?php
                                    if (isset($venue['time_inout'])) {
                                        $timeInOut = json_decode($venue['time_inout'], true); // Decode into array
                                    
                                        // Convert to 12-hour format with AM/PM
                                        $checkIn = DateTime::createFromFormat('H:i', $timeInOut['check_in'])->format('h:i A');
                                        $checkOut = DateTime::createFromFormat('H:i', $timeInOut['check_out'])->format('h:i A');
                                        ?>
                                        <li class="flex items-center gap-2">
                                            <i class="fas fa-clock text-gray-600"></i>
                                            <span>Check-in: After <?php echo htmlspecialchars($checkIn); ?></span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <i class="fas fa-clock text-gray-600"></i>
                                            <span>Checkout: Before <?php echo htmlspecialchars($checkOut); ?></span>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                    <li class="flex items-center gap-2">
                                        <i class="fas fa-users text-gray-600"></i>
                                        <span>Maximum <?php echo htmlspecialchars($venue['capacity']) ?> guests</span>
                                    </li>
                                    <?php
                                    if (!empty($venue['rules'])) {
                                        $rules = json_decode($venue['rules'], true); // Decode the JSON string into an array
                                        if ($rules):
                                            ?>
                                            <?php foreach ($rules as $rule): ?>
                                                <li class="list-disc list-inside flex items-center gap-2">
                                                    <?= htmlspecialchars($rule) ?>
                                                </li>
                                            <?php endforeach; ?>
                                            <?php
                                        endif;
                                    }
                                    ?>
                                </ul>
                            </div>

                            <!-- Cancellation Policy -->
                            <div>
                                <h4 class="font-semibold text-lg mb-3">Cancellation Policy</h4>
                                <div class="space-y-3">
                                    <p class="text-gray-700">Free cancellation for 48 hours after booking.</p>
                                    <p class="text-gray-700">Cancel before check-in and get a full refund, minus the
                                        service
                                        fee.</p>
                                    <div class="mt-4">
                                        <h5 class="font-medium mb-2">Refund Policy:</h5>
                                        <ul class="space-y-2 text-gray-700">
                                            <li class="flex items-center gap-2">
                                                <i class="fas fa-check text-green-600"></i>
                                                <span>100% refund: Cancel 7 days before check-in</span>
                                            </li>
                                            <li class="flex items-center gap-2">
                                                <i class="fas fa-check text-green-600"></i>
                                                <span>50% refund: Cancel 3-7 days before check-in</span>
                                            </li>
                                            <li class="flex items-center gap-2">
                                                <i class="fas fa-times text-red-600"></i>
                                                <span>No refund: Cancel less than 3 days before check-in</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-6">


                    </div>

                    <div class="md:w-1/3">
                        <div class="border rounded-xl p-6 shadow-lg mb-6">
                            <h3 class="text-xl font-semibold mb-4">The Owner</h3>
                            <div class="flex gap-4">
                                <a href="owner-page.php"
                                    class="bg-slate-50 p-6 w-full hover:bg-slate-100 transition duration-300 cursor-pointer">
                                    <!-- Card Header -->
                                    <div class="text-center mb-4">
                                        <div
                                            class="size-24 text-2xl rounded-full bg-black text-white flex items-center justify-center mx-auto mb-4">
                                            <?php

                                            $profilePic = $account->getProfilePic($owner[0]['id']);
                                            if (isset($owner) && empty($profilePic)) {
                                                echo $owner[0]['firstname'][0];
                                            } else {
                                                echo '<img id="profileImage" name="profile_image" src="./' . htmlspecialchars($profilePic) . '" alt="Profile Picture" class="w-full h-full rounded-full object-cover">';
                                            }
                                            ?>
                                        </div>
                                        <h2 class="text-xl font-semibold text-gray-800">
                                            <?php echo htmlspecialchars($owner[0]['firstname'] . " " . $owner[0]['lastname']); ?>
                                        </h2>
                                        <p class="text-xs text-gray-500">Owner</p>

                                    </div>
                                </a>
                            </div>
                        </div>




                        <div class="sticky top-32">
                            <form id="reservationForm" class="border rounded-xl p-6 shadow-lg bg-slate-50" method="GET"
                                action="payment.php">
                                <!-- Price Header -->
                                <div class="flex flex-col lg:flex-row justify-between items-center mb-6">
                                    <div class="flex items-baseline">
                                        <span
                                            class="text-3xl font-bold">₱<?php echo htmlspecialchars($venue['price']); ?></span>
                                        <span class="text-gray-600 ml-2">/ night</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        <span
                                            class="font-semibold"><?php echo number_format($venue['rating'], 1) ?></span>
                                        <span class="text-gray-500 text-xs"> <?php echo $venue['total_reviews'] ?>
                                            review/s</span>
                                    </div>
                                </div>

                                <!-- Date and Guest Selection -->
                                <div class="border rounded-xl mb-6 shadow-sm bg-gray-50 relative">
                                    <div class="flex border-b">
                                        <input type="hidden" name="venueId"
                                            value="<?php echo htmlspecialchars($venue['id']); ?>">
                                        <div class="w-1/2 p-3 border-r">
                                            <label
                                                class="block text-xs font-semibold text-gray-700 mb-1">CHECK-IN</label>
                                            <input type="date" name="checkin" placeholder="Set Date"
                                                class="w-full bg-transparent focus:outline-none text-gray-800">
                                        </div>
                                        <div class="w-1/2 p-3">
                                            <label
                                                class="block text-xs font-semibold text-gray-700 mb-1">CHECKOUT</label>
                                            <input type="date" name="checkout" placeholder="Set Date"
                                                class="w-full bg-transparent focus:outline-none text-gray-800">
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                                            GUESTS (Maximum <span
                                                class="text-red-500 font-bold"><?php echo htmlspecialchars($venue['capacity']); ?></span>)
                                        </label>
                                        <input type="number" name="numberOfGuest"
                                            class="w-full bg-transparent focus:outline-none text-gray-800"
                                            placeholder="Enter number of guests">
                                    </div>
                                </div>

                                <!-- Price Breakdown -->
                                <div class="space-y-4 mb-6">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 hover:text-gray-900 cursor-help">
                                            ₱<?php echo htmlspecialchars($venue['price']); ?> × <span
                                                total-nights>0</span>
                                            nights
                                        </span>
                                        <span class="font-medium">₱ <input type="number"
                                                class="text-right bg-transparent w-24" name="totalPriceForNights"
                                                value="0" readonly></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 hover:text-gray-900 cursor-help">
                                            Entrance fee × <span total-entrance-guests>0</span> guest
                                        </span>
                                        <span class="font-medium">₱ <input type="number"
                                                class="text-right bg-transparent w-24" name="totalEntranceFee"
                                                value="<?php echo htmlspecialchars($venue['entrance']); ?>"
                                                readonly></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 hover:text-gray-900 cursor-help">Cleaning fee</span>
                                        <span class="font-medium">₱ <input type="number"
                                                class="text-right bg-transparent w-24" name="cleaningFee"
                                                value="<?php echo htmlspecialchars($venue['cleaning']); ?>"
                                                readonly></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 hover:text-gray-900 cursor-help">HubVenue service
                                            fee</span>
                                        <span class="font-medium">₱ <input type="number"
                                                class="text-right bg-transparent w-24" name="serviceFee" value="0"
                                                readonly></span>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <span
                                            class="text-gray-600 hover:text-gray-900 cursor-help text-xs">Discounts(PWD/Senior
                                            Citizen)
                                        </span>
                                        <span class="font-medium text-right bg-transparent w-24" readonly>
                                            <?php
                                            if ($discountStatus['status'] == 'Active') {
                                                echo htmlspecialchars(number_format($discountStatus['discount_value'], 0)) . "%";
                                            } else {
                                                echo "0%";
                                            }
                                            ?>
                                        </span>
                                    </div>
                                </div>

                                <!-- Total -->
                                <div class="border-t pt-4 mb-6">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-bold">Total</span>
                                        <span class="font-bold text-lg">₱ <input type="number"
                                                class="text-right bg-transparent w-24 font-bold" name="totalPrice"
                                                value="0" readonly></span>
                                    </div>
                                </div>

                                <p class="text-center text-gray-600 my-4">You won't be charged yet</p>
                                <button type="submit"
                                    class="w-full bg-red-500 text-white rounded-lg py-3 font-semibold mb-4">Reserve</button>
                            </form>
                        </div>





                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="venue-comparison hidden" id="comparisonPanel">
        <button class="comparison-close hidden" id="closeCompareBtn" type="button" onclick="window.closeComparison()">
            <i class="fas fa-times text-xl"></i>
        </button>
        <div class="comparison-content">
            <h2 class="text-2xl font-semibold">Compare With</h2>
            <div id="comparisonVenues" class="space-y-4">
                <!-- Venues will be loaded here -->
            </div>
        </div>
    </div>

    <script src="./vendor/jQuery-3.7.1/jquery-3.7.1.min.js"></script>
    <script src="./js/user.jquery.js"></script>
    <script>
        // Define closeComparison in the global scope
        window.closeComparison = function () {
            const mainContent = document.querySelector('.main-content');
            const mainContainer = document.querySelector('.main-container');
            const comparisonPanel = document.getElementById('comparisonPanel');
            const closeCompareBtn = document.getElementById('closeCompareBtn');

            document.body.style.overflow = '';
            mainContent.classList.remove('shifted');
            mainContainer.classList.remove('shifted');
            comparisonPanel.classList.remove('active');

            setTimeout(() => {
                comparisonPanel.classList.add('hidden');
                closeCompareBtn.classList.add('hidden');
            }, 300);
        }
    </script>

    <!-- pagination -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const reviews = document.querySelectorAll('.review');
            let currentIndex = 0;

            function showReview(index) {
                reviews.forEach((review, i) => {
                    review.style.display = i === index ? 'block' : 'none';
                });
            }

            document.getElementById('prevReview').addEventListener('click', function () {
                if (currentIndex > 0) {
                    currentIndex--;
                    showReview(currentIndex);
                }
            });

            document.getElementById('nextReview').addEventListener('click', function () {
                if (currentIndex < reviews.length - 1) {
                    currentIndex++;
                    showReview(currentIndex);
                } else {
                    currentIndex = 0;
                    showReview(currentIndex);
                }
            });

            showReview(currentIndex);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Debug logging to verify elements are found
            console.log('Checkin:', document.querySelector('input[name="checkin"]'));
            console.log('Checkout:', document.querySelector('input[name="checkout"]'));
            console.log('Guests:', document.querySelector('input[name="numberOfGuest"]'));
            console.log('Total Price:', document.querySelector('input[name="totalPrice"]'));
            console.log('Price for Nights:', document.querySelector('input[name="totalPriceForNights"]'));
            console.log('Service Fee:', document.querySelector('input[name="serviceFee"]'));
            console.log('Entrance Fee:', document.querySelector('input[name="totalEntranceFee"]'));
            console.log('Cleaning Fee:', document.querySelector('input[name="cleaningFee"]'));

            const checkinInput = document.querySelector('input[name="checkin"]');
            const checkoutInput = document.querySelector('input[name="checkout"]');
            const guestsInput = document.querySelector('input[name="numberOfGuest"]');
            const totalPriceInput = document.querySelector('input[name="totalPrice"]');
            const totalPriceForNightsInput = document.querySelector('input[name="totalPriceForNights"]');
            const serviceFeeInput = document.querySelector('input[name="serviceFee"]');
            const entranceFeeInput = document.querySelector('input[name="totalEntranceFee"]');
            const cleaningFeeInput = document.querySelector('input[name="cleaningFee"]');
            const pricePerNight = <?php echo htmlspecialchars($venue['price']) ?>;
            const entranceFee = <?php echo htmlspecialchars($venue['entrance']) ?>;
            const cleaningFee = <?php echo htmlspecialchars($venue['cleaning']) ?>;
            const serviceFeeRate = 0.15;
            const maxGuests = <?php echo htmlspecialchars($venue['capacity']) ?>;

            const bookedDates = <?php echo json_encode($bookedDates); ?>;

            function disableBookedDates(date) {
                const dateString = date.toISOString().split('T')[0];
                return bookedDates.includes(dateString);
            }

            function disableDates(input, minDate = "today") {
                flatpickr(input, {
                    minDate: minDate,
                    disable: bookedDates,
                    appendTo: input.parentElement,
                    static: true,
                    onChange: function (selectedDates, dateStr, instance) {
                        validateDateRange();
                        calculateTotal();
                    }
                });
            }

            function validateDateRange() {
                const checkinDate = new Date(checkinInput.value);
                const checkoutDate = new Date(checkoutInput.value);
                const dateRange = new Date(checkinDate);

                while (dateRange <= checkoutDate) {
                    if (disableBookedDates(dateRange)) {
                        showModal('Selected date range includes unavailable dates.', undefined, 'icoco_black_ico.png');
                        checkinInput.value = '';
                        checkoutInput.value = '';
                        break;
                    }
                    dateRange.setDate(dateRange.getDate() + 1);
                }
            }

            disableDates(checkinInput);
            disableDates(checkoutInput);

            // Get today's date in YYYY-MM-DD format
            const today = new Date();
            const todayFormatted = today.toISOString().split('T')[0];

            // Set 'min' attributes to today for both checkin and checkout inputs
            checkinInput.setAttribute('min', todayFormatted);
            checkoutInput.setAttribute('min', todayFormatted);

            // Validate and correct selected date inputs
            function validateDate(input) {
                const selectedDate = new Date(input.value);
                if (selectedDate < today) {
                    input.value = todayFormatted; // Reset to today's date if past date is selected
                }
            }

            function calculateTotal() {
                console.log('Calculating total...'); // Debug log
                validateDate(checkinInput);
                validateDate(checkoutInput);

                const checkinDate = new Date(checkinInput.value);
                const checkoutDate = new Date(checkoutInput.value);
                const timeDiff = checkoutDate - checkinDate;
                const days = timeDiff / (1000 * 3600 * 24);

                let guests = parseInt(guestsInput.value);
                console.log('Days:', days, 'Guests:', guests); // Debug log

                if (isNaN(guests) || guests < 1) {
                    guests = 1;
                } else if (guests > maxGuests) {
                    guests = maxGuests;
                    guestsInput.value = maxGuests;
                }

                const discountRate = <?php echo $discountStatus['status'] == 'Active' ? $discountStatus['discount_value'] / 100 : 0; ?>;

                if (days > 0) {
                    const totalPriceForNights = pricePerNight * days;
                    const totalEntranceFee = entranceFee * guests;
                    const serviceFee = pricePerNight * serviceFeeRate;
                    let grandTotal = totalPriceForNights + totalEntranceFee + cleaningFee + serviceFee;

                    // Apply discount if applicable
                    if (discountRate > 0) {
                        grandTotal = grandTotal * (1 - discountRate);
                    }

                    console.log('Calculations:', { // Debug log
                        totalPriceForNights,
                        totalEntranceFee,
                        serviceFee,
                        grandTotal
                    });

                    document.querySelector('span[total-nights]').textContent = days;
                    document.querySelector('span[total-entrance-guests]').textContent = guests;
                    totalPriceForNightsInput.value = totalPriceForNights.toFixed(2);
                    entranceFeeInput.value = totalEntranceFee.toFixed(2);
                    serviceFeeInput.value = serviceFee.toFixed(2);
                    totalPriceInput.value = grandTotal.toFixed(2);
                }
            }

            // Add event listeners
            checkinInput.addEventListener('change', calculateTotal);
            checkoutInput.addEventListener('change', calculateTotal);
            guestsInput.addEventListener('input', calculateTotal);

            // Initial calculation
            calculateTotal();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const images = <?php echo json_encode($venue['image_urls'] ?? []); ?>;
            if (!images.length) return;

            const mainImage = document.querySelector('.col-span-2 .venue-image');
            const smallImages = document.querySelectorAll('.space-y-2 .venue-image');
            let currentMainIndex = 0;
            let currentSmallIndices = [1, 2];

            // Function to update main image with transition
            function updateMainImage(newIndex) {
                mainImage.classList.add('fade-out');

                setTimeout(() => {
                    mainImage.src = './' + images[newIndex];
                    mainImage.dataset.imageIndex = newIndex;
                    mainImage.classList.remove('fade-out');
                    mainImage.classList.add('fade-in');

                    setTimeout(() => {
                        mainImage.classList.remove('fade-in');
                    }, 500);
                }, 500);

                currentMainIndex = newIndex;
            }

            // Function to update small images without transition
            function updateSmallImages() {
                smallImages.forEach((img, i) => {
                    const nextIndex = (currentMainIndex + i + 1) % images.length;
                    img.src = './' + images[nextIndex];
                    img.dataset.imageIndex = nextIndex;
                    currentSmallIndices[i] = nextIndex;
                });
            }

            // Add click handlers to small images
            smallImages.forEach(img => {
                img.addEventListener('click', function () {
                    const clickedIndex = parseInt(this.dataset.imageIndex);
                    updateMainImage(clickedIndex);

                    // After updating main image, update small images
                    setTimeout(() => {
                        updateSmallImages();
                    }, 500);
                });
            });

            // Automatic rotation for main image only
            setInterval(() => {
                const nextIndex = (currentMainIndex + 1) % images.length;
                updateMainImage(nextIndex);
                updateSmallImages();
            }, 5000);

            // Pause shuffling when user hovers over images
            const imageContainer = document.querySelector('.grid');
            let rotationInterval;

            function startRotation() {
                rotationInterval = setInterval(() => {
                    const nextIndex = (currentMainIndex + 1) % images.length;
                    updateMainImage(nextIndex);
                    updateSmallImages();
                }, 5000);
            }

            imageContainer.addEventListener('mouseenter', () => {
                clearInterval(rotationInterval);
            });

            // Resume shuffling when user moves mouse away
            imageContainer.addEventListener('mouseleave', () => {
                startRotation();
            });

            // Start initial rotation
            startRotation();
        });
    </script>

    <!-- Photo Gallery Modal -->
    <div id="photoGalleryModal" class="fixed inset-0 z-50 hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black bg-opacity-80 transition-opacity duration-300 opacity-0"
            id="modalBackdrop"></div>

        <!-- Modal Content -->
        <div class="relative h-full w-full flex flex-col">
            <!-- Header -->
            <div class="absolute top-0 left-0 right-0 p-4 z-10">
                <div class="max-w-7xl mx-auto flex justify-between items-center">
                    <button id="closeGallery" class="text-white hover:bg-slate-50/10 p-2 rounded-full transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                    <span class="text-white text-sm">
                        <span id="currentImageIndex">1</span> / <span id="totalImages">5</span>
                    </span>
                </div>
            </div>

            <!-- Main Gallery Area -->
            <div class="flex-1 flex items-center justify-center p-4">
                <div class="relative w-full max-w-7xl mx-auto">
                    <!-- Navigation Buttons -->
                    <button id="prevImage"
                        class="absolute left-4 top-1/2 -translate-y-1/2 text-white hover:bg-slate-50/10 p-4 rounded-full transition">
                        <i class="fas fa-chevron-left text-2xl"></i>
                    </button>

                    <button id="nextImage"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-white hover:bg-slate-50/10 p-4 rounded-full transition">
                        <i class="fas fa-chevron-right text-2xl"></i>
                    </button>

                    <!-- Main Image -->
                    <div class="flex justify-center">
                        <img id="mainGalleryImage" src="" alt="Venue Image" class="max-h-[80vh] object-contain">
                    </div>
                </div>
            </div>

            <!-- Thumbnails -->
            <div class="w-full p-4">
                <div class="max-w-7xl mx-auto">
                    <div id="thumbnailContainer" class="flex gap-2 overflow-x-auto pb-2">
                        <!-- Thumbnails will be inserted here by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('photoGalleryModal');
            const modalBackdrop = document.getElementById('modalBackdrop');
            const mainGalleryImage = document.getElementById('mainGalleryImage');
            const thumbnailContainer = document.getElementById('thumbnailContainer');
            const currentImageIndex = document.getElementById('currentImageIndex');
            const totalImages = document.getElementById('totalImages');
            const prevButton = document.getElementById('prevImage');
            const nextButton = document.getElementById('nextImage');
            const closeButton = document.getElementById('closeGallery');

            const images = <?php echo json_encode($venue['image_urls'] ?? []); ?>;
            let currentIndex = 0;

            // Make openGallery function available globally
            window.openGallery = function (index) {
                currentIndex = index;
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modalBackdrop.classList.remove('opacity-0');
                }, 10);
                updateGallery();
                createThumbnails();
                document.body.style.overflow = 'hidden';
            }

            // Show All Photos button click handler
            const showAllPhotosButton = document.querySelector('button[class*="border-2 border-gray-500"]');
            if (showAllPhotosButton) {
                showAllPhotosButton.addEventListener('click', function () {
                    openGallery(0);
                });
            }

            function closeGallery() {
                modalBackdrop.classList.add('opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                }, 300);
            }

            function updateGallery() {
                mainGalleryImage.src = './' + images[currentIndex];
                currentImageIndex.textContent = currentIndex + 1;
                totalImages.textContent = images.length;

                // Update thumbnails active state
                document.querySelectorAll('.thumbnail').forEach((thumb, index) => {
                    if (index === currentIndex) {
                        thumb.classList.add('ring-2', 'ring-white');
                        thumb.classList.remove('opacity-70');
                    } else {
                        thumb.classList.remove('ring-2', 'ring-white');
                        thumb.classList.add('opacity-70');
                    }
                });
            }

            function createThumbnails() {
                thumbnailContainer.innerHTML = '';
                images.forEach((image, index) => {
                    const thumb = document.createElement('img');
                    thumb.src = './' + image;
                    thumb.classList.add('thumbnail', 'h-20', 'w-32', 'object-cover', 'cursor-pointer',
                        'transition-opacity', 'duration-200', 'opacity-70', 'hover:opacity-100');
                    if (index === currentIndex) {
                        thumb.classList.add('ring-2', 'ring-white');
                        thumb.classList.remove('opacity-70');
                    }
                    thumb.addEventListener('click', () => {
                        currentIndex = index;
                        updateGallery();
                    });
                    thumbnailContainer.appendChild(thumb);
                });
            }

            // Event Listeners
            closeButton.addEventListener('click', closeGallery);
            modalBackdrop.addEventListener('click', closeGallery);

            prevButton.addEventListener('click', () => {
                currentIndex = (currentIndex - 1 + images.length) % images.length;
                updateGallery();
            });

            nextButton.addEventListener('click', () => {
                currentIndex = (currentIndex + 1) % images.length;
                updateGallery();
            });

            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (!modal.classList.contains('hidden')) {
                    if (e.key === 'Escape') closeGallery();
                    if (e.key === 'ArrowLeft') prevButton.click();
                    if (e.key === 'ArrowRight') nextButton.click();
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Comparison Panel Functionality
            const compareButton = document.getElementById('compareButton');
            const mainContent = document.querySelector('.main-content');
            const comparisonPanel = document.getElementById('comparisonPanel');
            const comparisonVenues = document.getElementById('comparisonVenues');
            const closeCompareBtn = document.getElementById('closeCompareBtn');

            // Completely separate compare button handler
            if (compareButton) {
                compareButton.onclick = function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation(); // Stop other events from firing

                    // Remove any photo gallery related classes/states
                    const photoGalleryModal = document.getElementById('photoGalleryModal');
                    if (photoGalleryModal) {
                        photoGalleryModal.classList.add('hidden');
                    }

                    openComparisonPanel();
                    return false;
                };
            }

            function openComparisonPanel() {
                document.body.style.overflow = 'hidden';
                mainContent.classList.add('shifted');
                document.querySelector('.main-container').classList.add('shifted');
                comparisonPanel.classList.remove('hidden');
                comparisonPanel.classList.add('active');
                closeCompareBtn.classList.remove('hidden');
                loadComparisonVenues();
            }

            function closeComparison() {
                document.body.style.overflow = '';
                mainContent.classList.remove('shifted');
                document.querySelector('.main-container').classList.remove('shifted');
                comparisonPanel.classList.remove('active');
                setTimeout(() => {
                    comparisonPanel.classList.add('hidden');
                    closeCompareBtn.classList.add('hidden');
                }, 300);
            }

            async function loadComparisonVenues() {
                try {
                    comparisonVenues.innerHTML = '<div class="text-center py-4">Loading venues...</div>';
                    const response = await fetch('get_comparison_venues.php?current_venue_id=<?php echo $venue['id']; ?>');
                    const venues = await response.json();

                    if (venues.length === 0) {
                        comparisonVenues.innerHTML = '<div class="text-center py-4">No venues available for comparison</div>';
                        return;
                    }

                    comparisonVenues.innerHTML = venues.map(venue => `
    <div class="bg-slate-50 rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300 mb-6">
        <div class="relative">
            <div class="relative w-full h-48 overflow-hidden">
                <img src="./${venue.image_urls[0]}" alt="${venue.name}" class="w-full h-full object-cover">
            </div>
            <div class="p-4">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-lg font-semibold text-gray-900">${venue.name}</h3>
                    <div class="flex items-center">
                        <p class="font-bold text-sm">${parseFloat(venue.rating).toFixed(1)}</p>
                        <i class="fas fa-star text-yellow-500 ml-1"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-500 line-clamp-2 mb-3">${venue.description}</p>
                <div class="flex justify-between items-center">
                    <p class="text-gray-900">
                        <span class="font-semibold">₱${parseFloat(venue.price).toLocaleString()}</span>
                        <span class="text-sm"> / night</span>
                    </p>
                    <div class="flex gap-2">
                        <button onclick="toggleVenueDetails(${venue.id}, this)"
                            class="inline-flex items-center justify-center px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-red-600 transition-colors">
                            View Details
                        </button>
                        <a href="venues.php?id=${venue.id}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900 transition-colors">
                            View Venue
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div id="venue-details-${venue.id}" class="hidden p-4 border-t">
            <div class="space-y-4">
                <div>
                    <h4 class="font-semibold mb-2">Place Description</h4>
                    <p class="text-sm text-gray-600">${venue.description}</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-2">Venue Capacity</h4>
                    <p class="text-sm text-gray-600">${venue.capacity} guests</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-2">Location</h4>
                    <p class="text-sm text-gray-600">${venue.location}</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-2">Amenities</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        ${JSON.parse(venue.amenities).map(amenity => `
                        <li>• ${amenity}</li>
                        `).join('')}
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-2">House Rules</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        ${venue.rules ? JSON.parse(venue.rules).map(rule => `
                        <li>• ${rule}</li>
                        `).join('') : '<li>No specific rules listed</li>'}
                    </ul>
                </div>
            </div>
        </div>
    </div>
    `).join('');
                } catch (error) {
                    console.error('Error loading comparison venues:', error);
                    comparisonVenues.innerHTML = '<div class="text-center py-4 text-red-500">Error loading venues</div>';
                }
            }

            // Function to toggle venue details
            window.toggleVenueDetails = function (venueId, button) {
                const detailsSection = document.getElementById(`venue-details-${venueId}`);
                if (detailsSection.classList.contains('hidden')) {
                    detailsSection.classList.remove('hidden');
                    button.textContent = 'Hide Details';
                    // Allow time for the hidden class to be removed before setting max-height
                    requestAnimationFrame(() => {
                        detailsSection.style.maxHeight = detailsSection.scrollHeight + 'px';
                    });
                } else {
                    detailsSection.style.maxHeight = '0';
                    button.textContent = 'View Details';
                    // Wait for transition to complete before hiding
                    setTimeout(() => {
                        detailsSection.classList.add('hidden');
                    }, 300);
                }
            }

            // Make loadComparisonVenues available globally
            window.loadComparisonVenues = loadComparisonVenues;
        });
    </script>

    <!-- Photo Gallery Functionality -->
    <script>
        // Photo Gallery Functionality
        document.addEventListener('DOMContentLoaded', function () {
            const showAllPhotosBtn = document.getElementById('showAllPhotosBtn');
            const photoGalleryModal = document.getElementById('photoGalleryModal');

            // Only attach photo gallery event to the show all photos button
            if (showAllPhotosBtn) {
                showAllPhotosBtn.onclick = function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    openGallery(0);
                    return false;
                };
            }

            // Rest of the photo gallery code...
        });
    </script>

</body>

</html>