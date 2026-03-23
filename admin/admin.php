<?php
require_once "../config.php";

$name = "System Admin";
$email = "admin@gas.com";
$password = password_hash("admin123", PASSWORD_BCRYPT);
$role = "admin";

$sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->execute([$name, $email, $password, $role]);

echo "Admin created!";
?>