<?php

session_start();

session_destroy();

setcookie("email", "", time() - 3600, "/");

session_start();
$_SESSION['success'] = "You have been logged out successfully.";

header("Location: index.php");
exit();

?>