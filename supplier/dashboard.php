<?php
require_once "../config.php";
require_once "../includes/header.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'supplier'){
    header("Location: ../login.php");
    exit();
}

$sql = "SELECT orders.*, users.name AS supplier_name
FROM orders
JOIN users ON orders.supplier_id = users.id
WHERE delivery_agent_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_orders = count($orders);
$pending_orders = 0;
$completed_orders = 0;

foreach ($orders as $order) {
    $status = strtolower($order['status'] ?? '');
    if ($status === 'pending' || $status === 'assigned') {
        $pending_orders++;
    }
    if ($status === 'completed') {
        $completed_orders++;
    }
}
?>

<link rel="stylesheet" href="../includes/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<div class="supplier-layout">

    <aside class="supplier-sidebar">
        <div>
            <div class="supplier-brand">
                <div class="supplier-brand-icon">
                    <i class="bi bi-fuel-pump"></i>
                </div>
                <div>
                    <h2>GasOrder</h2>
                    <p>SUPPLIER PORTAL</p>
                </div>
            </div>

            <nav class="supplier-nav">
                <a href="view_orders.php" class="supplier-nav-item active">
                    <i class="bi bi-receipt-cutoff"></i>
                    <span>Orders</span>
                </a>

                <a href="delivery_agents.php" class="supplier-nav-item">
                    <i class="bi bi-people-fill"></i>
                    <span>Agents</span>
                </a>
                <a href="edit_gas.php" class="supplier-nav-item">
                    <i class="bi bi-people-fill"></i>
                    <span>My Gas</span>
                </a>

                 <a href="create_delivery_agents.php" class="supplier-nav-item">
                    <i class="bi bi-people-fill"></i>
                    <span>Create my Agents</span>
                </a>

                <a href="../logout.php" class="supplier-nav-item">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </div>

        <div class="supplier-profile">
            <div class="supplier-avatar">
                <i class="bi bi-person-fill"></i>
            </div>
            <div>
                <strong><?php echo htmlspecialchars($_SESSION['name'] ?? 'Supplier User'); ?></strong>
                <span>Regional Manager</span>
            </div>
        </div>
    </aside>

    <main class="supplier-main">
        <div class="supplier-topbar">
            <div>
                <h1>Supplier Dashboard</h1>
                <p>Overview of your current delivery operations</p>
            </div>

            <div class="supplier-topbar-right">
                <i class="bi bi-bell-fill"></i>
                <span><?php echo date('M d, Y'); ?></span>
            </div>
        </div>

        <div class="supplier-stats">
            <div class="supplier-stat-card">
                <div class="supplier-stat-top">
                    <div class="supplier-stat-icon orange">
                        <i class="bi bi-bar-chart-line-fill"></i>
                    </div>
                    <span class="supplier-trend up">▲ 12%</span>
                </div>
                <h3>Total Orders</h3>
                <p><?php echo $total_orders; ?></p>
            </div>

            <div class="supplier-stat-card">
                <div class="supplier-stat-top">
                    <div class="supplier-stat-icon yellow">
                        <i class="bi bi-bag-check"></i>
                    </div>
                </div>
                <h3>Pending</h3>
                <p><?php echo $pending_orders; ?></p>
            </div>

            <div class="supplier-stat-card">
                <div class="supplier-stat-top">
                    <div class="supplier-stat-icon green">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                </div>
                <h3>Completed</h3>
                <p><?php echo $completed_orders; ?></p>
            </div>
        </div>

        <section class="supplier-table-card">
            <div class="supplier-table-header">
                <h2>Assigned Orders</h2>

                <div class="supplier-search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search orders...">
                </div>
            </div>

            <div class="supplier-table-wrap">
                <table class="supplier-table">
                    <thead>
                        <tr>
                            <th>ORDER ID</th>
                            <th>CUSTOMER NAME</th>
                            <th>GAS TYPE</th>
                            <th>QUANTITY</th>
                            <th>STATUS</th>
                            <th>DATE</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($orders)): ?>
                            <?php foreach ($orders as $order): ?>
                                <?php
                                    $status = strtolower($order['status'] ?? 'assigned');
                                    $customerName = $order['customer_name'] ?? $order['name'] ?? 'Customer';
                                    $gasType = $order['gas_type'] ?? $order['gas_name'] ?? 'Gas';
                                    $quantity = $order['quantity'] ?? 1;
                                    $date = !empty($order['created_at']) ? date('Y-m-d', strtotime($order['created_at'])) : '-';
                                ?>
                                <tr>
                                    <td class="supplier-order-id">#ORD-<?php echo htmlspecialchars($order['id']); ?></td>
                                    <td><?php echo htmlspecialchars($customerName); ?></td>
                                    <td><?php echo htmlspecialchars($gasType); ?></td>
                                    <td><?php echo htmlspecialchars($quantity); ?></td>
                                    <td>
                                        <span class="supplier-status <?php echo htmlspecialchars($status); ?>">
                                            <?php echo htmlspecialchars(ucfirst($status)); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($date); ?></td>
                                    <td>
                                        <a class="supplier-action-link" href="my_gas.php">View Details</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="supplier-empty">No assigned orders found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="supplier-table-footer">
                <span>Showing <?php echo count($orders); ?> of <?php echo $pending_orders; ?> pending assignments</span>
                <div class="supplier-pagination">
                    <button type="button">Previous</button>
                    <button type="button">Next</button>
                </div>
            </div>
        </section>
    </main>
</div>