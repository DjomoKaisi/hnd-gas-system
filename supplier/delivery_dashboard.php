<?php
require_once "../config.php";
require_once "../includes/header.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'delivery_agent'){
header("Location: ../login.php");
exit();
}

$sql = "SELECT * FROM orders WHERE delivery_agent_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h2>My Deliveries</h2>

<table border="1">

<tr>
<th>Client</th>
<th>Phone</th>
<th>Location</th>
<th>Gas Size</th>
<th>Gas Company</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php foreach($orders as $order): ?>

<tr>

<td><?php echo $order['full_name']; ?></td>
<td><?php echo $order['phone']; ?></td>
<td><?php echo $order['location']; ?> - <?php echo $order['quarter']; ?></td>
<td><?php echo $order['gas_size']; ?></td>
<td><?php echo $order['gas_company']; ?></td>
<td><?php echo $order['status']; ?></td>

<td>
<a href="mark_delivered.php?order_id=<?php echo $order['id']; ?>">Mark Delivered</a>
</td>

</tr>

<?php endforeach; ?>

</table>

<br>

<a href="../logout.php">Logout</a>

