<?php
// Resolve base path — works from root, pages/, and orders/ subdirectories
$basePath = (strpos($_SERVER['PHP_SELF'], '/pages/') !== false || strpos($_SERVER['PHP_SELF'], '/orders/') !== false) ? '../' : '';
?>

<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="closeSidebar()"></div>

<aside class="sidebar" id="sidebar">

    <div class="px-5 py-5 border-b border-gray-200 flex items-center justify-between">

        <h1 class="text-2xl font-semibold text-gray-800">
            Dashboard
        </h1>

        <button
            class="hamburger-btn"
            onclick="closeSidebar()"
            aria-label="Close menu">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

    </div>

    <nav class="flex-1 px-4 py-5">

        <p class="text-gray-400 uppercase text-xs mb-4 tracking-wider">
            Main Menu
        </p>

        <ul class="space-y-2">

            <li>
                <a
                    href="<?php echo $basePath; ?>dashboard.php"
                    onclick="closeSidebar()"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl <?php echo (isset($page) && $page == 'dashboard') ? 'bg-blue-50 text-blue-700 font-medium' : 'hover:bg-gray-100 text-gray-700'; ?>">

                   
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" />
                    </svg>

                    Dashboard
                </a>
            </li>
           
            <li>
                <a
                    href="<?php echo $basePath; ?>pages/tasks-content.php"
                    onclick="closeSidebar()"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl <?php echo (isset($page) && $page == 'tasks') ? 'bg-blue-50 text-blue-700 font-medium' : 'hover:bg-gray-100 text-gray-700'; ?>">

                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>

                    Tasks

                </a>
            </li>
          
            <li>
                <a
                    href="<?php echo $basePath; ?>orders/order_list.php"
                    onclick="closeSidebar()"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl <?php echo (isset($page) && $page == 'orders') ? 'bg-blue-50 text-blue-700 font-medium' : 'hover:bg-gray-100 text-gray-700'; ?>">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        width="18"
                        height="18"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 17v-6h13v6M9 5v6h13V5M3 5h2v14H3V5z" />

                    </svg>

                    Orders

                </a>
            </li>

        </ul>

    </nav>

</aside>

<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('sidebarBackdrop').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarBackdrop').classList.remove('active');
        document.body.style.overflow = '';
    }
</script>