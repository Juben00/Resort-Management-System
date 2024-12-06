<div id="sidebar" class="w-64 bg-gray-50 transition-all duration-300 flex flex-col h-screen text-sm">
    <div class="p-4 w-full flex justify-center">
        <img src="./../images/black_ico.png" alt="HubVenue Logo" class="transition-all duration-300">
    </div>
    <div class="px-4 w-full mb-4 sidebar-full">
        <div class="flex">
            <input type="text" placeholder="Search..."
                class="w-full p-2 border rounded bg-white text-gray-800 search-input">
            <button class="bg-primary text-white p-2 rounded ml-2 search-button">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </div>
    </div>
    <!-- Updated search icon for collapsed state -->
    <div class="hidden px-4 w-full mb-4 sidebar-collapsed-search">
        <button class="flex items-center text-white bg-primary hover:bg-red-900 w-full px-4 py-2 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mx-auto">
                <path d="M8.25 10.875a2.625 2.625 0 1 1 5.25 0 2.625 2.625 0 0 1-5.25 0Z" />
                <path fill-rule="evenodd"
                    d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.125 4.5a4.125 4.125 0 1 0 2.338 7.524l2.007 2.006a.75.75 0 1 0 1.06-1.06l-2.006-2.007a4.125 4.125 0 0 0-3.399-6.463Z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </div>
    <nav class="flex-grow w-full overflow-y-auto">
        <a href="dashboard" id="dashboard-link"
            class="nav-link flex items-center text-gray-800 hover:bg-red-900 hover:text-white w-full px-4 py-2">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
            </svg>
            <span class="sidebar-full">Dashboard</span>
        </a>

        <a href="venue-management" id="venue-management-link"
            class="nav-link flex items-center text-gray-800 hover:bg-red-900 hover:text-white w-full px-4 py-2">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z" />
                <path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z" />
                <path d="M17 5c0 1.657-3.134 3-7 3s-7-1.343-7-3v-3c0 1.657 3.134 3 7 3s7-1.343 7-3z" />
            </svg>
            <span class="sidebar-full">Venue Management</span>
        </a>

        <a href="reservation-management" id="reservation-management-link"
            class="nav-link flex items-center text-gray-800 hover:bg-red-900 hover:text-white w-full px-4 py-2">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
            </svg>
            <span class="sidebar-full">Reservation Management</span>
        </a>

        <a href="user-management" id="user-management-link"
            class="nav-link flex items-center text-gray-800 hover:bg-red-900 hover:text-white w-full px-4 py-2">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
            </svg>
            <span class="sidebar-full">User Management</span>
        </a>

        <a href="host-application" id="host-application-link"
            class="nav-link flex items-center text-gray-800 hover:bg-red-900 hover:text-white w-full px-4 py-2">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
            </svg>
            <span class="sidebar-full">Host Application</span>
        </a>

        <a href="financial-management" id="financial-management-link"
            class="nav-link flex items-center text-gray-800 hover:bg-red-900 hover:text-white w-full px-4 py-2">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M5.5 2A1.5 1.5 0 004 3.5V4h12v-.5A1.5 1.5 0 0014.5 2h-9zM2 7.5A1.5 1.5 0 013.5 6h13A1.5 1.5 0 0118 7.5V8H2v-.5zM0 4.5A1.5 1.5 0 011.5 3h13A1.5 1.5 0 0116 4.5V5H0v-.5zM2 12.5A1.5 1.5 0 013.5 11h13A1.5 1.5 0 0118 12.5V13H2v-.5zM0 10.5A1.5 1.5 0 011.5 9h13A1.5 1.5 0 0116 10.5V11H0v-.5zM3.5 14A1.5 1.5 0 002 15.5V16h16v-.5a1.5 1.5 0 00-1.5-1.5h-13zM0 13.5A1.5 1.5 0 011.5 12h13A1.5 1.5 0 0116 13.5V14H0v-.5z" />
            </svg>
            <span class="sidebar-full">Financial Management</span>
        </a>

        <a href="reports-analytics" id="reports-analytics-link"
            class="nav-link flex items-center text-gray-800 hover:bg-red-900 hover:text-white w-full px-4 py-2">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                <path fill-rule="evenodd"
                    d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                    clip-rule="evenodd" />
            </svg>
            <span class="sidebar-full">Reports and Analytics</span>
        </a>

        <a href="notifications-alerts" id="notifications-alerts-link"
            class="nav-link flex items-center text-gray-800 hover:bg-red-900 hover:text-white w-full px-4 py-2">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM5 8h10v2H5V8zm0 4h10v2H5v-2z" />
            </svg>
            <span class="sidebar-full">Notifications and Alerts</span>
        </a>

        <a href="content-management" id="content-management-link"
            class="nav-link flex items-center text-gray-800 hover:bg-red-900 hover:text-white w-full px-4 py-2">
            <svg class="w-5 h-5 min-w-[1.25rem] mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                    clip-rule="evenodd" />
            </svg>
            <span class="sidebar-full">Content Management</span>
        </a>

        <a href="promotions-marketing" id="promotions-marketing-link"
            class="nav-link flex items-center text-gray-800 hover:bg-red-900 hover:text-white w-full px-4 py-2">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                    clip-rule="evenodd" />
            </svg>
            <span class="sidebar-full">Promotions and Marketing</span>
        </a>

        <a href="discount-application" id="discount-application-link"
            class="nav-link flex items-center text-gray-800 hover:bg-red-900 hover:text-white w-full px-4 py-2">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd" />
            </svg>
            <span class="sidebar-full">Discount Application</span>
        </a>

        <a href="support-helpdesk" id="support-helpdesk-link"
            class="nav-link flex items-center text-gray-800 hover:bg-red-900 hover:text-white w-full px-4 py-2">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
            </svg>
            <span class="sidebar-full">Support and Helpdesk</span>
        </a>

        <a href="settings" id="settings-link"
            class="nav-link flex items-center text-gray-800 hover:bg-red-900 hover:text-white w-full px-4 py-2">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                    clip-rule="evenodd" />
            </svg>
            <span class="sidebar-full">Settings</span>
        </a>

        <a href="audit-logs" id="audit-logs-link"
            class="nav-link flex items-center text-gray-800 hover:bg-red-900 hover:text-white w-full px-4 py-2">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                    clip-rule="evenodd" />
            </svg>
            <span class="sidebar-full">Audit Logs</span>
        </a>

    </nav>

    <!-- Logout button -->
    <div class="w-full mt-auto mb-4">
        <button id="admin-logout"
            class="nav-link flex items-center text-gray-800 hover:bg-red-900 hover:text-white w-full px-4 py-2">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                </path>
            </svg>
            <span class="sidebar-full">Logout</span>
        </button>
    </div>
