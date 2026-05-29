<?php

session_start();
include("../db.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $head_id = mysqli_real_escape_string($conn, $_POST['head_id']);
    $customer = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $order_date = mysqli_real_escape_string($conn, $_POST['order_date']);
    $updated_by = $_SESSION['name'] ?? 'Admin';

    mysqli_query(
        $conn,
        "UPDATE head
        SET
        customer_name='$customer',
        order_date='$order_date',
        updated_by='$updated_by'
        WHERE id='$head_id'"
    );

    // Compile the submitted detail IDs
    $submitted_detail_ids = []; //Store rows still present in frontend.
    if (isset($_POST['detail_id'])) { //is used to check wether this form send details id or not 
        foreach ($_POST['detail_id'] as $did) {
            if (!empty($did)) { // used to skip blank rows
                $submitted_detail_ids[] = intval($did);
            }
        }
    }

    // Soft-delete items that were removed in the frontend (in a single elegant query)
    $notInSql = !empty($submitted_detail_ids) ? "AND id NOT IN (" . implode(",", $submitted_detail_ids) . ")" : "";
    mysqli_query($conn, "UPDATE details SET is_deleted = 1, updated_by = '$updated_by' WHERE head_id = '$head_id' AND is_deleted = 0 $notInSql");

    // Process all rows from the submitted table in a clean foreach loop
    $products = $_POST['product_name'] ?? [];
    $qtys = $_POST['quantity'] ?? [];
    $prices = $_POST['price'] ?? [];
    $detail_ids = $_POST['detail_id'] ?? [];

    // Check the updates from the edit page and update the details table accordingly
    foreach ($products as $i => $prod) { //$i means: row index.
        $product = mysqli_real_escape_string($conn, $prod);
        $qty = max(1, intval($qtys[$i] ?? 1));
        $price = max(0.00, floatval($prices[$i] ?? 0.00));
        $total = $qty * $price;
        $detail_id = !empty($detail_ids[$i]) ? intval($detail_ids[$i]) : null; //If row already exists:store id.Else:new row.

        if ($detail_id !== null) {
            // Update existing detail row
            mysqli_query($conn,"UPDATE details SET product_name='$product', quantity='$qty', price='$price', total='$total', updated_by='$updated_by' WHERE id='$detail_id' AND head_id='$head_id'"
            );
        } else {
            // Insert new detail row (using updated_by as created_by fallback, saving a DB query)
            mysqli_query(
                $conn,
                "INSERT INTO details (head_id, product_name, quantity, price, total, created_by, updated_by, is_deleted) 
                 VALUES ('$head_id', '$product', '$qty', '$price', '$total', '$updated_by', '$updated_by', 0)"
            );
        }
    }

    header("Location: order_list.php?success=updated");
    exit();
}
?>