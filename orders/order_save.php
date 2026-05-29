<?php

session_start();
include("../db.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") { //Run this code only when form is submitted.

    $customer_name = $_POST['customer_name'];
    $order_date = $_POST['order_date'];

    $created_by = $_SESSION['name'] ?? 'Admin';

    //for insert head 

    $headQuery = mysqli_query(

        $conn,

        "INSERT INTO head
        (
            customer_name,
            order_date,
            created_by,
            is_deleted
        )
        VALUES
        (
            '$customer_name',
            '$order_date',
            '$created_by',
            0
        )"
    );

    $head_id = mysqli_insert_id($conn); //Get ID of newly inserted head row.

    //for details table

    $products = $_POST['product_name'];
    $qtys = $_POST['quantity'];
    $prices = $_POST['price'];

    for ($i = 0; $i < count($products); $i++) {

        $product = mysqli_real_escape_string(
            $conn,
            $products[$i] //Take current row product
        );

        $qty = max(1, intval($qtys[$i]));

        $price = max(0.00, floatval($prices[$i]));

    
        $total = $qty * $price;

        mysqli_query(

            $conn,

            "INSERT INTO details
            (
                head_id,
                product_name,
                quantity,
                price,
                total,
                is_deleted,
                created_by
            )
            VALUES
            (
                '$head_id',
                '$product',
                '$qty',
                '$price',
                '$total',
                0,
                '$created_by'
            )"
        );
    }

    header(
        "Location: order_list.php?success=added"
    );

}
?>