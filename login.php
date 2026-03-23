<?php
require_once "config.php";

$error = '';
$success = '';

if(isset($_POST['login'])) {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                if($user['role'] == 'admin') {
                        header("Location: admin/dashboard.php");
                } elseif($user['role'] == 'customer') {
                        header("Location: customer/dashboard.php");
                } elseif($user['role'] == 'supplier') {
                        header("Location: supplier/dashboard.php");
                } elseif($user['role'] == 'delivery_agent') {
                        header("Location: supplier/delivery_dashboard.php");
                }
                exit();
        } else {
                $error = "Invalid email or password.";
        }
}
?>

<!DOCTYPE html>
<html>
<head>
        <title>Login - Gas Bottle System</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="assets/login.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<div class="wrap">
    <div class="form-container">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
            <div style="display:flex;gap:12px;align-items:center">
                <a href="index.php" style="text-decoration:none;color:var(--accent);font-weight:800">← Home</a>
            </div>
            <div><a class="help-link" href="register.php">Create an account</a></div>
        </div>

        <div class="form-card">
            <h2>Sign in to your account</h2>
            <?php if($error): ?>
                <div class="error"><?=htmlspecialchars($error)?></div>
            <?php endif; ?>
            <?php if($success): ?>
                <div class="success"><?=htmlspecialchars($success)?></div>
            <?php endif; ?>

            <form method="POST" novalidate>
                <div class="form-field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" required placeholder="you@example.com">
                </div>

                <div class="form-field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required placeholder="Your password">
                </div>

                <div class="form-actions">
                    <button class="btn btn-primary" type="submit" name="login"><i class="bi bi-box-arrow-in-right" style="margin-right:8px"></i>Login</button>
                    <a class="help-link" href="#"><i class="bi bi-key-fill" style="margin-right:6px"></i>Forgot password?</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>