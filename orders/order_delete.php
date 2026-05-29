<?php

include("../db.php");

$id = intval($_GET['id']);

// Soft-delete the order head record
mysqli_query($conn, "UPDATE head SET is_deleted = 1 WHERE id='$id'");

// Soft-delete all associated details records
mysqli_query($conn, "UPDATE details SET is_deleted = 1 WHERE head_id='$id'");

echo "success";
?>