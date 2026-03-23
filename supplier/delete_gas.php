<?php
require_once "../config.php";

$id = $_GET['id'];

$sql = "DELETE FROM gas_bottles WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);

header("Location: my_gas.php");
exit();
?>
