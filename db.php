<?php

// Dynamic Database Connection using git-ignored config file
// This keeps your credentials secure and allows you to keep your GitHub repository public!

$config_file = __DIR__ . '/config.php';

if (file_exists($config_file)) {
    require_once $config_file;
} else {
    die("Configuration file 'config.php' is missing. Please copy 'config.example.php' to 'config.php' and fill in your details.");
}

if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
    // Local XAMPP Database Settings
    $conn = mysqli_connect(
        DB_HOST_LOCAL,
        DB_USER_LOCAL,
        DB_PASS_LOCAL,
        DB_NAME_LOCAL
    );
} else {
    // Online InfinityFree Database Settings
    $conn = mysqli_connect(
        DB_HOST_PROD,
        DB_USER_PROD,
        DB_PASS_PROD,
        DB_NAME_PROD
    );
}

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
?>