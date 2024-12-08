<?php
require_once '../classes/venue.class.php';
session_start();
$venueObj = new Venue();

$pendingBooking = $venueObj->getAllBookings($_SESSION['user']['id'], 1);
$currentBooking = $venueObj->getAllBookings($_SESSION['user']['id'], 2);
$cancelledBooking = $venueObj->getAllBookings($_SESSION['user']['id'], 3);
$previousBooking = $venueObj->getAllBookings($_SESSION['user']['id'], 4);


?>

<main class="max-w-7xl mx-auto py-12 pt-20 sm:px-6 lg:px-8 bg-gray-50">
    <div class="px-4 sm:px-0">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Your Rent History</h1>

        <!-- Tabs -->
        <div class="mb-8">
            <nav class="flex space-x-4 bg-white p-2 rounded-lg shadow-sm" aria-label="Tabs">
                <button onclick="showTab('pending')"5
                    class="tab-btn text-gray-600 hover:text-gray-800 px-3 py-2 font-medium text-sm rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Pending Rentals
                </button>
                <button onclick="showTab('current')"
                    class="tab-btn text-gray-600 hover:text-gray-800 px-3 py-2 font-medium text-sm rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Current Rental
                </button>
                <button onclick="showTab('previous')"
                    class="tab-btn text-gray-600 hover:text-gray-800 px-3 py-2 font-medium text-sm rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Previous Rentals
                </button>
                <button onclick="showTab('cancelled')"
                    class="tab-btn text-gray-600 hover:text-gray-800 px-3 py-2 font-medium text-sm rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancelled Rentals
                </button>
            </nav>
        </div>

        <!-- Pending Rental Tab -->
        <div id="pending-tab" class="tab-content">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <?php if (empty($pendingBooking)): ?>
                        <p class="p-8 text-center text-gray-500">You do not have any pending bookings.</p>
                <?php else: ?>
                        <?php foreach ($pendingBooking as $booking):
                            $timezone = new DateTimeZone('Asia/Manila');
                            $currentDateTime = new DateTime('now', $timezone);
                            $bookingStartDate = new DateTime($booking['booking_start_date'], $timezone);
                            ?>
                                <div class="p-6 border-b border-gray-200 last:border-b-0">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                                        <div class="flex items-center gap-2 mt-2 md:mt-0">
                                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                                <?php
                                                switch ($booking['booking_status_id']) {
                                                    case '1':
                                                        echo 'Pending';
                                                        break;
                                                    case '2':
                                                        echo 'Approved';
                                                        break;
                                                    case '3':
                                                        echo 'Cancelled';
                                                        break;
                                                    case '4':
                                                        echo 'Completed';
                                                        break;
                                                    default:
                                                        echo 'Unknown';
                                                        break;
                                                }
                                                ?>
                                            </span>
                                            <?php if ($bookingStartDate > $currentDateTime): ?>
                                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Upcoming Booking</span>
                                            <?php else: ?>
                                                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">Active Booking</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="flex flex-col md:flex-row gap-6">
                                        <?php
                                        $imageUrls = !empty($booking['image_urls']) ? explode(',', $booking['image_urls']) : [];
                                        if (!empty($imageUrls)):
                                            ?>
                                                <img src="./<?= htmlspecialchars($imageUrls[0]) ?>" alt="<?= htmlspecialchars($booking['venue_name']) ?>" class="w-full md:w-48 h-48 object-cover rounded-lg shadow-md">
                                        <?php endif; ?>
                                        <div class="flex-1">
                                            <h3 class="text-lg font-medium text-gray-900"><?php echo htmlspecialchars($booking['venue_name']) ?></h3>
                                            <p class="text-gray-600 mt-1"><?php echo htmlspecialchars($booking['venue_location']) ?></p>
                                            <p class="text-gray-700 font-medium mt-2">
                                                ₱<?php echo number_format(htmlspecialchars($booking['booking_grand_total'] ? $booking['booking_grand_total'] : 0.0)) ?>
                                                for <?php echo number_format(htmlspecialchars($booking['booking_duration'] ? $booking['booking_duration'] : 0.0)) ?> days
                                            </p>
                                            <p class="text-gray-600 mt-1">
                                                <?php
                                                $startDate = new DateTime($booking['booking_start_date']);
                                                $endDate = new DateTime($booking['booking_end_date']);
                                                echo $startDate->format('F j, Y') . ' to ' . $endDate->format('F j, Y');
                                                ?>
                                            </p>
                                            <div class="mt-4 flex flex-wrap gap-3">
                                                <button onclick="showDetails(<?php echo htmlspecialchars(json_encode($booking)); ?>)"
                                                    class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                                    View Details
                                                </button>
                                                <?php if ($booking['booking_status_id'] == '2' || $booking['booking_status_id'] == '4'): ?>
                                                        <button onclick="printReceipt(<?php echo htmlspecialchars(json_encode([
                                                            'booking_id' => $booking['booking_id'],
                                                            'venue_name' => $booking['venue_name'],
                                                            'booking_start_date' => $booking['booking_start_date'],
                                                            'booking_end_date' => $booking['booking_end_date'],
                                                            'booking_duration' => $booking['booking_duration'],
                                                            'booking_grand_total' => $booking['booking_grand_total'],
                                                            'booking_payment_method' => $booking['booking_payment_method'],
                                                            'booking_payment_reference' => $booking['booking_payment_reference'],
                                                            'booking_service_fee' => $booking['booking_service_fee'],
                                                            'venue_location' => $booking['venue_location']
                                                        ])); ?>" 
                                                        type="button" 
                                                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                                            <i class="fas fa-print mr-2"></i>Print Receipt
                                                        </button>
                                                        <button onclick="downloadReceipt(<?php echo htmlspecialchars(json_encode([
                                                            'booking_id' => $booking['booking_id'],
                                                            'venue_name' => $booking['venue_name'],
                                                            'booking_start_date' => $booking['booking_start_date'],
                                                            'booking_end_date' => $booking['booking_end_date'],
                                                            'booking_duration' => $booking['booking_duration'],
                                                            'booking_grand_total' => $booking['booking_grand_total'],
                                                            'booking_payment_method' => $booking['booking_payment_method'],
                                                            'booking_payment_reference' => $booking['booking_payment_reference'],
                                                            'booking_service_fee' => $booking['booking_service_fee'],
                                                            'venue_location' => $booking['venue_location']
                                                        ])); ?>" 
                                                        type="button" 
                                                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                                            <i class="fas fa-download mr-2"></i>Download Receipt
                                                        </button>
                                                <?php endif; ?>
                                                <?php if ($bookingStartDate > $currentDateTime): ?>
                                                        <button onclick="cancelBooking(<?php echo htmlspecialchars($booking['booking_id']); ?>)"
                                                            class="px-4 py-2 border border-red-500 text-red-500 rounded-lg hover:bg-red-50 transition-colors duration-200">
                                                            Cancel Booking
                                                        </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Current Rental Tab -->
        <div id="current-tab" class="tab-content hidden">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <?php if (empty($currentBooking)): ?>
                        <p class="p-8 text-center text-gray-500">You do not have any current bookings.</p>
                <?php else: ?>
                        <?php foreach ($currentBooking as $booking):
                            $timezone = new DateTimeZone('Asia/Manila');
                            $currentDateTime = new DateTime('now', $timezone);
                            $bookingStartDate = new DateTime($booking['booking_start_date'], $timezone);
                            ?>
                                <div class="p-6 border-b border-gray-200 last:border-b-0">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                                        <div class="flex items-center gap-2 mt-2 md:mt-0">
                                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                                <?php
                                                switch ($booking['booking_status_id']) {
                                                    case '1':
                                                        echo 'Pending';
                                                        break;
                                                    case '2':
                                                        echo 'Approved';
                                                        break;
                                                    case '3':
                                                        echo 'Cancelled';
                                                        break;
                                                    case '4':
                                                        echo 'Completed';
                                                        break;
                                                    default:
                                                        echo 'Unknown';
                                                        break;
                                                }
                                                ?>
                                            </span>
                                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">Active Booking</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col md:flex-row gap-6">
                                        <?php
                                        $imageUrls = !empty($booking['image_urls']) ? explode(',', $booking['image_urls']) : [];
                                        if (!empty($imageUrls)):
                                            ?>
                                                <img src="./<?= htmlspecialchars($imageUrls[0]) ?>" alt="<?= htmlspecialchars($booking['venue_name']) ?>" class="w-full md:w-48 h-48 object-cover rounded-lg shadow-md">
                                        <?php endif; ?>
                                        <div class="flex-1">
                                            <h3 class="text-lg font-medium text-gray-900"><?php echo htmlspecialchars($booking['venue_name']) ?></h3>
                                            <p class="text-gray-600 mt-1"><?php echo htmlspecialchars($booking['venue_location']) ?></p>
                                            <p class="text-gray-700 font-medium mt-2">
                                                ₱<?php echo number_format(htmlspecialchars($booking['booking_grand_total'] ? $booking['booking_grand_total'] : 0.0)) ?>
                                                for <?php echo number_format(htmlspecialchars($booking['booking_duration'] ? $booking['booking_duration'] : 0.0)) ?> days
                                            </p>
                                            <p class="text-gray-600 mt-1">
                                                <?php
                                                $startDate = new DateTime($booking['booking_start_date']);
                                                $endDate = new DateTime($booking['booking_end_date']);
                                                echo $startDate->format('F j, Y') . ' to ' . $endDate->format('F j, Y');
                                                ?>
                                            </p>
                                            <div class="mt-4 flex flex-wrap gap-3">
                                                <button onclick="showDetails(<?php echo htmlspecialchars(json_encode($booking)); ?>)"
                                                    class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                                    View Details
                                                </button>
                                                <?php if ($booking['booking_status_id'] == '2' || $booking['booking_status_id'] == '4'): ?>
                                                        <button onclick="printReceipt(<?php echo htmlspecialchars(json_encode([
                                                            'booking_id' => $booking['booking_id'],
                                                            'venue_name' => $booking['venue_name'],
                                                            'booking_start_date' => $booking['booking_start_date'],
                                                            'booking_end_date' => $booking['booking_end_date'],
                                                            'booking_duration' => $booking['booking_duration'],
                                                            'booking_grand_total' => $booking['booking_grand_total'],
                                                            'booking_payment_method' => $booking['booking_payment_method'],
                                                            'booking_payment_reference' => $booking['booking_payment_reference'],
                                                            'booking_service_fee' => $booking['booking_service_fee'],
                                                            'venue_location' => $booking['venue_location']
                                                        ])); ?>" 
                                                        type="button" 
                                                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                                            <i class="fas fa-print mr-2"></i>Print Receipt
                                                        </button>
                                                        <button onclick="downloadReceipt(<?php echo htmlspecialchars(json_encode([
                                                            'booking_id' => $booking['booking_id'],
                                                            'venue_name' => $booking['venue_name'],
                                                            'booking_start_date' => $booking['booking_start_date'],
                                                            'booking_end_date' => $booking['booking_end_date'],
                                                            'booking_duration' => $booking['booking_duration'],
                                                            'booking_grand_total' => $booking['booking_grand_total'],
                                                            'booking_payment_method' => $booking['booking_payment_method'],
                                                            'booking_payment_reference' => $booking['booking_payment_reference'],
                                                            'booking_service_fee' => $booking['booking_service_fee'],
                                                            'venue_location' => $booking['venue_location']
                                                        ])); ?>" 
                                                        type="button" 
                                                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                                            <i class="fas fa-download mr-2"></i>Download Receipt
                                                        </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Previous Rentals Tab -->
        <div id="previous-tab" class="tab-content hidden">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <?php if (empty($previousBooking)): ?>
                        <p class="p-8 text-center text-gray-500">You do not have any previous bookings.</p>
                <?php else: ?>
                        <?php foreach ($previousBooking as $booking):
                            $timezone = new DateTimeZone('Asia/Manila');
                            $currentDateTime = new DateTime('now', $timezone);
                            $bookingStartDate = new DateTime($booking['booking_start_date'], $timezone);
                            ?>
                                <div class="p-6 border-b border-gray-200 last:border-b-0">
                                    <div class="flex flex-col md:flex-row gap-6">
                                        <?php
                                        $imageUrls = !empty($booking['image_urls']) ? explode(',', $booking['image_urls']) : [];
                                        if (!empty($imageUrls)):
                                            ?>
                                                <img src="./<?= htmlspecialchars($imageUrls[0]) ?>" alt="<?= htmlspecialchars($booking['venue_name']) ?>" class="w-full md:w-48 h-48 object-cover rounded-lg shadow-md">
                                        <?php endif; ?>
                                        <div class="flex-1">
                                            <h3 class="text-lg font-medium text-gray-900"><?php echo htmlspecialchars($booking['venue_name']) ?></h3>
                                            <p class="text-gray-600 mt-1">
                                                <?php
                                                $startDate = new DateTime($booking['booking_start_date']);
                                                $endDate = new DateTime($booking['booking_end_date']);
                                                echo $startDate->format('F j, Y') . ' to ' . $endDate->format('F j, Y');
                                                ?>
                                            </p>
                                            <p class="text-gray-700 font-medium mt-2">
                                                ₱<?php echo number_format(htmlspecialchars($booking['booking_grand_total'] ? $booking['booking_grand_total'] : 0.0)) ?>
                                                for <?php echo number_format(htmlspecialchars($booking['booking_duration'] ? $booking['booking_duration'] : 0.0)) ?> days
                                            </p>
                                            <div class="mt-4">
                                                <form id="reviewForm" class="space-y-4">
                                                    <input type="hidden" name="venueId" value="<?php echo htmlspecialchars($booking['venue_id']) ?>">
                                                    <div class="flex items-center space-x-1">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <label onclick="rate(<?php echo $i; ?>)" for="star<?php echo $i; ?>" class="text-2xl text-gray-300 hover:text-yellow-400 star cursor-pointer" data-rating="<?php echo $i; ?>">
                                                                    <input type="radio" name="ratings" value="<?php echo $i; ?>" class="hidden" id="star<?php echo $i; ?>">★
                                                                </label>
                                                        <?php endfor; ?>
                                                        <span class="ml-2 text-sm text-gray-600">Rate your stay</span>
                                                    </div>
                                                    <textarea id="review-text" name="review-text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800 focus:border-transparent" rows="3" placeholder="Share your experience (optional)"></textarea>
                                                    <div class="flex flex-wrap gap-3">
                                                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                                            Submit Review
                                                        </button>
                                                        <button onclick="showDetails(<?php echo htmlspecialchars(json_encode($booking)); ?>)" type="button" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                                            View Details
                                                        </button>
                                                        <button id="bookAgainBtn" data-bvid="<?php echo htmlspecialchars($booking['venue_id']); ?>" type="button" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                                            Book Again
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Cancelled Rentals Tab -->
        <div id="cancelled-tab" class="tab-content hidden">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <?php if (empty($cancelledBooking)): ?>
                        <p class="p-8 text-center text-gray-500">You do not have any cancelled bookings.</p>
                <?php else: ?>
                        <?php foreach ($cancelledBooking as $booking):
                            $timezone = new DateTimeZone('Asia/Manila');
                            $currentDateTime = new DateTime('now', $timezone);
                            $bookingStartDate = new DateTime($booking['booking_start_date'], $timezone);
                            ?>
                                <div class="p-6 border-b border-gray-200 last:border-b-0">
                                    <div class="flex flex-col md:flex-row gap-6">
                                        <?php
                                        $imageUrls = !empty($booking['image_urls']) ? explode(',', $booking['image_urls']) : [];
                                        if (!empty($imageUrls)):
                                            ?>
                                                <img src="./<?= htmlspecialchars($imageUrls[0]) ?>" alt="<?= htmlspecialchars($booking['venue_name']) ?>" class="w-full md:w-48 h-48 object-cover rounded-lg shadow-md">
                                        <?php endif; ?>
                                        <div class="flex-1">
                                            <h3 class="text-lg font-medium text-gray-900"><?php echo htmlspecialchars($booking['venue_name']) ?></h3>
                                            <p class="text-gray-600 mt-1">
                                                <?php
                                                $startDate = new DateTime($booking['booking_start_date']);
                                                $endDate = new DateTime($booking['booking_end_date']);
                                                echo $startDate->format('F j, Y') . ' to ' . $endDate->format('F j, Y');
                                                ?>
                                            </p>
                                            <p class="text-gray-700 font-medium mt-2">
                                                ₱<?php echo number_format(htmlspecialchars($booking['booking_grand_total'] ? $booking['booking_grand_total'] : 0.0)) ?>
                                                for <?php echo number_format(htmlspecialchars($booking['booking_duration'] ? $booking['booking_duration'] : 0.0)) ?> days
                                            </p>
                                            <p class="text-gray-600 mt-2">
                                                <span class="font-medium">Reason:</span> <?php echo htmlspecialchars($booking['booking_cancellation_reason']) ?>
                                            </p>
                                            <div class="mt-4">
                                                <button id="bookAgainBtn" data-bvid="<?php echo htmlspecialchars($booking['venue_id']); ?>" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                                    Book Again
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="details-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title"></h3>
                <div class="mt-2 px-7 py-3" id="modal-content">
                    <!-- Content will be dynamically populated here -->
                </div>
                <div class="items-center px-4 py-3">
                    <button id="closeModalBtn" class="px-4 py-2 bg-gray-800 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancellation Modal -->
    <div id="cancellation-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Cancel Booking</h3>
                <div class="mt-2 px-7 py-3">
                    <form id="cancellation-form">
                        <input type="hidden" id="cancellation-booking-id" name="booking-id">
                        <div class="mb-4">
                            <label for="cancellation-reason" class="block text-gray-700 text-sm font-bold mb-2">Reason for Cancellation</label>
                            <textarea id="cancellation-reason" name="cancellation-reason" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                        </div>
                        <div class="flex items-center justify-between">
                            <button type="button" onclick="closeCancellationModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Close
                            </button>
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Confirm Cancellation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    function showTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });
        document.getElementById(tabName + '-tab').classList.remove('hidden');

        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-black', 'text-gray-900');
            btn.classList.add('border-transparent', 'text-gray-500');
        });

        event.currentTarget.classList.remove('border-transparent', 'text-gray-500');
        event.currentTarget.classList.add('border-black', 'text-gray-900');
    }

    // Set default tab to 'pending'
    document.addEventListener('DOMContentLoaded', function () {
        showTab('pending');
    });

    function showDetails(booking) {
        const modal = document.getElementById('details-modal');
        const bookAgainContainer = document.getElementById('book-again-container');

        // Show modal with fade-in effect
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            modal.classList.add('opacity-100');
            modal.querySelector('.relative').classList.add('scale-100');
            modal.querySelector('.relative').classList.remove('scale-95');
        });

        // Set main title
        document.getElementById('modal-title').textContent = booking.venue_name;

        // Setup main image and gallery
        const mainImage = document.getElementById('modal-main-image');
        mainImage.src = './' + booking.image_urls.split(',')[0];

        // Setup image gallery with horizontal thumbnails
        const imageGallery = document.getElementById('image-gallery');
        const imageUrls = booking.image_urls.split(',');
        imageGallery.innerHTML = imageUrls.map(url => `
            <div class="flex-shrink-0 h-16 w-16 rounded-lg overflow-hidden">
                <img src="./${url}" 
                    alt="Venue Image" 
                    class="w-full h-full object-cover cursor-pointer hover:opacity-75 transition-opacity duration-200" 
                    onclick="changeMainImage(this.src)">
            </div>
        `).join('');

        // Set booking status and type
        const bookingStatus = document.getElementById('booking-status');
        const statusText = getBookingStatusText(booking.booking_status_id);
        bookingStatus.textContent = statusText;
        bookingStatus.className = `px-3 py-1 rounded-full text-sm font-medium ${getStatusColor(booking.booking_status_id)}`;

        // Set price details
        document.getElementById('price-per-night').textContent = `₱${numberWithCommas(booking.booking_grand_total)}`;
        document.getElementById('booking-duration').textContent = `${booking.booking_duration} days`;
        document.getElementById('cleaning-fee').textContent = `Cleaning fee: ₱500`;

        // Set location details
        const locationDetails = document.getElementById('location-details');
        locationDetails.innerHTML = `
            <p>${booking.venue_location}</p>
            <p>Governor Camins Avenue, Zone II</p>
            <p>Baliwasan, Zamboanga City</p>
            <p>Zamboanga Peninsula, 7000</p>
        `;

        // Set capacity and amenities (using the original amenities)
        document.getElementById('venue-capacity').textContent = `${booking.venue_capacity || 3} guests`;
        const amenitiesList = document.getElementById('amenities-list');
        amenitiesList.innerHTML = `
            <li>• Pool</li>
            <li>• WiFi</li>
            <li>• Air-conditioned Room</li>
            <li>• Smart TV</li>
        `;

        // Set contact details (using the original contact info)
        const contactDetails = document.getElementById('contact-details');
        contactDetails.innerHTML = `
            <p>Email: joevinansoc870@gmail.com</p>
            <p>Phone: 09053258512</p>
        `;

        // Toggle book again button
        bookAgainContainer.classList.toggle('hidden', booking.booking_status_id === '2');
    }

    // Helper functions
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function getBookingStatusText(statusId) {
        const statuses = {
            '1': 'Pending',
            '2': 'Approved',
            '3': 'Cancelled',
            '4': 'Completed'
        };
        return statuses[statusId] || 'Unknown';
    }

    function getStatusColor(statusId) {
        const colors = {
            '1': 'bg-yellow-100 text-yellow-800',
            '2': 'bg-green-100 text-green-800',
            '3': 'bg-red-100 text-red-800',
            '4': 'bg-blue-100 text-blue-800'
        };
        return colors[statusId] || 'bg-gray-100 text-gray-800';
    }

    // Update close modal function with smooth transition
    function closeModal() {
        const modal = document.getElementById('details-modal');
        modal.classList.remove('opacity-100');
        modal.querySelector('.relative').classList.remove('scale-100');
        modal.querySelector('.relative').classList.add('scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function changeMainImage(src) {
        const mainImage = document.getElementById('modal-main-image');
        mainImage.style.opacity = '0';
        setTimeout(() => {
            mainImage.src = src;
            mainImage.style.opacity = '1';
        }, 200);
    }

    function cancelBooking(bookingId) {
        document.getElementById('cancellation-booking-id').value = bookingId;
        showCancellationModal();
    }

    function showCancellationModal() {
        const modal = document.getElementById('cancellation-modal');
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            modal.classList.add('opacity-100');
            modal.querySelector('.relative').classList.add('scale-100');
            modal.querySelector('.relative').classList.remove('scale-95');
        });
    }

    function closeCancellationModal() {
        const modal = document.getElementById('cancellation-modal');
        modal.classList.remove('opacity-100');
        modal.querySelector('.relative').classList.remove('scale-100');
        modal.querySelector('.relative').classList.add('scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function rate(rating) {
        currentRating = rating;
        const stars = document.querySelectorAll('.star');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }

    window.onclick = function (event) {
        const modal = document.getElementById('details-modal');
        if (event.target === modal) {
            closeModal();
        }
    }

    function printReceipt(bookingData) {
        const receiptWindow = window.open('', '_blank');
        
        // Add error handling for the window opening
        if (!receiptWindow) {
            alert('Please allow popups to print the receipt');
            return;
        }
        
        const receiptHTML = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Booking Receipt</title>
                <style>
                    @page {
                        size: A4;
                        margin: 1.5cm;
                    }
                    body {
                        font-family: Arial, sans-serif;
                        max-width: 800px;
                        margin: 0 auto;
                        padding: 20px;
                        color: #333;
                        line-height: 1.6;
                    }
                    .logo {
                        width: 150px;
                        height: auto;
                        margin-bottom: 10px;
                        object-fit: contain;
                    }
                    .header {
                        text-align: center;
                        margin-bottom: 40px;
                        padding-bottom: 20px;
                        border-bottom: 2px solid #e5e5e5;
                    }
                    .header h1 {
                        color: #1a1a1a;
                        margin: 10px 0;
                        font-size: 28px;
                    }
                    .header p {
                        color: #666;
                        font-size: 14px;
                    }
                    .receipt-details {
                        margin-bottom: 30px;
                        display: grid;
                        grid-template-columns: repeat(2, 1fr);
                        gap: 20px;
                    }
                    .receipt-details .section {
                        margin-bottom: 20px;
                    }
                    .receipt-details .section-title {
                        font-weight: bold;
                        color: #1a1a1a;
                        margin-bottom: 10px;
                        font-size: 16px;
                    }
                    .receipt-details .info {
                        background: #f8f8f8;
                        padding: 15px;
                        border-radius: 8px;
                    }
                    .receipt-details .info div {
                        margin-bottom: 8px;
                        font-size: 14px;
                    }
                    .receipt-details .label {
                        color: #666;
                        font-weight: 500;
                    }
                    .total {
                        margin-top: 30px;
                        padding: 20px;
                        background: #f8f8f8;
                        border-radius: 8px;
                    }
                    .total-row {
                        display: flex;
                        justify-content: space-between;
                        margin-bottom: 10px;
                        font-size: 14px;
                    }
                    .grand-total {
                        margin-top: 15px;
                        padding-top: 15px;
                        border-top: 2px solid #e5e5e5;
                        font-weight: bold;
                        font-size: 16px;
                    }
                    .footer {
                        margin-top: 40px;
                        text-align: center;
                        color: #666;
                        font-size: 12px;
                        padding-top: 20px;
                        border-top: 1px solid #e5e5e5;
                    }
                    @media print {
                        .no-print {
                            display: none;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <img src="./images/white_ico.png" alt="Icoco Logo" class="logo" 
                         onerror="this.style.display='none'">
                    <h1>Booking Receipt</h1>
                    <p>Thank you for choosing Icoco!</p>
                </div>
                
                <div class="receipt-details">
                    <div class="section">
                        <div class="section-title">Booking Information</div>
                        <div class="info">
                            <div><span class="label">Booking ID:</span> #${bookingData.booking_id}</div>
                            <div><span class="label">Check-in:</span> ${new Date(bookingData.booking_start_date).toLocaleDateString()}</div>
                            <div><span class="label">Check-out:</span> ${new Date(bookingData.booking_end_date).toLocaleDateString()}</div>
                            <div><span class="label">Duration:</span> ${bookingData.booking_duration} days</div>
                        </div>
                    </div>

                    <div class="section">
                        <div class="section-title">Venue Details</div>
                        <div class="info">
                            <div><span class="label">Venue Name:</span> ${bookingData.venue_name}</div>
                            <div><span class="label">Location:</span> ${bookingData.venue_location}</div>
                        </div>
                    </div>

                    <div class="section">
                        <div class="section-title">Payment Details</div>
                        <div class="info">
                            <div><span class="label">Payment Method:</span> ${bookingData.booking_payment_method}</div>
                            <div><span class="label">Reference Number:</span> ${bookingData.booking_payment_reference}</div>
                        </div>
                    </div>
                </div>

                <div class="total">
                    <div class="total-row">
                        <span>Service Fee</span>
                        <span>₱${parseFloat(bookingData.booking_service_fee).toFixed(2)}</span>
                    </div>
                    <div class="total-row grand-total">
                        <span>Total Amount</span>
                        <span>₱${parseFloat(bookingData.booking_grand_total).toFixed(2)}</span>
                    </div>
                </div>

                <div class="footer">
                    <p>This is an electronically generated receipt. For questions or concerns, please contact our support team.</p>
                    <p>© ${new Date().getFullYear()} Icoco. All rights reserved.</p>
                </div>
            </body>
            </html>
        `;
        
        receiptWindow.document.write(receiptHTML);
        receiptWindow.document.close();

        // Add error handling for printing
        receiptWindow.onload = function() {
            try {
                receiptWindow.print();
                receiptWindow.onafterprint = function() {
                    receiptWindow.close();
                };
            } catch (error) {
                console.error('Printing failed:', error);
                alert('There was an error printing the receipt. Please try again.');
                receiptWindow.close();
            }
        };

        // Add error handler for load failures
        receiptWindow.onerror = function() {
            alert('There was an error generating the receipt. Please try again.');
            receiptWindow.close();
        };
    }

    function downloadReceipt(bookingData) {
        // Create the receipt HTML (reuse the same template as printReceipt)
        const receiptHTML = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Booking Receipt</title>
                <style>
                    @page {
                        size: A4;
                        margin: 1.5cm;
                    }
                    body {
                        font-family: Arial, sans-serif;
                        max-width: 800px;
                        margin: 0 auto;
                        padding: 20px;
                        color: #333;
                        line-height: 1.6;
                    }
                    .logo {
                        width: 150px;
                        height: auto;
                        margin-bottom: 10px;
                        object-fit: contain;
                    }
                    .header {
                        text-align: center;
                        margin-bottom: 40px;
                        padding-bottom: 20px;
                        border-bottom: 2px solid #e5e5e5;
                    }
                    .header h1 {
                        color: #1a1a1a;
                        margin: 10px 0;
                        font-size: 28px;
                    }
                    .header p {
                        color: #666;
                        font-size: 14px;
                    }
                    .receipt-details {
                        margin-bottom: 30px;
                        display: grid;
                        grid-template-columns: repeat(2, 1fr);
                        gap: 20px;
                    }
                    .receipt-details .section {
                        margin-bottom: 20px;
                    }
                    .receipt-details .section-title {
                        font-weight: bold;
                        color: #1a1a1a;
                        margin-bottom: 10px;
                        font-size: 16px;
                    }
                    .receipt-details .info {
                        background: #f8f8f8;
                        padding: 15px;
                        border-radius: 8px;
                    }
                    .receipt-details .info div {
                        margin-bottom: 8px;
                        font-size: 14px;
                    }
                    .receipt-details .label {
                        color: #666;
                        font-weight: 500;
                    }
                    .total {
                        margin-top: 30px;
                        padding: 20px;
                        background: #f8f8f8;
                        border-radius: 8px;
                    }
                    .total-row {
                        display: flex;
                        justify-content: space-between;
                        margin-bottom: 10px;
                        font-size: 14px;
                    }
                    .grand-total {
                        margin-top: 15px;
                        padding-top: 15px;
                        border-top: 2px solid #e5e5e5;
                        font-weight: bold;
                        font-size: 16px;
                    }
                    .footer {
                        margin-top: 40px;
                        text-align: center;
                        color: #666;
                        font-size: 12px;
                        padding-top: 20px;
                        border-top: 1px solid #e5e5e5;
                    }
                    @media print {
                        .no-print {
                            display: none;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <img src="./images/white_ico.png" alt="Icoco Logo" class="logo" 
                         onerror="this.style.display='none'">
                    <h1>Booking Receipt</h1>
                    <p>Thank you for choosing Icoco!</p>
                </div>
                
                <div class="receipt-details">
                    <div class="section">
                        <div class="section-title">Booking Information</div>
                        <div class="info">
                            <div><span class="label">Booking ID:</span> #${bookingData.booking_id}</div>
                            <div><span class="label">Check-in:</span> ${new Date(bookingData.booking_start_date).toLocaleDateString()}</div>
                            <div><span class="label">Check-out:</span> ${new Date(bookingData.booking_end_date).toLocaleDateString()}</div>
                            <div><span class="label">Duration:</span> ${bookingData.booking_duration} days</div>
                        </div>
                    </div>

                    <div class="section">
                        <div class="section-title">Venue Details</div>
                        <div class="info">
                            <div><span class="label">Venue Name:</span> ${bookingData.venue_name}</div>
                            <div><span class="label">Location:</span> ${bookingData.venue_location}</div>
                        </div>
                    </div>

                    <div class="section">
                        <div class="section-title">Payment Details</div>
                        <div class="info">
                            <div><span class="label">Payment Method:</span> ${bookingData.booking_payment_method}</div>
                            <div><span class="label">Reference Number:</span> ${bookingData.booking_payment_reference}</div>
                        </div>
                    </div>
                </div>

                <div class="total">
                    <div class="total-row">
                        <span>Service Fee</span>
                        <span>₱${parseFloat(bookingData.booking_service_fee).toFixed(2)}</span>
                    </div>
                    <div class="total-row grand-total">
                        <span>Total Amount</span>
                        <span>₱${parseFloat(bookingData.booking_grand_total).toFixed(2)}</span>
                    </div>
                </div>

                <div class="footer">
                    <p>This is an electronically generated receipt. For questions or concerns, please contact our support team.</p>
                    <p>© ${new Date().getFullYear()} Icoco. All rights reserved.</p>
                </div>
            </body>
            </html>
        `;

        // Create a Blob from the HTML content
        const blob = new Blob([receiptHTML], { type: 'text/html' });
        
        // Create a temporary link element
        const downloadLink = document.createElement('a');
        downloadLink.href = URL.createObjectURL(blob);
        downloadLink.download = `receipt-${bookingData.booking_id}.html`;
        
        // Append link to body, click it, and remove it
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
        
        // Clean up the URL object
        URL.revokeObjectURL(downloadLink.href);
    }
</script>

<style>
    #modal-main-image {
        transition: opacity 0.2s ease-in-out;
    }

    #image-gallery {
        scrollbar-width: thin;
        scrollbar-color: rgba(0, 0, 0, 0.2) transparent;
    }

    #image-gallery::-webkit-scrollbar {
        height: 4px;
    }

    #image-gallery::-webkit-scrollbar-track {
        background: transparent;
    }

    #image-gallery::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 2px;
    }

    #image-gallery img {
        aspect-ratio: 1/1;
    }
</style>