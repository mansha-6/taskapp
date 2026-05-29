<?php
session_start();
include("../db.php");

/* INSERT TASK */
if (isset($_POST['add_task'])) {

    $title       = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $due_date    = $_POST['due_date'];
    $status      = "pending";
    
    

    /* DEFAULT ATTACHMENT */
    $attachment = "";

    /* CREATE uploads FOLDER */
    if (!file_exists("../uploads")) {

        mkdir("../uploads", 0777, true);
    }

    /* FILE UPLOAD */
    if (
        isset($_FILES['attachment']) &&
        $_FILES['attachment']['error'] == 0
    ) {

        date_default_timezone_set('Asia/Kolkata');

        $originalName = basename(
            $_FILES['attachment']['name']
        );

        $dateTime = date("d-m-Y_H-i-s");

        /* FINAL FILE NAME */
        $attachment =
            "file_" .
            $dateTime .
            "_" .
            $originalName;

        $tempName =
            $_FILES['attachment']['tmp_name'];

        $destination =
            "../uploads/" . $attachment;

        move_uploaded_file(
            $tempName,
            $destination
        );
    }

    $created_by = $_SESSION['name'] ?? 'Unknown';

    /* INSERT QUERY */
    $sql = "INSERT INTO tasks
    (
        title,
        description,
        due_date,
        status,
        attachment,
        created_by,
        updated_by
    )
    VALUES
    (
        '$title',
        '$description',
        '$due_date',
        '$status',
        '$attachment',
        '$created_by',
        '$created_by'
    )";

    mysqli_query($conn, $sql);

    header(
        "Location: tasks-content.php?success=added"
    );

    exit();
}
include("../includes/header.php");

?>

<div class="flex min-h-screen bg-gray-100">

    <?php include("../includes/sidebar.php"); ?>
    <main class="flex-1 flex flex-col overflow-y-auto">
        <div class="p-4 sm:p-6 lg:p-8 pb-0">

            <div class="top-navbar flex justify-between items-center bg-white shadow-sm rounded-2xl border border-gray-200 px-4 sm:px-6 py-4 relative z-50">

                <button
                    class="hamburger-btn"
                    onclick="openSidebar()"
                    aria-label="Open menu">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4 6h16M4 12h16M4 18h16" />

                    </svg>

                </button>

                <div class="flex items-center gap-3 ml-auto">

                    <div class="relative">

                        <button
                            onclick="toggleAttachmentsDropdown()"
                            class="bg-white border border-gray-200 shadow-sm rounded-full p-2 hover:bg-gray-100">

                            📁

                        </button>

                        <div
                            id="attachmentsDropdown"
                            class="hidden absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-gray-200 z-50 max-h-96 overflow-y-auto">

                            <div class="p-4 border-b font-semibold text-gray-700">
                                Attachments
                            </div>

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

                                    $file = $fileRow['attachment'];

                                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                                    $imgTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                                    $filePath = "uploads/" . basename($file);

                                    if (in_array($ext, $imgTypes)) {
                                ?>

                                        <div
                                            onclick="openImage('<?php echo $filePath; ?>')"
                                            class="cursor-pointer flex items-center gap-3 hover:bg-gray-100 p-2 rounded-xl">

                                            <img
                                                src="<?php echo $filePath; ?>"
                                                class="w-12 h-12 object-cover rounded-lg">

                                            <div class="text-sm text-gray-700 truncate">
                                                <?php echo htmlspecialchars($fileRow['title']); ?>
                                            </div>

                                        </div>

                                    <?php } else { ?>

                                    <a
                                            href="<?php echo $filePath; ?>"
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
                                ?>

                            </div>

                        </div>

                    </div>

                    <a
                        href="../logout.php"
                        class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-lg text-sm shadow-sm">

                        Logout

                    </a>

                </div>

            </div>

        </div>

        <div class="p-4 sm:p-6 lg:p-10">

            <div class="max-w-3xl mx-auto">

                <div class="bg-white rounded-3xl shadow-lg p-6 sm:p-8">

                    <div class="mb-8">

                        <h1 class="text-3xl font-bold text-gray-800">
                            Add New Task
                        </h1>

                        <p class="text-gray-500 mt-2">
                            Create and manage your workflow tasks
                        </p>

                    </div>

                    <form
                        method="POST"
                        enctype="multipart/form-data"
                        class="space-y-6"
                         id="taskForm">

                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Task Title<span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="title"
                                required
                                placeholder="Enter task title"
                                class="w-full border border-gray-300 p-4 rounded-xl outline-none focus:ring-2 focus:ring-blue-500">

                        </div>

                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Description<span class="text-red-500">*</span>
                            </label>

                            <textarea
                                name="description"
                                rows="5"
                                required
                                placeholder="Enter task description"
                                class="w-full border border-gray-300 p-4 rounded-xl outline-none focus:ring-2 focus:ring-blue-500"></textarea>

                        </div>

                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Deadline<span class="text-red-500">*</span>
                            </label>

                            <input
                                type="date"
                                name="due_date"
                                required
                                min="<?php echo date('Y-m-d'); ?>"
                                class="w-full border border-gray-300 p-4 rounded-xl outline-none focus:ring-2 focus:ring-blue-500">

                        </div>

                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Attachment <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="file"
                                name="attachment"
                                required
                                class="w-full border border-gray-300 p-3 rounded-xl bg-white">

                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 pt-2">

                            <button
                                type="submit"
                                name="add_task"
                                onclick="validateTaskForm()"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl transition">

                                Add Task

                            </button>

                            <a
                                href="tasks-content.php"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-xl text-center transition">

                                Cancel

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

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
    function confirmAddTask() {

        return confirm(
            "Are you sure you want to add this task?"
        );

    }
    function toggleAttachmentsDropdown() {

    document
        .getElementById('attachmentsDropdown')
        .classList.toggle('hidden');
}

document.addEventListener('click', function(e) {

    const drop = document.getElementById('attachmentsDropdown');

    if (
        drop &&
        !drop.contains(e.target) &&
        !e.target.closest('button')
    ) {

        drop.classList.add('hidden');
    }

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
<script>

function validateTaskForm() {

    const title = document.getElementById('title').value.trim();
    const description = document.getElementById('description').value.trim();
    const dueDate = document.getElementById('due_date').value.trim();
    const attachment = document.getElementById('attachment').value;

    if (
        title === '' ||
        description === '' ||
        dueDate === '' ||
        attachment === ''
    ) {

        Swal.fire({
            icon: 'warning',
            title: 'Incomplete Details',
            text: 'Please fill all details first.'
        });

        return;
    }

    document.getElementById('taskForm').submit();
}

</script>

<?php include("../includes/footer.php"); ?>