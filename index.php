<?php

require_once './sanitize.php';
require_once './classes/venue.class.php';
require_once './classes/account.class.php';

session_start();

if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['user_type_id'] == 3) {
        header('Location: admin/');
    }

}

$venueObj = new Venue();
$accountObj = new Account();

// Get all venues
$venues = $venueObj->getAllVenues('2');
$bookmarks = $accountObj->getBookmarks(isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0);
$bookmarkIds = array_column($bookmarks, 'venue_id');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iCoCo Resort Management System || Home</title>
    <link rel="icon" href="./images/icoco_black_ico.png">
    <link rel="stylesheet" href="./output.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/luxon@3.3.0/build/global/luxon.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <style>
        .slideshow-container .slide {
            display: none;
            opacity: 0;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            transition: opacity 0.5s ease-in-out;
        }

        .slideshow-container .slide.active {
            display: block;
            opacity: 1;
            z-index: 1;
        }


        .cloud-bottom {
            position: absolute;
            bottom: -30px;
            /* Slightly overlap to avoid thin line */
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }

        .cloud-bottom svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 150px;
            /* Increased height for more pronounced layers */
        }

        .cloud-bottom .shape-fill {
            fill: #3490dc;
        }

        .sidebar {
            background: transparent;
            transition: color 0.3s ease;
        }

        .sidebar button {
            color: white;
            transition: color 0.3s ease;
        }

        .first-section {
            height: 80vh;
            /* 3/4 of the viewport height */
        }
    </style>
    <style>
        /* ... (rest of the styles remain unchanged) */

        #authModal {
            transition: opacity 0.3s ease-in-out;
        }

        #loginForm,
        #signupForm {
            transition: opacity 0.3s ease-in-out;
        }
    </style>
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
    <style>
        .bookmark-btn {
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .bookmark-btn:active {
            transform: scale(1.5) rotate(360deg);
        }

        .bookmark-btn.bookmarked {
            color: #ef4444;
            /* red-500 */
            filter: drop-shadow(0 0 8px rgba(239, 68, 68, 0.5));
            transform: scale(1.2);
        }

        .bookmark-btn:hover {
            transform: scale(1.2);
        }

        @keyframes heartbeat {
            0% {
                transform: scale(1);
            }

            25% {
                transform: scale(1.3);
            }

            50% {
                transform: scale(1);
            }

            75% {
                transform: scale(1.3);
            }

            100% {
                transform: scale(1.2);
            }
        }

        .bookmark-btn.animate {
            animation: heartbeat 0.8s ease-in-out;
        }
    </style>
    <style>
        .scroll-animate {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease-out;
        }

        .scroll-animate.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Add different animation delays for grid items */
        .grid-item-delay:nth-child(1) {
            transition-delay: 0s;
        }

        .grid-item-delay:nth-child(2) {
            transition-delay: 0.2s;
        }

        .grid-item-delay:nth-child(3) {
            transition-delay: 0.4s;
        }
    </style>
</head>

<body class="min-h-screen text-gray-900 flex flex-col">



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


    <div class="flex flex-1 ">
        <!-- Main content -->
        <main class="flex-1">
            <!-- First section with blue background -->
            <div class="bg-blue-500/20 relative">
                <?php require_once './components/coverPage.html' ?>
            </div>

            <!-- New second section -->
            <section class="bg-slate-50 py-16">
                <!-- Content container with left margin for sidebar -->
                <div class="ml-20">
                    <div class="container mx-auto px-4 md:px-8">
                        <div class="mt-16 max-w-6xl mx-auto scroll-animate">
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8 text-center">
                                Find Your Perfect Resort
                            </h2>
                            <p class="text-gray-600 text-center mb-12 max-w-3xl mx-auto">
                                Welcome to iCoCo Resort Management System, your ultimate solution for managing resort
                                operations efficiently. Our platform is designed to enhance guest experiences and
                                streamline resort management processes.
                            </p>
                        </div>
                    </div>

                    <!-- Second section with white background -->
                    <div class="bg-slate-50 p-50 pt-10 relative z-10">
                        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 scroll-animate">
                            <h2 class="text-3xl font-bold mb-4">Featured Resorts</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 w-full h-full"
                                id="venueList">
                                <?php
                                if (empty($venues)) {
                                    echo '<p class="text-left text-gray-500">No resorts available</p>';
                                }
                                foreach ($venues as $venue) {
                                    ?>
                                    <div class="bg-slate-50 rounded-2xl overflow-hidden  cursor-pointer">
                                        <div class="relative">
                                            <!-- Slideshow Container for each venue -->
                                            <div class="relative w-full h-96 overflow-hidden">
                                                <!-- Image Slideshow for each venue -->
                                                <div class="slideshow-container venueCard"
                                                    data-id="venues.php?id=<?php echo $venue['venue_id']; ?>"
                                                    data-isloggedin="<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>">
                                                    <?php if (!empty($venue['image_urls'])): ?>
                                                        <?php foreach ($venue['image_urls'] as $index => $imageUrl): ?>
                                                            <div class="slide <?= $index === 0 ? 'active' : '' ?>">
                                                                <img src="./<?= htmlspecialchars($imageUrl) ?>"
                                                                    alt="<?= htmlspecialchars($venue['name']) ?>"
                                                                    class="w-full h-full object-cover rounded-2xl transition-opacity duration-1000">
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if (isset($venue['venue_tag_name'])): ?>
                                                    <span
                                                        class="absolute top-3 left-3 bg-slate-50 text-black text-xs font-semibold px-2 py-1 rounded-full z-50">
                                                        <?= htmlspecialchars($venue['venue_tag_name']) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <!-- Button (can be used for manual control) -->
                                            <?php
                                            if (isset($_SESSION['user'])) { ?>
                                                <button id="bookmarkBtn"
                                                    data-venueId="<?php echo htmlspecialchars($venue['id']); ?>"
                                                    data-userId="<?php echo htmlspecialchars($_SESSION['user']['id']); ?>"
                                                    class="bookmark-btn absolute top-3 right-3 z-50 <?php echo $isBookmarked ? 'bookmarked' : 'text-white'; ?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            <?php } ?>



                                            <!-- Venue details below the slideshow -->
                                            <div class="p-4 ">
                                                <div class="flex justify-between items-center mb-1">
                                                    <h3 class="text-base font-semibold text-gray-900">
                                                        <?= htmlspecialchars($venue['name']) ?>
                                                    </h3>
                                                    <div class="flex items-center">
                                                        <p class="font-bold text-xs">
                                                            <?php echo number_format($venue['rating'], 1) ?? "0" ?>
                                                        </p>
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-4 w-4 text-yellow-500 mr-1" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path
                                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    </div>
                                                </div>

                                                <p class="text-sm text-gray-500 leading-tight line-clamp-2">
                                                    <?= htmlspecialchars($venue['description']) ?>
                                                </p>



                                                <p class="mt-2">
                                                    <span
                                                        class="font-semibold text-gray-900 text-base">₱<?= number_format($venue['price'], 2) ?></span>
                                                    <span class="text-gray-900 text-sm"> per night</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>

                            </div>
                        </section>
                        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-32">
                            <div class="flex flex-col">
                                <div class="text-center mb-8 scroll-animate">
                                    <h2 class="text-3xl font-bold mb-2">Our Services</h2>
                                    <p class="text-gray-600 max-w-2xl mx-auto text-sm">iCoCo Resort Management System
                                        offers innovative tools to simplify booking, facility management, and customer
                                        service, ensuring a seamless experience for both guests and resort managers.
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-2">
                                    <!-- Space Rentals Card -->
                                    <div
                                        class="group bg-slate-50 p-4 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 scroll-animate grid-item-delay">
                                        <div class="relative overflow-hidden rounded-xl mb-4">
                                            <img src="./images/serviceimages/pexels-pixabay-267569.jpg" alt="Rent Space"
                                                class="w-full h-52 object-cover transform group-hover:scale-105 transition-transform duration-300">
                                        </div>
                                        <div class="space-y-2">
                                            <h3 class="text-xl font-semibold text-gray-800">Resort Rentals</h3>
                                            <p class="text-sm text-gray-600">Ensure a memorable stay for guests by
                                                enabling personalized services and streamlined interactions from booking
                                                to checkout.</p>
                                        </div>
                                    </div>

                                    <!-- Post Your Space Card -->
                                    <div
                                        class="group bg-slate-50 p-4 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 scroll-animate grid-item-delay">
                                        <div class="relative overflow-hidden rounded-xl mb-4">
                                            <img src="./images/serviceimages/pexels-rdne-7414284.jpg"
                                                alt="Post Listings"
                                                class="w-full h-52 object-cover transform group-hover:scale-105 transition-transform duration-300">
                                        </div>
                                        <div class="space-y-2">
                                            <h3 class="text-xl font-semibold text-gray-800">Resort Owners</h3>
                                            <p class="text-sm text-gray-600">
                                                Efficiently manage bookings, availability, and reservations with
                                                real-time updates to ensure a seamless guest experience.

                                            </p>
                                        </div>
                                    </div>

                                    <!-- Book Event Space Card -->
                                    <div
                                        class="group bg-slate-50 p-4 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 scroll-animate grid-item-delay">
                                        <div class="relative overflow-hidden rounded-xl mb-4">
                                            <img src="./images/serviceimages/pexels-tima-miroshnichenko-6694575.jpg"
                                                alt="Book Event"
                                                class="w-full h-52 object-cover transform group-hover:scale-105 transition-transform duration-300">
                                        </div>
                                        <div class="space-y-2">
                                            <h3 class="text-xl font-semibold text-gray-800">Book an Event Space</h3>
                                            <p class="text-sm text-gray-600">Easily browse and book spaces for weddings,
                                                meetings, parties, and more.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- Services section -->
                        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16 mb-8">
                            <div class="container mx-auto flex flex-col ">
                                <h2 class="text-3xl font-bold mb-4 text-center scroll-animate">About Us</h2>

                                <div class="flex flex-col gap-4">

                                    <div
                                        class="flex flex-col items-center bg-slate-50 border p-4 lg:p-4 lg:py-8 rounded-lg shadow-md gap-2 scroll-animate">
                                        <h3 class="text-xl font-semibold  text-red-500 italic">Our Story</h3>
                                        <p>
                                            iCoCo Resort Management System was created to address the challenges faced
                                            by resort managers and guests. Our founders recognized the need for a
                                            comprehensive platform that integrates all aspects of resort management,
                                            from booking to customer service. With iCoCo, you can manage your resort
                                            effortlessly and provide an exceptional experience for your guests.
                                        </p>
                                        <p>Our journey has been driven by a commitment to innovation and customer
                                            satisfaction. We continuously improve our platform based on user feedback,
                                            ensuring that iCoCo remains the leading solution for resort management. Join
                                            us and experience the future of resort management today.</p>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <!-- mission -->
                                        <div
                                            class="flex flex-col items-center bg-slate-50 border p-4 lg:p-4 lg:py-8 rounded-lg shadow-md gap-2 scroll-animate">
                                            <h3 class="text-xl font-semibold  text-red-500 italic">Our Mission</h3>
                                            <p class="text-center">
                                                Our mission is to revolutionize resort management by providing a
                                                user-friendly platform that connects guests with the perfect resort
                                                experience, ensuring seamless operations from booking to checkout.
                                            </p>
                                        </div>
                                        <!-- vission -->
                                        <div
                                            class="flex flex-col items-center bg-slate-50 border p-4 lg:p-4 lg:py-8 rounded-lg shadow-md gap-2 scroll-animate">
                                            <h3 class="text-xl font-semibold  text-red-500 italic">Our Vision</h3>
                                            <p class="text-center">
                                                Our vision is to be the premier platform for resort management, helping
                                                resorts worldwide deliver exceptional guest experiences.
                                            </p>
                                        </div>
                                    </div>

                                    <!-- FAQ -->
                                    <div
                                        class="flex flex-col bg-slate-50 border text-neutral-700 p-4 lg:p-4 lg:py-8 rounded-lg shadow-md">
                                        <h3 class="text-xl font-semibold text-red-500 italic text-center">FAQs</h3>
                                        <div class="w-full ">
                                            <div class="faq-item mb-4">
                                                <button class="faq-header w-full text-left">
                                                    1. How do I book a resort?
                                                </button>
                                                <div class="faq-content hidden">
                                                    <p class="text-xs">To book a resort, simply search for your desired
                                                        location and dates on our platform. Browse through the available
                                                        options, select the resort that suits your needs, and follow the
                                                        booking process to confirm your reservation.</p>
                                                </div>
                                            </div>
                                            <div class="faq-item mb-4">
                                                <button class="faq-header  w-full text-left">
                                                    2. Can I list my own space on Icoco?
                                                </button>
                                                <div class="faq-content hidden">
                                                    <p class="text-sm ">Yes, you can list your resort on iCoCo Resort
                                                        Management System. Create an account, provide details about your
                                                        resort, upload photos, and set your availability and pricing.
                                                        Once your listing is approved, it will be visible to potential
                                                        guests.</p>
                                                </div>
                                            </div>
                                            <div class="faq-item mb-4">
                                                <button class="faq-header  w-full text-left">
                                                    3. What types of spaces can I list?
                                                </button>
                                                <div class="faq-content hidden">
                                                    <p class="text-sm ">You can list a variety of resort types,
                                                        including luxury resorts, budget-friendly options, and unique
                                                        stays. Our platform accommodates all types of resorts that offer
                                                        exceptional guest experiences.</p>
                                                </div>
                                            </div>
                                            <div class="faq-item mb-4">
                                                <button class="faq-header  w-full text-left">
                                                    4. Are there any fees associated with booking or listing a space?
                                                </button>
                                                <div class="faq-content hidden">
                                                    <p class="text-sm ">Yes, there may be fees associated with both
                                                        booking and listing resorts. Booking fees are typically a
                                                        percentage of the total rental cost, while listing fees may vary
                                                        based on the type of resort and duration of the listing.
                                                        Detailed information about fees will be provided during the
                                                        booking or listing process.</p>
                                                </div>
                                            </div>
                                            <div class="faq-item mb-4">
                                                <button class="faq-header  w-full text-left">
                                                    5. How can I contact customer support?
                                                </button>
                                                <div class="faq-content hidden">
                                                    <p class="text-sm ">If you need assistance, you can contact our
                                                        customer support team via the contact form on our website, or by
                                                        email at support@icoco.com. Our team is available to help you
                                                        with any questions or issues you may have.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </section>
                    </div>
                </div>
            </section>
            <div id="openstreetmapplaceholder"></div>
            <?php require_once './components/footer.html' ?>
        </main>
    </div>

    <!-- jQuery -->
    <script src="./vendor/jQuery-3.7.1/jquery-3.7.1.min.js"></script>
    <script src="./js/user.jquery.js"></script>
    <script>
        let map;
        let marker;
    </script>
    <!-- venue slideshow -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const venueSlideshows = document.querySelectorAll('.slideshow-container');

            venueSlideshows.forEach((slideshow) => {
                let currentSlide = 0;
                const slides = slideshow.querySelectorAll('.slide');

                function showSlide(index) {
                    // First set display:block on next slide before starting transition
                    slides[index].style.display = 'block';

                    // Small delay to ensure display:block is processed
                    setTimeout(() => {
                        // Hide all slides
                        slides.forEach((slide) => {
                            slide.classList.remove('active');
                            slide.style.opacity = '0';
                        });

                        // Show and fade in the current slide
                        slides[index].classList.add('active');
                        slides[index].style.opacity = '1';

                        // Clean up non-active slides after transition
                        setTimeout(() => {
                            slides.forEach((slide, i) => {
                                if (i !== index) {
                                    slide.style.display = 'none';
                                }
                            });
                        }, 500); // Match this to transition duration
                    }, 10);
                }

                // Show next slide every 4 seconds
                setInterval(() => {
                    currentSlide = (currentSlide + 1) % slides.length;
                    showSlide(currentSlide);
                }, 4000);

                // Initialize the first slide
                showSlide(currentSlide);
            });
        });

    </script>

    <!-- <script>
        // Date picker functionality
        const checkInBtn = document.getElementById('checkInBtn');
        const checkOutBtn = document.getElementById('checkOutBtn');
        const checkInText = document.getElementById('checkInText');
        const checkOutText = document.getElementById('checkOutText');

        checkInBtn.addEventListener('click', () => {
            const date = luxon.DateTime.local().plus({ days: 1 }).toFormat('LLL dd, yyyy');
            checkInText.textContent = date;
        });

        checkOutBtn.addEventListener('click', () => {
            const date = luxon.DateTime.local().plus({ days: 6 }).toFormat('LLL dd, yyyy');
            checkOutText.textContent = date;
        });
    </script> -->

    <!-- login and signup form functionality || styling css  -->
    <script>
        // Get modal elements
        const authModal = document.getElementById('authModal');
        const closeModal = document.getElementById('closeModal');
        const loginTab = document.getElementById('loginTab');
        const signupTab = document.getElementById('signupTab');
        const loginForm = document.getElementById('loginForm');
        const signupForm = document.getElementById('signupForm');
        const tabUnderline = document.getElementById('tabUnderline');

        // Get all buttons that should open the modal
        const signInButtons = document.querySelectorAll('button[onclick="openModal()"]');

        // Function to open modal with smooth transition
        function openModal() {
            authModal.style.display = 'flex';
            authModal.style.opacity = '0';
            setTimeout(() => {
                authModal.style.opacity = '1';
            }, 10);
        }

        // Function to close modal with smooth transition
        function closeModalFunc() {
            authModal.style.opacity = '0';
            setTimeout(() => {
                authModal.style.display = 'none';
            }, 300);
        }

        // Add click event listeners to all sign in buttons
        signInButtons.forEach(button => {
            button.addEventListener('click', openModal);
        });

        // Close modal when clicking close button
        closeModal.addEventListener('click', closeModalFunc);

        // Tab switching functionality
        loginTab.addEventListener('click', () => {
            switchTab(loginTab, signupTab, loginForm, signupForm);
        });

        signupTab.addEventListener('click', () => {
            switchTab(signupTab, loginTab, signupForm, loginForm);
        });

        function switchTab(activeTab, inactiveTab, activeForm, inactiveForm) {
            activeTab.classList.add('text-blue-500');
            activeTab.classList.remove('text-gray-500');
            inactiveTab.classList.remove('text-blue-500');
            inactiveTab.classList.add('text-gray-500');

            // Move the tab underline
            if (activeTab === loginTab) {
                tabUnderline.style.left = '0';
            } else {
                tabUnderline.style.left = '50%';
            }

            // Fade out the current form
            activeForm.classList.add('opacity-0');
            inactiveForm.classList.add('opacity-0');

            setTimeout(() => {
                activeForm.classList.add('hidden');
                inactiveForm.classList.add('hidden');

                // Show and fade in the new form
                activeForm.classList.remove('hidden');
                setTimeout(() => {
                    activeForm.classList.remove('opacity-0');
                }, 50);
            }, 300);
        }

        // Ensure the DOM is fully loaded before attaching event listeners
        document.addEventListener('DOMContentLoaded', (event) => {
            // Reattach event listeners to make sure they work
            closeModal.addEventListener('click', closeModalFunc);
            loginTab.addEventListener('click', () => switchTab(loginTab, signupTab, loginForm, signupForm));
            signupTab.addEventListener('click', () => switchTab(signupTab, loginTab, signupForm, loginForm));
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const faqHeaders = document.querySelectorAll(".faq-header");

            faqHeaders.forEach((header) => {
                header.addEventListener("click", function () {
                    const faqContent = this.nextElementSibling;

                    if (faqContent.classList.contains("hidden")) {
                        faqContent.classList.remove("hidden");
                    } else {
                        faqContent.classList.add("hidden");
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('show');
                        // Optionally, stop observing the element after it's animated
                        // observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Observe all elements with scroll-animate class
            document.querySelectorAll('.scroll-animate').forEach(element => {
                observer.observe(element);
            });
        });
    </script>

</body>

</html>