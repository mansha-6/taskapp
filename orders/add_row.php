<?php
// include("includes/header.php");
// include("includes/sidebar.php");
// include("includes/task-content.php");
// include("includes/footer.php");
session_start();
include("../db.php");

if (isset($_GET['detail_id'])) {
    $detail_id = mysqli_real_escape_string($conn, $_GET['detail_id']);


    $query = mysqli_query($conn, "SELECT * FROM details WHERE id='$detail_id'");
    // Convert DB row into associative array.
    if ($row = mysqli_fetch_assoc($query)) {
        $head_id = $row['head_id'];
        $product_name = '';
        $quantity = 0;
        $price = 0.00;
        $total = 0.00;
        $created_by = $_SESSION['name'] ?? 'Admin'; //Null Coalescing Operator

        // Insert duplicate row in the details table
        mysqli_query($conn, "INSERT INTO details (head_id, product_name, quantity, price, total, created_by) 
                             VALUES ('$head_id', '$product_name', '$quantity', '$price', '$total', '$created_by')");
    }
}

header("Location: ../orders-list.php");
exit();
?>
