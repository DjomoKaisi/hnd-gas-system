<?php
require_once "../config.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'supplier'){
header("Location: ../login.php");
exit();
}

if(isset($_POST['add'])){

$gas_name = $_POST['gas_name'];
$company = $_POST['company'];
$size = $_POST['size'];

if($size == "small"){
$price = 3500;
}else{
$price = 6500;
}

$sql = "INSERT INTO gas_bottles (supplier_id,gas_name,company,size,price)
VALUES (?,?,?,?,?)";

$stmt = $conn->prepare($sql);

$stmt->execute([
$_SESSION['user_id'],
$gas_name,
$company,
$size,
$price
]);

echo "Gas bottle added successfully!";
}
?>

<h2>Add Gas Bottle</h2>

<form method="POST">

Gas Name  
<input type="text" name="gas_name" required><br><br>

Company  
<input type="text" name="company" placeholder="Total, Bocom, Tradex" required><br><br>

Size  
<select name="size">
<option value="small">Small (3500)</option>
<option value="big">Big (6500)</option>
</select><br><br>

<button name="add">Add Gas</button>

</form>

<a href="dashboard.php">Back to Dashboard</a>