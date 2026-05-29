<?php
if (!isset($conn)) {
    chdir(__DIR__ . '/..');
    $page = 'tasks';
    include('dashboard.php');
    exit();
}

$success = $_GET['success'] ?? '';

$filter = $_GET['filter'] ?? 'all';

$sql = "SELECT * FROM tasks WHERE is_deleted = 0";

if ($filter == "pending") {

    $sql .= " AND status='pending'";
} elseif ($filter == "completed") {

    $sql .= " AND status='completed'";
}

$sql .= " ORDER BY id DESC";

$result = mysqli_query($conn, $sql);

?>
<style>
    table.dataTable thead th {
        text-align: left !important;
    }
</style>
<div class="page-header flex justify-between items-start mb-8">

    <div>

        <h1 class="text-3xl font-bold text-slate-800">
            Task Management
        </h1>

        <p class="text-slate-500 mt-1">
            Manage all your tasks efficiently
        </p>

    </div>

    <div class="flex items-center gap-3">

        <a
            href="add-task.php"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-xl font-semibold shadow-md shadow-indigo-600/20 hover:shadow-lg transition duration-200 hover:-translate-y-0.5 transform">

            + Add Task

        </a>

    </div>

</div>

<!-- TASK CONTENT — only this div is refreshed by AJAX, scripts stay outside -->
<div id="taskContent">

    <!-- FILTER PILLS -->
    <div class="flex flex-wrap gap-2 mb-6 bg-slate-100/60 backdrop-blur p-1.5 rounded-2xl max-w-max border border-slate-200/40">
        <a
            href="javascript:void(0)"
            onclick="changeFilter('all')"
            class="px-5 py-2.5 rounded-xl text-sm font-semibold transition duration-200 <?php echo ($filter == 'all') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/50'; ?>">

            All

        </a>

        <a
            href="javascript:void(0)"
            onclick="changeFilter('pending')"
            class="px-5 py-2.5 rounded-xl text-sm font-semibold transition duration-200 <?php echo ($filter == 'pending') ? 'bg-amber-500 text-white shadow-md shadow-amber-500/10' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/50'; ?>">

            Pending

        </a>

        <a
            href="javascript:void(0)"
            onclick="changeFilter('completed')"
            class="px-5 py-2.5 rounded-xl text-sm font-semibold transition duration-200 <?php echo ($filter == 'completed') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/10' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/50'; ?>">

            Completed

        </a>

    </div>

    <div class="tasks-desktop-table glass-card rounded-3xl p-6 overflow-x-auto hover-lift">

        <table id="taskTable" class="w-full border-collapse">

            <thead class="bg-gray-50 border-b border-gray-200 border-t">

                <tr>

                    <th class="p-4  text-sm font-semibold text-gray-600 uppercase">Done</th>

                    <th class="p-4  text-sm font-semibold text-gray-600 uppercase">Title</th>

                    <th class="p-4  text-sm font-semibold text-gray-600 uppercase">Description</th>

                    <th class="p-4  text-sm font-semibold text-gray-600 uppercase">
                        Deadline
                    </th>

                    <th class="p-4 text-left text-sm font-semibold text-gray-600 uppercase w-[150px]">
                        View
                    </th>
                    <th class="p-4  text-sm font-semibold text-gray-600 uppercase">
                        Status
                    </th>

                    <th class="p-4  text-sm font-semibold text-gray-600 uppercase">Actions</th>

                </tr>

            </thead>

            <tbody class="divide-y divide-gray-100">

                <?php while ($task = mysqli_fetch_assoc($result)) { ?>

                    <?php

                    $today = new DateTime('today');

                    $dueDate = new DateTime($task['due_date']);

                    $dueDate->setTime(0, 0, 0);

                    $diff_days = (int)$today->diff($dueDate)->format('%R%a');

                    $rowColor = '';

                    if ($task['status'] == 'completed') {

                        $rowColor = 'completed-task';
                    }

                     elseif ($diff_days < 0) {

                        $rowColor = 'highlight-overdue';
                    }

                    elseif ($diff_days == 0) {

                        $rowColor = 'highlight-today';
                    }

                     elseif ($diff_days <= 4) {

                        $rowColor = 'highlight-upcoming';
                    }
                    ?>

                    <tr class="<?php echo $rowColor; ?>"> 
                        <td class="p-4 text-left align-middle">
                            <input
                                type="checkbox"
                                class="w-5 h-5 accent-green-500 cursor-pointer"
                                <?php echo ($task['status'] == 'completed') ? 'checked' : ''; ?>
                                onclick="confirmToggle(event, this, <?php echo $task['id']; ?>)">
                        </td>

                        <!-- TITLE -->
                        <td class="p-4 font-medium text-gray-800">

                            <?php echo htmlspecialchars($task['title']); ?>

                        </td>

                        <!-- DESCRIPTION -->
                        <td class="p-4 text-gray-700">

                            <?php echo htmlspecialchars($task['description']); ?>

                        </td>

                        <td class="p-4 font-semibold" style="text-align: left !important;">
                            <?php echo date("d-m-Y", strtotime($task['due_date'])); ?>
                        </td>
                        <!-- VIEW FILE -->
                        <td class="p-4 text-left !text-left align-middle" style="text-align:left !important;">

                            <?php
                            if (!empty($task['attachment'])) {

                                $file = trim($task['attachment']);

                                /* SERVER PATH */
                                $serverPath = __DIR__ . "/../uploads/" . basename($file);

                                $viewPath = "../uploads/" . basename($file);

                                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                                $imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];                                if (file_exists($serverPath)) {

                                    if (in_array($ext, $imageTypes)) {
                            ?>

                                        <div class="flex justify-start items-center">
                                            <button
                                                onclick="openImage('<?php echo $viewPath; ?>')"
                                                class="flex items-center gap-2 hover:text-purple-500 text-purple-700 py-2 rounded-lg text-sm font-semibold"
                                                style="padding-left: 0 !important; margin-left: 0 !important;">
                                                🖼️ Image
                                            </button>
                                        </div>

                                    <?php } else { ?>

                                        <div class="flex justify-start items-center">
                                            <a
                                                href="<?php echo $viewPath; ?>"
                                                target="_blank"
                                                class="flex items-center gap-2 hover:text-blue-500 text-blue-700 py-2 rounded-lg text-sm font-semibold"
                                                style="padding-left: 0 !important; margin-left: 0 !important;">
                                                📄 File
                                            </a>
                                        </div>

                             <?php
                                    }
                                } else {

                                    echo '<span class="text-red-500 text-sm text-left font-semibold">Missing File</span>';
                                }
                            } else {

                                echo '<span class="text-gray-400 text-sm text-left font-semibold">No File</span>';
                            }
                            ?>

                        </td>
                        <!-- STATUS -->
                        <td class="p-4">

                            <span
                                id="status-<?php echo $task['id']; ?>"

                                class="<?php echo $task['status'] == 'completed'
                                            ? 'bg-green-100 text-green-600'
                                            : 'bg-yellow-100 text-yellow-600'; ?> px-3 py-1 rounded-full text-xs font-semibold">

                                <?php echo ucfirst($task['status']); ?>

                            </span>

                        </td>

                        <!-- ACTIONS -->
                        <td class="p-4">

                            <div class="flex gap-2">

                                <!-- EDIT -->
                                <a
                                    href="edit-task.php?id=<?php echo $task['id']; ?>"
                                    class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm">

                                    Edit

                                </a>

                                <!-- DELETE -->
                                <a
                                    href="delete-task.php?id=<?php echo $task['id']; ?>"
                                    onclick="confirmDelete(event, this.href)"
                                    class="bg-red-500 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm">

                                    Delete

                                </a>

                            </div>

                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>

