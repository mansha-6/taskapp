<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "loginform"
);

if (!$conn) {
    die("Connection Failed");
}