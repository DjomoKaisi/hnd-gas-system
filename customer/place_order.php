<?php
require_once "../config.php";
require_once "../includes/header.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer'){
    header("Location: ../login.php");
    exit();
}

$message = "";

if(isset($_POST['order'])){
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];
    $quarter = $_POST['quarter'];
    $gas_size = $_POST['gas_size'];
    $gas_company = $_POST['gas_company'];
    $notes = $_POST['notes'];

    // Trouver un fournisseur aléatoire
    $sql = "SELECT id FROM users WHERE role='supplier' ORDER BY RAND() LIMIT 1";
    $stmt = $conn->query($sql);
    $supplier = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($supplier) {
        $supplier_id = $supplier['id'];

        $sql = "INSERT INTO orders 
        (customer_id, supplier_id, full_name, phone, location, quarter, gas_size, gas_company, notes, status)
        VALUES (?,?,?,?,?,?,?,?,?, 'pending')";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $_SESSION['user_id'],
            $supplier_id,
            $full_name,
            $phone,
            $location,
            $quarter,
            $gas_size,
            $gas_company,
            $notes
        ]);
        $message = "your order is succesful!";
    } else {
        $message = "Error : No supplier has been assigned.";
    }
}
?>

<link rel="stylesheet" href="../includes/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
    .order-container {
        max-width: 600px;
        margin: 40px auto;
        padding: 20px;
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
    textarea.form-control {
        padding-left: 12px;
    }
    .btn-order {
        width: 100%;
        background: #27ae60;
        color: white;
        padding: 15px;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: bold;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }
    .alert-success {
        background: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
    }
</style>

<div class="admin-layout">
    <main class="main-content" style="margin-left: 0; width: 100%;">
        
        <div class="topbar">
            <h1><i class="bi bi-fuel-pump-fill"></i> Order your gaz</h1>
            <div class="topbar-right">
                <a href="../logout.php" class="sidebar-logout" style="padding: 10px 20px; text-decoration: none;">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </a>
            </div>
        </div>

        <div class="order-container">
            <?php if($message): ?>
                <div class="alert-success">
                    <i class="bi bi-check-circle-fill"></i> <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="table-card" style="padding: 30px;">
                <form method="POST">
                    
                    <div class="form-group">
                        <label>Name</label>
                        <div class="input-wrapper">
                            <i class="bi bi-person"></i>
                            <input type="text" name="full_name" class="form-control" placeholder="Ex: Jean Dupont" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Telephone</label>
                        <div class="input-wrapper">
                            <i class="bi bi-telephone"></i>
                            <input type="text" name="phone" class="form-control" placeholder="6xx xxx xxx" required>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>Town/location</label>
                            <div class="input-wrapper">
                                <i class="bi bi-geo-alt"></i>
                                <input type="text" name="location" class="form-control" placeholder="Ex: Douala" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Quarter</label>
                            <div class="input-wrapper">
                                <i class="bi bi-house"></i>
                                <input type="text" name="quarter" class="form-control" placeholder="Ex: Akwa" required>
                            </div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>Bottle size</label>
                            <div class="input-wrapper">
                                <i class="bi bi-moisture"></i>
                                <select name="gas_size" class="form-control">
                                    <option value="small">small (6.5kg)</option>
                                    <option value="big">big (12kg)</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Company</label>
                            <div class="input-wrapper">
                                <i class="bi bi-building"></i>
                                <input type="text" name="gas_company" class="form-control" placeholder="Total, Tradex..." required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Shortnotes(Optionnel)</label>
                        <textarea name="notes" class="form-control" placeholder="Précisions sur la livraison..." rows="3"></textarea>
                    </div>

                    <button name="order" class="btn-order">
                        <i class="bi bi-cart-plus"></i> Confirm Order
                    </button>

                </form>
            </div>
        </div>
    </main>
</div>

</body>
</html>