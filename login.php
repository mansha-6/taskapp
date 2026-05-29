<?php

session_start();

require_once __DIR__ . '/db.php';

if (!isset($conn)) {
    die('Database connection not established.');
}

$name = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';
$password = isset($_POST['pass']) ? mysqli_real_escape_string($conn, $_POST['pass']) : '';

if ($name === '' || $password === '') {
    $_SESSION['error'] = "Please enter both name and password.";
    header("Location: index.php");
    exit();
}

$sql = "SELECT * FROM users WHERE name='$name' AND password='$password'";

$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    $_SESSION['loggedin'] = true;
    $_SESSION['id'] = $row['id'];
    $_SESSION['name'] = $row['name'];
    // $_SESSION['email'] = $row['email'];
    $_SESSION['password'] = $row['password'];

    header("Location: dashboard.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid Name or Password";
    header("Location: index.php");
    exit();
}
?>