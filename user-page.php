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
</head>

<body class="bg-slate-50">

    <!-- Header -->
    <?php
    // Check if the 'user' key exists in the session
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

    <div class="container mx-auto px-4 py-8 pt-24">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- User Profile Card -->
            <div class="bg-slate-50 rounded-xl shadow-xl p-6 md:col-span-1">
                <div class="flex flex-col items-center mt-16 space-y-4 mb-4">
                    <img src="/placeholder.svg?height=80&width=80" alt="User Avatar"
                        class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                    <div class="text-center">
                        <h2 class="text-xl font-bold">Rezier Doom Cat</h2>
                        <p class="text-gray-500">Member since 2023</p>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-4">
                    Event enthusiast and frequent venue booker. Always looking for unique spaces for special occasions.
                </p>
                <div class="flex items-center space-x-2 mb-2">
                    <i class="fas fa-map-marker-alt text-gray-400"></i>
                    <span class="text-sm text-gray-500">Zamboanga City, Zamboanga del Sur</span>
                </div>
                <div class="flex items-center space-x-2 mb-4">
                    <i class="fas fa-envelope text-gray-400"></i>
                    <span class="text-sm text-gray-500">john.doe@email.com</span>
                </div>


            </div>

            <!-- Rental History Section -->
            <div class="md:col-span-2">
                <h2 class="text-2xl font-bold mb-6">Places I've Rented</h2>
                <div class="space-y-6">
                    <!-- Rental Card -->
                    <div class="bg-slate-50 rounded-xl shadow-sm p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold">Urban Loft Space</h3>
                                <p class="text-gray-500">Rented on Jan 15, 2024</p>
                                <div class="flex items-center space-x-2 mt-2">
                                    <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    <span class="text-sm text-gray-500">Zamboanga City, Zamboanga del Sur</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Event Type</p>
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2.5 py-0.5 rounded-full">
                                    Outdoor
                                </span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-100">
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-user text-gray-400 mr-2"></i>
                                Hosted by Doom Cat
                            </div>
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View Venue
                            </button>
                        </div>
                    </div>

                    <!-- You can repeat the rental card structure for more rentals -->
                </div>

                <div class="mt-6">
                    <button
                        class="bg-slate-50 text-black border border-black py-2 px-6 rounded-lg hover:bg-gray-50 transition duration-300">
                        View All Rentals
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>