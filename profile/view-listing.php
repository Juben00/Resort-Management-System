<?php
require_once '../classes/venue.class.php';

session_start();
$venueObj = new Venue();
$venuePost = null;

$getParams = $_GET['id'];
$venueView = $venueObj->getSingleVenue($getParams);

$ratings = $venueObj->getRatings($_GET['id']);
$reviews = $venueObj->getReview($_GET['id']);

$bookings = $venueObj->getBookingByVenue($_GET['id'], 2);

$bookingCount = 0;
$bookingRevenue = 0;
$bookingThisMonth = 0;
?>

<head>
    <link rel="stylesheet" href="./output.css">
</head>
<!-- Venue Details View (Initially Hidden) -->
<div id="venueDetailsView" class="container mx-auto pt-20 px-4 sm:px-6 lg:px-8">
    <div class="mb-4">
        <a id="backToListing" class="flex items-center text-sm cursor-pointer text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Listings
        </a>
    </div>

    <div class="flex flex-col w-full lg:flex-row gap-8">
        <!-- Main Content -->
        <div class="w-full">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h1 id="detailVenueName" class="text-3xl font-bold text-gray-900 view-mode">
                            <?php echo htmlspecialchars($venueView['venue_name']); ?>
                        </h1>
                        <input id="editVenueName" class="text-3xl font-bold w-full edit-mode hidden"
                            value="<?php echo htmlspecialchars(trim($venueView['venue_name'])); ?>">
                        <button onclick="toggleEditMode()"
                            class="text-sm px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg flex items-center gap-2 transition duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            Edit Details
                        </button>
                    </div>

                    <!-- Image Gallery -->
                    <div class="mb-8">
                        <div class="relative">
                            <?php if (!empty($venueView['image_urls'])): ?>
                                <img src="./<?= htmlspecialchars($venueView['image_urls'][0]) ?>" alt="Venue Image"
                                    class="w-full h-[500px] object-cover rounded-xl">
                            <?php else: ?>
                                <div class="w-full h-[500px] bg-gray-200 rounded-xl flex items-center justify-center">
                                    <p class="text-gray-500">No image available</p>
                                </div>
                            <?php endif; ?>
                            <button
                                class="absolute bottom-4 right-4 bg-white text-gray-800 px-4 py-2 rounded-lg font-semibold hover:bg-gray-100 transition duration-300 shadow-md">
                                Show all photos
                            </button>
                        </div>
                        <div class="grid grid-cols-4 gap-4 mt-4">
                            <?php
                            $imageUrls = !empty($venueView['image_urls']) ? array_slice($venueView['image_urls'], 1, 4) : [];
                            foreach ($imageUrls as $image):
                                ?>
                                <img src="./<?= htmlspecialchars($image) ?>" alt="Venue Image"
                                    class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-75 transition duration-300">
                            <?php endforeach; ?>
                            <?php for ($i = count($imageUrls); $i < 4; $i++): ?>
                                <div class="w-full h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <p class="text-gray-500 text-sm">No image</p>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- Location -->
                        <div>
                            <h3 class="text-xl font-semibold mb-3">Location</h3>
                            <p id="detailVenueLocation" class="text-gray-600 view-mode">
                                <?php echo htmlspecialchars(trim($venueView['venue_location'])); ?>
                            </p>
                            <input type="text" id="editVenueLocation"
                                class="form-input w-full rounded-md edit-mode hidden"
                                value="<?php echo htmlspecialchars(trim($venueView['venue_location'])); ?>">
                        </div>

                        <!-- Capacity -->
                        <div>
                            <h3 class="text-xl font-semibold mb-3">Capacity</h3>
                            <p id="detailVenueCapacity" class="text-gray-600 view-mode">
                                <?php echo trim(htmlspecialchars($venueView['capacity'])); ?> guests
                            </p>
                            <input type="number" id="editVenueCapacity"
                                class="form-input w-full rounded-md edit-mode hidden"
                                value="<?php echo htmlspecialchars($venueView['capacity']); ?>">
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold mb-3">Description</h3>
                        <p id="detailVenueDescription" class="text-gray-600 view-mode">
                            <?php echo nl2br(htmlspecialchars(trim($venueView['venue_description']))); ?>
                        </p>
                        <textarea id="editVenueDescription" class="form-textarea w-full rounded-md edit-mode hidden"
                            rows="4"><?php echo trim(htmlspecialchars($venueView['venue_description'])); ?></textarea>
                    </div>

                    <!-- Amenities -->
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold mb-3">What this place offers</h3>
                        <?php if (!empty($venueView['amenities'])): ?>
                            <?php
                            $amenities = json_decode($venueView['amenities'], true);
                            if ($amenities):
                                ?>
                                <ul class="grid grid-cols-2 gap-4">
                                    <?php foreach ($amenities as $amenity): ?>
                                        <li class="flex items-center text-gray-600">
                                            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <?= htmlspecialchars($amenity) ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-gray-500">No amenities available</p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-gray-500">No amenities available</p>
                        <?php endif; ?>
                        <div id="editVenueAmenities" class="edit-mode hidden space-y-2 mt-4">
                            <div id="amenitiesList"></div>
                            <button onclick="addAmenityField()" class="text-blue-600 hover:text-blue-800 text-sm">+ Add
                                amenity</button>
                        </div>
                    </div>

                    <!-- Venue Rules -->
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold mb-3">Venue Rules</h3>
                        <?php if (!empty($venueView['rules'])): ?>
                            <?php
                            $rules = json_decode($venueView['rules'], true);
                            if ($rules):
                                ?>
                                <ul class="space-y-2">
                                    <?php foreach ($rules as $rule): ?>
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 mr-2 text-red-500 mt-1 flex-shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                </path>
                                            </svg>
                                            <span class="text-gray-600"><?= htmlspecialchars($rule) ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-gray-500">No rules stated</p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-gray-500">No rules stated</p>
                        <?php endif; ?>
                        <div id="editVenueRules" class="edit-mode hidden space-y-2 mt-4">
                            <div id="rulesList"></div>
                            <button onclick="addRuleField()" class="text-blue-600 hover:text-blue-800 text-sm">+ Add
                                rule</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ratings & Reviews Section -->
            <div class="bg-white rounded-xl shadow-sm mt-8 p-6">
                <h3 class="text-2xl font-bold mb-6">Ratings & Reviews</h3>

                <!-- Rating Summary -->
                <div class="bg-gray-50 p-6 rounded-lg mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <!-- Overall Rating -->
                        <div class="text-center mb-4 md:mb-0">
                            <div class="text-5xl font-bold mb-1">
                                <?php echo number_format($ratings['average'], 1) ?>
                            </div>
                            <div class="flex items-center justify-center text-yellow-400 mb-1">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <svg class="w-6 h-6 <?php echo $i <= $ratings['average'] ? 'text-yellow-400' : 'text-gray-300'; ?>"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                <?php endfor; ?>
                            </div>
                            <div class="text-sm text-gray-600">
                                <?php echo htmlspecialchars($ratings['total']) ?? 0 ?> reviews
                            </div>
                        </div>

                        <!-- Rating Breakdown -->
                        <div class="flex-grow md:ml-8">
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

                                $maxReviewCount = max($rate);

                                for ($i = 5; $i >= 1; $i--):
                                    $count = isset($rate[$i]) ? $rate[$i] : 0;
                                    $normalizedPercentage = $maxReviewCount > 0 ? (($count) / $ratings['total']) * 100 : 0;
                                    ?>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm w-16"><?php echo $i; ?> stars</span>
                                        <div class="flex-grow h-2 bg-gray-200 rounded max-w-[200px]">
                                            <div class="h-full bg-yellow-400 rounded"
                                                style="width: <?php echo $normalizedPercentage; ?>%;"></div>
                                        </div>
                                        <span class="text-sm w-8"><?php echo $count; ?></span>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Individual Reviews -->
                <div class="mt-8 space-y-6">
                    <?php foreach ($reviews as $index => $review): ?>
                        <div class="border-b pb-6 review" data-index="<?php echo $index; ?>"
                            style="<?php echo $index === 0 ? '' : 'display: none;'; ?>">
                            <div class="flex items-center gap-4 mb-4">
                                <?php if ($review['profile_pic'] == null): ?>
                                    <div
                                        class="w-12 h-12 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-bold">
                                        <?php echo htmlspecialchars(substr($review['user_name'], 0, 1)); ?>
                                    </div>
                                <?php else: ?>
                                    <img class="w-12 h-12 rounded-full object-cover"
                                        src="./<?php echo htmlspecialchars($review['profile_pic']); ?>" alt="Profile Picture">
                                <?php endif; ?>
                                <div>
                                    <a href="user-page.php" class="font-semibold hover:underline">
                                        <?php echo htmlspecialchars($review['user_name']); ?>
                                    </a>
                                    <p class="text-sm text-gray-500">
                                        <?php
                                        $originalDate = $review['date'];
                                        $formattedDate = date('F j, Y \a\t g:i A', strtotime($originalDate));
                                        echo htmlspecialchars($formattedDate);
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex text-yellow-400 mb-2">
                                <?php for ($i = 0; $i < $review['rating']; $i++): ?>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                <?php endfor; ?>
                            </div>
                            <p class="text-gray-700"><?php echo htmlspecialchars($review['review']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <div class="flex items-center justify-center gap-2 mt-6">
                    <button id="prevReview"
                        class="px-4 py-2 text-sm border bg-white text-gray-700 hover:bg-gray-50 rounded-lg transition duration-300">Previous</button>
                    <button id="nextReview"
                        class="px-4 py-2 text-sm border bg-white text-gray-700 hover:bg-gray-50 rounded-lg transition duration-300">Next</button>
                </div>
            </div>

            <!-- Calendar & Pricing Section -->
            <div class="bg-white rounded-xl shadow-sm mt-8 p-6">
                <h3 class="text-2xl font-bold mb-6">Calendar & Pricing</h3>

                <!-- Calendar Header -->
                <div class="flex justify-between items-center mb-4 calendar-header">
                    <div class="flex items-center space-x-4">
                        <button class="p-2 hover:bg-gray-100 rounded-lg calendar-prev transition duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <h4 class="text-lg font-semibold">October 2024</h4>
                        <button class="p-2 hover:bg-gray-100 rounded-lg calendar-next transition duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Calendar Grid -->
                <div class="border rounded-lg overflow-hidden">
                    <!-- Calendar Header -->
                    <div class="grid grid-cols-7 text-sm font-medium text-gray-500 bg-gray-50">
                        <div class="p-2 text-center">Su</div>
                        <div class="p-2 text-center">Mo</div>
                        <div class="p-2 text-center">Tu</div>
                        <div class="p-2 text-center">We</div>
                        <div class="p-2 text-center">Th</div>
                        <div class="p-2 text-center">Fr</div>
                        <div class="p-2 text-center">Sa</div>
                    </div>

                    <!-- Calendar Days -->
                    <div class="grid grid-cols-7 calendar-days">
                        <?php
                        // Previous month days (greyed out)
                        for ($i = 0; $i < 0; $i++) {
                            echo '<div class="p-2 border-b border-r text-gray-400"></div>';
                        }

                        // Current month days
                        for ($day = 1; $day <= 31; $day++) {
                            $isToday = $day === 5; // Example: 5th is today
                            $hasPrice = true; // Example: All days have prices
                        
                            echo '<div class="relative p-2 border-b border-r hover:bg-gray-50 transition duration-300 cursor-pointer">';
                            echo '<div class="text-sm ' . ($isToday ? 'font-bold text-blue-600' : '') . '">' . $day . '</div>';
                            if ($hasPrice) {
                                echo '<div class="text-xs text-gray-600"> ₱' . number_format($venueView['price']) . '</div>';
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="w-1/3">
            <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                <div class="mb-6">
                    <h3 class="text-xl font-semibold mb-4">Venue Settings</h3>

                    <!-- Price Setting -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price per day</label>
                        <div class="flex items-center">
                            <span class="text-gray-500 mr-2">₱</span>
                            <input type="number" id="venuePrice" class="form-input rounded-md w-full"
                                value="<?php echo htmlspecialchars($venueView['price']) ?>">
                        </div>
                    </div>

                    <!-- Save Changes Button -->
                    <button onclick="saveChanges()"
                        class="w-full bg-black text-white py-2 px-4 rounded-lg hover:bg-gray-800 transition duration-300 mt-4">
                        Save Changes
                    </button>
                </div>

                <!-- Quick Stats -->
                <div class="border-t pt-6">
                    <h4 class="text-lg font-semibold mb-4">Booking Statistics</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Total Bookings</p>
                            <p class="text-xl font-semibold"><?php echo htmlspecialchars($bookingCount) ?></p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">This Month</p>
                            <p class="text-xl font-semibold"><?php echo htmlspecialchars($bookingThisMonth) ?></p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Revenue</p>
                            <p class="text-xl font-semibold">₱<?php echo number_format($bookingRevenue) ?></p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Rating</p>
                            <p class="text-xl font-semibold"><?php echo number_format($ratings['average'], 1) ?>/5</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Reservations Section -->
                <div class="border-t pt-6 mt-6">
                    <h4 class="text-lg font-semibold mb-4">Recent Reservations</h4>
                    <div class="space-y-4">
                        <?php
                        if (empty($bookings)) {
                            echo '<p class="text-gray-600 text-sm text-center">No bookings found.</p>';
                        } else {
                            foreach ($bookings as $booking):
                                ?>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="font-medium">
                                                <?php echo htmlspecialchars($booking['event_type'] ?? 'Event'); ?>
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                <?php echo htmlspecialchars($booking['firstname'] . " " . $booking['middlename'] . "." . " " . $booking['lastname']); ?>
                                            </p>
                                        </div>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <?php echo htmlspecialchars($booking['booking_status'] ?? 'Confirmed'); ?>
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-600 mb-2">
                                        <?php
                                        $startDate = new DateTime($booking['booking_start_date']);
                                        $endDate = new DateTime($booking['booking_end_date']);
                                        echo $startDate->format('F j, Y') . ' to ' . $endDate->format('F j, Y');
                                        ?>
                                    </div>
                                    <p class="font-semibold">₱<?php echo number_format($booking['booking_grand_total']) ?></p>
                                </div>
                                <?php
                            endforeach;
                        }
                        ?>
                    </div>

                    <!-- View All Reservations Link -->
                    <div class="mt-4 text-center">
                        <a href="calendar.php"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium transition duration-300">
                            View All Reservations →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showVenueDetails(venue) {
        // Hide listings view and show details view
        document.getElementById('listingsView').classList.add('hidden');
        document.getElementById('venueDetailsView').classList.remove('hidden');

        // Populate venue details
        document.getElementById('detailVenueName').textContent = venue.name;
        document.getElementById('detailVenueLocation').textContent = venue.location;
        document.getElementById('detailVenueDescription').textContent = venue.description || 'No description provided yet';
        document.getElementById('detailVenueCapacity').textContent = venue.capacity ? `${venue.capacity} guests` : 'Capacity not specified';
        document.getElementById('detailVenuePrice').textContent = venue.price;

        // Populate amenities with descriptions
        const amenitiesList = document.getElementById('detailVenueAmenities');
        amenitiesList.innerHTML = '';
        if (Array.isArray(venue.amenities) && venue.amenities.length === 0) {
            amenitiesList.innerHTML = '<p class="text-gray-500 italic">No amenities listed yet</p>';
        } else if (typeof venue.amenities === 'object') {
            Object.entries(venue.amenities).forEach(([amenity, details]) => {
                const li = document.createElement('li');
                li.className = 'flex items-center gap-2 mb-3';
                if (details && details.description) {
                    // Detailed amenity format
                    li.innerHTML = `
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="font-medium">${amenity}</span>
                        <p class="text-sm text-gray-500">${details.description}</p>
                    </div>
                `;
                } else {
                    // Simple amenity format
                    li.innerHTML = `
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="font-medium">${amenity}</span>
                    </div>
                `;
                }
                amenitiesList.appendChild(li);
            });
        }

        // Populate rules with sections
        const rulesList = document.getElementById('detailVenueRules');
        rulesList.innerHTML = '';
        if (Array.isArray(venue.rules) && venue.rules.length === 0) {
            rulesList.innerHTML = `
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-yellow-700 font-medium">Required: Please add venue rules</p>
                </div>
                <p class="text-yellow-600 text-sm mt-2">Specify guidelines and restrictions for venue use</p>
            </div>
        `;
        } else {
            // Existing rules display code...
        }

        // Populate owner information
        const ownerDiv = document.getElementById('detailVenueOwner');
        ownerDiv.innerHTML = `
        <p><strong>Name:</strong> ${venue.owner.first_name} ${venue.owner.last_name}</p>
        <p><strong>Contact:</strong> ${venue.owner.contact}</p>
        <p><strong>Email:</strong> ${venue.owner.email}</p>
    `;

        // Show status badge
        const statusDiv = document.getElementById('detailVenueStatus');
        let statusColor = '';
        switch (venue.status) {
            case 'Approved':
                statusColor = 'bg-green-500 border-green-600';
                break;
            case 'Pending':
                statusColor = 'bg-yellow-500 border-yellow-600';
                break;
            case 'Declined':
                statusColor = 'bg-red-500 border-red-600';
                break;
        }
        statusDiv.innerHTML = `
        <div class="inline-block border ${statusColor} text-white px-3 py-1 rounded-full">
            ${venue.status}
        </div>
    `;

        // Set edit mode fields initial values
        document.getElementById('editVenueLocation').value = venue.location;
        document.getElementById('editVenueDescription').value = venue.description;
        document.getElementById('editVenueCapacity').value = venue.capacity;

        // Reset to view mode when showing details
        const viewElements = document.querySelectorAll('.view-mode');
        const editElements = document.querySelectorAll('.edit-mode');
        viewElements.forEach(el => el.classList.remove('hidden'));
        editElements.forEach(el => el.classList.add('hidden'));

        // Handle missing images
        const mainImage = document.getElementById('mainImage');
        mainImage.src = venue.image_urls && venue.image_urls.length > 0
            ? venue.image_urls[0]
            : '../images/black_ico.png';
        mainImage.alt = venue.name || 'Venue image';
    }

    function toggleEditMode() {
        const viewElements = document.querySelectorAll('.view-mode');
        const editElements = document.querySelectorAll('.edit-mode');

        viewElements.forEach(el => el.classList.toggle('hidden'));
        editElements.forEach(el => el.classList.toggle('hidden'));

        // Populate edit fields with current values
        if (!editElements[0].classList.contains('hidden')) {
            document.getElementById('editVenueName').value = document.getElementById('detailVenueName').textContent.trim();
            document.getElementById('editVenueLocation').value = document.getElementById('detailVenueLocation').textContent.trim();
            document.getElementById('editVenueDescription').value = document.getElementById('detailVenueDescription').textContent.trim();
            document.getElementById('editVenueCapacity').value = document.getElementById('detailVenueCapacity').textContent.trim();
            populateAmenitiesEdit();
            populateRulesEdit();
        }
    }

    function saveChanges() {
        // Collect values from input fields
        const venueName = document.getElementById('editVenueName').value;
        const venueLocation = document.getElementById('editVenueLocation').value;
        const venueDescription = document.getElementById('editVenueDescription').value;
        const venueCapacity = document.getElementById('editVenueCapacity').value;
        const venuePrice = document.getElementById('venuePrice').value;
        const amenities = Array.from(document.querySelectorAll('#editVenueAmenities input')).map(input => input.value);
        const rules = Array.from(document.querySelectorAll('#editVenueRules input')).map(input => input.value);

        // Send data to server to save changes
        fetch('save-venue-details.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                venue_name: venueName,
                venue_location: venueLocation,
                venue_description: venueDescription,
                venue_capacity: venueCapacity,
                venue_price: venuePrice,
                amenities: amenities,
                rules: rules
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update view mode with new values
                    document.getElementById('detailVenueName').textContent = venueName;
                    document.getElementById('detailVenueLocation').textContent = venueLocation;
                    document.getElementById('detailVenueDescription').textContent = venueDescription;
                    document.getElementById('detailVenueCapacity').textContent = `${venueCapacity} guests`;
                    document.getElementById('detailVenuePrice').textContent = `₱${venuePrice}`;
                    updateAmenitiesView(amenities);
                    updateRulesView(rules);

                    // Switch back to view mode
                    toggleEditMode();
                } else {
                    alert('Failed to save changes');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to save changes');
            });
    }

    function populateAmenitiesEdit() {
        const amenitiesList = document.getElementById('amenitiesList');
        amenitiesList.innerHTML = '';

        const currentAmenities = Array.from(document.querySelectorAll('#detailVenueAmenities li'))
            .map(li => li.textContent.trim());

        currentAmenities.forEach(amenity => {
            addAmenityField(amenity);
        });
    }

    function populateRulesEdit() {
        const rulesList = document.getElementById('rulesList');
        rulesList.innerHTML = '';

        const currentRules = Array.from(document.querySelectorAll('#detailVenueRules li'))
            .map(li => li.textContent.trim());

        currentRules.forEach(rule => {
            addRuleField(rule);
        });
    }

    function updateAmenitiesView(amenities) {
        const amenitiesList = document.getElementById('detailVenueAmenities');
        amenitiesList.innerHTML = '';
        amenities.forEach(amenity => {
            const li = document.createElement('li');
            li.className = 'text-sm text-gray-800 leading-tight';
            li.textContent = amenity;
            amenitiesList.appendChild(li);
        });
    }

    function updateRulesView(rules) {
        const rulesList = document.getElementById('detailVenueRules');
        rulesList.innerHTML = '';
        rules.forEach(rule => {
            const li = document.createElement('li');
            li.className = 'text-sm text-gray-800 leading-tight';
            li.textContent = rule;
            rulesList.appendChild(li);
        });
    }

    function addAmenityField(value = '') {
        const amenitiesList = document.getElementById('amenitiesList');
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2';
        div.innerHTML = `
        <input type="text" class="form-input rounded-md flex-grow" value="${value}">
        <button onclick="this.parentElement.remove()" class="p-2 text-red-500 hover:bg-red-50 rounded-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    `;
        amenitiesList.appendChild(div);
    }

    function addRuleField(value = '') {
        const rulesList = document.getElementById('rulesList');
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2';
        div.innerHTML = `
        <input type="text" class="form-input rounded-md flex-grow" value="${value}" placeholder="Enter venue rule">
        <button onclick="this.parentElement.remove()" class="p-2 text-red-500 hover:bg-red-50 rounded-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    `;
        rulesList.appendChild(div);
    }

    // Add this to your existing script section
    function initializeCalendar() {
        const date = new Date();
        let currentMonth = date.getMonth();
        let currentYear = date.getFullYear();

        updateCalendarHeader(currentMonth, currentYear);
        generateCalendarDays(currentMonth, currentYear);

        document.querySelector('.calendar-prev').addEventListener('click', function () {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            updateCalendarHeader(currentMonth, currentYear);
            generateCalendarDays(currentMonth, currentYear);
        });

        document.querySelector('.calendar-next').addEventListener('click', function () {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            updateCalendarHeader(currentMonth, currentYear);
            generateCalendarDays(currentMonth, currentYear);
        });
    }

    function updateCalendarHeader(month, year) {
        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"];
        document.querySelector('.calendar-header h4').textContent = `${monthNames[month]} ${year}`;
    }

    function generateCalendarDays(month, year) {
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const calendarDays = document.querySelector('.calendar-days');
        calendarDays.innerHTML = '';

        // Previous month days
        for (let i = 0; i < firstDay; i++) {
            calendarDays.innerHTML += `<div class="p-2 border-b border-r text-gray-400"></div>`;
        }

        // Current month days
        for (let day = 1; day <= daysInMonth; day++) {
            const isToday = day === new Date().getDate() &&
                month === new Date().getMonth() &&
                year === new Date().getFullYear();

            calendarDays.innerHTML += `
                <div class="relative p-2 border-b border-r hover:bg-gray-50 cursor-pointer" 
                     onclick="editDayPrice(${year}, ${month}, ${day})">
                    <div class="text-sm ${isToday ? 'font-bold' : ''}">${day}</div>
                    <div class="text-xs text-gray-600">₱2,341</div>
                </div>
            `;
        }
    }

    function editDayPrice(year, month, day) {
        const date = new Date(year, month, day);
        const formattedDate = date.toLocaleDateString();
        const newPrice = prompt(`Enter new price for ${formattedDate}:`);
        if (newPrice && !isNaN(newPrice)) {
            console.log(`Updated price for ${formattedDate} to ₱${newPrice}`);
        }
    }

    document.addEventListener('DOMContentLoaded', initializeCalendar);
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
            } else {
                currentIndex = reviews.length - 1;
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