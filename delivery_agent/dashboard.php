<?php
require_once "../config.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'delivery_agent'){
        header("Location: ../login.php");
        exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delivery Agent Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="page-container">
        <div class="mini-card">
            <h1>Delivery Agent Dashboard</h1>
            <p>View your assigned routes and update delivery statuses.</p>
            <p><a href="../logout.php">Logout</a></p>
        </div>
    </div>
</body>
</html>