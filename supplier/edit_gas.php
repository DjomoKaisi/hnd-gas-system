<?php
require_once "../config.php";

$id = $_GET['id'];

$sql = "SELECT * FROM gas_bottles WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$gas = $stmt->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['update'])){

$gas_name = $_POST['gas_name'];
$company = $_POST['company'];

$sql = "UPDATE gas_bottles SET gas_name=?, company=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->execute([$gas_name,$company,$id]);

header("Location: my_gas.php");
}
?>

<form method="POST">

Gas Name  
<input type="text" name="gas_name" value="<?php echo $gas['gas_name']; ?>"><br><br>

Company  
<input type="text" name="company" value="<?php echo $gas['company']; ?>"><br><br>

<button name="update">Update</button>

</form>
