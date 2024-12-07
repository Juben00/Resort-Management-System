<?php

require_once './sanitize.php';
require_once './classes/venue.class.php';
require_once './classes/account.class.php';

session_start();
$accountObj = new Account();

if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['user_type_id'] == 3) {
        header('Location: admin/');
    }
    $user = $accountObj->retrieveUser($_SESSION['user']['id']);
}

$discountStatus = $accountObj->getDiscountApplication($_SESSION['user']['id']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Icoco || Settings</title>
    <link rel="icon" href="./images/icoco_black_ico.png">
    <link rel="stylesheet" href="./output.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/luxon@3.3.0/build/global/luxon.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="bg-gray-50">
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

    <main class="w-full px-4 py-8 min-h-screen">
        <div class="max-w-7xl mt-16 mx-auto">
            <h1 class="text-3xl font-bold mb-8 text-gray-800">Settings</h1>

            <!-- Settings Navigation -->
            <div class="flex mb-8 border-b border-gray-200 bg-slate-50 rounded-lg p-2 gap-2 shadow-sm">
                <button
                    class="flex items-center gap-2 px-6 py-3 text-gray-600 rounded-lg transition-all duration-200 hover:bg-gray-100 hover:text-black data-[active=true]:bg-gray-100 data-[active=true]:text-black data-[active=true]:font-semibold"
                    data-tab="account" data-active="true">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Account Settings
                </button>
                <button
                    class="flex items-center gap-2 px-6 py-3 text-gray-600 rounded-lg transition-all duration-200 hover:bg-gray-100 hover:text-black data-[active=true]:bg-gray-100 data-[active=true]:text-black data-[active=true]:font-semibold"
                    data-tab="discounts">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Discounts
                </button>
            </div>

            <!-- Settings Content -->
            <div class="bg-slate-50 rounded-xl shadow-lg p-8">
                <!-- Account Settings -->
                <div id="account-settings"
                    class="transition-all duration-200 ease-in-out transform opacity-100 translate-x-0">
                    <div class="max-w-7xl mx-auto">
                        <!-- Profile Picture Section -->
                        <form class="space-y-8" id="updateUserInfoForm">
                            <div class="mb-12 flex flex-col items-center">
                                <div class="relative group">
                                    <div class="w-64 h-64 rounded-full overflow-hidden border-4 border-white shadow-lg">
                                        <?php
                                        if ($user['profile_pic'] == null) {
                                            echo '<div id="profilePlaceholder" class="h-full w-full object-cover text-7xl bg-black text-white flex items-center justify-center">';
                                            echo htmlspecialchars($user["firstname"][0]);
                                            echo '</div>';
                                        } else {
                                            echo '<img id="profileImage" name="profile_image" src="./' . htmlspecialchars($user["profile_pic"]) . '" alt="Profile Picture" class="w-full h-full object-cover">';
                                        }
                                        ?>
                                    </div>
                                    <label for="profilePicture"
                                        class="absolute inset-0 flex items-center justify-center bg-black/50 text-white rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity duration-200">
                                        <span class="text-sm">Change Photo</span>
                                    </label>
                                    <input type="file" id="profilePicture" name="profilePicture" class="hidden"
                                        accept="image/*">
                                </div>
                                <p class="text-sm text-gray-500 mt-2">Click to upload new profile picture</p>
                            </div>
                            <!-- Personal Information Section -->
                            <div class="space-y-6">
                                <h3 class="flex items-center gap-2 text-lg font-medium text-gray-800 border-b pb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Personal Information
                                </h3>

                                <!-- Add Bio section here -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Bio</label>
                                    <textarea name="bio" rows="4"
                                        class="w-full px-3 py-2 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition-colors"
                                        placeholder="Tell us a little bit about yourself..."><?php echo htmlspecialchars($user['bio']); ?></textarea>
                                    <p class="text-sm text-gray-500">Write a short bio that describes you (maximum 500
                                        characters)</p>
                                </div>

                                <!-- Form inputs with Tailwind classes -->
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                    <div class="space-y-2">

                                        <label class="block text-sm font-medium text-gray-700">First Name</label>
                                        <input type="text" name="firstname"
                                            value="<?php echo htmlspecialchars($user['firstname']); ?>"
                                            class="w-full h-12 px-3 py-2 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition-colors">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Last Name</label>
                                        <input type="text" name="lastname"
                                            value="<?php echo htmlspecialchars($user['lastname']); ?>"
                                            class="w-full h-12 px-3 py-2 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition-colors">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Middle Initial</label>
                                        <input type="text" name="middlename"
                                            value="<?php echo htmlspecialchars($user['middlename']); ?>"
                                            class="w-full h-12 px-3 py-2 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition-colors">
                                    </div>
                                </div>

                                <!-- Sex and Birthday -->
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    <div class="space-y-2">

                                        <label class="block text-sm font-medium text-gray-700">Sex</label>
                                        <select name="sex"
                                            class="w-full h-12 px-3 py-2 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition-colors">
                                            <option value="" disabled <?php echo empty($user['sex']) ? 'selected' : ''; ?>>
                                                Select sex</option>
                                            <option value="Male" <?php echo htmlspecialchars($user['sex']) === "Male" ? "selected" : ""; ?>>Male</option>
                                            <option value="Female" <?php echo htmlspecialchars($user['sex']) === "Female" ? "selected" : ""; ?>>Female</option>
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Birthday</label>
                                        <input type="date" name="birthdate"
                                            value="<?php echo htmlspecialchars($user['birthdate']); ?>"
                                            class="w-full h-12 px-3 py-2 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition-colors">
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information Section -->
                            <div class="space-y-6 pt-6 border-t">
                                <h3 class="text-lg font-medium text-gray-700 border-b pb-2">Contact Information</h3>

                                <!-- Address -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Address</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="address" id="address" readonly
                                            value="<?php echo htmlspecialchars($user['address']); ?>"
                                            class="w-full h-12 px-3 py-2 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition-colors"
                                            placeholder="Enter your address">
                                        <button id="maps-button"
                                            class=" border bg-gray-50 hover:bg-gray-100 duration-150 p-2 rounded-md">
                                            <svg height="24px" width="24px" version="1.1" id="Layer_1"
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"
                                                xml:space="preserve" fill="#bcc2bc" stroke="#bcc2bc">
                                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                    stroke-linejoin="round"></g>
                                                <g id="SVGRepo_iconCarrier">
                                                    <polygon points="154.64,420.096 154.64,59.496 0,134 0,512 ">
                                                    </polygon>
                                                    <polygon style="fill:#d3d5de;"
                                                        points="309.288,146.464 309.288,504.472 154.64,420.096 154.64,59.496 ">
                                                    </polygon>
                                                    <polygon
                                                        points="463.928,50.152 309.288,146.464 309.288,504.472 463.928,415.68 ">
                                                    </polygon>
                                                    <path style="fill:#e73023;"
                                                        d="M414.512,281.656l-11.92-15.744c-8.8-11.472-85.6-113.984-85.6-165.048 C317.032,39.592,355.272,0,414.512,0S512,39.592,512,100.864c0,50.992-76.8,153.504-85.488,165.048L414.512,281.656z">
                                                    </path>
                                                    <circle style="fill:#FFFFFF;" cx="414.512" cy="101.536" r="31.568">
                                                    </circle>
                                                </g>
                                            </svg>
                                        </button> </button>
                                    </div>
                                </div>

                                <!-- Email and Contact -->
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" name="email"
                                            value="<?php echo htmlspecialchars($user['email']); ?>"
                                            class="w-full h-12 px-3 py-2 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition-colors"
                                            placeholder="your.email@example.com">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                                        <input type="tel" name="contact"
                                            value="<?php echo htmlspecialchars($user['contact_number']); ?>"
                                            class="w-full h-12 px-3 py-2 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition-colors"
                                            placeholder="09XX XXX XXXX">
                                    </div>
                                </div>
                            </div>

                            <div class="pt-6 flex justify-end">
                                <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-black text-white font-semibold rounded-lg transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg min-w-[200px] hover:bg-gray-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h-2v5.586l-1.293-1.293z" />
                                    </svg>
                                    Save Changes
                                </button>
                            </div>
                        </form>

                        <!-- Password Section -->
                        <div class="mt-16 pt-8 border-t border-gray-200">
                            <h3 class="text-lg font-medium mb-8 text-gray-800 border-b pb-2">Change Password</h3>
                            <form class="space-y-8" id="updatePasswordForm">
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Current Password</label>
                                        <input type="password" name="current_password" placeholder="***********"
                                            class="w-full h-12 px-3 py-2 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition-colors">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">New Password</label>
                                        <input type="password" name="new_password" placeholder="New Password"
                                            class="w-full h-12 px-3 py-2 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition-colors">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Confirm New
                                            Password</label>
                                        <input type="password" name="confirm_password" placeholder="Confirm Password"
                                            class="w-full h-12 px-3 py-2 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition-colors">
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit"
                                        class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-black text-white font-semibold rounded-lg transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg min-w-[200px] hover:bg-gray-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Notifications Settings -->
                <div id="notifications-settings"
                    class="transition-all duration-200 ease-in-out transform opacity-100 translate-x-0">
                    <div class="max-w-7xl mx-auto">
                        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Notification Preferences</h2>
                        <form class="space-y-6">
                            <div class="space-y-6">

                                <div
                                    class="flex items-center justify-between p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-800">Messages Notifications</h3>
                                        <p class="text-sm text-gray-600">Get notifications for important messages</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer">
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-50 after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-black">
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="form-button">
                                    Save Preferences
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Privacy Settings -->
                <div id="privacy-settings"
                    class="transition-all duration-200 ease-in-out transform opacity-100 translate-x-0">
                    <div class="max-w-7xl mx-auto">
                        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Privacy Settings</h2>
                        <form class="space-y-6">
                            <div class="space-y-6">
                                <div
                                    class="flex items-center justify-between p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-800">Profile Visibility</h3>
                                        <p class="text-sm text-gray-600">Control who can see your profile information
                                        </p>
                                    </div>
                                    <select class="form-input w-auto bg-transparent">
                                        <option>Public</option>
                                        <option>Private</option>
                                    </select>
                                </div>

                                <div
                                    class="flex items-center justify-between p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-800">Two-Factor Authentication</h3>
                                        <p class="text-sm text-gray-600">Add an extra layer of security to your account
                                        </p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer">
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-50 after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-black">
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="form-button">
                                    Save Privacy Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Discounts Settings -->
                <div id="discounts-settings"
                    class="transition-all duration-200 ease-in-out transform opacity-100 translate-x-0">
                    <div class="max-w-7xl mx-auto">
                        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Discount Settings</h2>
                        <!-- Senior/PWD Discount Section -->
                        <?php
                        if (empty($discountStatus)) {
                            ?>
                            <div class="bg-slate-50 rounded-lg shadow p-6 mb-6">
                                <h3 class="text-lg font-medium text-gray-800 mb-4">Senior Citizen / PWD Discount</h3>
                                <div class="space-y-4">
                                    <p class="text-gray-600">Enable 20% discount for Senior Citizens and Persons with
                                        Disabilities (PWD)</p>
                                    <button onclick="openDiscountModal()"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-slate-50 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                                        <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Senior/PWD ID
                                    </button>
                                </div>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div id="discountDetailsBox"
                                class="mb-6 p-4 <?php echo htmlspecialchars($discountStatus['status']) == 'Active' ? "bg-green-50 border-green-200" : "bg-neutral-50 border-neutral-200" ?> border  rounded-lg">
                                <div class="flex items-start">
                                    <svg class="h-5 w-5 <?php echo htmlspecialchars($discountStatus['status']) == 'Active' ? "text-green-500" : "text-neutral-800" ?>  mt-0.5 mr-2"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p
                                            class="<?php echo htmlspecialchars($discountStatus['status']) == 'Active' ? "text-green-700" : "text-neutral-800" ?> font-medium mb-2">
                                            Verified Senior/PWD Status</p>
                                        <div
                                            class="space-y-1 text-sm <?php echo htmlspecialchars($discountStatus['status']) == 'Active' ? "text-green-600" : "text-neutral-800" ?>">
                                            <p><span class="font-medium">Status:</span> <span
                                                    id="displayName"><?php echo htmlspecialchars($discountStatus['status']) ?></span>
                                            </p>
                                            <p><span class="font-medium">Name:</span> <span
                                                    id="displayName"><?php echo htmlspecialchars($discountStatus['fullname']) ?></span>
                                            </p>
                                            <p><span class="font-medium">ID Number:</span> <span
                                                    id="displayId"><?php echo htmlspecialchars($discountStatus['discount_id']) ?></span>
                                            </p>
                                            <p><span class="font-medium">ID Photo:</span></p>
                                            <img id="displayPhotoPreview"
                                                class="w-48 h-48 object-cover rounded-lg border border-green-200 mt-2"
                                                src="./<?php echo htmlspecialchars($discountStatus['card_image']) ?>"
                                                alt="ID Photo">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div id="openstreetmapplaceholder"></div>
    </main>

    <script src="./vendor/jQuery-3.7.1/jquery-3.7.1.min.js"></script>
    <script src="./js/user.jquery.js"></script>
    <script>
        let map;
        let marker;
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get all tab buttons and content sections
            const tabButtons = document.querySelectorAll('[data-tab]');
            const contentSections = {
                account: document.getElementById('account-settings'),
                notifications: document.getElementById('notifications-settings'),
                privacy: document.getElementById('privacy-settings'),
                discounts: document.getElementById('discounts-settings')
            };

            function switchTab(tabId) {
                // Update button states
                tabButtons.forEach(button => {
                    button.setAttribute('data-active', button.dataset.tab === tabId);
                });

                // First set all sections to start transitioning out
                Object.values(contentSections).forEach(section => {
                    if (!section.classList.contains('hidden')) {
                        section.style.opacity = '0';
                        section.style.transform = 'translateX(-20px)';
                    }
                });

                // Wait for fade out, then switch content
                setTimeout(() => {
                    // Hide all content sections
                    Object.values(contentSections).forEach(section => {
                        section.classList.add('hidden');
                        section.setAttribute('data-hidden', 'true');
                    });

                    // Show selected content
                    const selectedContent = contentSections[tabId];
                    selectedContent.classList.remove('hidden');
                    selectedContent.setAttribute('data-hidden', 'false');

                    // Trigger reflow
                    void selectedContent.offsetHeight;

                    // Start fade in
                    selectedContent.style.opacity = '1';
                    selectedContent.style.transform = 'translateX(0)';
                }, 200); // Match this with the CSS transition duration
            }

            // Add click handlers to all tab buttons
            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    switchTab(button.dataset.tab);
                });
            });

            // Initialize with account tab active
            switchTab('account');
        });

        // Profile picture preview
        document.getElementById('profilePicture').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const profileImage = document.getElementById('profileImage');
                    const profilePlaceholder = document.getElementById('profilePlaceholder');
                    if (profileImage) {
                        profileImage.src = e.target.result;
                    } else if (profilePlaceholder) {
                        profilePlaceholder.outerHTML = '<img id="profileImage" src="' + e.target.result + '" alt="Profile Picture" class="w-full h-full object-cover">';
                    }
                }
                reader.readAsDataURL(file);
            }
        });

        // Add event listener for profilePlaceholder click
        const profilePlaceholder = document.getElementById('profilePlaceholder');
        if (profilePlaceholder) {
            profilePlaceholder.addEventListener('click', function () {
                document.getElementById('uploadedprofile').click();
            });
        }


        // Add these new functions for the discount modal
        function openDiscountModal() {
            const modal = document.getElementById('discountModal');
            const modalContent = document.getElementById('discountApplicationForm');

            // Show modal
            modal.classList.remove('hidden');
            // Force a reflow
            void modal.offsetWidth;
            // Add flex and animate in
            modal.classList.add('flex', 'opacity-100');

            // Animate content
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0', 'translate-y-4');
                modalContent.classList.add('scale-100', 'opacity-100', 'translate-y-0');
            }, 50);
        }

        function closeDiscountModal() {
            const modal = document.getElementById('discountModal');
            const modalContent = document.getElementById('discountApplicationForm');

            // Animate out
            modal.classList.remove('opacity-100');
            modalContent.classList.remove('scale-100', 'opacity-100', 'translate-y-0');
            modalContent.classList.add('scale-95', 'opacity-0', 'translate-y-4');

            // Hide modal after animation
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }

    </script>
    <div id="discountModal"
        class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 transition-all duration-300 ease-in-out opacity-0">
        <form id="discountApplicationForm"
            class="bg-slate-50 rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl transform transition-all duration-300 ease-in-out scale-95 opacity-0 translate-y-4">

            <div class="flex justify-between items-start mb-6">
                <div>
                    <h4 class="text-2xl font-bold text-gray-900">Senior Citizen / PWD Details</h4>
                    <p class="text-sm text-gray-600 mt-2">Please provide the required information to apply the discount
                    </p>
                </div>
                <button onclick="closeDiscountModal()"
                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200 p-1 hover:bg-gray-100 rounded-full">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Discount Type</label>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="discountType" value="PWD" class="form-radio h-4 w-4 text-black">
                            <span class="ml-2 text-gray-700">PWD</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="discountType" value="Senior Citizen"
                                class="form-radio h-4 w-4 text-black">
                            <span class="ml-2 text-gray-700">Senior Citizen</span>
                        </label>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">ID Holder's Full Name</label>
                    <input type="text" id="seniorPwdName" name="seniorPwdName"
                        placeholder="Enter the name as shown on the ID"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-black focus:ring-black h-11">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">ID Number</label>
                    <input type="text" id="seniorPwdId" name="seniorPwdId"
                        placeholder="Enter Senior Citizen/PWD ID Number"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-black focus:ring-black h-11">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Upload ID Photo</label>
                    <div class="relative">
                        <input type="file" id="seniorPwdIdPhoto" name="seniorPwdIdPhoto" accept="image/*"
                            class="hidden">
                        <label for="seniorPwdIdPhoto" id="fileLabel"
                            class="flex items-center justify-center w-full h-11 px-4 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-black transition-colors duration-200">
                            <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Click to upload ID photo
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Please upload a clear photo of the valid ID (Max 5MB)</p>
                </div>



                <button id="applyDiscountBtn" class="w-full h-11 bg-black text-white rounded-lg text-sm font-medium 
                               hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed
                               transform transition-all duration-200">
                    Apply Discount
                </button>
            </div>
        </form>
    </div>
</body>

</html>