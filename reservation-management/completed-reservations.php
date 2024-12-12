<?php
require_once '../classes/venue.class.php';
// require_once '../classes/notification.class.php';

$venueObj = new Venue();
// $notification = new Notification();

// Fetch completed venueObjs
$completedReservations = $venueObj->getCompletedReservations();
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

<div class="overflow-x-auto">
    <table class="min-w-full bg-white rounded-lg overflow-hidden">
        <thead class="bg-gray-100 text-gray-700">
            <tr>
                <th class="py-3 px-4 text-left">Reservation ID</th>
                <th class="py-3 px-4 text-left">Customer</th>
                <th class="py-3 px-4 text-left">Venue</th>
                <th class="py-3 px-4 text-left">Date</th>
                <th class="py-3 px-4 text-left">Status</th>
                <th class="py-3 px-4 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="text-gray-600">
            <?php foreach ($completedReservations as $reservation): ?>
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-3 px-4"><?php echo $reservation['id']; ?></td>
                    <td class="py-3 px-4"><?php echo $reservation['customer_name']; ?></td>
                    <td class="py-3 px-4"><?php echo $reservation['name']; ?></td>
                    <td class="py-3 px-4"><?php echo date('M d, Y', strtotime($reservation['booking_start_date'])); ?> - <?php echo date('M d, Y', strtotime($reservation['booking_end_date'])); ?></td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                            Completed
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <button class="text-blue-600 hover:text-blue-800 mr-2 view-details" 
                                data-venue-id="<?php echo $reservation['id']; ?>">
                            View Details
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div> 

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