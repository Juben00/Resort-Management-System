<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

require_once './classes/account.class.php';

$accountObj = new Account();

$user = $accountObj->getUser($_SESSION['user']['id']);

$appliedHost = $accountObj->HostApplicationStats($_SESSION['user']['id'], 1);
$isHost = $accountObj->HostApplicationStats($_SESSION['user']['id'], 2);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Host Account Application</title>
    <link rel="icon" href="./images/icoco_black_ico.png">
    <link rel="stylesheet" href="./output.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

    <!-- Add custom styles -->
    <style>
        /* Input field transitions and styles */
        select,
        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="url"],
        textarea {
            transition: all 0.3s ease;
            border: 2px solid transparent;
            background-color: #f3f4f6;
            padding: 0.5rem 0.7rem;
            font-size: 1.1rem;
            border-radius: 8px;
        }

        select:focus,
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="date"]:focus,
        input[type="url"]:focus,
        textarea:focus {
            outline: none;
            border-color: transparent;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            transform: translateY(-2px);
            background-color: white;
        }


        /* Label styles */
        .form-label {
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
            transition: all 0.3s ease;
        }

        /* Description text styles */
        .step p {
            font-size: 1.1rem;
            line-height: 1.6;
        }


        /* File input styling */
        input[type="file"] {
            padding: 1rem;
            font-size: 1.1rem;
        }

        /* Input container spacing */
        .space-y-4>div {
            margin-bottom: 2rem;
        }

        /* Optional: Add hover effect */
        input:hover,
        textarea:hover {
            background-color: #e5e7eb;
        }

        /* Map container styles */
        .map-container {
            height: 300px;
            width: 100%;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            overflow: hidden;
        }

        /* Address confirmation modal styles */
        .address-confirm-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .address-confirm-content {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .address-fields {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 1rem 0;
        }

        .address-field {
            margin-bottom: 1rem;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col bg-slate-50">
    <!-- Top Navigation -->
    <?php
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

    <!-- Main Content -->
    <?php
    if ($appliedHost) {
        echo '<div class="fixed inset-0 flex items-center justify-center bg-black/5 backdrop-blur-sm">
            <div class="bg-slate-50 p-8 rounded-xl shadow-xl max-w-md w-full mx-4 border-l-4 border-blue-500 transform transition-all">
                <div class="flex items-center space-x-4">
                    <div class="p-2 bg-blue-50 rounded-full">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Application Under Review</h3>
                        <p class="text-gray-600 mt-1">Please allow some time for the admin to process your request.</p>
                    </div>
                </div>
            </div>
        </div>';
    } else if ($isHost) {
        echo '<div class="fixed inset-0 flex items-center justify-center bg-black/5 backdrop-blur-sm">
            <div class="bg-slate-50 p-8 rounded-xl shadow-xl max-w-md w-full mx-4 border-l-4 border-green-500 transform transition-all">
                <div class="flex items-center space-x-4">
                    <div class="p-2 bg-green-50 rounded-full">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Account Activated!</h3>
                        <p class="text-gray-600 mt-1">You may start posting and managing your listings.</p>
                    </div>
                </div>
            </div>
        </div>';
    } else {
        ?>
            <main class="flex-1 mt-20 container mx-auto px-4 py-8 relative ">

                <h1 class="text-2xl md:text-4xl font-bold mb-4 text-center">Host Account Application</h1>
                <form id="hostApplicationForm" method="POST" class="max-w-2xl mx-auto">
                    <!-- Step 1: Personal Information -->
                    <div id="step1" class="step">
                        <h1 class="text-2xl font-bold mb-2">Personal Details</h1>
                        <p class="text-gray-600 mb-6">Let's start with your personal information.</p>
                        <div class="flex flex-col gap-4">

                            <?php
                            foreach ($user as $index): ?>
                                <div>
                                    <label for="fullName" class="block text-sm font-medium text-gray-700">Full Name</label>
                                    <input type="text" id="fullName" name="fullName" placeholder="Last Name, First Name M.I."
                                        required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                        value="<?php echo htmlspecialchars($index['lastname'] . ', ' . $index['firstname'] . ' ' . $index['middlename']) . '.'; ?>"
                                        readonly>
                                </div>
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                    <span class="flex items-center space-x-2">
                                        <input type="text" id="address" name="address" placeholder="Where do you live?" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                            value="<?php echo htmlspecialchars($index['address']); ?>" readonly>
                                        <button class="maps-button border bg-gray-50 hover:bg-gray-100 duration-150 p-3 rounded-md">
                                            <svg height="24px" width="24px" version="1.1" id="Layer_1"
                                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                viewBox="0 0 512 512" xml:space="preserve" fill="#bcc2bc" stroke="#bcc2bc">
                                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                                <g id="SVGRepo_iconCarrier">
                                                    <polygon points="154.64,420.096 154.64,59.496 0,134 0,512 "></polygon>
                                                    <polygon style="fill:#d3d5de;"
                                                        points="309.288,146.464 309.288,504.472 154.64,420.096 154.64,59.496 ">
                                                    </polygon>
                                                    <polygon
                                                        points="463.928,50.152 309.288,146.464 309.288,504.472 463.928,415.68 ">
                                                    </polygon>
                                                    <path style="fill:#e73023;"
                                                        d="M414.512,281.656l-11.92-15.744c-8.8-11.472-85.6-113.984-85.6-165.048 C317.032,39.592,355.272,0,414.512,0S512,39.592,512,100.864c0,50.992-76.8,153.504-85.488,165.048L414.512,281.656z">
                                                    </path>
                                                    <circle style="fill:#FFFFFF;" cx="414.512" cy="101.536" r="31.568"></circle>
                                                </g>
                                            </svg>
                                        </button>
                                    </span>
                                </div>
                                <div>
                                    <label for="birthdate" class="block text-sm font-medium text-gray-700">Birthdate</label>
                                    <input type="date" id="hostBd" name="birthdate" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                        value="<?php echo htmlspecialchars($index['birthdate']); ?>" readonly>
                                </div>
                        <?php endforeach; ?>

                        </div>
                    </div>

                    <!-- Step 2: Venue Details -->
                    <div id="step2" class="step hidden">
                        <h1 class="text-2xl font-bold mb-2">Identification card number 1 details</h1>
                        <p class="text-gray-600 mb-6">Identify your card and upload an image.</p>
                        <div class="space-y-4">
                            <div>
                                <label for="idType" class="block text-sm font-medium text-gray-700">Identification Card</label>
                                <select name="idType" id="idType"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-2 py-3">
                                    <option value="" disabled selected>Please choose Identification Card Type</option>
                                    <option value="Philippine Passport">Philippine Passport</option>
                                    <option value="UMID Card">UMID Card</option>
                                    <option value="National ID">National ID</option>
                                    <option value="Driver's License">Driver's License</option>
                                </select>
                            </div>
                            <div>
                                <label for="idImage">Take a picture of your ID card <span
                                        class="text-xs font-thin text-gray-500">*Upload only
                                        1 image of
                                        your ID
                                        (front)</span></label>
                                <input type="file" name="idImage" id="idImage"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-2 py-3">
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Online Presence -->
                    <div id="step3" class="step hidden">
                        <h1 class="text-2xl font-bold mb-2">Identification card number 2 details</h1>
                        <p class="text-gray-600 mb-6">Identify your card and upload an image.</p>
                        <div class="space-y-4">
                            <div>
                                <label for="idType" class="block text-sm font-medium text-gray-700">Identification Card</label>
                                <select name="idType2" id="idType2"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-2 py-3">
                                    <option value="" disabled selected>Please choose Identification Card Type</option>
                                    <option value="Philippine Passport">Philippine Passport</option>
                                    <option value="UMID Card">UMID Card</option>
                                    <option value="National ID">National ID</option>
                                    <option value="Driver's License">Driver's License</option>
                                </select>
                            </div>

                            <div>
                                <label for="idImage">Take a picture of your ID card <span
                                        class="text-xs font-thin text-gray-500">*Upload only
                                        1 image of
                                        your ID
                                        (front)</span></label>
                                <input type="file" name="idImage2" id="idImage2"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-2 py-3">
                            </div>
                        </div>
                    </div>


                    <!-- Step 4: Review and Submit -->
                    <div id="step5" class="step hidden">
                        <h1 class="text-2xl font-bold mb-2">Review and Submit</h1>
                        <p class="text-gray-600 mb-6">Please review your information before submitting.</p>
                        <div id="reviewContent" class="space-y-2"></div>
                        <button type="submit" id="sform" class="hidden">Submit Form</button>
                    </div>

                </form>
                <div id="openstreetmapplaceholder"></div>
            </main>
            <!-- Bottom Navigation -->
            <footer class="bg-slate-50 border-t">
                <div class="container mx-auto px-4 h-20 flex items-center justify-between">
                    <button id="prevBtn" class="text-gray-900 font-medium">Back</button>
                    <div class="flex-1 flex justify-center">
                        <div class="w-1/2 relative">
                            <div class="h-1 bg-gray-200 rounded-full">
                                <div id="progressBarFill" class="h-1 bg-gray-900 rounded-full transition-all duration-300"
                                    style="width: 20%"></div>
                            </div>
                        </div>
                    </div>
                    <button id="nextBtn" class="bg-black text-white px-6 py-2 rounded-md">Next</button>
                </div>
            </footer>

    <?php } ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const steps = document.querySelectorAll('.step');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const progressBarFill = document.getElementById('progressBarFill');
            const reviewContent = document.getElementById('reviewContent');
            let currentStep = 0;

            // Show specific step based on current index
            function showStep(stepIndex) {
                steps.forEach((step, index) => {
                    step.classList.toggle('hidden', index !== stepIndex);
                });
                prevBtn.style.display = stepIndex === 0 ? 'none' : 'block';
                nextBtn.textContent = stepIndex === steps.length - 1 ? 'Submit' : 'Next';
                progressBarFill.style.width = `${((stepIndex + 1) / steps.length) * 100}%`;
            }

            // Update review content with user inputs
            function updateReviewContent() {
                const fullName = document.getElementById('fullName').value;
                const address = document.getElementById('address').value;
                const bd = document.getElementById('hostBd').value;
                const idType1 = document.getElementById('idType').value;
                const idType2 = document.getElementById('idType2').value;

                reviewContent.innerHTML = `
                <p><strong>Full Name:</strong> ${fullName || 'Empty'}</p>
                <p><strong>Address:</strong> ${address || 'Empty'}</p>
                <p><strong>Birthdate:</strong> ${bd || 'Empty'}</p>
                <p><strong>Identification Card 1 Type:</strong> ${idType1 || 'Empty'}</p>
                <p><strong>Identification Card 2 Type:</strong> ${idType2 || 'Empty'} </p>
            `;
            }

            // Validate required fields in the current step
            function validateStep() {
                const currentFields = steps[currentStep].querySelectorAll('input, select');
                let isValid = true;
                currentFields.forEach(field => {
                    if (field.hasAttribute('required') && !field.value) {
                        isValid = false;
                        field.classList.add('border-red-500');
                    } else {
                        field.classList.remove('border-red-500');
                    }
                });
                return isValid;
            }


            // Handle the Next button click
            nextBtn.addEventListener('click', function () {
                if (validateStep()) {
                    if (currentStep < steps.length - 1) {
                        if (currentStep === steps.length - 2) {
                            updateReviewContent();
                        }
                        currentStep++;
                        showStep(currentStep);
                    } else {
                        document.getElementById('sform').click();
                    }
                } else {
                    showModal('Please fill in all required fields before proceeding.', undefined, "icoco_black_ico.png");
                }
            });

            // Handle the Previous button click
            prevBtn.addEventListener('click', function () {
                if (currentStep > 0) {
                    currentStep--;
                    showStep(currentStep);
                }
            });

            // Initialize the first step
            showStep(currentStep);
        });
    </script>


    <script src="./vendor/jQuery-3.7.1/jquery-3.7.1.min.js"></script>
    <script src="./js/user.jquery.js"></script>
    <script>
        let map;
        let marker;
        $(document).ready(function () {
            console.log("jQuery is working!");
        });
    </script>

</body>

</html>