<?php
require_once './classes/account.class.php';

$account = new Account();

$profilePic = $account->getProfilePic($_SESSION['user']['id']);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<nav id="main-nav" class="bg-transparent backdrop-blur-xl z-40 fixed w-full px-2 lg:px-8">
  <div class="flex items-center justify-between md:px-4">
    <!-- Left Section - Logo -->
    <a href="./" class="flex items-center">
      <img src="./images/icoco_black_ico.png" alt="Icoco_Logo" class="h-[80px]" />
      <span class="text-4xl font-semibold">Icoco <span class="text-sm text-neutral-800">Resort Management
          System</span></span>
    </a>

    <!-- Center Section - Navigation Links -->
    <div class="flex items-center justify-between" id="navbar-sticky">
      <ul class="flex space-x-8 font-medium text-center">
        <li>
          <a id="rent-history" data-profileUrl="rent-history"
            class="profileNav active w-[120px] cursor-pointer block py-2 px-3">
            Rent History
          </a>
        </li>

        <li>
          <a id="bookmarks" data-profileUrl="bookmarks" class="profileNav w-[100px] cursor-pointer block py-2 px-3">
            Bookmarks
          </a>
        </li>


        <?php
        if ($_SESSION['user']['user_type_id'] == 1) {
          echo '
          <li>
              <a id="venue-owner" data-profileUrl="venue-owner"
                class="profileNav w-[100px] cursor-pointer block py-2 px-3">
                Reservations
              </a>
            </li>
        <li>
          <a id="listing" data-profileUrl="listings" class="profileNav w-[100px] cursor-pointer block py-2 px-3">
            Listings
          </a>
        </li>';
        }
        ?>
      </ul>
    </div>

    <!-- Right Section -->
    <div class="flex items-center space-x-4">
      <!-- Notification Button -->


      <!-- User Menu Button -->
      <button class="flex items-center space-x-4" id="menutabtrigger">
        <div class="relative flex items-center space-x-2 bg-slate-50 shadow-md rounded-full ps-4 p-1">
          <i class="fas fa-bars text-gray-600"> </i>
          <div class="relative">
            <div class="h-8 w-8 rounded-full bg-black text-white flex items-center justify-center">
              <?php
              if (isset($_SESSION['user']) && empty($profilePic)) {
                echo $_SESSION['user']['firstname'][0];
              } else {
                echo '<img id="profileImage" name="profile_image" src="./' . htmlspecialchars($profilePic) . '" alt="Profile Picture" class="w-full h-full rounded-full object-cover">';
              }
              ?>
            </div>
          </div>
        </div>
      </button>
    </div>
  </div>
</nav>