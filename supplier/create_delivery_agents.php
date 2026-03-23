<?php
require_once "../config.php";
require_once "../includes/header.php"; // Intègre les sessions et l'entête HTML

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'supplier'){
    header("Location: ../login.php");
    exit();
}

$message = "";
$message_type = ""; // success ou error

if(isset($_POST['create'])){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // 1. Vérification si l'email existe déjà
    $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkEmail->execute([$email]);

    if($checkEmail->rowCount() > 0) {
        $message = "Désolé, cet email est déjà utilisé par un autre utilisateur.";
        $message_type = "error";
    } else {
        // 2. Insertion du nouvel agent de livraison
        $sql = "INSERT INTO users (name, email, password, role, supplier_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if($stmt->execute([$name, $email, $password, 'delivery_agent', $_SESSION['user_id']])) {
            $message = "L'agent de livraison a été créé avec succès !";
            $message_type = "success";
        } else {
            $message = "Une erreur est survenue lors de la création.";
            $message_type = "error";
        }
    }
}
?>

<link rel="stylesheet" href="../includes/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
    .form-box {
        max-width: 500px;
        margin: 30px auto;
        padding: 30px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #444;
    }
    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }
    .input-wrapper i {
        position: absolute;
        left: 12px;
        color: #777;
    }
    .form-control {
        width: 100%;
        padding: 12px 12px 12px 40px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s;
    }
    .form-control:focus {
        border-color: #27ae60;
        outline: none;
    }
    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
</style>

<div class="admin-layout">

    <aside class="sidebar">
        <div>
            <div class="sidebar-brand">
                <h2><i class="bi bi-fuel-pump"></i> GasOrder</h2>
                <p>PORTAIL FOURNISSEUR</p>
            </div>

            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-item">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
                <a href="delivery_agents.php" class="nav-item active">
                    <i class="bi bi-truck"></i>
                    <span>Livreurs</span>
                </a>
                <a href="orders.php" class="nav-item">
                    <i class="bi bi-bag"></i>
                    <span>Commandes</span>
                </a>
            </nav>
        </div>

        <a href="../logout.php" class="sidebar-logout">
            <i class="bi bi-box-arrow-left"></i>
            <span>Déconnexion</span>
        </a>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <h1>Gestion de la Flotte</h1>
            <a href="delivery_agents.php" class="action-btn" style="text-decoration: none; background: #f1f1f1; color: #333;">
                <i class="bi bi-arrow-left"></i> Retour à la liste
            </a>
        </div>

        <div class="table-card form-box">
            <div style="text-align: center; margin-bottom: 25px;">
                <div style="font-size: 2.5rem; color: #27ae60;"><i class="bi bi-person-plus-fill"></i></div>
                <h2 style="margin-top: 10px;">Nouvel Agent</h2>
                <p style="color: #777;">Remplissez les informations de connexion du livreur</p>
            </div>

            <?php if($message): ?>
                <div class="alert alert-<?php echo $message_type; ?>">
                    <i class="bi <?php echo ($message_type == 'success') ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'; ?>"></i>
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-group">
                    <label>Nom Complet</label>
                    <div class="input-wrapper">
                        <i class="bi bi-person"></i>
                        <input type="text" name="name" class="form-control" placeholder="Jean Kevin" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Adresse Email</label>
                    <div class="input-wrapper">
                        <i class="bi bi-envelope"></i>
                        <input type="email" name="email" class="form-control" placeholder="agent@gasorder.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Mot de passe provisoire</label>
                    <div class="input-wrapper">
                        <i class="bi bi-lock"></i>
                        <input type="password" name="password" class="form-control" placeholder="********" required>
                    </div>
                </div>

                <button type="submit" name="create" class="action-btn" style="width: 100%; padding: 14px; background: #27ae60; color: white; border: none; font-size: 1rem; font-weight: bold; display: flex; justify-content: center; align-items: center; gap: 10px;">
                    <i class="bi bi-person-check"></i> Enregistrer l'agent
                </button>

            </form>
        </div>
    </main>
</div>

</body>
</html>