</div>

<script src="../vendor/jQuery-3.7.1/jquery-3.7.1.min.js"></script>
<script src="../js/admin.jquery.js"></script>
<style>
    #sidebar nav a:hover,
    #sidebar nav button:hover {
        background-color: #f41c1c;
        color: white;
        border-radius: 0.5rem;
    }

    #sidebar.w-16 nav a,
    #sidebar.w-16 nav button {
        justify-content: center;
    }

    #sidebar.w-16 nav a svg,
    #sidebar.w-16 nav button svg {
        margin-left: 0;
        margin-right: 0;
    }

    #sidebar nav a,
    #sidebar nav button {
        margin: 0.25rem 0.5rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem 0 0 0.5rem;
        justify-content: flex-start;
        width: calc(100% - 1rem);
        overflow: hidden;
    }

    #sidebar nav a svg,
    #sidebar nav button svg {
        min-width: 1.25rem;
        width: 1.25rem;
        height: 1.25rem;
        margin-right: 0.75rem;
    }

    #sidebar nav a span,
    #sidebar nav button span {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    #sidebar nav a:hover,
    #sidebar nav button:hover {
        background-color: #e60e0e;
        color: white;
        border-radius: 0.5rem 0 0 0.5rem;
        margin-right: 0;
        width: calc(100% - 0.5rem);
    }

    #sidebar.w-16 nav a,
    #sidebar.w-16 nav button {
        justify-content: center;
        padding: 0.5rem;
        margin: 0.25rem;
        width: calc(100% - 0.5rem);
        border-radius: 0.5rem;
    }

    #sidebar.w-16 nav a:hover,
    #sidebar.w-16 nav button:hover {
        border-radius: 0.5rem;
        width: calc(100% - 0.5rem);
    }

    #sidebar {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    #sidebar nav {
        width: 100%;
    }

    #sidebar nav a,
    #sidebar nav button {
        margin: 0.50rem 0.5rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem 0 0 0.5rem;
    }

    #sidebar.w-16 {
        border-right: none;
    }

    .dark-mode #sidebar {
        background-color: rgba(0, 0, 0, 0.5);
    }

    .dark-mode #sidebar nav a,
    .dark-mode #sidebar nav button,
    .dark-mode #sidebar .sidebar-full {
        color: #FFFFFF;
    }

    .dark-mode #sidebar nav a:hover,
    .dark-mode #sidebar nav button:hover {
        background-color: #f41c1c;
        color: #FFFFFF;
    }
