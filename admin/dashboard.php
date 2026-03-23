<?php
require_once "../config.php";
require_once "../includes/header.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_suppliers = $conn->query("SELECT COUNT(*) FROM users WHERE role='supplier'")->fetchColumn();
$total_customers = $conn->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetchColumn();
$total_orders = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$completed_orders = $conn->query("SELECT COUNT(*) FROM orders WHERE status='completed'")->fetchColumn();
$recent_orders = $conn->query("SELECT COUNT(*) FROM orders WHERE created_at >= NOW() - INTERVAL 7 DAY")->fetchColumn();

$pending_orders = $conn->query("SELECT COUNT(*) FROM orders WHERE status='pending'")->fetchColumn();

// $recent_orders_list = $conn->query("
//     SELECT id, customer_name, status, total_amount, created_at
//     FROM orders
//     ORDER BY created_at DESC
//     LIMIT 5
// ")->fetchAll(PDO::FETCH_ASSOC);
// ?>

<link rel="stylesheet" href="../includes/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<div class="admin-layout">

    <aside class="sidebar">
        <div>
            <div class="sidebar-brand">
                <h2><i class="bi bi-fuel-pump"></i> QuickGas Admin</h2>
                <p>MANAGEMENT PORTAL</p>
            </div>

            <nav class="sidebar-nav">
                <a href="#" class="nav-item active">
                    <i class="bi bi-grid"></i>
                    <span>Overview</span>
                </a>
                <a href="../supplier/orders.php" class="nav-item">
                    <i class="bi bi-bag"></i>
                    <span>Orders</span>
                </a>
                <a href="users.php" class="nav-item">
                    <i class="bi bi-people"></i>
                    <span>Users</span>
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
            <h1>Dashboard</h1>

            <div class="topbar-right">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search orders...">
                </div>

                <div class="topbar-icon">
                    <i class="bi bi-bell-fill"></i>
                </div>

                <div class="admin-profile">
                    <div class="admin-text">
                        <strong>Admin User</strong>
                        <span>Super Admin</span>
                    </div>
                    <div class="avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-icon orange"><i class="bi bi-receipt"></i></div>
                    <span class="trend up">↗ 12%</span>
                </div>
                <h3>Total Orders</h3>
                <p><?php echo $total_orders; ?></p>
            </div>

            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-icon yellow"><i class="bi bi-bag-exclamation"></i></div>
                    <span class="trend down">↘ 5%</span>
                </div>
                <h3>Pending</h3>
                <p><?php echo $pending_orders; ?></p>
            </div>

            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
                    <span class="trend up">↗ 15%</span>
                </div>
                <h3>Completed</h3>
                <p><?php echo $completed_orders; ?></p>
            </div>

            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-icon blue"><i class="bi bi-person-fill"></i></div>
                    <span class="trend up">↗ 2%</span>
                </div>
                <h3>Total Users</h3>
                <p><?php echo $total_users; ?></p>
            </div>
        </div>

        <div class="extra-stats">
            <div class="mini-stat">
                <h4>Suppliers</h4>
                <p><?php echo $total_suppliers; ?></p>
            </div>
            <div class="mini-stat">
                <h4>Customers</h4>
                <p><?php echo $total_customers; ?></p>
            </div>
            <div class="mini-stat">
                <h4>Recent Orders</h4>
                <p><?php echo $recent_orders; ?></p>
            </div>
        </div>

        <section class="table-card">
            <div class="table-header">
                <h2>Recent Orders</h2>
                <a href="/supplier/orders.php">View All</a>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ORDER ID</th>
                            <th>CUSTOMER</th>
                            <th>STATUS</th>
                            <th>AMOUNT</th>
                            <th>DATE</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recent_orders_list as $order): ?>
                            <tr>
                                <td>#GO-<?php echo htmlspecialchars($order['id']); ?></td>
                                <td><?php echo htmlspecialchars($order['customer_name'] ?? 'Customer'); ?></td>
                                <td>
                                    <span class="status-badge <?php echo strtolower($order['status']); ?>">
                                        <?php echo htmlspecialchars(ucfirst($order['status'])); ?>
                                    </span>
                                </td>
                                <td>
                                    $<?php echo number_format((float)($order['total_amount'] ?? 0), 2); ?>
                                </td>
                                <td>
                                    <?php echo date("M d, Y", strtotime($order['created_at'])); ?>
                                </td>
                                <td>
                                    <button class="action-btn">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if(empty($recent_orders_list)): ?>
                            <tr>
                                <td colspan="6" class="empty-row">No recent orders found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <span>Showing <?php echo count($recent_orders_list); ?> recent orders</span>
                <div class="pager">
                    <button>Previous</button>
                    <button>Next</button>
                </div>
            </div>
        </section>

    </main>
</div>

    
</body>
</html>