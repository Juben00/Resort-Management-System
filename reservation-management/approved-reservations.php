<?php
require_once '../classes/venue.class.php';
require_once '../classes/account.class.php';
$venueObj = new Venue();
$accountObj = new Account();

// Get all bookings
$allReservations = $venueObj->getBookings();

// Filter for status_id = 2 (Confirmed)
$Reservations = array_filter($allReservations, function ($booking) {
    return $booking['booking_status_id'] == 2;
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
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Approved Reservations</h2>
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
                    <th class="py-2 px-4 text-left">Actions</th>
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
                                <span class="bg-green-200 text-green-800 rounded-full px-2">Approved</span>
                            </td>
                            <td class="py-2 px-4">
                                <form class="cancelReservationButton inline-block" method="POST">
                                    <input type="hidden" name="booking_id" value="<?php echo $reservation['booking_id']; ?>">
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">
                                        Cancel
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="19" class="py-4 text-center">No approved reservations found</td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<script>
    $(document).ready(function () {
        // Handle filter functionality
        $('#applyFilters').click(function () {
            applyFilters();
        });

        // Handle clear filters
        $('#clearFilters').click(function () {
            // Clear all inputs
            $('#startDate, #endDate, #venueFilter, #customerFilter').val('');
            // Show all data rows
            $('#reservationsTable tr').show();
            $('#noResultsRow').remove();
        });

        function applyFilters() {
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();
            const venue = $('#venueFilter').val().toLowerCase();
            const customer = $('#customerFilter').val().toLowerCase();

            // Remove existing no results row
            $('#noResultsRow').remove();

            // Get all data rows (excluding the header)
            const dataRows = $('#reservationsTable tr:not(thead tr)');
            let visibleRows = 0;

            dataRows.each(function () {
                const row = $(this);
                if (row.attr('id') === 'noResultsRow') return;

                const rowStartDate = new Date(row.find('td:eq(1)').text()).getTime();
                const rowEndDate = new Date(row.find('td:eq(2)').text()).getTime();
                const rowVenue = row.find('td:eq(7)').text().toLowerCase();
                const rowCustomer = row.find('td:eq(4)').text().toLowerCase();

                let showRow = true;

                // Date range filter
                if (startDate && endDate) {
                    const filterStartTimestamp = new Date(startDate).getTime();
                    const filterEndTimestamp = new Date(endDate).getTime();

                    if (rowStartDate < filterStartTimestamp || rowEndDate > filterEndTimestamp) {
                        showRow = false;
                    }
                }

                // Venue filter
                if (venue && !rowVenue.includes(venue)) {
                    showRow = false;
                }

                // Customer filter
                if (customer && !rowCustomer.includes(customer)) {
                    showRow = false;
                }

                if (showRow) {
                    visibleRows++;
                    row.show();
                } else {
                    row.hide();
                }
            });

            // Show "No results found" if no data rows are visible
            if (visibleRows === 0) {
                $('#reservationsTable tbody').append(
                    '<tr id="noResultsRow"><td colspan="19" class="py-4 text-center">No results found</td></tr>'
                );
            }
        }

        // Update cancel reservation handler
        $('.cancelReservationButton').on("submit", function (e) {
            e.preventDefault();
            const formData = $(this).serialize();

            confirmshowModal(
                "Are you sure you want to cancel this reservation?",
                function () {
                    $.ajax({
                        type: "POST",
                        url: "../api/CancelReservation.api.php",
                        data: formData,
                        dataType: 'json',
                        success: function (response) {
                            if (response.status === "success") {
                                loadReservationView('cancelled-reservations'); // Change to load cancelled reservations
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Error:", error);
                        }
                    });
                },
                "icoco_black_ico.png"
            );
        });
    });
</script>