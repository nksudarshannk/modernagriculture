<?php
session_start();

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'customer') {
    header('Location: login.php');
    exit();
}

include 'db.php';

// Example stats for customer: total orders, recent orders
$customer_id = $_SESSION['user_id'];

// Count of orders (table modern_new_orders stores orders)
$order_sql = "SELECT COUNT(*) as order_count FROM modern_new_orders WHERE user_id = ?";
$stmt = $conn->prepare($order_sql);
if ($stmt) {
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $order_result = $stmt->get_result();
    $order_data = $order_result->fetch_assoc();
    $order_count = $order_data['order_count'];
} else {
    $order_count = 0;
}

// Fetch recent orders
$recent_orders = [];
$recent_sql = "SELECT * FROM modern_new_orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
$stmt = $conn->prepare($recent_sql);
if ($stmt) {
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $recent_orders = $stmt->get_result();
}

// Fetch member since date from users table for accurate display
$member_since = date('Y-m-d');
$member_sql = "SELECT created_at FROM modern_new_users WHERE id = ? LIMIT 1";
$mstmt = $conn->prepare($member_sql);
if ($mstmt) {
    $mstmt->bind_param("i", $customer_id);
    $mstmt->execute();
    $mres = $mstmt->get_result();
    if ($mres && $mres->num_rows > 0) {
        $mrow = $mres->fetch_assoc();
        if (!empty($mrow['created_at'])) {
            $member_since = $mrow['created_at'];
        }
    }
    $mstmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/customer.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="hero mb-4">
            <div class="avatar"><?php echo strtoupper(substr($_SESSION['name'],0,1)); ?></div>
            <div>
                <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>
                <p>Explore fresh products and manage your orders from one place.</p>
                <div class="mt-3">
                    <a href="index.php?return=customer_dashboard" class="btn btn-primary me-2">Browse Products</a>
                    <a href="cart.php" class="btn btn-primary-ghost">View Cart</a>
                </div>
            </div>
            <div class="ms-auto text-end d-none d-md-block">
                <small class="text-muted">Member since</small>
                <div><strong><?php echo date('M Y', strtotime($_SESSION['created_at'] ?? date('Y-m-d'))); ?></strong></div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="stat-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Total Orders</div>
                            <div class="stat-number"><?php echo intval($order_count); ?></div>
                        </div>
                        <div class="text-end">
                            <i class="bi bi-receipt-cutoff" style="font-size:28px;color:var(--accent)"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card p-3 recent-orders">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Recent Orders</h5>
                        <a href="myorder.php" class="small">View all</a>
                    </div>
                    <?php if ($recent_orders && $recent_orders->num_rows > 0): ?>
                        <div class="list-group">
                            <?php while ($o = $recent_orders->fetch_assoc()): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div><strong>Order #<?php echo $o['id']; ?></strong></div>
                                        <div class="text-muted small"><?php echo htmlspecialchars($o['status']); ?></div>
                                    </div>
                                    <div class="text-end">
                                        <div class="small text-muted"><?php echo date('d M Y', strtotime($o['created_at'])); ?></div>
                                        <a href="myorder.php?order_id=<?php echo $o['id']; ?>" class="btn btn-sm btn-outline-primary mt-1">Details</a>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="mb-0">You have no recent orders.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
