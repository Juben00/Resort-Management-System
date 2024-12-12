$(document).ready(function () {

    console.log("User jQuery loaded");
    
    // signup terms and conditions checker
    const signupAgreeTerms = $("#agreeTerms");
    const signupButton = $("#signupSubmit");

    if (signupAgreeTerms.length) {
        // Initial check on page load
        signupButton.prop("disabled", !signupAgreeTerms.is(":checked"));

        // Add a click event listener
        signupAgreeTerms.on("click", function () {
            signupButton.prop("disabled", !$(this).is(":checked"));
        });
    }

    //bookmark btn
    $(document).on('click', '#bookmarkBtn', function (e) {
        e.preventDefault();
        const btn = $(this);
        const venueId = btn.data('venueid');
        const userId = btn.data('userid');

        // Add animation class
        btn.addClass('animate');
        
        // Toggle bookmarked class immediately for instant feedback
        btn.toggleClass('bookmarked');
        
        // Remove animation class after animation completes
        setTimeout(() => {
            btn.removeClass('animate');
        }, 800);
        
        // Call the bookmarkVenue function
        bookmarkVenue(venueId, userId);
    });


    $('#HubvenueLogo').on('click', function (e) {
        e.preventDefault();
        window.location.href = "index.php";
    })

    // login form
    $('#loginForm').on("submit", function (e) {
        e.preventDefault();
        const formElement = $(this);
        login(formElement);
    });

    // signup form
    $('#signupForm').on("submit", function (e) {
        e.preventDefault();
        const formElement = $(this);
        signup(formElement);
    });

    // payment form
    $('#paymentForm').on("submit", function (e) {
        e.preventDefault();
        const formElement = $(this);
        book(formElement);
    });

    //logout button
    $('#logoutBtn').on('click', function (e) {
        e.preventDefault();
        logout();
    });

    //menu trigger buttons open
    $("#menutabtrigger").on("click", function (e) {
        e.preventDefault();
        $("#menutab").toggleClass("hidden");
    });

    //menu trigger buttons close
    $('#menutab').on("dblclick", function (e) {
        e.preventDefault();
        $("#menutab").toggleClass("hidden");
    });

    //map trigger buttons open
    // $("#maps-button").on("click", function (e) {
    //     e.preventDefault();
    //     viewOpenStreetMap();  // Open the map for user interaction
    // });

    $(document).on('click', '#maps-button', function (e) {
        e.preventDefault();
        viewOpenStreetMap();  // Open the map for user interaction
    });
    //add venue trigger button
    $('#add-venue-form').on("submit", function (e) {
        e.preventDefault();
        addVenue();
    });

    $('.venueCard').on("click", function () {
        let isLogged = $(this).data("isloggedin"); 
        let venueUrl = $(this).data("id");
        
        if (isLogged === true) {  
            viewVenue(venueUrl);
        } else {
            showModal("Please login to view the venue", undefined, "icoco_black_ico.png");
        }
    });

    //profile navigation
    $('.profileNav').on('click', function (e) {
        e.preventDefault();
        let url = $(this).attr("data-profileUrl");
        
        $('.profileNav').removeClass('active');
        $(this).addClass('active');
    
        openProfileNav(url);
        
        
    })

    //profile button
    $(document).on('click', '#profileBtn', function (e) {
        e.preventDefault();
        const url = $(this).data("url");
        menuRedirection(url);
        openProfileNav('rent-history');
    });

    //host account button
    $(document).on('click', '#hostAccountBtn', function (e) {
        e.preventDefault();
        const url = $(this).data("url");
        menuRedirection(url);
    });

    //settings button
    $(document).on('click', '#settingsBtn', function (e) {
        e.preventDefault();
        const url = $(this).data("url");
        menuRedirection(url);
    });

    //host application button
    $('#hostApplicationForm').on("submit", function (e) {
        e.preventDefault();
        const formElement = $(this);
        hostApplication(formElement);
    });  

    //reservation form
    $('#reservationForm').on('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission
        
        const formElement = $(this);
        let isValid = true;

        // Loop through all the form fields and check if they are empty
        formElement.find('input').each(function () {    
            if ($(this).val() === '') {
                isValid = false;
                $(this).addClass('border-red-500'); // Add red border to indicate the field is empty
            } else {
                $(this).removeClass('border-red-500'); // Remove red border if the field is filled
            }
        });

        // If all fields are valid, proceed with serialization and redirection
        if (isValid) {
            const formData = formElement.serialize(); // Serialize form data
            const actionUrl = formElement.attr('action'); // Get the form action URL
            const redirectUrl = actionUrl + '?' + formData; // Construct the URL with the serialized data

            window.location.href = redirectUrl; // Redirect to the constructed URL
        } else {
            showModal("Please fill out all the required fields.", undefined, 'icoco_black_ico.png'); // Show alert if any field is empty
        }
    });

    // Update user info form submission
    $('#updateUserInfoForm').on('submit', function (e) {
        e.preventDefault();
        const formElement = $(this);
        updateUserInfo(formElement);
    });

    // Change password form submission
    $('#updatePasswordForm').on('submit', function (e) {
        e.preventDefault();
        const formElement = $(this);
        updatePassword(formElement);
    }); 

    //mandatory discount form submission
    $('#discountApplicationForm').on('submit', function (e) {
        e.preventDefault();
        const formElement = $(this);
        mandatoryDiscount(formElement);
    });

    function viewOpenStreetMap(){
    $.ajax({
        type: "GET",
        url: "./openStreetMap/userOpenStreetMap.html",
        dataType: "html",
        success: function (response) {
          $("#openstreetmapplaceholder").html(response);

          $('#openStreetMapSubmit').on("click", function (e) {
            
                e.preventDefault();
                console.log("hgloasd");
                
                // // Capture the value of #OpenStreetaddress
                // let openStreetAddress = $('#OpenStreetaddress').val();
                // // Set this value to #venue-location if it exists
                // $('#venue-location').val(openStreetAddress);
                $('#openStreetMapDiv').addClass("hidden"); // Hide the map modal

                
            });
        },
    })
    }

    function login(formElement) {
        let form = new FormData(formElement[0]);
        $.ajax({
            type: "POST",
            url: "./api/Login.api.php",
            data: form,
            processData: false,  // prevent jQuery from processing the FormData
            contentType: false,  // prevent jQuery from setting the content type
            success: function (response) {
                response = JSON.parse(response);
                if (response.status === "success") {
                    showModal(response.message, function () {
                        formElement[0].reset();
                        $('#authModal').addClass("hidden");
                        window.location.reload();
                    }, "icoco_black_ico.png");
                } else {
                    showModal(response.message, undefined, "icoco_black_ico.png");
                }
            },
        });
    }

    function signup(formElement) {
        let form = new FormData(formElement[0]);
        $.ajax({
            type: "POST",
            url: "./api/Signup.api.php",
            data: form,
            processData: false,  
            contentType: false, 
            success: function (response) {
                // Ensure response is parsed as JSON
                try {
                    const jsonResponse = typeof response === 'string' ? JSON.parse(response) : response;
    
                    if (jsonResponse.status === "success") {
                        showModal(jsonResponse.message, function () {
                            formElement[0].reset();
                            $('#authModal').addClass("hidden");
                            $('#loginTab').click();
                        }, "icoco_black_ico.png");
                    } else {
                        showModal(jsonResponse.message, undefined, "icoco_black_ico.png");
                    }
                } catch (error) {
                    console.error('Error parsing response:', error);
                    showModal('An unexpected error occurred.', undefined, "icoco_black_ico.png");
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', status, error);
                showModal('An error occurred while processing your request.', undefined, "icoco_black_ico.png");
            }
        });
    }

    

    function viewVenue(url) {
        window.location.href = url;
    }

    function menuRedirection(url){
        window.location.href = url;
    }

    function hostApplication(formElement) {
        let form = new FormData(formElement[0]);
        $.ajax({
            type: "POST",
            url: "./api/HostApplication.api.php",
            data: form,
            processData: false,
            contentType: false,
            success: function (response) {
                response = JSON.parse(response);
                if (response.status === "success") {
                    formElement[0].reset();
                    showModal(response.message, function () {
                        $("#hostAccountBtn").click();
                    }, "icoco_black_ico.png");
                } else {
                    showModal(response.message, undefined, "icoco_black_ico.png");
                }
            }
            });
    }

    function logout(){
        confirmshowModal('Are you sure you want to log out?', function () {
            window.location.href = "./logout.php";
        }, 'icoco_black_ico.png');
    }

    function openProfileNav(url) {
        $.ajax(
            {
                type: "GET",
                url: './profile/' + url + '.php',
                success: function (response) {
                    $('#profileDisplay').html(response);

                    //view listing
                    $('.venue-card').on('click', function (e) {
                        e.preventDefault();
                        const url = $(this).data("id");
                        viewListing(url);
                    });

                    //view bookmarks
                    $('.venueCard').on("click", function () {
                        let isLogged = $(this).data("isloggedin"); 
                        let venueUrl = $(this).data("id");
                        
                        if (isLogged === true) {  
                            viewVenue(venueUrl);
                        } else {
                            showModal("Please login to view the venue", undefined, "icoco_black_ico.png");
                        }
                    });

                    //add venue button
                    $('#addVenueButton').on('click',  function (e) {
                        e.preventDefault();
                        window.location.href = './list-your-venue.php';
                    });

                   $('#bookAgainBtn').on('click', function (e) {
                        e.preventDefault();
                        const venueId = $(this).data("bvid");
                        window.location.href = './venues.php?id=' + venueId; // Uncomment if you want to navigate
                    });

                    $('#reviewForm').on('submit', function (e) {
                        e.preventDefault();
                        const formElement = $(this);
                        addReview(formElement);
                    });

                    $('#cancellation-form').on('submit', function (e) {
                        e.preventDefault();
                        const formElement = $(this);
                        cancelBooking(formElement);
                    });
                    
                }
            }
        )
    }
    
    function viewListing(url) {
        $.ajax({
            type: "GET",
            url: "./profile/view-listing.php?id=" + url,
            data: {
                id: url
            },
            success: function (response) {
                $('#profileDisplay').html(response);

                //back to listing
                $('#backToListing').on('click', function(e) {
                    e.preventDefault();
                    openProfileNav('listings');
                })
            }
        });
    }

    function addReview(formElement) {
        let form = new FormData(formElement[0]);
        $.ajax({
            type: "POST",
            url: "./api/AddReview.api.php",
            data: form,
            processData: false,
            contentType: false,
            success: function (response) {
                response = JSON.parse(response);
                if (response.status === "success") {
                    showModal(response.message, undefined, "icoco_black_ico.png");
                    openProfileNav('rent-history');
                } else {
                    showModal(response.message, undefined, "icoco_black_ico.png");
                }
                },
            });
    }


    function addVenue(){
    let form = new FormData($("#add-venue-form")[0]);
    $.ajax({
        type: "POST",
        url: "./api/hostAddVenue.api.php",
        data: form,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (response) {
            if (response.status == "success") {
                showModal(
                    "Venue added successfully",
                    function () {
                        $("#add-venue-form")[0].reset();
                        window.location.href = "./profile.php";
                    },
                    "icoco_black_ico.png"
                );
            } else {
                showModal(
                    "Venue not added",
                    undefined,
                    "icoco_black_ico.png"
                );
            }
        },
    });
    }

    function bookmarkVenue(venueId, userId, buttonElement) {
        console.log(`Venue ID: ${venueId}, User ID: ${userId}`); // Debugging
        $.ajax({
            type: "POST",
            url: "./api/Bookmark.api.php",
            data: {
                userId: userId,
                venueId: venueId,
            },
            success: function (response) {
                console.log(response); // Log response for debugging
                response = JSON.parse(response);

                if (response.status === "success") {
                    if (response.action === "bookmarked") {
                        $(buttonElement).removeClass("text-white").addClass("text-red-500");
                    } else if (response.action === "unbookmarked") {
                        $(buttonElement).removeClass("text-red-500").addClass("text-white");
                    }
                    // showModal(response.message, undefined, "icoco_black_ico.png");
                } else {
                    showModal(response.message, undefined, "icoco_black_ico.png");
                }
            },
            error: function (xhr, status, error) {
                console.error(`Error: ${error}`); // Log AJAX error
            },
        });
    }

    function book(formElement){
        let form = new FormData(formElement[0]);
        $.ajax({
            type: "POST",
            url: "./api/AddBooking.api.php",
            data: form,
            processData: false,  
            contentType: false, 
            success: function (response) {
                response = JSON.parse(response);
                if (response.status === "success") {
                    showModal(response.message, function () {
                        formElement[0].reset();
                        window.location.href = "./index.php";
                    }, "icoco_black_ico.png");
                } else {
                    showModal(response.message, undefined, "icoco_black_ico.png");
                }
            },
        });
    } 

    function updateUserInfo(formElement) {
        let form = new FormData(formElement[0]);
        $.ajax({
            type: "POST",
            url: "./api/UpdateUserInfo.api.php",
            data: form,
            processData: false,
            contentType: false,
            success: function (response) {
                response = JSON.parse(response);
                if (response.status === "success") {
                    showModal(response.message, undefined, "icoco_black_ico.png");
                } else {
                    showModal(response.message, undefined, "icoco_black_ico.png");
                }
            },
        });
    }

   function updatePassword(formElement) {
    let form = new FormData(formElement[0]);
    $.ajax({
        type: "POST",
        url: "./api/UserChangePass.api.php",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            let res = JSON.parse(response); // Parse the response if it's not automatically parsed
            if (res.status === "success") {
                showModal(res.message, function(){
                    formElement[0].reset();
                }, "icoco_black_ico.png");
            } else {
                showModal(res.message, undefined, "icoco_black_ico.png");
            }
        }
    });
}

    function mandatoryDiscount(formElement) {
        let form = new FormData(formElement[0]);
        $.ajax({
            type: "POST",
            url: "./api/DiscountApplication.api.php",
            data: form,
            processData: false,
            contentType: false,
            success: function (response) {
                let res = JSON.parse(response); // Parse the response if it's not automatically parsed
                if (res.status === "success") {
                    showModal(res.message, function(){
                        formElement[0].reset();
                        menuRedirection("./settings.php");
                    }, "icoco_black_ico.png");
                } else {
                    showModal(res.message, undefined, "icoco_black_ico.png");
                }
            },


     
            
    });

    
}

    function cancelBooking(formElement){
        let form = new FormData(formElement[0]);
        $.ajax({
            type: "POST",
            url: "./api/CancelBooking.api.php",
            data: form,
            processData: false,
            contentType: false,
            success: function (response) {
                let res = JSON.parse(response); // Parse the response if it's not automatically parsed
                if (res.status === "success") {
                    showModal(res.message, function(){
                        formElement[0].reset();
                        window.location.reload();
                    }, "icoco_black_ico.png");
                } else {
                    showModal(res.message, undefined, "icoco_black_ico.png");
                }
            }
        });

    }
    // setting default view for profile
    openProfileNav('rent-history');

});