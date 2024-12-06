<!-- Top Navigation -->
<header class="bg-gray-50 flex justify-between items-center p-4">
    <div class="flex items-center">
        <!-- Sidebar Toggle Button -->
        <button onclick="toggleSidebar()" class="text-gray-500 hover:text-gray-700" title="Toggle Sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                <path d="M0 0h24v24H0z" fill="none" />
                <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z" />
            </svg>
        </button>
        <h1 class="text-2xl font-bold ml-4" id="h1" for="darkModeToggle">Icoco Admin</h1>
    </div>
    <!-- Dark Mode Toggle (Centered) -->
    <div class="flex items-center">
        <input type="checkbox" id="darkModeToggle" class="hidden">
        <label for="darkModeToggle"
            class="w-12 h-6 rounded-full bg-gray-50-300 flex items-center cursor-pointer relative mx-2">
            <div
                class="w-5 h-5 rounded-full bg-gray-50 absolute left-0.5 transition-transform duration-300 ease-in-out flex items-center justify-center">
                <svg class="w-4 h-4 text-yellow-500 sun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                    fill="currentColor">
                    <path
                        d="M12 2.25a.75.75 0 0 1 .75.75v2.25a.75.75 0 0 1-1.5 0V3a.75.75 0 0 1 .75-.75ZM7.5 12a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM18.894 6.166a.75.75 0 0 0-1.06-1.06l-1.591 1.59a.75.75 0 1 0 1.06 1.061l1.591-1.591ZM21.75 12a.75.75 0 0 1-.75.75h-2.25a.75.75 0 0 1 0-1.5H21a.75.75 0 0 1 .75.75ZM17.834 18.894a.75.75 0 0 0 1.06-1.06l-1.59-1.591a.75.75 0 1 0-1.061 1.06l1.59 1.591ZM12 18a.75.75 0 0 1 .75.75V21a.75.75 0 0 1-1.5 0v-2.25A.75.75 0 0 1 12 18ZM7.758 17.303a.75.75 0 0 0-1.061-1.06l-1.59-1.591a.75.75 0 0 0-1.061 1.06l1.59 1.591ZM6 12a.75.75 0 0 1-.75.75H3a.75.75 0 0 1 0-1.5h2.25A.75.75 0 0 1 6 12ZM6.697 7.757a.75.75 0 0 0 1.06-1.06l-1.59-1.591a.75.75 0 0 0-1.061 1.06l1.59 1.591Z" />
                </svg>
                <svg class="w-4 h-4 text-gray-600 moon hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.528 1.718a.75.75 0 0 1 .162.819A8.97 8.97 0 0 0 9 6a9 9 0 0 0 9 9 8.97 8.97 0 0 0 3.463-.69.75.75 0 0 1 .981.98 10.503 10.503 0 0 1-9.694 6.46c-5.799 0-10.5-4.7-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 0 1 .818.162Z"
                        clip-rule="evenodd" />
                </svg>
            </div>
        </label>
    </div>
    <div class="flex items-center space-x-4">
        <button onclick="toggleFullscreen()" class="text-gray-600 hover:text-gray-800" title="Fullscreen">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 9V5a2 2 0 012-2h6a2 2 0 012 2v4m-6 10v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4m8-10h4a2 2 0 012 2v4m-6 10h4a2 2 0 002-2v-4" />
            </svg>
        </button>
        <button class="text-gray-600 hover:text-gray-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                </path>
            </svg>
        </button>
        <button class="text-gray-600 hover:text-gray-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                </path>
            </svg>
        </button>
        <button class="text-gray-600 hover:text-gray-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.724 1.724 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.724 1.724 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.723 1.723 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.724 1.724 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.724 1.724 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.724 1.724 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                    clip-rule="evenodd" />
            </svg>
        </button>
        <button class="bg-gray-800 rounded-full w-8 h-8"></button>
    </div>
</header>