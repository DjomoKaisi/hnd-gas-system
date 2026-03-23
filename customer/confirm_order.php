<?php
require_once "../config.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer'){
header("Location: ../login.php");
exit();
}

$order_id = $_GET['order_id'];

$sql = "UPDATE orders 
SET status='completed'
WHERE id=?";

$stmt = $conn->prepare($sql);
$stmt->execute([$order_id]);

$sql = "UPDATE orders
SET status='delivered'
WHERE id=?";

header("Location: my_orders.php");
exit();
?>