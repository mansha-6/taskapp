<?php
// Resolve base path — works from root, pages/, and orders/ subdirectories
$basePath = (strpos($_SERVER['PHP_SELF'], '/pages/') !== false || strpos($_SERVER['PHP_SELF'], '/orders/') !== false) ? '../' : '';

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

session_start();

if (!isset($_SESSION['loggedin'])) {

    header("Location: index.php");

    exit();
}

include("db.php");
include("includes/header.php");

/* CURRENT PAGE */
$page = $page ?? $_GET['page'] ?? 'dashboard';

?>

<!-- MAIN LAYOUT -->
<div class="flex min-h-screen bg-gray-100">

    <!-- SIDEBAR -->
    <?php include("includes/sidebar.php"); ?>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">

        <!-- TOP NAVBAR -->
        <div class="top-navbar flex justify-between items-center glass-card rounded-2xl p-4 mb-8 relative z-50">

            <!-- HAMBURGER (mobile) -->
            <button
                class="hamburger-btn"
                onclick="openSidebar()"
                aria-label="Open menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- RIGHT SIDE -->
            <div class="flex items-center gap-3 ml-auto">

                <!-- NOTIFICATION ICON -->
                <div class="relative">
                    <?php
                    $notiQuery = mysqli_query(
                        $conn,
                        "SELECT id, title, due_date
                         FROM tasks
                         WHERE status='pending'
                         AND (due_date = CURDATE() OR due_date < CURDATE())
                         AND is_deleted = 0
                         ORDER BY due_date ASC"
                    );
                    $notiCount = mysqli_num_rows($notiQuery);
                    ?>
                    <button
                        id="notiBtn"
                        class="bg-white border border-gray-200 shadow-sm rounded-full p-2 hover:bg-gray-100 relative">
                        🔔
                        <?php if ($notiCount > 0) { ?>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"><?php echo $notiCount; ?></span>
                        <?php } ?>
                    </button>

                    <!-- DROPDOWN -->
                    <div
                        id="notificationsDropdown"
                        class="hidden absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-gray-200 z-50 max-h-96 overflow-y-auto">
                        <div class="p-4 border-b font-semibold text-gray-700">
                            Due/Pending Tasks
                        </div>
                        <div class="p-3 space-y-3">
                            <?php
                            if ($notiCount == 0) {
                                echo '<div class="text-sm text-gray-500 text-center p-2">No pending or due tasks</div>';
                            } else {
                                while ($notiRow = mysqli_fetch_assoc($notiQuery)) {
                                    $isOverdue = strtotime($notiRow['due_date']) < strtotime(date('d-m-Y'));
                                    $colorClass = $isOverdue ? 'text-red-600' : 'text-yellow-600';
                            ?>
                                    <div class="flex items-center gap-3 hover:bg-gray-100 p-2 rounded-xl">
                                        <div class="text-xl">⚠️</div>
                                        <div class="text-sm">
                                            <div class="font-medium text-gray-800"><?php echo htmlspecialchars($notiRow['title']); ?></div>
                                            <div class="<?php echo $colorClass; ?> text-xs">Due: <?php echo $notiRow['due_date']; ?></div>
                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- ATTACHMENTS ICON -->
                <div class="relative">

                    <button
                        id="attachBtn"
                        class="bg-white border border-gray-200 shadow-sm rounded-full p-2 hover:bg-gray-100">

                        📁

                    </button>

                    <!-- DROPDOWN -->
                    <div
                        id="attachmentsDropdown"
                        class="hidden absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-gray-200 z-50 max-h-96 overflow-y-auto">

                        <!-- TITLE -->
                        <div class="p-4 border-b font-semibold text-gray-700">
                            Attachments
                        </div>

                        <!-- LIST -->
                        <div class="p-3 space-y-3">

                            <?php

                            $filesQuery = mysqli_query(
                                $conn,
                                "SELECT attachment,title
                                FROM tasks
                                WHERE attachment IS NOT NULL
                                AND attachment != ''
                                ORDER BY id DESC"
                            );

                            while ($fileRow = mysqli_fetch_assoc($filesQuery)) {

                                $file = trim($fileRow['attachment']);

                                $filePath = "uploads/" . basename($file);

                                $ext = strtolower(
                                    pathinfo($file, PATHINFO_EXTENSION)
                                );

                                $imgTypes = [
                                    'jpg',
                                    'jpeg',
                                    'png',
                                    'gif',
                                    'webp'
                                ];

                                if (file_exists(__DIR__ . "/" . $filePath)) {

                                    if (in_array($ext, $imgTypes)) {
                            ?>

                                        <!-- IMAGE -->
                                        <div
                                            onclick="openImage('<?php echo $basePath . $filePath; ?>')"
                                            class="cursor-pointer flex items-center gap-3 hover:bg-gray-100 p-2 rounded-xl">

                                            <img
                                                src="<?php echo $basePath . $filePath; ?>"
                                                class="w-12 h-12 object-cover rounded-lg">

                                            <div class="text-sm text-gray-700 truncate">
                                                <?php echo htmlspecialchars($fileRow['title']); ?>
                                            </div>

                                        </div>

                                    <?php } else { ?>

                                        <!-- FILE -->
                                        <a
                                            href="<?php echo $basePath . $filePath; ?>"
                                            target="_blank"
                                            class="flex items-center gap-3 hover:bg-gray-100 p-2 rounded-xl">

                                            <div class="text-2xl">
                                                📄
                                            </div>

                                            <div class="text-sm text-gray-700 truncate">
                                                <?php echo htmlspecialchars($fileRow['title']); ?>
                                            </div>

                                        </a>

                            <?php
                                    }
                                }
                            }
                            ?>

                        </div>

                    </div>

                </div>

                <!-- LOGOUT -->
                <a
                    href="<?php echo $basePath; ?>logout.php"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm shadow-sm transition-colors">

                    Logout

                </a>

            </div>
        </div>

        <?php

        /* DASHBOARD PAGE */
        if ($page == "dashboard") {

        ?>

            <!-- DASHBOARD CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- CARD 1: PENDING TASKS -->
                <div class="glass-card rounded-3xl p-6 hover-lift">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">⏳</span>
                            <h2 class="text-xl font-bold text-gray-800">Pending Tasks</h2>
                        </div>
                        <span class="bg-amber-100 text-amber-600 px-3 py-1 rounded-full text-xs font-semibold">
                            <?php
                            $pendingQuery = mysqli_query($conn, "SELECT COUNT(*) as count FROM tasks WHERE status='pending' AND is_deleted=0");
                            $pendingCount = mysqli_fetch_assoc($pendingQuery)['count'];
                            echo $pendingCount;
                            ?>
                        </span>
                    </div>
                    <div class="space-y-3">
                        <?php
                        $pendingTasksQuery = mysqli_query($conn, "SELECT title, created_by FROM tasks WHERE status='pending' AND is_deleted=0 ORDER BY id DESC LIMIT 5");
                        if (mysqli_num_rows($pendingTasksQuery) == 0) {
                            echo '<p class="text-gray-500 text-sm text-center py-4">No pending tasks</p>';
                        } else {
                            while ($taskRow = mysqli_fetch_assoc($pendingTasksQuery)) {
                        ?>
                                <div class="p-3 bg-gray-50 hover:bg-gray-100 rounded-xl flex justify-between items-center transition-colors">
                                    <div class="font-medium text-gray-800 flex items-center gap-2">
                                        <div class="w-2 h-2 bg-amber-500 rounded-full"></div>
                                        <?php echo htmlspecialchars($taskRow['title']); ?>
                                    </div>
                                    <div class="text-xs text-gray-500 bg-white px-2 py-1 rounded-md shadow-sm">By: <?php echo is_numeric($taskRow['created_by']) ? 'Unknown' : htmlspecialchars($taskRow['created_by']); ?></div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>

                <!-- CARD 2: DUE TASKS -->
                <div class="glass-card rounded-3xl p-6 hover-lift">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">📅</span>
                            <h2 class="text-xl font-bold text-gray-800">Due/Overdue</h2>
                        </div>
                        <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-semibold">
                            <?php
                            $dueQuery = mysqli_query($conn, "SELECT COUNT(*) as count FROM tasks WHERE status='pending' AND (due_date = CURDATE() OR due_date < CURDATE()) AND is_deleted=0");
                            $dueCount = mysqli_fetch_assoc($dueQuery)['count'];
                            echo $dueCount;
                            ?>
                        </span>
                    </div>
                    <div class="space-y-3">
                        <?php
                        $dueTasksQuery = mysqli_query($conn, "SELECT title, due_date FROM tasks WHERE status='pending' AND (due_date = CURDATE() OR due_date < CURDATE()) AND is_deleted=0 ORDER BY due_date ASC LIMIT 5");
                        if (mysqli_num_rows($dueTasksQuery) == 0) {
                            echo '<p class="text-gray-500 text-sm text-center py-4">No due or overdue tasks</p>';
                        } else {
                            while ($taskRow = mysqli_fetch_assoc($dueTasksQuery)) {
                                $isOverdue = strtotime($taskRow['due_date']) < strtotime(date('d-m-Y'));
                                $colorClass = $isOverdue ? 'text-red-600 bg-red-50' : 'text-amber-600 bg-amber-50';
                        ?>
                                <div class="p-3 bg-gray-50 hover:bg-gray-100 rounded-xl flex justify-between items-center transition-colors">
                                    <div class="font-medium text-gray-800 flex items-center gap-2">
                                        <div class="w-2 h-2 <?php echo $isOverdue ? 'bg-red-500' : 'bg-amber-500'; ?> rounded-full"></div>
                                        <?php echo htmlspecialchars($taskRow['title']); ?>
                                    </div>
                                    <div class="text-xs <?php echo $colorClass; ?> px-2 py-1 rounded-md shadow-sm">Due: <?php echo $taskRow['due_date']; ?></div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>

            </div>

            <!-- GRAPH CARD -->
            <div class="glass-card rounded-3xl p-6 mt-6 hover-lift">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-2xl">📊</span>
                    <h2 class="text-xl font-bold text-gray-800">Tasks Overview</h2>
                </div>
                <div class="w-full max-w-md mx-auto">
                    <canvas id="taskChart"></canvas>
                </div>
            </div>

            <?php
            // Fetch counts for chart
            $pendingChartQuery = mysqli_query($conn, "SELECT COUNT(*) as count FROM tasks WHERE status='pending' AND is_deleted=0");
            $pendingChartCount = mysqli_fetch_assoc($pendingChartQuery)['count'];

            $completedChartQuery = mysqli_query($conn, "SELECT COUNT(*) as count FROM tasks WHERE status='completed' AND is_deleted=0");
            $completedChartCount = mysqli_fetch_assoc($completedChartQuery)['count'];
            ?>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('taskChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Pending', 'Completed'],
                            datasets: [{
                                data: [<?php echo $pendingChartCount; ?>, <?php echo $completedChartCount; ?>],
                                backgroundColor: [
                                    'rgba(245, 158, 11, 0.8)', // Yellow/Amber
                                    'rgba(16, 185, 129, 0.8)' // Green
                                ],
                                borderColor: [
                                    'rgba(245, 158, 11, 1)',
                                    'rgba(16, 185, 129, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                },
                                title: {
                                    display: false
                                }
                            },
                            cutout: '70%'
                        }
                    });
                });
            </script>

        <?php

        }

        /* TASK PAGE */ elseif ($page == "tasks") {

            include("pages/tasks-content.php");
        } elseif ($page == "orders") {

            include("orders/order_list.php");
        } elseif ($page == "add_order") {

            include("orders/order_add.php");
        } elseif ($page == "edit_order") {

            include("orders/order_edit.php");
        }

        ?>

    </main>

</div>

<!-- IMAGE MODAL -->
<div
    id="imageModal"
    onclick="closeImage()"
    class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden flex items-center justify-center p-6"
    style="z-index: 9999;">

    <div
        class="relative max-w-4xl"
        onclick="event.stopPropagation()">
        <button
            onclick="closeImage()"
            class="absolute -top-12 right-0 text-white text-4xl">
            &times;
        </button>

        <img
            id="modalImage"
            src=""
            class="max-h-[85vh] rounded-2xl shadow-2xl">
    </div>

</div>

<script>
$(document).ready(function() {
    const $notiBtn = $('#notiBtn');
    const $attachBtn = $('#attachBtn');
    const $notiDrop = $('#notificationsDropdown');
    const $attachDrop = $('#attachmentsDropdown');

    // Toggle notifications dropdown programmatically
    $notiBtn.on('click', function(e) {
        e.stopPropagation();
        $attachDrop.addClass('hidden');
        $notiDrop.toggleClass('hidden');
    });

    // Toggle attachments dropdown programmatically
    $attachBtn.on('click', function(e) {
        e.stopPropagation();
        $notiDrop.addClass('hidden');
        $attachDrop.toggleClass('hidden');
    });

    // Handle clicks outside either dropdown to safely close them
    $(document).on('click', function(e) {
        // Close notifications if click is outside the button and the dropdown list
        if (!$notiBtn.is(e.target) && $notiBtn.has(e.target).length === 0 &&
            !$notiDrop.is(e.target) && $notiDrop.has(e.target).length === 0) {
            $notiDrop.addClass('hidden');
        }

        // Close attachments if click is outside the button and the dropdown list
        if (!$attachBtn.is(e.target) && $attachBtn.has(e.target).length === 0 &&
            !$attachDrop.is(e.target) && $attachDrop.has(e.target).length === 0) {
            $attachDrop.addClass('hidden');
        }
    });
});

function openImage(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImage() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = '';
}
</script>

<?php include("includes/footer.php"); ?>