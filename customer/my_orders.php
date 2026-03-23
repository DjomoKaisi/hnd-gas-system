<?php
require_once "../config.php";
require_once "../includes/header.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer'){
    header("Location: ../login.php");
    exit();
}

// Requête unique pour récupérer les commandes et le nom du livreur
$sql = "SELECT orders.*, users.name AS delivery_name 
        FROM orders 
        LEFT JOIN users ON orders.delivery_agent_id = users.id 
        WHERE customer_id = ? 
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="../includes/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<div class="admin-layout">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2><i class="bi bi-fuel-pump"></i> GasOrder</h2>
            <p>Customer Dashboard</p>
        </div>
        <nav class="sidebar-nav">
            <a href="place_order.php" class="nav-item">
                <i class="bi bi-cart-plus"></i> <span>New Order</span>
            </a>
            <a href="#" class="nav-item active">
                <i class="bi bi-clock-history"></i> <span>My orders</span>
            </a>
        </nav>
        <a href="../logout.php" class="sidebar-logout">
            <i class="bi bi-box-arrow-left"></i> <span>Logout</span>
        </a>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <h1>View Orders</h1>
            <div class="admin-profile">
                <div class="avatar"><i class="bi bi-person"></i></div>
            </div>
        </div>

        <section class="table-card">
            <div class="table-header">
                <h2>Order tracking</h2>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Gaz Details</th>
                            <th>Location</th>
                            <th>Agent</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($orders as $order): ?>
                        <tr>
                            <td>
                                <strong><?php echo ucfirst($order['gas_size']); ?></strong><br>
                                <span style="font-size: 0.85rem; color: #666;"><?php echo htmlspecialchars($order['gas_company']); ?></span>
                            </td>
                            <td>
                                <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($order['location']); ?><br>
                                <small><?php echo htmlspecialchars($order['quarter']); ?></small>
                            </td>
                            <td>
                                <?php if($order['delivery_name']): ?>
                                    <div style="display: flex; align-items: center; gap: 5px;">
                                        <i class="bi bi-person-badge"></i> <?php echo htmlspecialchars($order['delivery_name']); ?>
                                    </div>
                                <?php else: ?>
                                    <span style="color: #999; font-style: italic;">Pending...</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                    $statusClass = strtolower($order['status']);
                                    $statusLabel = ucfirst($order['status']);
                                    
                                    // Traduction personnalisée pour l'utilisateur
                                    if($order['status'] == 'pending') $statusLabel = "pending";
                                    if($order['status'] == 'assigned') $statusLabel = "ungoing";
                                    if($order['status'] == 'delivered') $statusLabel = "delivered";
                                    if($order['status'] == 'completed') $statusLabel = "completed";
                                ?>
                                <span class="status-badge <?php echo $statusClass; ?>">
                                    <?php echo $statusLabel; ?>
                                </span>
                            </td>
                            <td>
                                <?php if($order['status'] == 'delivered'): ?>
                                    <a href="confirm_order.php?order_id=<?php echo $order['id']; ?>" class="action-btn" style="background: #27ae60; color: white; text-decoration: none; padding: 5px 10px; border-radius: 4px; font-size: 0.8rem;">
                                        Confirm delivery
                                    </a>
                                <?php elseif($order['status'] == 'completed'): ?>
                                    <span style="color: #27ae60;"><i class="bi bi-check2-all"></i> Delivered</span>
                                <?php else: ?>
                                    <span style="font-size: 0.8rem; color: #999;">Pending</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                        <?php if(empty($orders)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px; color: #888;">
                                You have not yet placed an order.
                            </td>
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