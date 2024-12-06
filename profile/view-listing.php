<?php
require_once '../classes/venue.class.php';

session_start();
$venueObj = new Venue();
$venuePost = null;

$getParams = $_GET['id'];
$venueView = $venueObj->getSingleVenue($getParams);

?>
<!-- Venue Details View (Initially Hidden) -->
<div id="venueDetailsView" class="container mx-auto pt-20">
    <div class="mb-4">
        <a id="backToListing" class="flex items-center text-xs cursor-pointer text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Listings
        </a>
    </div>

    <div class="flex gap-6">
        <!-- Main Content -->
        <div class="flex-grow">
            <div class="bg-white text-neutral-900 rounded-lg shadow-sm">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h1 id="detailVenueName" class="text-gray-600 text-2xl view-mode">
                            <?php echo htmlspecialchars($venueView['venue_name']); ?>
                        </h1>
                        <input id="editVenueName" class="text-2xl font-bold w-full edit-mode hidden"
                            value="<?php echo htmlspecialchars(trim($venueView['venue_name'])); ?>">
                        <button onclick="toggleEditMode()"
                            class="text-xs px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            Edit Details
                        </button>
                    </div>

                    <!-- Image Gallery -->
                    <div class="mb-6 grid grid-cols-2 gap-4 relative">
                        <div class="col-span-2">
                            <?php if (!empty($venueView['image_urls'])): ?>
                                <img src="./<?= htmlspecialchars($venueView['image_urls'][0]) ?>" alt="Venue Image"
                                    class="w-full h-96 object-cover rounded-lg">
                            <?php else: ?>
                                <img src="default-image.jpg" alt="Default Venue Image"
                                    class="bg-slate-50 w-full h-96 object-cover rounded-lg">
                            <?php endif; ?>
                        </div>
                        <div class="grid grid-cols-3 col-span-2 gap-2">
                            <?php if (!empty($venueView['image_urls']) && count($venueView['image_urls']) > 1): ?>
                                <img src="./<?= htmlspecialchars($venueView['image_urls'][1]) ?>" alt="Venue Image"
                                    class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-75">
                            <?php else: ?>
                                <div
                                    class="bg-slate-50 w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-75 border flex items-center justify-center">
                                    <p class="text-center">No more image to show</p>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($venueView['image_urls']) && count($venueView['image_urls']) > 2): ?>
                                <img src="./<?= htmlspecialchars($venueView['image_urls'][2]) ?>" alt="Venue Image"
                                    class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-75">
                            <?php else: ?>
                                <div
                                    class="bg-slate-50 w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-75 border flex items-center justify-center">
                                    <p class="text-center">No more image to show</p>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($venueView['image_urls']) && count($venueView['image_urls']) > 3): ?>
                                <img src="./<?= htmlspecialchars($venueView['image_urls'][3]) ?>" alt="Venue Image"
                                    class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-75">
                            <?php else: ?>
                                <div
                                    class="bg-slate-50 w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-75 border flex items-center justify-center">
                                    <p class="text-center">No more image to show</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <button
                            class="absolute text-xs border-2 border-gray-500 bottom-4 right-4 bg-white px-4 py-2 rounded-lg font-semibold hover:bg-gray-100">
                            Show all photos
                        </button>
                    </div>

                    <div class="flex gap-6 w-full ">
                        <div class=" w-full">
                            <!-- Location -->
                            <div class="mb-6  w-full">
                                <h3 class="text-lg font-semibold mb-2">Location</h3>
                                <p id="detailVenueLocation" class="text-gray-600 view-mode">
                                    <?php echo htmlspecialchars(trim($venueView['venue_location'])); ?>
                                </p>
                                <input type="text" id="editVenueLocation"
                                    class="form-input w-full rounded-md edit-mode hidden"
                                    value="<?php echo htmlspecialchars(trim($venueView['venue_location'])); ?>">
                            </div>

                            <!-- Description -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-2">Description</h3>
                                <p id="detailVenueDescription" class="text-gray-600 view-mode">
                                    <?php echo trim(htmlspecialchars($venueView['venue_description'])); ?>
                                </p>
                                <textarea id="editVenueDescription"
                                    class="form-textarea w-full rounded-md edit-mode hidden"
                                    rows="4"><?php echo trim(htmlspecialchars($venueView['venue_description'])); ?></textarea>
                            </div>

                            <!-- Capacity -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-2">Capacity</h3>
                                <p id="detailVenueCapacity" class="text-gray-600 view-mode">
                                    <?php echo trim(htmlspecialchars($venueView['capacity'])); ?> guests
                                </p>
                                <input type="number" id="editVenueCapacity"
                                    class="form-input w-full rounded-md edit-mode hidden"
                                    value=<?php echo trim(htmlspecialchars($venueView['capacity'])); ?>>
                            </div>

                            <!-- Amenities -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-2">What this place offers</h3>
                                <?php if (!empty($venueView['amenities'])): ?>
                                    <?php
                                    $amenities = json_decode($venueView['amenities'], true);
                                    if ($amenities):
                                        ?>
                                        <ul class="list-disc pl-5 space-y-1">
                                            <?php foreach ($amenities as $amenity): ?>
                                                <li class="text-sm text-gray-800 leading-tight">
                                                    <?= htmlspecialchars($amenity) ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p class="text-sm text-gray-500">No amenities available</p>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <p class="text-sm text-gray-500">No amenities available</p>
                                <?php endif; ?>
                                <div id="editVenueAmenities" class="edit-mode hidden space-y-2">
                                    <div id="amenitiesList"></div>
                                    <button onclick="addAmenityField()"
                                        class="text-blue-600 hover:text-blue-800 text-sm">+ Add amenity</button>
                                </div>
                            </div>

                            <!-- Venue Rules -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-2">Venue Rules</h3>
                                <?php if (!empty($venueView['rules'])): ?>
                                    <?php
                                    $rules = json_decode($venueView['rules'], true);
                                    if ($rules):
                                        ?>
                                        <ul class="list-disc pl-5 space-y-1">
                                            <?php foreach ($rules as $rule): ?>
                                                <li class="text-sm text-gray-800 leading-tight">
                                                    <?= htmlspecialchars($rule) ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p class="text-sm text-gray-500">No Rules Stated</p>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <p class="text-sm text-gray-500">No Rules Stated</p>
                                <?php endif; ?>
                                <div id="editVenueRules" class="edit-mode hidden space-y-2">
                                    <div id="rulesList"></div>
                                    <button onclick="addRuleField()" class="text-blue-600 hover:text-blue-800 text-sm">+
                                        Add rule</button>
                                </div>
                            </div>

                            <!-- Cancellation Policy -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-2">Cancellation Policy</h3>
                                <div class="view-mode">
                                    <div id="detailCancellationPolicy" class="text-gray-600"></div>
                                </div>
                                <div class="edit-mode hidden">
                                    <select id="editCancellationPolicy" class="form-select rounded-md w-full mb-2">
                                        <option value="flexible">Flexible - Full refund 24 hours prior</option>
                                        <option value="moderate">Moderate - Full refund 5 days prior</option>
                                        <option value="strict">Strict - 50% refund 7 days prior</option>
                                        <option value="custom">Custom Policy</option>
                                    </select>
                                    <textarea id="editCustomPolicy" class="form-textarea w-full rounded-md mt-2 hidden"
                                        rows="4" placeholder="Enter your custom cancellation policy..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Ratings & Reviews Section - Moved to bottom -->
            <div class="bg-white rounded-lg shadow-sm mt-6 p-6">
                <h3 class="text-2xl font-bold mb-6">Ratings & Reviews</h3>

                <!-- Rating Summary -->
                <div class="bg-gray-50 p-6 rounded-lg mb-6">
                    <div class="flex items-center gap-8">
                        <!-- Overall Rating -->
                        <div class="text-center">
                            <div class="text-5xl font-bold mb-2" id="averageRating">4.8</div>
                            <div class="flex items-center justify-center text-yellow-400 mb-1">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <!-- Repeat stars for 5 total -->
                            </div>
                            <p class="text-sm text-gray-500"><span id="totalReviews">128</span> reviews</p>
                        </div>

                        <!-- Rating Breakdown -->
                        <div class="flex-grow">
                            <div class="space-y-2">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <div class="flex items-center gap-2">
                                        <span class="w-12 text-sm text-gray-600"><?php echo $i; ?> stars</span>
                                        <div class="flex-grow bg-gray-200 rounded-full h-2">
                                            <div class="bg-yellow-400 rounded-full h-2"
                                                style="width: <?php echo rand(10, 100); ?>%"></div>
                                        </div>
                                        <span
                                            class="w-12 text-sm text-gray-600 text-right"><?php echo rand(0, 100); ?></span>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews List -->
                <div class="space-y-6" id="reviewsList">
                    <!-- Sample Review -->
                    <div class="border-b pb-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-gray-200 flex-shrink-0"></div>
                            <div class="flex-grow">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h4 class="font-medium">Sarah Johnson</h4>
                                        <div class="flex text-yellow-400">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <!-- Repeat for 5 stars -->
                                        </div>
                                    </div>
                                    <span class="text-sm text-gray-500">2 weeks ago</span>
                                </div>
                                <p class="text-gray-600">Amazing venue! Perfect for our wedding reception. The
                                    staff was very accommodating and professional. The place was exactly as
                                    described and the amenities were all in great condition.</p>
                            </div>
                        </div>
                    </div>

                    <!-- More reviews can be dynamically added here -->
                </div>

                <!-- Pagination -->
                <div class="mt-6 flex justify-center">
                    <nav class="flex items-center gap-2">
                        <button class="px-3 py-1 rounded-lg hover:bg-gray-100">Previous</button>
                        <button class="px-3 py-1 rounded-lg bg-gray-900 text-white">1</button>
                        <button class="px-3 py-1 rounded-lg hover:bg-gray-100">2</button>
                        <button class="px-3 py-1 rounded-lg hover:bg-gray-100">3</button>
                        <button class="px-3 py-1 rounded-lg hover:bg-gray-100">Next</button>
                    </nav>
                </div>
            </div>

            <!-- After the Ratings & Reviews section, add this new Calendar Pricing section -->
            <div class="bg-white rounded-lg shadow-sm mt-6 p-6">
                <h3 class="text-2xl font-bold mb-6">Calendar & Pricing</h3>

                <!-- Calendar Header -->
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center space-x-4">
                        <button class="p-2 hover:bg-gray-100 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <h4 class="text-lg font-semibold">October 2024</h4>
                        <button class="p-2 hover:bg-gray-100 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex items-center space-x-2">
                        <button class="px-3 py-1 text-sm border rounded-lg hover:bg-gray-50">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </button>
                        <button class="px-3 py-1 text-sm border rounded-lg hover:bg-gray-50">View</button>
                    </div>
                </div>

                <!-- Calendar Grid -->
                <div class="border rounded-lg">
                    <!-- Calendar Header -->
                    <div class="grid grid-cols-7 text-sm font-medium text-gray-500 border-b">
                        <div class="p-2 text-center">Su</div>
                        <div class="p-2 text-center">Mo</div>
                        <div class="p-2 text-center">Tu</div>
                        <div class="p-2 text-center">We</div>
                        <div class="p-2 text-center">Th</div>
                        <div class="p-2 text-center">Fr</div>
                        <div class="p-2 text-center">Sa</div>
                    </div>

                    <!-- Calendar Days -->
                    <div class="grid grid-cols-7">
                        <?php
                        // Previous month days (greyed out)
                        for ($i = 0; $i < 0; $i++) {
                            echo '<div class="p-2 border-b border-r text-gray-400"></div>';
                        }

                        // Current month days
                        for ($day = 1; $day <= 31; $day++) {
                            $isToday = $day === 5; // Example: 5th is today
                            $hasPrice = true; // Example: All days have prices
                        
                            echo '<div class="relative p-2 border-b border-r hover:bg-gray-50 cursor-pointer">';
                            echo '<div class="text-sm ' . ($isToday ? 'font-bold' : '') . '">' . $day . '</div>';
                            if ($hasPrice) {
                                echo '<div class="text-xs text-gray-600"> ₱' . $venueView['price'] . '</div>';
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>

                <!-- Settings Panel -->
                <div class="mt-6 border rounded-lg p-4">
                    <h4 class="text-lg font-semibold mb-4">Settings</h4>
                    <p class="text-sm text-gray-600 mb-4">These apply to all nights, unless you customize them
                        by date.</p>

                    <!-- Pricing Tab -->
                    <div class="border-b pb-4 mb-4">
                        <div class="flex justify-between items-center">
                            <span class="font-medium">Base price</span>
                            <span class="text-sm text-gray-500">PHP</span>
                        </div>
                        <div class="mt-2">
                            <label class="block text-sm text-gray-600 mb-1">Per night</label>
                            <p>₱ <?php echo htmlspecialchars($venueView['price']) ?></p>
                        </div>
                    </div>

                    <!-- Custom Weekend Price -->
                    <div class="border-b pb-4 mb-4">
                        <div class="flex justify-between items-center">
                            <span class="font-medium">Custom weekend price</span>
                            <button class="text-sm text-blue-600 hover:text-blue-800">Add</button>
                        </div>
                    </div>

                    <!-- Smart Pricing Toggle -->
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="font-medium block">Smart Pricing</span>
                            <span class="text-sm text-gray-600">Adjust your pricing to attract more
                                guests.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="w-96">
            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-24">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4">Venue Settings</h3>

                    <!-- Price Setting -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price per day</label>
                        <div class="flex items-center">
                            <span class="text-gray-500 mr-2">₱</span>
                            <input type="number" id="venuePrice" class="form-input rounded-md w-full"
                                value="<?php echo htmlspecialchars($venueView['price']) ?>">
                        </div>
                    </div>

                    <!-- Down Payment Options -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Down Payment
                            Required</label>
                        <select class="form-select rounded-md w-full">
                            <option value="30">30% of total amount</option>
                            <option value="40">40% of total amount</option>
                            <option value="100">Full payment required</option>
                        </select>
                    </div>

                    <!-- Discounts -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Discounts</label>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <input type="number" placeholder="%" class="form-input rounded-md w-20">
                                <input type="text" placeholder="Discount name" class="form-input rounded-md flex-grow">
                                <button class="p-2 text-red-500 hover:bg-red-50 rounded-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <button class="text-sm text-blue-600 hover:text-blue-800">+ Add new
                                discount</button>
                        </div>
                    </div>

                    <!-- Save Changes Button -->
                    <button onclick="saveChanges()"
                        class="w-full bg-black text-white py-2 px-4 rounded-lg hover:bg-gray-800 mt-4">
                        Save Changes
                    </button>
                </div>

                <!-- Quick Stats -->
                <div class="border-t pt-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Quick Stats</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-600">Total Bookings</p>
                            <p class="text-xl font-semibold">24</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-600">This Month</p>
                            <p class="text-xl font-semibold">3</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-600">Revenue</p>
                            <p class="text-xl font-semibold">₱360k</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-600">Rating</p>
                            <p class="text-xl font-semibold">4.8/5</p>
                        </div>
                    </div>
                </div>

                <!-- New Reservation History Section -->
                <div class="border-t pt-6 mt-6">
                    <h4 class="text-lg font-semibold mb-4">Recent Reservations</h4>
                    <div class="space-y-4">
                        <!-- Sample Reservation Items -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-medium">Wedding Reception</p>
                                    <p class="text-sm text-gray-600">Maria Santos</p>
                                </div>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Confirmed
                                </span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <p>Dec 15, 2024</p>
                                <p>₱15,000</p>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-medium">Birthday Party</p>
                                    <p class="text-sm text-gray-600">John Cruz</p>
                                </div>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <p>Dec 20, 2024</p>
                                <p>₱12,000</p>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-medium">Corporate Event</p>
                                    <p class="text-sm text-gray-600">Tech Corp.</p>
                                </div>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Completed
                                </span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <p>Nov 30, 2024</p>
                                <p>₱20,000</p>
                            </div>
                        </div>
                    </div>

                    <!-- View All Reservations Link -->
                    <div class="mt-4 text-center">
                        <a href="calendar.php" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View All Reservations →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showVenueDetails(venue) {
        // Hide listings view and show details view
        document.getElementById('listingsView').classList.add('hidden');
        document.getElementById('venueDetailsView').classList.remove('hidden');

        // Populate venue details
        document.getElementById('detailVenueName').textContent = venue.name;
        document.getElementById('detailVenueLocation').textContent = venue.location;
        document.getElementById('detailVenueDescription').textContent = venue.description || 'No description provided yet';
        document.getElementById('detailVenueCapacity').textContent = venue.capacity ? `${venue.capacity} guests` : 'Capacity not specified';
        document.getElementById('detailVenuePrice').textContent = venue.price;

        // Populate amenities with descriptions
        const amenitiesList = document.getElementById('detailVenueAmenities');
        amenitiesList.innerHTML = '';
        if (Array.isArray(venue.amenities) && venue.amenities.length === 0) {
            amenitiesList.innerHTML = '<p class="text-gray-500 italic">No amenities listed yet</p>';
        } else if (typeof venue.amenities === 'object') {
            Object.entries(venue.amenities).forEach(([amenity, details]) => {
                const li = document.createElement('li');
                li.className = 'flex items-center gap-2 mb-3';
                if (details && details.description) {
                    // Detailed amenity format
                    li.innerHTML = `
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="font-medium">${amenity}</span>
                        <p class="text-sm text-gray-500">${details.description}</p>
                    </div>
                `;
                } else {
                    // Simple amenity format
                    li.innerHTML = `
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="font-medium">${amenity}</span>
                    </div>
                `;
                }
                amenitiesList.appendChild(li);
            });
        }

        // Populate rules with sections
        const rulesList = document.getElementById('detailVenueRules');
        rulesList.innerHTML = '';
        if (Array.isArray(venue.rules) && venue.rules.length === 0) {
            rulesList.innerHTML = `
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-yellow-700 font-medium">Required: Please add venue rules</p>
                </div>
                <p class="text-yellow-600 text-sm mt-2">Specify guidelines and restrictions for venue use</p>
            </div>
        `;
        } else {
            // Existing rules display code...
        }

        // Populate cancellation policy with detailed breakdown
        const policyDiv = document.getElementById('detailCancellationPolicy');
        if (!venue.cancellation_policy) {
            policyDiv.innerHTML = `
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-yellow-700 font-medium">Required: Please add cancellation policy</p>
            </div>
                <p class="text-yellow-600 text-sm mt-2">Define your booking cancellation terms and conditions</p>
            </div>
        `;
        } else {
            // Existing cancellation policy display code...
        }

        // Populate owner information
        const ownerDiv = document.getElementById('detailVenueOwner');
        ownerDiv.innerHTML = `
        <p><strong>Name:</strong> ${venue.owner.first_name} ${venue.owner.last_name}</p>
        <p><strong>Contact:</strong> ${venue.owner.contact}</p>
        <p><strong>Email:</strong> ${venue.owner.email}</p>
    `;

        // Show status badge
        const statusDiv = document.getElementById('detailVenueStatus');
        let statusColor = '';
        switch (venue.status) {
            case 'Approved':
                statusColor = 'bg-green-500 border-green-600';
                break;
            case 'Pending':
                statusColor = 'bg-yellow-500 border-yellow-600';
                break;
            case 'Declined':
                statusColor = 'bg-red-500 border-red-600';
                break;
        }
        statusDiv.innerHTML = `
        <div class="inline-block border ${statusColor} text-white px-3 py-1 rounded-full">
            ${venue.status}
        </div>
    `;

        // Set edit mode fields initial values
        document.getElementById('editVenueLocation').value = venue.location;
        document.getElementById('editVenueDescription').value = venue.description;
        document.getElementById('editVenueCapacity').value = venue.capacity;

        // Reset to view mode when showing details
        const viewElements = document.querySelectorAll('.view-mode');
        const editElements = document.querySelectorAll('.edit-mode');
        viewElements.forEach(el => el.classList.remove('hidden'));
        editElements.forEach(el => el.classList.add('hidden'));

        // Handle missing images
        const mainImage = document.getElementById('mainImage');
        mainImage.src = venue.image_urls && venue.image_urls.length > 0
            ? venue.image_urls[0]
            : '../images/icoco_black_ico.png';
        mainImage.alt = venue.name || 'Venue image';
    }

    function toggleEditMode() {
        const viewElements = document.querySelectorAll('.view-mode');
        const editElements = document.querySelectorAll('.edit-mode');

        viewElements.forEach(el => el.classList.toggle('hidden'));
        editElements.forEach(el => el.classList.toggle('hidden'));

        // Populate edit fields with current values
        if (!editElements[0].classList.contains('hidden')) {
            document.getElementById('editVenueName').value = document.getElementById('detailVenueName').textContent.trim();
            document.getElementById('editVenueLocation').value = document.getElementById('detailVenueLocation').textContent.trim();
            document.getElementById('editVenueDescription').value = document.getElementById('detailVenueDescription').textContent.trim();
            document.getElementById('editVenueCapacity').value = document.getElementById('detailVenueCapacity').textContent.split(' ')[0].trim();
            populateAmenitiesEdit();

            // Populate rules edit fields
            const currentRules = Array.from(document.getElementById('detailVenueRules').children)
                .map(li => li.textContent.trim());
            const rulesList = document.getElementById('rulesList');
            rulesList.innerHTML = '';
            currentRules.forEach(rule => {
                addRuleField(rule);
            });

            // Handle cancellation policy edit mode
            document.getElementById('editCancellationPolicy').addEventListener('change', function () {
                const customPolicyField = document.getElementById('editCustomPolicy');
                if (this.value === 'custom') {
                    customPolicyField.classList.remove('hidden');
                } else {
                    customPolicyField.classList.add('hidden');
                }
            });
        }
    }

    function saveChanges() {
        // Collect values from input fields
        const venueName = document.getElementById('detailVenueName').value;
        const venueLocation = document.getElementById('editVenueLocation').value;
        const venueDescription = document.getElementById('editVenueDescription').value;
        const venueCapacity = document.getElementById('editVenueCapacity').value;
        // Collect other values as needed

        // Send data to server to save changes
        // Example using fetch API
        fetch('save-venue-details.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                venue_name: venueName,
                venue_location: venueLocation,
                venue_description: venueDescription,
                venue_capacity: venueCapacity,
                // Include other fields as needed
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update view mode with new values
                    document.getElementById('detailVenueLocation').textContent = venueLocation;
                    document.getElementById('detailVenueDescription').textContent = venueDescription;
                    document.getElementById('detailVenueCapacity').textContent = `${venueCapacity} guests`;
                    // Update other fields as needed

                    // Switch back to view mode
                    toggleEditMode();
                } else {
                    alert('Failed to save changes');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to save changes');
            });
    }

    function populateAmenitiesEdit() {
        const amenitiesList = document.getElementById('amenitiesList');
        amenitiesList.innerHTML = '';

        const currentAmenities = Array.from(document.getElementById('detailVenueAmenities').children)
            .map(li => li.textContent);

        currentAmenities.forEach(amenity => {
            addAmenityField(amenity);
        });
    }

    function addAmenityField(value = '') {
        const amenitiesList = document.getElementById('amenitiesList');
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2';
        div.innerHTML = `
        <input type="text" class="form-input rounded-md flex-grow" value="${value}">
        <button onclick="this.parentElement.remove()" class="p-2 text-red-500 hover:bg-red-50 rounded-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    `;
        amenitiesList.appendChild(div);
    }

    function addRuleField(value = '') {
        const rulesList = document.getElementById('rulesList');
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2';
        div.innerHTML = `
        <input type="text" class="form-input rounded-md flex-grow" value="${value}" placeholder="Enter venue rule">
        <button onclick="this.parentElement.remove()" class="p-2 text-red-500 hover:bg-red-50 rounded-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    `;
        rulesList.appendChild(div);
    }

    // Add this to your existing script section
    function initializeCalendar() {
        // Get current date
        const date = new Date();
        const currentMonth = date.getMonth();
        const currentYear = date.getFullYear();

        // Update calendar header
        updateCalendarHeader(currentMonth, currentYear);

        // Generate calendar days
        generateCalendarDays(currentMonth, currentYear);
    }

    function updateCalendarHeader(month, year) {
        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        document.querySelector('.calendar-header h4').textContent = `${monthNames[month]} ${year}`;
    }

    function generateCalendarDays(month, year) {
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        let calendarHTML = '';

        // Previous month days
        for (let i = 0; i < firstDay; i++) {
            calendarHTML += `<div class="p-2 border-b border-r text-gray-400"></div>`;
        }

        // Current month days
        for (let day = 1; day <= daysInMonth; day++) {
            const isToday = day === new Date().getDate() &&
                month === new Date().getMonth() &&
                year === new Date().getFullYear();

            calendarHTML += `
            <div class="relative p-2 border-b border-r hover:bg-gray-50 cursor-pointer" 
                 onclick="editDayPrice(${year}, ${month}, ${day})">
                <div class="text-sm ${isToday ? 'font-bold' : ''}">${day}</div>
                <div class="text-xs text-gray-600">₱2,341</div>
            </div>
        `;
        }

        document.querySelector('.calendar-days').innerHTML = calendarHTML;
    }

    function editDayPrice(year, month, day) {
        // Show a modal or form to edit the price for this specific day
        const date = new Date(year, month, day);
        const formattedDate = date.toLocaleDateString();

        // You can implement your own modal here
        const newPrice = prompt(`Enter new price for ${formattedDate}:`);
        if (newPrice && !isNaN(newPrice)) {
            // Update the price in your database
            // Then refresh the calendar display
            console.log(`Updated price for ${formattedDate} to ₱${newPrice}`);
        }
    }

    // Initialize calendar when page loads
    document.addEventListener('DOMContentLoaded', initializeCalendar);
</script>