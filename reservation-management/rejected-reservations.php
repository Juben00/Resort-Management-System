<?php
require_once '../classes/venue.class.php';
require_once '../classes/account.class.php';
$venueObj = new Venue();
$accountObj = new Account();

// Get all bookings
$allReservations = $venueObj->getBookings();

// Filter for status_id = 4 (Completed)
$Reservations = array_filter($allReservations, function ($booking) {
    return $booking['booking_status_id'] == 5;
});

function formatDate($date)
{
    return date('F d, Y', strtotime($date));
}
?>

<!-- Search and Filter Section -->
<section class="bg-white rounded-lg shadow-md p-4 mb-8">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Search and Filter Reservations</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
            <div class="flex gap-2">
                <input type="date" id="startDate" class="border rounded p-2 w-full" placeholder="Start Date">
                <input type="date" id="endDate" class="border rounded p-2 w-full" placeholder="End Date">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Venue</label>
            <input type="text" id="venueFilter" class="border rounded p-2 w-full" placeholder="Filter by venue name">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
            <input type="text" id="customerFilter" class="border rounded p-2 w-full"
                placeholder="Filter by customer name">
        </div>
    </div>
    <div class="flex items-center gap-2">
        <button id="applyFilters" class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 transition-colors">
            Apply Filters
        </button>
        <button id="clearFilters"
            class="border border-gray-300 bg-white text-gray-700 py-2 px-4 rounded hover:bg-gray-100 transition-colors">
            Clear
        </button>
    </div>
</section>

<!-- Reservations Table -->
<section class="bg-white rounded-lg shadow-md p-4 mb-8">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Rejected Reservations</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 text-left">Book Date</th>
                    <th class="py-2 px-4 text-left">Start Date</th>
                    <th class="py-2 px-4 text-left">End Date</th>
                    <th class="py-2 px-4 text-left">Number of Days</th>
                    <th class="py-2 px-4 text-left">Name</th>
                    <th class="py-2 px-4 text-left">Contact</th>
                    <th class="py-2 px-4 text-left">Email</th>
                    <th class="py-2 px-4 text-left">Venue</th>
                    <th class="py-2 px-4 text-left">Venue Location</th>
                    <th class="py-2 px-4 text-left">Capacity</th>
                    <th class="py-2 px-4 text-left">Participants</th>
                    <th class="py-2 px-4 text-left">Original Price</th>
                    <th class="py-2 px-4 text-left">Discount Code</th>
                    <th class="py-2 px-4 text-left">Discount Value</th>
                    <th class="py-2 px-4 text-left">Grand Total</th>
                    <th class="py-2 px-4 text-left">Payment Method</th>
                    <th class="py-2 px-4 text-left">Payment Reference</th>
                    <th class="py-2 px-4 text-left">Status</th>
                </tr>
            </thead>
            <tbody id="reservationsTable">
                <?php
                if (!empty($Reservations)) {
                    foreach ($Reservations as $reservation) {
                        ?>
                        <tr>
                            <td class="py-2 px-4"><?php echo formatDate($reservation['booking_created_at']); ?></td>
                            <td class="py-2 px-4"><?php echo formatDate($reservation['booking_start_date']); ?></td>
                            <td class="py-2 px-4"><?php echo formatDate($reservation['booking_end_date']); ?></td>
                            <td class="py-2 px-4"><?php echo $reservation['booking_duration']; ?></td>
                            <td class="py-2 px-4"><?php echo $reservation['guest_name']; ?></td>
                            <td class="py-2 px-4"><?php echo $reservation['guest_contact_number']; ?></td>
                            <td class="py-2 px-4"><?php echo $reservation['guest_email']; ?></td>
                            <td class="py-2 px-4"><?php echo $reservation['venue_name']; ?></td>
                            <td class="py-2 px-4"><?php echo $reservation['venue_location']; ?></td>
                            <td class="py-2 px-4"><?php echo $reservation['venue_capacity']; ?></td>
                            <td class="py-2 px-4"><?php echo $reservation['booking_participants']; ?></td>
                            <td class="py-2 px-4">₱<?php echo number_format($reservation['booking_original_price'], 2); ?></td>
                            <td class="py-2 px-4"><?php echo $reservation['booking_discount'] ?: 'N/A'; ?></td>
                            <td class="py-2 px-4">₱<?php echo number_format($reservation['discount_value'], 2); ?></td>
                            <td class="py-2 px-4">₱<?php echo number_format($reservation['booking_grand_total'], 2); ?></td>
                            <td class="py-2 px-4"><?php echo $reservation['booking_payment_method']; ?></td>
                            <td class="py-2 px-4"><?php echo $reservation['booking_payment_reference']; ?></td>
                            <td class="py-2 px-4">
                                <span class="bg-red-200 text-red-800 rounded-full px-2">Rejected</span>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="18" class="py-4 text-center">No rejected reservations found</td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Keep the same script as cancelled-reservations.php -->
<script>
    // Same script as cancelled-reservations.php
    $(document).ready(function () {
        // ... (same filter functionality)
    });
</script>