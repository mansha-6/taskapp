<?php
session_start();
include("../db.php");

$id = intval($_GET['id']);

$updated_by = $_SESSION['name'] ?? 'Unknown';

$sql = "UPDATE tasks 
SET 
    is_deleted = 1,
    updated_at = NOW(),
    updated_by = '$updated_by'
WHERE id = $id";

mysqli_query($conn, $sql);

header("Location: tasks-content.php?success=deleted");
exit();
?>