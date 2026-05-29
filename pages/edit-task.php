<?php
session_start();
include("../db.php");

/* GET TASK ID */
$id = $_GET['id'];

/* FETCH TASK */
$sql = "SELECT * FROM tasks WHERE id=$id";

$result = mysqli_query($conn, $sql);

$task = mysqli_fetch_assoc($result);

/* UPDATE TASK */
if (isset($_POST['update_task'])) {

    $title       = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $due_date    = $_POST['due_date'];
    $status      = mysqli_real_escape_string($conn, $_POST['status']);

    /* KEEP OLD ATTACHMENT */
    $attachment = $task['attachment'];

    /* CREATE uploads FOLDER */
    if (!file_exists("../uploads")) {

        mkdir("../uploads", 0777, true);
    }

    /* NEW FILE UPLOAD */
    if (
        isset($_FILES['task_file']) &&
        $_FILES['task_file']['error'] == 0
    ) {

        $originalName = basename($_FILES['task_file']['name']);

        $dateTime = date('d-m-Y_H-i-s');

        $newFile =
            "file_" .
            $dateTime .
            "_" .
            $originalName;

        $uploadPath = "../uploads/" . $newFile;

        move_uploaded_file(
            $_FILES['task_file']['tmp_name'],
            $uploadPath
        );

        /* DELETE OLD FILE */
        if (
            !empty($attachment) &&
            file_exists("../uploads/" . $attachment)
        ) {

            unlink("../uploads/" . $attachment);
        }

        /* SAVE NEW FILE NAME */
        $attachment = $newFile;
    }

    $updated_by = $_SESSION['name'] ?? 'Unknown';

    /* UPDATE QUERY */
    $update = "UPDATE tasks SET

    title='$title',
    description='$description',
    status='$status',
    due_date='$due_date',
    attachment='$attachment',
    updated_by='$updated_by'

    WHERE id=$id";

    mysqli_query($conn, $update);

    header(
        "Location: tasks-content.php?success=updated"
    );

    exit();
}

/* HEADER AFTER LOGIC */
include("../includes/header.php");

?>

<!-- MAIN LAYOUT -->
<div class="flex min-h-screen bg-gray-100">

    <!-- SIDEBAR -->
    <?php include("../includes/sidebar.php"); ?>

    <!-- CONTENT -->
    <main class="flex-1 flex flex-col overflow-y-auto">

        <!-- TOP NAVBAR -->
        <div class="p-4 sm:p-6 lg:p-8 pb-0">

            <div class="top-navbar flex justify-between items-center bg-white shadow-sm rounded-2xl border border-gray-200 px-4 sm:px-6 py-4 relative z-50">

                <!-- HAMBURGER -->
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

                <!-- LOGOUT -->
                <a
                    href="../logout.php"
                    class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-lg text-sm shadow-sm transition ml-auto">

                    Logout

                </a>

            </div>

        </div>

        <!-- PAGE CONTENT -->
        <div class="p-4 sm:p-6 lg:p-10">

            <div class="max-w-3xl mx-auto">

                <div class="bg-white rounded-3xl shadow-lg p-6 sm:p-8">

                    <!-- HEADER -->
                    <div class="mb-8">

                        <h1 class="text-3xl font-bold text-gray-800">
                            Edit Task
                        </h1>

                        <p class="text-gray-500 mt-2">
                            Modify your task details
                        </p>

                    </div>

                    <!-- FORM -->
                    <form
                        method="POST"
                        enctype="multipart/form-data"
                        class="space-y-6"
                         id="taskForm">

                        <!-- TITLE -->
                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Task Title<span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="title"
                                value="<?php echo htmlspecialchars($task['title']); ?>"
                                required
                                class="w-full border border-gray-300 p-4 rounded-xl outline-none focus:ring-2 focus:ring-blue-500">

                        </div>

                        <!-- DESCRIPTION -->
                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Description<span class="text-red-500">*</span>
                            </label>

                            <textarea
                                name="description"
                                rows="5"
                                required
                                class="w-full border border-gray-300 p-4 rounded-xl outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($task['description']); ?></textarea>

                        </div>

                        <!-- DEADLINE -->
                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Deadline<span class="text-red-500">*</span>
                            </label>

                            <input
                                type="date"
                                name="due_date"
                                value="<?php echo $task['due_date']; ?>"
                                required
                                min="<?php echo date('Y-m-d'); ?>"
                                class="w-full border border-gray-300 p-4 rounded-xl outline-none focus:ring-2 focus:ring-blue-500">

                        </div>

                        <!-- STATUS -->
                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status<span class="text-red-500">*</span>
                            </label>

                            <select
                                name="status"
                                required
                                class="w-full border border-gray-300 p-4 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                <option value="pending" <?php echo ($task['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="completed" <?php echo ($task['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                            </select>

                        </div>

                        <!-- CURRENT ATTACHMENT -->
                        <?php if (!empty($task['attachment'])) { ?>

                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Current Attachment
                            </label>

                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">

                                <a
                                    href="../uploads/<?php echo $task['attachment']; ?>"
                                    target="_blank"
                                    class="text-blue-600 hover:text-blue-800 break-all">

                                    <?php echo htmlspecialchars($task['attachment']); ?>

                                </a>

                            </div>

                        </div>

                        <?php } ?>

                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Replace Attachment<span class="text-red-500">*</span>
                            </label>

                            <input
                                type="file"
                                name="task_file"
                                required
                                class="w-full border border-gray-300 p-3 rounded-xl bg-white">

                            <p class="text-xs text-gray-500 mt-2">
                                Upload a new file to replace existing attachment
                            </p>

                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 pt-2">

                            <button
                                type="submit"
                                name="update_task"
                                onclick="validateTaskForm()"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl transition">

                                Update Task

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