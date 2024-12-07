<?php
session_start();

if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['user_type_id'] == 3) {
        $user = $_SESSION['user'];
    } else {
        header('Location: ../index.php');
    }
} else {
    header('Location: ../index.php');
}



include_once '../classes/venue.class.php';

$venueObj = new Venue();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Icoco Dashboard</title>
    <link rel="icon" href="../images/icoco_black_ico.png">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="../output.css">
</head>

<body class="text-gray-800 gradient-background">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php include_once './admin-components/sidebar.php'; ?>
        <div class="flex-1 flex flex-col overflow-hidden">
            <?php include_once './admin-components/topbar.php'; ?>

            <!-- Dashboard Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100" id="adminView">


            </main>

        </div>
        <?php
        include_once '../components/feedback.modal.html';
        include_once '../components/confirm.feedback.modal.html';
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- <script>

        // Ensure the dark mode toggle is working
        const darkModeToggle = document.getElementById('darkModeToggle');
        const body = document.body;

        darkModeToggle.addEventListener('change', () => {
            body.classList.toggle('dark-mode');
        });

        // Sample JSON data (in a real application, this would come from an API)
        const dashboardData = {
            totalReservations: 1234,
            completedReservations: 980,
            upcomingReservations: 234,
            canceledReservations: 20,
            totalEarnings: 50000,
            monthlyEarnings: 15000,
            venueOccupancy: 75,
            newBookings: 15,
            upcomingReservationsList: [
                { date: '2023-09-30', venue: 'La Gran', customer: 'Joevin Hermosa', status: 'Confirmed' },
                { date: '2023-10-01', venue: 'Garden Orchid', customer: 'Randolf Festival', status: 'Pending' },
                { date: '2023-10-02', venue: 'Hui Fon', customer: 'Fritzie Balagosa', status: 'Confirmed' }
            ],
            financialData: {
                income: [10000, 15000, 20000, 18000, 25000, 30000],
                expenses: [8000, 10000, 12000, 15000, 18000, 20000]
            },
            paymentBreakdown: {
                completed: 45000,
                pending: 5000,
                refunded: 500
            },
            popularVenues: [
                { name: 'La Gran', bookings: 50 },
                { name: 'Garden Orchid', bookings: 45 },
                { name: 'Hui Fon', bookings: 30 }
            ],
            venueAvailability: [
                { name: 'La Gran', available: true },
                { name: 'Garden Orchid', available: false },
                { name: 'Hui Fon', available: true },
                { name: 'Conference Room B', available: true }
            ]
        };

        // Function to update the dashboard with data
        function updateDashboard(data) {
            // Update overview section
            document.getElementById('totalReservations').textContent = data.totalReservations;
            document.getElementById('completedReservations').textContent = data.completedReservations;
            document.getElementById('upcomingReservations').textContent = data.upcomingReservations;
            document.getElementById('canceledReservations').textContent = data.canceledReservations;
            document.getElementById('totalEarnings').textContent = `₱${data.totalEarnings.toLocaleString()}`;
            document.getElementById('monthlyEarnings').textContent = `₱${data.monthlyEarnings.toLocaleString()}`;
            document.getElementById('venueOccupancy').textContent = `${data.venueOccupancy}%`;
            document.getElementById('newBookings').textContent = data.newBookings;

            // Update upcoming reservations table
            const tableBody = document.getElementById('upcomingReservationsTable');
            tableBody.innerHTML = '';
            data.upcomingReservationsList.forEach(reservation => {
                const row = tableBody.insertRow();
                row.insertCell(0).textContent = reservation.date;
                row.insertCell(1).textContent = reservation.venue;
                row.insertCell(2).textContent = reservation.customer;
                row.insertCell(3).textContent = reservation.status;
            });

            // Update financial dashboard
            document.getElementById('completedPayments').textContent = `₱${data.paymentBreakdown.completed.toLocaleString()}`;
            document.getElementById('pendingPayments').textContent = `₱${data.paymentBreakdown.pending.toLocaleString()}`;
            document.getElementById('refundedPayments').textContent = `₱${data.paymentBreakdown.refunded.toLocaleString()}`;

            // Update income vs expenses chart
            const ctx = document.getElementById('incomeExpensesChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Income',
                        data: data.financialData.income,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }, {
                        label: 'Expenses',
                        data: data.financialData.expenses,
                        borderColor: 'rgb(255, 99, 132)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Update popular venues
            const popularVenuesList = document.getElementById('popularVenues');
            popularVenuesList.innerHTML = '';
            data.popularVenues.forEach(venue => {
                const li = document.createElement('li');
                li.textContent = `${venue.name} - ${venue.bookings} bookings`;
                popularVenuesList.appendChild(li);
            });

            // Update venue availability
            const venueAvailabilityDiv = document.getElementById('venueAvailability');
            venueAvailabilityDiv.innerHTML = '';
            data.venueAvailability.forEach(venue => {
                const p = document.createElement('p');
                p.textContent = `${venue.name}: ${venue.available ? 'Available' : 'Booked'}`;
                p.className = venue.available ? 'text-green-600' : 'text-red-600';
                venueAvailabilityDiv.appendChild(p);
            });
        }

        // Initialize the dashboard with sample data
        updateDashboard(dashboardData);

        // Handle logout button click
        document.getElementById('logoutButton').addEventListener('click', function () {
            alert('Logout functionality would be implemented here.');
        });

        // Handle account settings form submission
        document.getElementById('accountSettingsForm').addEventListener('submit', function (e) {
            e.preventDefault();
            alert('Account settings update functionality would be implemented here.');
        });

        // Modified mobile menu toggle functionality
        document.querySelector('.mobile-menu').addEventListener('click', function (event) {
            event.stopPropagation();
            const sidebar = document.querySelector('.desktop-sidebar');
            sidebar.classList.toggle('show');
        });

        // Close sidebar when clicking outside of it
        document.addEventListener('click', function (event) {
            const sidebar = document.querySelector('.desktop-sidebar');
            const mobileMenu = document.querySelector('.mobile-menu');
            if (!sidebar.contains(event.target) && !mobileMenu.contains(event.target) && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });

        // Prevent clicks inside the sidebar from closing it
        document.querySelector('.desktop-sidebar').addEventListener('click', function (event) {
            event.stopPropagation();
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const logo = document.querySelector('#sidebar img');
            const sidebarFullElements = document.querySelectorAll('.sidebar-full');
            const icons = document.querySelectorAll('#sidebar nav a svg, #sidebar nav button svg, #sidebar > div:last-child button svg');
            const navItems = document.querySelectorAll('#sidebar nav a, #sidebar nav button, #sidebar > div:last-child button');
            const searchInput = document.querySelector('.search-input');
            const searchButton = document.querySelector('.search-button');
            const collapsedSearch = document.querySelector('.sidebar-collapsed-search');
            const logoutButton = document.querySelector('#sidebar > div:last-child button');
            const logoutIcon = logoutButton.querySelector('svg');
            const logoutText = logoutButton.querySelector('span');

            sidebar.classList.toggle('w-64');
            sidebar.classList.toggle('w-16');

            // Toggle logo size
            logo.classList.toggle('w-12');
            logo.classList.toggle('h-12');
            logo.classList.toggle('w-8');
            logo.classList.toggle('h-8');

            sidebarFullElements.forEach(element => {
                element.classList.toggle('hidden');
            });

            navItems.forEach(item => {
                item.classList.toggle('justify-center');
                item.classList.toggle('px-4');
                item.classList.toggle('px-2');
            });

            icons.forEach(icon => {
                icon.classList.toggle('mr-3');
                icon.classList.toggle('mr-0');
            });

            sidebar.classList.toggle('border-r-0');

            // Toggle search input and button
            searchInput.parentElement.classList.toggle('hidden');
            collapsedSearch.classList.toggle('hidden');

            // Align collapsed search with other icons
            collapsedSearch.querySelector('button').classList.toggle('justify-center');
            collapsedSearch.querySelector('button').classList.toggle('px-4');
            collapsedSearch.querySelector('button').classList.toggle('px-2');

            // Adjust logout button
            logoutButton.classList.toggle('justify-center');
            logoutButton.classList.toggle('px-4');
            logoutButton.classList.toggle('px-2');
            logoutIcon.classList.toggle('mr-3');
            logoutIcon.classList.toggle('mr-0');

            // Toggle visibility of logout text and adjust icon
            if (sidebar.classList.contains('w-16')) {
                logoutText.classList.add('hidden');
                logoutIcon.classList.remove('mr-3');
                logoutIcon.classList.add('mx-auto');
            } else {
                logoutText.classList.remove('hidden');
                logoutIcon.classList.add('mr-3');
                logoutIcon.classList.remove('mx-auto');
            }
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

    </script> -->

    <script src="../vendor/jQuery-3.7.1/jquery-3.7.1.min.js"></script>
    <script src="../js/admin.jquery.js"></script>
    <script>
        let map;
        let marker;
        $(document).ready(function () {
            console.log("jQuery is working!");
        });
    </script>



</body>

</html>