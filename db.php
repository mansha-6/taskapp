<?php

// Dynamic Database Connection
// Automatically detects if running locally on XAMPP or online on InfinityFree

if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
    // Local XAMPP Database Settings
    $conn = mysqli_connect(
        "localhost",
        "root",
        "",
        "loginform"
    );
} else {
    // Online InfinityFree Database Settings
    $conn = mysqli_connect(
        "sql212.infinityfree.com",
        "if0_42046545",
        "DkDiOxapRs",
        "if0_42046545_loginform"
    );
}

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
?>