</style>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const logo = document.querySelector('#sidebar img');
        const sidebarFullElements = document.querySelectorAll('.sidebar-full');
        const icons = document.querySelectorAll('#sidebar nav a svg, #sidebar nav button svg, #sidebar > div:last-child button svg');
        const navItems = document.querySelectorAll('#sidebar nav a, #sidebar nav button, #sidebar > div:last-child button');
        const searchInput = document.querySelector('.search-input');
        const searchButton = document.querySelector('.search-button');
        const collapsedSearch = document.querySelector('.sidebar-collapsed-search');
        const logoutButton = document.querySelector('#sidebar > div:last-child button');
        const logoutIcon = logoutButton.querySelector('svg');
        const logoutText = logoutButton.querySelector('span');

        sidebar.classList.toggle('w-64');
        sidebar.classList.toggle('w-16');

        // Toggle logo size
        logo.classList.toggle('w-12');

        sidebarFullElements.forEach(element => {
            element.classList.toggle('hidden');
        });

        navItems.forEach(item => {
            item.classList.toggle('justify-center');
            item.classList.toggle('px-4');
            item.classList.toggle('px-2');
        });

        icons.forEach(icon => {
            icon.classList.toggle('mr-3');
            icon.classList.toggle('mr-0');
        });

        sidebar.classList.toggle('border-r-0');

        // Toggle search input and button
        searchInput.parentElement.classList.toggle('hidden');
        collapsedSearch.classList.toggle('hidden');

        // Align collapsed search with other icons
        collapsedSearch.querySelector('button').classList.toggle('justify-center');
        collapsedSearch.querySelector('button').classList.toggle('px-4');
        collapsedSearch.querySelector('button').classList.toggle('px-2');

        // Adjust logout button
        logoutButton.classList.toggle('justify-center');
        logoutButton.classList.toggle('px-4');
        logoutButton.classList.toggle('px-2');
        logoutIcon.classList.toggle('mr-3');
        logoutIcon.classList.toggle('mr-0');

        // Toggle visibility of logout text and adjust icon
        if (sidebar.classList.contains('w-16')) {
            logoutText.classList.add('hidden');
            logoutIcon.classList.remove('mr-3');
            logoutIcon.classList.add('mx-auto');
        } else {
            logoutText.classList.remove('hidden');
            logoutIcon.classList.add('mr-3');
            logoutIcon.classList.remove('mx-auto');
        }
    }
</script>