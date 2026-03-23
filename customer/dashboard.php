<?php
require_once "../config.php";
require_once "../includes/header.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer'){
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// 1. Correction : user_id -> customer_id
$total_orders_stmt = $conn->prepare("SELECT COUNT(*) FROM orders WHERE customer_id = ?");
$total_orders_stmt->execute([$userId]);
$total_orders = $total_orders_stmt->fetchColumn();

// 2. Correction : user_id -> customer_id
$pending_orders_stmt = $conn->prepare("SELECT COUNT(*) FROM orders WHERE customer_id = ? AND status = 'pending'");
$pending_orders_stmt->execute([$userId]);
$pending_orders = $pending_orders_stmt->fetchColumn();

// 3. Correction : status 'completed' -> 'delivered' (selon votre ENUM SQL) et user_id -> customer_id
$completed_orders_stmt = $conn->prepare("SELECT COUNT(*) FROM orders WHERE customer_id = ? AND status = 'delivered'");
$completed_orders_stmt->execute([$userId]);
$completed_orders = $completed_orders_stmt->fetchColumn();

// 4. Correction : Requête pour les commandes récentes
$recent_orders_stmt = $conn->prepare("
    SELECT *
    FROM orders
    WHERE customer_id = ?
    ORDER BY created_at DESC
    LIMIT 5
");
$recent_orders_stmt->execute([$userId]);
$recent_orders = $recent_orders_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="../includes/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <div class="customer-layout">

        <aside class="customer-sidebar">
            <div>
                <div class="customer-brand">
                    <div class="customer-brand-icon">
                        <i class="bi bi-fuel-pump"></i>
                    </div>
                    <div>
                        <h2>GasOrder</h2>
                        <p>CUSTOMER PORTAL</p>
                    </div>
                </div>

                <nav class="customer-nav">
                    <a href="customer_dashboard.php" class="customer-nav-item active">
                        <i class="bi bi-grid"></i>
                        <span>Overview</span>
                    </a>

                    <a href="place_order.php" class="customer-nav-item">
                        <i class="bi bi-plus-circle"></i>
                        <span>Place Order</span>
                    </a>

                    <a href="my_orders.php" class="customer-nav-item">
                        <i class="bi bi-bag"></i>
                        <span>My Orders</span>
                    </a>

                    <a href="../logout.php" class="customer-nav-item">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>
                </nav>
            </div>

            <div class="customer-profile">
                <div class="customer-avatar">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div>
                    <strong><?php echo htmlspecialchars($_SESSION['name'] ?? 'Customer'); ?></strong>
                    <span>Customer</span>
                </div>
            </div>
        </aside>

        <main class="customer-main">
            <div class="customer-topbar">
                <div>
                    <h1>Customer Dashboard</h1>
                    <p>Welcome — here you can create and track your orders.</p>
                </div>

                <div class="customer-topbar-right">
                    <a href="place_order.php" class="customer-primary-btn">
                        <i class="bi bi-plus-lg"></i> Place Gas Order
                    </a>
                </div>
            </div>

            <div class="customer-stats">
                <div class="customer-stat-card">
                    <div class="customer-stat-top">
                        <div class="customer-stat-icon orange">
                            <i class="bi bi-receipt"></i>
                        </div>
                    </div>
                    <h3>Total Orders</h3>
                    <p><?php echo $total_orders; ?></p>
                </div>

                <div class="customer-stat-card">
                    <div class="customer-stat-top">
                        <div class="customer-stat-icon yellow">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                    <h3>Pending</h3>
                    <p><?php echo $pending_orders; ?></p>
                </div>

                <div class="customer-stat-card">
                    <div class="customer-stat-top">
                        <div class="customer-stat-icon green">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                    <h3>Completed</h3>
                    <p><?php echo $completed_orders; ?></p>
                </div>
            </div>

            <div class="customer-actions">
                <a href="place_order.php" class="customer-action-card">
                    <div class="customer-action-icon">
                        <i class="bi bi-plus-circle-fill"></i>
                    </div>
                    <div>
                        <h4>Place New Order</h4>
                        <p>Create a new gas delivery request</p>
                    </div>
                </a>

                <a href="my_orders.php" class="customer-action-card">
                    <div class="customer-action-icon blue">
                        <i class="bi bi-bag-check-fill"></i>
                    </div>
                    <div>
                        <h4>Track My Orders</h4>
                        <p>View all your current and past orders</p>
                    </div>
                </a>
            </div>

            <section class="customer-table-card">
                <div class="customer-table-header">
                    <h2>Recent Orders</h2>
                    <a href="my_orders.php">View All</a>
                </div>

                <div class="customer-table-wrap">
                    <table class="customer-table">
                        <thead>
                            <tr>
                                <th>ORDER ID</th>
                                <th>GAS TYPE</th>
                                <th>QUANTITY</th>
                                <th>STATUS</th>
                                <th>DATE</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_orders)): ?>
                                <?php foreach ($recent_orders as $order): ?>
                                    <?php
                                        $status = strtolower($order['status'] ?? 'pending');
                                        $gasType = $order['gas_type'] ?? $order['gas_name'] ?? 'Gas';
                                        $quantity = $order['quantity'] ?? 1;
                                        $date = !empty($order['created_at']) ? date('Y-m-d', strtotime($order['created_at'])) : '-';
                                    ?>
                                    <tr>
                                        <td class="customer-order-id">#ORD-<?php echo htmlspecialchars($order['id']); ?></td>
                                        <td><?php echo htmlspecialchars($gasType); ?></td>
                                        <td><?php echo htmlspecialchars($quantity); ?></td>
                                        <td>
                                            <span class="customer-status <?php echo htmlspecialchars($status); ?>">
                                                <?php echo htmlspecialchars(ucfirst($status)); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($date); ?></td>
                                        <td>
                                            <a class="customer-action-link" href="my_orders.php">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="customer-empty">No orders found yet.</td>
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