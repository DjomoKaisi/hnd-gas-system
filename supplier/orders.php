<?php
require_once "../config.php";
require_once "../includes/header.php";


$sql = "SELECT * FROM orders WHERE supplier_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management</title>
    <link rel="stylesheet" href="../includes/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>

<div class="orders-layout">

    <aside class="orders-sidebar">
        <div>
            <div class="orders-brand">
                <div class="orders-brand-icon">
                    <i class="bi bi-fuel-pump"></i>
                </div>
                <div>
                    <h2>GasOrder</h2>
                    <p>SUPPLIER PORTAL</p>
                </div>
            </div>

            <nav class="orders-nav">
                <a href="supplier_dashboard.php" class="orders-nav-item">
                    <i class="bi bi-grid"></i>
                    <span>Overview</span>
                </a>

                <a href="#" class="orders-nav-item active">
                    <i class="bi bi-bag"></i>
                    <span>Orders</span>
                </a>

                <a href="delivery_agents.php" class="orders-nav-item">
                    <i class="bi bi-people"></i>
                    <span>Agents</span>
                </a>

                <a href="../logout.php" class="orders-nav-item logout-link">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </div>

        <div class="orders-profile">
            <div class="orders-avatar">
                <i class="bi bi-person-fill"></i>
            </div>
            <div>
                <strong><?php echo htmlspecialchars($_SESSION['name'] ?? 'Supplier'); ?></strong>
                <span>Supplier</span>
            </div>
        </div>
    </aside>

    <main class="orders-main">
        <div class="orders-header">
            <div>
                <h1>Orders Management</h1>
                <p>Review and process customer gas delivery requests.</p>
            </div>

            <a href="assign_delivery.php" class="orders-primary-btn">
                <i class="bi bi-plus-lg"></i>
                Assign Delivery
            </a>
        </div>

        <div class="orders-filter-box">
            <div class="orders-filter-grid">
                <div class="orders-filter-group">
                    <label>Search Orders</label>
                    <div class="orders-search-input">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Client, phone, location...">
                    </div>
                </div>

                <div class="orders-filter-group">
                    <label>Status</label>
                    <select>
                        <option>All Statuses</option>
                        <option>Pending</option>
                        <option>Assigned</option>
                        <option>Completed</option>
                        <option>Cancelled</option>
                    </select>
                </div>

                <div class="orders-filter-group">
                    <label>Gas Company</label>
                    <select>
                        <option>All Companies</option>
                        <option>Total</option>
                        <option>Bocom</option>
                        <option>Tradex</option>
                    </select>
                </div>

                <div class="orders-filter-group">
                    <label>&nbsp;</label>
                    <button type="button" class="orders-clear-btn">Clear Filters</button>
                </div>
            </div>
        </div>

        <div class="orders-table-card">
            <div class="orders-table-wrap">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>CLIENT</th>
                            <th>PHONE</th>
                            <th>LOCATION</th>
                            <th>GAS SIZE</th>
                            <th>GAS COMPANY</th>
                            <th>STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($orders)): ?>
                            <?php foreach($orders as $order): ?>
                                <?php $status = strtolower(trim($order['status'] ?? 'pending')); ?>
                                <tr>
                                    <td class="orders-client-name">
                                        <?php echo htmlspecialchars($order['full_name']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($order['phone']); ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($order['location']); ?>
                                        <?php if(!empty($order['quarter'])): ?>
                                            - <?php echo htmlspecialchars($order['quarter']); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($order['gas_size']); ?></td>
                                    <td><?php echo htmlspecialchars($order['gas_company']); ?></td>
                                    <td>
                                        <span class="orders-status <?php echo htmlspecialchars($status); ?>">
                                            <?php echo htmlspecialchars(ucfirst($order['status'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a class="orders-action-link" href="assign_delivery.php?order_id=<?php echo $order['id']; ?>">
                                            Assign
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="orders-empty">No orders assigned to you yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="orders-table-footer">
                <span>Showing <?php echo count($orders); ?> assigned orders</span>
                <div class="orders-pagination">
                    <button type="button">Previous</button>
                    <button type="button">Next</button>
                </div>
            </div>
        </div>
    </main>
</div>

</body>
</html>