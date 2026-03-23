<?php
require_once "../config.php";
require_once "../includes/header.php"; // Inclure le header pour la session et le head HTML

// Protection de la page (Optionnel mais conseillé si c'est pour les fournisseurs)
if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$sql = "SELECT * FROM users 
        WHERE role='delivery_agent' 
        AND supplier_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="../includes/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<div class="admin-layout">

    <aside class="sidebar">
        <div>
            <div class="sidebar-brand">
                <h2><i class="bi bi-fuel-pump"></i> GasOrder</h2>
                <p>SUPPLIER PORTAL</p>
            </div>

            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-item">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
                <a href="create_delivery_agents.php" class="nav-item">
                    <i class="bi bi-grid"></i>
                    <span>Create agents</span>
                </a>    
                <a href="#" class="nav-item active">
                    <i class="bi bi-truck"></i>
                    <span>Delivery Agents</span>
                </a>
                <a href="orders.php" class="nav-item">
                    <i class="bi bi-bag"></i>
                    <span>Orders</span>
                </a>
            </nav>
        </div>

        <a href="../logout.php" class="sidebar-logout">
            <i class="bi bi-box-arrow-left"></i>
            <span>Logout</span>
        </a>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <h1>Delivery Agents</h1>
            <div class="topbar-right">
                <button class="action-btn" style="background: var(--primary-color); color: white; padding: 8px 15px; border-radius: 6px; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-plus-lg"></i> Add New Agent
                </button>
                <div class="admin-profile">
                    <div class="avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <section class="table-card">
            <div class="table-header">
                <h2>Manage your fleet</h2>
                <span>Total: <?php echo count($agents); ?> agents</span>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>AGENT NAME</th>
                            <th>EMAIL ADDRESS</th>
                            <th>STATUS</th>
                            <th style="text-align: right;">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($agents as $agent): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div class="avatar-small" style="width: 30px; height: 30px; background: #eee; border-radius: 50%; display: grid; place-items: center;">
                                            <i class="bi bi-person" style="font-size: 0.9rem;"></i>
                                        </div>
                                        <strong><?php echo htmlspecialchars($agent['name']); ?></strong>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($agent['email']); ?></td>
                                <td>
                                    <span class="status-badge active">Active</span>
                                </td>
                                <td style="text-align: right;">
                                    <button class="action-btn">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="action-btn" style="color: #e74c3c;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if(empty($agents)): ?>
                            <tr>
                                <td colspan="4" class="empty-row">No delivery agents found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

</body>
</html>