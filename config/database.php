<?php
$conn = new mysqli('db', 'root', 'root', 'delivery_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>