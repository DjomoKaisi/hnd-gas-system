<?php

require_once "../config.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'supplier'){
header("Location: ../login.php");
exit();
}

$sql = "SELECT * FROM gas_bottles WHERE supplier_id = ?";

$stmt = $conn->prepare($sql);

$stmt->execute([$_SESSION['user_id']]);

$gas = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h2>My Gas Bottles</h2>

<table border="1">

<tr>
<th>Gas Name</th>
<th>Company</th>
<th>Size</th>
<th>Price</th>
</tr>
<th>Edit</th>
<th>Delete</th>

<?php foreach($gas as $g): ?>

<tr>
<td><?php echo $g['gas_name']; ?></td>
<td><?php echo $g['company']; ?></td>
<td><?php echo $g['size']; ?></td>
<td><?php echo $g['price']; ?></td>
</tr>

<td>
<a href="edit_gas.php?id=<?php echo $g['id']; ?>">Edit</a>
</td>

<td>
<a href="delete_gas.php?id=<?php echo $g['id']; ?>">Delete</a>
</td>

<?php endforeach; ?>

</table>

<br>

<a href="dashboard.php">Back</a>
