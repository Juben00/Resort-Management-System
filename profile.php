<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ./index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Icoco || Profile</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="icon" href="./images/icoco_black_ico.png">
    <link rel="stylesheet" href="./output.css">

    <style>
        .slideshow {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .slide {
            display: none;
            width: 100%;
            height: 100%;
        }

        .slide.active {
            display: block;
        }

        .active {
            border-bottom: 2px solid black;
            color: black;
            font-weight: bold;
            transition: all 0.1s;
        }

        #add-venue {
            width: 80vh;
        }

        #add-venue #formGrids {
            gap: .5rem;
        }

        #add-venue #formTitle {
            padding: 1rem;
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
</head>

<body class="bg-gray-50 relative">
    <?php
    require_once './components/profile.nav.php';

    include_once './components/SignupForm.html';
    include_once './components/feedback.modal.html';
    include_once './components/confirm.feedback.modal.html';
    include_once './components/Menu.html';

    ?>

    <div id="profileDisplay">

    </div>

    <div id="userAddVenueForm"
        class="fixed inset-0 bg-black bg-opacity-40 items-center justify-center z-40 p-8 hidden  ">
        <?php require_once './venue-management/add-venue.html' ?>
    </div>

    <script src="./vendor/jQuery-3.7.1/jquery-3.7.1.min.js"></script>
    <script src="./js/user.jquery.js"></script>
    <script>
        let map;
        let marker;
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slideshows = document.querySelectorAll('.slideshow');
            slideshows.forEach(slideshow => {
                let slides = slideshow.querySelectorAll('.slide');
                let currentIndex = 0;

                function showSlide(index) {
                    slides.forEach((slide, i) => {
                        slide.classList.toggle('active', i === index);
                    });
                }

                function nextSlide() {
                    currentIndex = (currentIndex + 1) % slides.length;
                    showSlide(currentIndex);
                }

                showSlide(currentIndex);
                setInterval(nextSlide, 3000); // Change slide every 3 seconds
            });
        });
    </script>
</body>