</div><!-- end #taskContent -->

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

            ×

        </button>

        <img
            id="modalImage"
            src=""
            class="max-h-[85vh] rounded-2xl shadow-2xl">

    </div>

</div>

<script>

    document.addEventListener('DOMContentLoaded', function() {

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        let hasToast = false;

        <?php if ($success == "added") { ?>
            hasToast = true;
            Toast.fire({
                icon: 'success',
                title: 'Task Added Successfully'
            });
        <?php } elseif ($success == "updated") { ?>
            hasToast = true;
            Toast.fire({
                icon: 'info',
                title: 'Task Updated Successfully'
            });
        <?php } elseif ($success == "deleted") { ?>
            hasToast = true;
            Toast.fire({
                icon: 'error',
                title: 'Task Deleted Successfully'
            });
        <?php } ?>

        /* REMOVE success FROM URL */
        if (hasToast) {

            const url = new URL(window.location);

            url.searchParams.delete('success');

            window.history.replaceState({}, '', url);
        }

    });
    /* IMAGE MODAL */

    function openImage(src) {

        document.getElementById('modalImage').src = src;

        document
            .getElementById('imageModal')
            .classList.remove('hidden');

        document.body.style.overflow = 'hidden';
    }

    function closeImage() {

        document
            .getElementById('imageModal')
            .classList.add('hidden');

        document.body.style.overflow = '';

    }

    function getCurrentFilterUrl() {

        const urlParams = new URLSearchParams(window.location.search);

        let filter = urlParams.get('filter') || 'all';

        return `tasks-content.php?filter=${filter}`;

    }
    function changeFilter(filter) {

        const url = `tasks-content.php?filter=${filter}`;

        history.pushState({}, '', url);

        loadTasks(url);

    }
    function loadTasks(url) {

        $.get(url, function(responseText) {

            /* Parse the full HTML response */
            const parser = new DOMParser();

            const doc = parser.parseFromString(responseText, 'text/html');

            const newContent = doc.getElementById('taskContent');

            if (newContent) {

                document.getElementById('taskContent').innerHTML = newContent.innerHTML;

            }

            initializeDataTable();

        });

    }

    function initializeDataTable() {

    if ($.fn.DataTable.isDataTable('#taskTable')) {

        $('#taskTable').DataTable().destroy();

    }

    $('#taskTable').DataTable({

        destroy: true,
        responsive: true,
        autoWidth: false,
        pageLength: 5,
        lengthMenu: [5,10,25,50],
        ordering: true,
        searching: true,

        columnDefs: [
            {
                targets: [4, 5, 6],
                className: "text-left"
            }
        ],

        language: {
            search: "Search Tasks:",
            lengthMenu: "Show _MENU_ Tasks",
            info: "Showing _START_ to _END_ of _TOTAL_ Tasks",
            paginate: {
                previous: "Prev",
                next: "Next"
            }
        }
    });
}

    $(document).ready(function() {

        initializeDataTable();

    });

    function confirmToggle(event, checkbox, taskId) {

        const action = checkbox.checked ?
            'complete' :
            'mark pending';

        Swal.fire({

            title: 'Are you sure?',

            text: `Do you want to ${action} this task?`,

            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#3085d6',

            cancelButtonColor: '#d33',

            confirmButtonText: 'Yes'

        }).then((result) => {

            if (result.isConfirmed) {

                toggleTaskStatus(taskId);

            } else {

                checkbox.checked = !checkbox.checked;

            }

        });

    }

    /*TOGGLE STATUS AJAX */

    function toggleTaskStatus(taskId) {

        fetch(`complete-task.php?id=${taskId}`)

            .then(response => response.text())

            .then(() => {

                loadTasks(getCurrentFilterUrl());

            });

    }
    function confirmDelete(event, url) {

        event.preventDefault();

        Swal.fire({

            title: 'Are you sure?',

            text: "You won't be able to revert this!",

            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#d33',

            cancelButtonColor: '#3085d6',

            confirmButtonText: 'Yes, delete it!'

        }).then((result) => {

            if (result.isConfirmed) {

                fetch(url)

                    .then(() => {

                        loadTasks(getCurrentFilterUrl());

                    });

            }

        });
    }
</script>