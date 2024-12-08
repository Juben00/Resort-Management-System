<?php
session_start();
?>

<div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">Welcome, <?php echo $_SESSION['user']['firstname'] ?></h3>
    <h6 class="text-gray-500 text-1xl font-small">Let's make this day productive.</h6>

    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 main-content">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-6">Booking Dashboard</h1>

            <!-- Overview Section -->
            <section id="overview" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Reservations</h3>
                    <p class="text-3xl font-bold text-primary" id="totalReservations">-</p>
                    <ul class="mt-2 text-sm text-gray-600">
                        <li>Completed: <span id="completedReservations">-</span></li>
                        <li>Upcoming: <span id="upcomingReservations">-</span></li>
                        <li>Canceled: <span id="canceledReservations">-</span></li>
                    </ul>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Revenue Statistics</h3>
                    <p class="text-3xl font-bold text-secondary" id="totalEarnings">-</p>
                    <p class="mt-2 text-sm text-gray-600">This Month: <span id="monthlyEarnings">-</span></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">New Bookings (Last 24h)</h3>
                    <p class="text-3xl font-bold text-primary" id="newBookings">-</p>
                </div>
            </section>

            <!-- Upcoming Reservations Section -->
            <section id="upcoming-reservations" class="bg-white rounded-lg shadow-md p-4 mb-8">
                <h2 class="text-xl md:text-2xl font-semibold text-gray-800 mb-4">Upcoming Reservations</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-4 text-left">Date</th>
                                <th class="py-2 px-4 text-left">Venue</th>
                                <th class="py-2 px-4 text-left">Location</th>
                                <th class="py-2 px-4 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody id="upcomingReservationsTable">
                            <!-- Will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>
</div>

<script>
// document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
// });

function loadDashboardData() {
    fetch('../api/GetDashboardStats.api.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                updateDashboard(data.data);
                console.log(data.data);
                console.log("adas");
                
            } else {
                console.error('Error loading dashboard data:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}

function updateDashboard(data) {
    // Update statistics
    const stats = data.reservations;
    document.getElementById('totalReservations').textContent = stats.total_reservations;
    document.getElementById('completedReservations').textContent = stats.completed_reservations;
    document.getElementById('upcomingReservations').textContent = stats.upcoming_reservations;
    document.getElementById('canceledReservations').textContent = stats.canceled_reservations;
    document.getElementById('totalEarnings').textContent = '₱' + formatNumber(stats.total_earnings);
    document.getElementById('monthlyEarnings').textContent = '₱' + formatNumber(stats.monthly_earnings);
    document.getElementById('newBookings').textContent = data.new_bookings;

    // Update upcoming reservations table
    const tableBody = document.getElementById('upcomingReservationsTable');
    tableBody.innerHTML = data.upcoming_reservations.map(booking => `
        <tr class="border-b hover:bg-gray-50">
            <td class="py-2 px-4">${formatDate(booking.booking_start_date)}</td>
            <td class="py-2 px-4">${booking.venue_name}</td>
            <td class="py-2 px-4">${booking.venue_location}</td>
            <td class="py-2 px-4">
                <span class="px-2 py-1 rounded-full text-xs ${getStatusClass(booking.booking_status_id)}">
                    ${getStatusText(booking.booking_status_id)}
                </span>
            </td>
        </tr>
    `).join('');
}

function formatNumber(number) {
    return new Intl.NumberFormat('en-PH').format(number || 0);
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-PH', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function getStatusText(statusId) {
    const statuses = {
        '1': 'Pending',
        '2': 'Approved',
        '3': 'Cancelled',
        '4': 'Completed'
    };
    return statuses[statusId] || 'Unknown';
}

function getStatusClass(statusId) {
    const classes = {
        '1': 'bg-yellow-100 text-yellow-800',
        '2': 'bg-green-100 text-green-800',
        '3': 'bg-red-100 text-red-800',
        '4': 'bg-blue-100 text-blue-800'
    };
    return classes[statusId] || 'bg-gray-100 text-gray-800';
}
</script>