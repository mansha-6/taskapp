<?php
session_start();
include("../db.php");

$id = intval($_GET['id']);

$query = "SELECT status FROM tasks WHERE id = $id";

$result = mysqli_query($conn, $query);

$task = mysqli_fetch_assoc($result);

$newStatus = ($task['status'] == 'completed')
? 'pending'
: 'completed';

$updated_by = $_SESSION['name'] ?? 'Unknown';

$update = "UPDATE tasks SET status='$newStatus', updated_at=NOW(), updated_by='$updated_by' WHERE id=$id";

mysqli_query($conn, $update);

echo $newStatus;