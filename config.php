<?php
session_start();

$host = "localhost";
$dbname = "gas_delivery_system";
$username = "root";
$password = ""; // default for XAMPP
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}


?>