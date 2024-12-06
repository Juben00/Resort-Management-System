<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HubVenue || Settings</title>
    <link rel="icon" href="../images/icoco_black_ico.png">
    <link rel="stylesheet" href="../output.css">
    <style>
        .settings-content {
            transition: all 0.3s ease-in-out;
            opacity: 1;
            transform: translateX(0);
        }

        .settings-content.hidden {
            opacity: 0;
            transform: translateX(20px);
            display: none;
        }

        .settings-tab {
            position: relative;
            transition: all 0.2s ease;
        }

        .settings-tab::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: #E73023;
            transition: width 0.2s ease;
        }

        .settings-tab.active::after {
            width: 100%;
        }

        /* Modern form styling */
        .form-input {
            @apply mt-1 block w-full rounded-lg border-gray-300 shadow-sm transition-all duration-200 focus:border-red-500 focus:ring-2 focus:ring-red-500 focus:ring-opacity-20;
        }

        .form-button {
            @apply bg-gradient-to-r from-[#E73023] to-[#B01B1B] text-white px-8 py-3 rounded-lg transition-all duration-200 hover:from-[#B01B1B] hover:to-[#E73023] focus:outline-none focus:ring-2 focus:ring-[#E73023] focus:ring-opacity-50 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-semibold text-center text-lg min-w-[200px] border border-[#E73023] flex items-center justify-center gap-2;
        }

        /* Add a secondary button style for alternative actions */
        .form-button-secondary {
            @apply bg-white text-red-500 px-8 py-3 rounded-lg transition-all duration-200 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-semibold text-center text-lg min-w-[200px] border-2 border-red-500;
        }

        /* Active tab styling */
        .settings-tab.active {
            @apply text-red-500 font-semibold;
        }

        /* Toggle switch colors */
        .peer:checked~.peer-checked\:bg-red-500 {
            background-color: #E73023;
        }

        /* Add these new styles for better visual appeal */
        .settings-container {
            @apply bg-gradient-to-br from-gray-50 to-gray-100;
        }

        .settings-card {
            @apply bg-white rounded-xl shadow-lg p-8 border border-gray-100;
        }

        .settings-tab {
            @apply font-medium;
        }

        .settings-tab.active {
            @apply text-[#E73023] font-semibold;
        }

        .settings-tab::after {
            background: #E73023;
        }

        .form-input {
            @apply mt-1 block w-full rounded-lg border-gray-300 shadow-sm transition-all duration-200 focus:border-[#E73023] focus:ring-2 focus:ring-[#E73023] focus:ring-opacity-20 hover:border-[#E73023];
        }

        .section-title {
            @apply text-2xl font-bold text-gray-800 mb-8 border-b-2 border-[#E73023] pb-2 inline-block;
        }

        .subsection-title {
            @apply text-lg font-semibold text-gray-700 border-b-2 border-gray-200 pb-2 flex items-center gap-2;
        }

        /* Update toggle switch colors */
        .peer:checked~.peer-checked\:bg-red-500 {
            background-color: #E73023;
        }

        /* Add hover effect to profile picture */
        .profile-picture-container:hover .profile-picture-overlay {
            opacity: 1;
            background: rgba(231, 48, 35, 0.7);
        }
    </style>
</head>

<body class="bg-gray-50">
    <?php
    if (isset($_SESSION['user'])) {
        require_once '../components/profile.nav.php';
    } else {
        require_once '../components/navbar.html';
    }
    ?>

    <main class="w-full px-4 py-8 settings-container min-h-screen">
        <div class="max-w-7xl mt-16 mx-auto">
            <h1 class="text-3xl font-bold mb-8 text-gray-800">Settings</h1>

            <!-- Settings Navigation -->
            <div class="flex mb-8 border-b border-gray-200">
                <button class="px-6 py-3 text-gray-600 hover:text-red-500 settings-tab active" data-tab="account">
                    Account Settings
                </button>
                <button class="px-6 py-3 text-gray-600 hover:text-red-500 settings-tab" data-tab="notifications">
                    Notifications
                </button>
                <button class="px-6 py-3 text-gray-600 hover:text-red-500 settings-tab" data-tab="privacy">
                    Privacy
                </button>
            </div>

            <!-- Settings Content -->
            <div class="bg-white rounded-xl shadow-lg p-8 settings-card">
                <!-- Account Settings -->
                <div id="account-settings" class="settings-content">
                    <div class="max-w-7xl mx-auto">
                        <h2 class="section-title">Account Settings</h2>

                        <!-- Profile Picture Section -->
                        <div class="mb-12 flex flex-col items-center">
                            <div class="relative group">
                                <div class="w-64 h-64 rounded-full overflow-hidden border-4 border-white shadow-lg">
                                    <img id="profileImage" src="../images/default-avatar.png" alt="Profile Picture"
                                        class="w-full h-full object-cover">
                                </div>
                                <label for="profilePicture"
                                    class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 text-white rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity duration-200">
                                    <span class="text-sm">Change Photo</span>
                                </label>
                                <input type="file" id="profilePicture" name="profilePicture" class="hidden"
                                    accept="image/*">
                            </div>
                            <p class="text-sm text-gray-500 mt-2">Click to upload new profile picture</p>
                        </div>

                        <form class="space-y-8">
                            <!-- Personal Information Section -->
                            <div class="space-y-6">
                                <h3 class="subsection-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#E73023]"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Personal Information
                                </h3>

                                <!-- Name Fields -->
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">First Name</label>
                                        <input type="text" name="firstname" class="form-input h-12 text-lg">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Last Name</label>
                                        <input type="text" name="lastname" class="form-input h-12 text-lg">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Middle Initial</label>
                                        <input type="text" name="middlename" class="form-input h-12 text-lg">
                                    </div>
                                </div>

                                <!-- Sex and Birthday -->
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Sex</label>
                                        <select name="sex" class="form-input h-12 text-lg">
                                            <option value="" disabled selected>Select sex</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Birthday</label>
                                        <input type="date" name="birthdate" class="form-input h-12 text-lg">
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
                                        <input type="text" name="address" class="form-input h-12 text-lg flex-grow"
                                            placeholder="Enter your address">
                                        <button type="button"
                                            class="px-4 py-2 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Email and Contact -->
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" name="email" class="form-input h-12 text-lg"
                                            placeholder="your.email@example.com">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                                        <input type="tel" name="contact" class="form-input h-12 text-lg"
                                            placeholder="09XX XXX XXXX">
                                    </div>
                                </div>
                            </div>

                            <div class="pt-6 flex justify-end">
                                <button type="submit" class="form-button">
                                    <span class="flex items-center justify-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h-2v5.586l-1.293-1.293z" />
                                        </svg>
                                        Save Changes
                                    </span>
                                </button>
                            </div>
                        </form>

                        <!-- Password Section -->
                        <div class="mt-16 pt-8 border-t border-gray-200">
                            <h3 class="text-lg font-medium mb-8 text-gray-800 border-b pb-2">Change Password</h3>
                            <form class="space-y-8">
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Current Password</label>
                                        <input type="password" name="current_password" class="form-input h-12 text-lg">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">New Password</label>
                                        <input type="password" name="new_password" class="form-input h-12 text-lg">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Confirm New
                                            Password</label>
                                        <input type="password" name="confirm_password" class="form-input h-12 text-lg">
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="form-button">
                                        <span class="flex items-center justify-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Update Password
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Notifications Settings -->
                <div id="notifications-settings" class="settings-content hidden">
                    <h2 class="text-2xl font-semibold mb-6 text-gray-800">Notification Preferences</h2>
                    <form class="space-y-6">
                        <div class="space-y-6">
                            <div
                                class="flex items-center justify-between p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-800">Email Notifications</h3>
                                    <p class="text-sm text-gray-600">Receive updates about your bookings via email</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500">
                                    </div>
                                </label>
                            </div>

                            <div
                                class="flex items-center justify-between p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-800">SMS Notifications</h3>
                                    <p class="text-sm text-gray-600">Get text messages for important updates</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500">
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

                <!-- Privacy Settings -->
                <div id="privacy-settings" class="settings-content hidden">
                    <h2 class="text-2xl font-semibold mb-6 text-gray-800">Privacy Settings</h2>
                    <form class="space-y-6">
                        <div class="space-y-6">
                            <div
                                class="flex items-center justify-between p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-800">Profile Visibility</h3>
                                    <p class="text-sm text-gray-600">Control who can see your profile information</p>
                                </div>
                                <select class="form-input w-auto">
                                    <option>Public</option>
                                    <option>Private</option>
                                    <option>Friends Only</option>
                                </select>
                            </div>

                            <div
                                class="flex items-center justify-between p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-800">Two-Factor Authentication</h3>
                                    <p class="text-sm text-gray-600">Add an extra layer of security to your account</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500">
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
        </div>
    </main>

    <?php include_once '../components/SignupForm.html'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('.settings-tab');
            const contents = document.querySelectorAll('.settings-content');

            function switchTab(clickedTab) {
                // Remove active class from all tabs
                tabs.forEach(t => t.classList.remove('active', 'text-blue-600'));

                // Add active class to clicked tab
                clickedTab.classList.add('active', 'text-blue-600');

                // Hide all content sections with transition
                contents.forEach(content => {
                    content.classList.add('hidden');
                    content.style.opacity = '0';
                    content.style.transform = 'translateX(20px)';
                });

                // Show selected content with transition
                const targetContent = document.getElementById(`${clickedTab.dataset.tab}-settings`);
                targetContent.classList.remove('hidden');

                // Trigger reflow
                void targetContent.offsetWidth;

                // Apply transitions
                targetContent.style.opacity = '1';
                targetContent.style.transform = 'translateX(0)';
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', () => switchTab(tab));
            });
        });

        function openModal() {
            const modal = document.getElementById('authModal');
            modal.style.display = 'flex';
            modal.style.opacity = '0';
            setTimeout(() => modal.style.opacity = '1', 10);
        }

        // Profile picture preview
        document.getElementById('profilePicture').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('profileImage').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>