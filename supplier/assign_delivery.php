<?php
require_once "../config.php";
require_once "../includes/header.php";

// 1. Protection d'accès
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'supplier'){
    header("Location: ../login.php");
    exit();
}

// 2. CORRECTION : On vérifie si order_id existe dans l'URL (GET) ou dans le formulaire (POST)
$order_id = $_GET['order_id'] ?? $_POST['order_id'] ?? null;

// Si aucun ID n'est trouvé, on ne peut pas travailler, on redirige
if (!$order_id) {
    header("Location: orders.php?error=missing_id");
    exit();
}

// 3. Récupération des agents pour le menu déroulant
$sql = "SELECT id, name FROM users WHERE role='delivery_agent' AND supplier_id=?";
$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$agents = $stmt->fetchAll(PDO::FETCH_ASSOC);

$status_message = "";

// 4. Traitement de la soumission
if(isset($_POST['assign'])){
    $delivery_agent = $_POST['delivery_agent'];
    
    $sql = "UPDATE orders SET delivery_agent_id=?, status='assigned' WHERE id=?";
    $stmt = $conn->prepare($sql);
    
    if($stmt->execute([$delivery_agent, $order_id])) {
        $status_message = "Agent assigné avec succès !";
    } else {
        $status_message = "Erreur lors de l'assignation.";
    }
}
?>

<link rel="stylesheet" href="../includes/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<div class="admin-layout">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2><i class="bi bi-fuel-pump"></i> GasOrder</h2>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="nav-item"><i class="bi bi-grid"></i> <span>Dashboard</span></a>
            <a href="orders.php" class="nav-item active"><i class="bi bi-bag"></i> <span>Orders</span></a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <h1>Assigned orders</h1>
        </div>

        <section class="table-card" style="max-width: 600px; margin: 2rem auto; padding: 2rem;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <div style="font-size: 2.5rem; color: #3498db;"><i class="bi bi-truck"></i></div>
                <h3 style="margin-top: 10px;">Orders #GO-<?php echo htmlspecialchars($order_id); ?></h3>
            </div>

            <?php if($status_message): ?>
                <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #c3e6cb;">
                    <i class="bi bi-check-circle-fill"></i> <?php echo $status_message; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Choose Delivery Agents:</label>
                    <select name="delivery_agent" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; background: white;">
                        <option value="">-- Sélectionner --</option>
                        <?php foreach($agents as $agent): ?>
                            <option value="<?php echo $agent['id']; ?>">
                                <?php echo htmlspecialchars($agent['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button name="assign" class="action-btn" style="width: 100%; background: #2ecc71; color: white; border: none; padding: 12px; border-radius: 6px; font-weight: bold; cursor: pointer;">
                    Confirm assgnation
                </button>
                
                <div style="text-align: center; margin-top: 1rem;">
                    <a href="orders.php" style="color: #666; text-decoration: none; font-size: 0.9rem;">Stop/back</a>
                </div>
            </form>
        </section>
    </main>
</div>