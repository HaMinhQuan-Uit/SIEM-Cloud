<?php
$conn = new mysqli(
    "192.168.20.220",
    "awsuser", // The mysql user name
    "", // The password for the mysql user
    "website_db"
);

if ($conn->connect_error) {
    die("Database connection error");
}
?>
