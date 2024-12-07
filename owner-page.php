<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venue Owner Profile - Icoco</title>
    <link rel="stylesheet" href="./output.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link rel="icon" href="./images/icoco_black_ico.png">
</head>

<body class="bg-slate-50">
    <?php
    session_start();
    // Include navbar based on login status
    if (isset($_SESSION['user'])) {
        include_once './components/navbar.logged.in.php';
    } else {
        include_once './components/navbar.html';
    }
    ?>

    <div class="container mx-auto px-4 py-8 pt-24">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Venue Owner Profile Card -->
            <div class="bg-slate-50 rounded-xl shadow-xl p-6 md:col-span-1">
                <div class="flex flex-col items-center mt-16 space-y-4 mb-4">
                    <img src="/placeholder.svg?height=80&width=80" alt="Doom Cat"
                        class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                    <div class="text-center">
                        <h2 class="text-xl font-bold">Doom Cat</h2>
                        <p class="text-gray-500">Venue Owner since 2020</p>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-4">
                    Passionate about creating unforgettable experiences through unique venues. I specialize in urban and
                    rustic spaces perfect for any occasion.
                </p>
                <div class="flex items-center space-x-2 mb-2">
                    <i class="fas fa-star text-yellow-400"></i>
                    <span class="font-semibold">4.9</span>
                    <span class="text-sm text-gray-500">(120 reviews)</span>
                </div>
                <div class="flex items-center space-x-2 mb-2">
                    <i class="fas fa-map-marker-alt text-gray-400"></i>
                    <span class="text-sm text-gray-500">Zamboanga City, Zamboanga del Sur</span>
                </div>
                <div class="flex items-center space-x-2 mb-4">
                    <i class="fas fa-clock text-gray-400"></i>
                    <span class="text-sm text-gray-500">Usually responds within 1 hour</span>
                </div>
                <button
                    class="w-full mt-12 bg-black text-white py-2 px-4 rounded-lg hover:bg-gray-800 transition duration-300 flex items-center justify-center">
                    <i class="fas fa-comment-alt mr-2"></i>
                    Contact Doom Cat
                </button>

                <!-- Add the Share Profile dropdown -->
                <div class="relative mt-4">
                    <button id="shareDropdownButton"
                        class="w-full bg-slate-50 text-black border border-gray-300 py-2 px-4 rounded-lg hover:bg-gray-50 transition duration-300 flex items-center justify-center">
                        <i class="fas fa-share-alt mr-2"></i>
                        Share Profile
                    </button>
                    <div id="shareDropdown"
                        class="hidden absolute left-0 right-0 mt-2 bg-slate-50 border border-gray-200 rounded-lg shadow-lg z-50">
                        <a href="#" onclick="shareOnFacebook()"
                            class="flex items-center px-4 py-2 hover:bg-gray-100 transition duration-300">
                            <i class="fab fa-facebook text-blue-600 mr-2"></i>
                            Facebook
                        </a>
                        <a href="#" onclick="shareOnTwitter()"
                            class="flex items-center px-4 py-2 hover:bg-gray-100 transition duration-300">
                            <i class="fab fa-twitter text-blue-400 mr-2"></i>
                            Twitter
                        </a>
                        <a href="#" onclick="shareOnLinkedIn()"
                            class="flex items-center px-4 py-2 hover:bg-gray-100 transition duration-300">
                            <i class="fab fa-linkedin text-blue-700 mr-2"></i>
                            LinkedIn
                        </a>
                        <a href="#" onclick="shareOnWhatsApp()"
                            class="flex items-center px-4 py-2 hover:bg-gray-100 transition duration-300">
                            <i class="fab fa-whatsapp text-green-500 mr-2"></i>
                            WhatsApp
                        </a>
                        <button onclick="copyProfileLink()"
                            class="w-full flex items-center px-4 py-2 hover:bg-gray-100 transition duration-300">
                            <i class="fas fa-link text-gray-600 mr-2"></i>
                            Copy Link
                        </button>
                    </div>
                </div>
            </div>

            <!-- Venue Listings -->
            <div class="md:col-span-3 ml-12 ">
                <h2 class="text-2xl mt-12 font-bold mb-6">Doom Cat's Venues</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Venue Cards -->
                    <div class="bg-transparent rounded-xl overflow-hidden transition duration-300">
                        <img src="/placeholder.svg?height=400&width=600" alt="Urban Loft Space"
                            class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold mb-2">Urban Loft Space</h3>
                            <p class="text-gray-600 mb-4">Perfect for photoshoots and small gatherings</p>
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    <span class="text-sm text-gray-500">Downtown SF</span>
                                </div>
                                <span
                                    class="bg-blue-50 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">₱200/Day</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-users text-gray-400"></i>
                                    <span class="text-sm text-gray-500">Up to 50 guests</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-star text-yellow-400"></i>
                                    <span class="font-semibold">4.8</span>
                                    <span class="text-sm text-gray-500">(45)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional venue cards follow the same pattern... -->
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-6">Reviews</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Review Cards -->
                <div class="bg-transparent rounded-xl shadow-sm p-6">
                    <div class="flex items-center space-x-4 mb-4">
                        <img src="/placeholder.svg?height=40&width=40" alt=" Munchkin Doom Catson"
                            class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <h3 class="text-lg font-semibold">Munchkin Ninja</h3>
                            <p class="text-gray-500">Reviewed Urban Loft Space</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-1 mb-2">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <span class="ml-2 text-sm text-gray-500">1 month ago</span>
                    </div>
                    <p class="text-gray-700">
                        The Urban Loft Space was perfect for our company photoshoot. Doom Cat was incredibly helpful and
                        accommodating. The natural light in the space is amazing!
                    </p>
                </div>

                <!-- Additional review cards... -->
            </div>
            <div class="mt-6 text-center">
                <button
                    class="bg-slate-50 text-black border border-black py-2 px-6 rounded-lg hover:bg-gray-50 transition duration-300">
                    View All Reviews
                </button>
            </div>
        </div>
    </div>
    <script src="./vendor/jQuery-3.7.1/jquery-3.7.1.min.js"></script>
    <script>
        // Your existing JavaScript with improved event handling...

        // Toggle dropdown
        document.getElementById('shareDropdownButton').addEventListener('click', function () {
            document.getElementById('shareDropdown').classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (event) {
            const dropdown = document.getElementById('shareDropdown');
            const button = document.getElementById('shareDropdownButton');
            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Share functions
        function shareOnFacebook() {
            const url = encodeURIComponent(window.location.href);
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
        }

        function shareOnTwitter() {
            const url = encodeURIComponent(window.location.href);
            const text = encodeURIComponent("Check out this venue owner's profile on Icoco!");
            window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
        }

        function shareOnLinkedIn() {
            const url = encodeURIComponent(window.location.href);
            window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}`, '_blank');
        }

        function shareOnWhatsApp() {
            const url = encodeURIComponent(window.location.href);
            const text = encodeURIComponent("Check out this venue owner's profile on Icoco!");
            window.open(`https://wa.me/?text=${text}%20${url}`, '_blank');
        }

        function copyProfileLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                alert('Profile link copied to clipboard!');
            }).catch(err => {
                console.error('Failed to copy link: ', err);
            });
        }
    </script>
</body>

</html>