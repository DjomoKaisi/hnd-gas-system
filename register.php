<?php
require_once "config.php";

$error = '';
$success = '';

if(isset($_POST['register'])) {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];

        try {
                $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$name, $email, $password, $role]);
                $success = "Registration successful — you can now log in.";
        } catch (Exception $e) {
                $error = "Could not register. The email may already be used.";
        }
}
?>

<!DOCTYPE html>
<html>
<head>
        <title>Register - Gas Bottle System</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="assets/login.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<div class="wrap">
    <div class="form-container">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
            <div><a href="index.php" style="text-decoration:none;color:var(--accent);font-weight:800; border-radius:10px;">← Home</a></div>
            <div><a class="help-link" href="login.php">Already have an account?</a></div>
        </div>

        <div class="form-card">
            <h2>Create a new account</h2>
            <?php if($error): ?>
                <div class="error"><?=htmlspecialchars($error)?></div>
            <?php endif; ?>
            <?php if($success): ?>
                <div class="success"><?=htmlspecialchars($success)?></div>
            <?php endif; ?>

            <form method="POST" novalidate>
                <div class="form-field">
                    <label for="name">Full name</label>
                    <input id="name" type="text" name="name" required placeholder="Your full name">
                </div>

                <div class="form-field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" required placeholder="you@example.com">
                </div>

                <div class="form-field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required placeholder="Choose a secure password">
                </div>

                <div class="form-field">
                    <label for="role">Role</label>
                    <select id="role" name="role">
                        <option value="customer">Customer</option>
                        <option value="supplier">Supplier</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button class="btn btn-primary" type="submit" name="register"><i class="bi bi-person-plus" style="margin-right:8px"></i>Register</button>
                    <a class="help-link" href="login.php"><i class="bi bi-box-arrow-in-right" style="margin-right:6px"></i>Sign in instead</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>