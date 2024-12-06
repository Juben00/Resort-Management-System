<?php
require_once '../classes/venue.class.php';
require_once '../classes/account.class.php';
$venueObj = new Venue();
$accountObj = new Account();
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-6">Reservation Management</h1>

    <!-- Tabs -->
    <div class="flex space-x-1 rounded-lg bg-white p-1 mb-4">
        <button
            class="tab-button flex-1 inline-flex items-center justify-center whitespace-nowrap rounded-md px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-red-600 text-white"
            id="all-reservations-rm">All Reservations</button>
        <button
            class="tab-button flex-1 inline-flex items-center justify-center whitespace-nowrap rounded-md px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
            id="approved-reservations-rm">Approved</button>
        <button
            class="tab-button flex-1 inline-flex items-center justify-center whitespace-nowrap rounded-md px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
            id="cancelled-reservations-rm">Cancelled</button>
        <button
            class="tab-button flex-1 inline-flex items-center justify-center whitespace-nowrap rounded-md px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
            id="rejected-reservations-rm">Rejected</button>
    </div>

    <div id="reservation-management-view">
        <!-- Content will be loaded here -->
    </div>
</div>

<script>
    $(document).ready(function () {
        // Initially load the all reservations view
        viewRmAllReservations();

        // Event listeners for tabs
        $("#all-reservations-rm").on("click", function (e) {
            e.preventDefault();
            viewRmAllReservations();
            activateTab($(this));
        });

        $("#approved-reservations-rm").on("click", function (e) {
            e.preventDefault();
            viewRmApproved();
            activateTab($(this));
        });

        $("#cancelled-reservations-rm").on("click", function (e) {
            e.preventDefault();
            viewRmCancelled();
            activateTab($(this));
        });

        $("#rejected-reservations-rm").on("click", function (e) {
            e.preventDefault();
            viewRmRejected();
            activateTab($(this));
        });
    });

    // Function to activate the clicked tab
    function activateTab(selectedTab) {
        $(".tab-button")
            .removeClass("bg-red-600 text-white")  // Remove active styles from all tabs
            .addClass("text-gray-800");            // Set default text color for all tabs

        selectedTab
            .addClass("bg-red-600 text-white")     // Add active styles to selected tab
            .removeClass("text-gray-800");         // Remove default text color for active tab
    }

    // View loading functions
    function viewRmAllReservations() {
        $.ajax({
            type: "GET",
            url: "../reservation-management/all-reservations.php",
            dataType: "html",
            success: function (response) {
                $("#reservation-management-view").html(response);
            },
        });
    }

    function viewRmApproved() {
        $.ajax({
            type: "GET",
            url: "../reservation-management/approved-reservations.php",
            dataType: "html",
            success: function (response) {
                $("#reservation-management-view").html(response);
            },
        });
    }

    function viewRmCancelled() {
        $.ajax({
            type: "GET",
            url: "../reservation-management/cancelled-reservations.php",
            dataType: "html",
            success: function (response) {
                $("#reservation-management-view").html(response);
            },
        });
    }

    function viewRmRejected() {
        $.ajax({
            type: "GET",
            url: "../reservation-management/rejected-reservations.php",
            dataType: "html",
            success: function (response) {
                $("#reservation-management-view").html(response);
            },
        });
    }
</script>