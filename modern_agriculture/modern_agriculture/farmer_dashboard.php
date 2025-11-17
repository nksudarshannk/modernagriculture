<?php
session_start(); 

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'farmer') {
    header('Location: login.php');
    exit();
}

include 'db.php';

// Fetch product count (products are not currently associated per farmer)
$farmer_id = $_SESSION['user_id'];
$product_sql = "SELECT COUNT(*) as product_count FROM modern_new_products";
$stmt = $conn->prepare($product_sql);
$stmt->execute();
$product_result = $stmt->get_result();
$product_data = $product_result->fetch_assoc();
$product_count = $product_data['product_count'];

// Fetch orders count (orders are associated to buyers in this schema)
$order_sql = "SELECT COUNT(*) as order_count FROM modern_new_orders";
$stmt = $conn->prepare($order_sql);
$stmt->execute();
$order_result = $stmt->get_result();
$order_data = $order_result->fetch_assoc();
$order_count = $order_data['order_count'];

// Fetch recent orders (global)
$recent_orders_sql = "SELECT * FROM modern_new_orders ORDER BY created_at DESC LIMIT 5";
$stmt = $conn->prepare($recent_orders_sql);
$stmt->execute();
$recent_orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 80px;
        }

        .farmer-navbar {
            background: linear-gradient(135deg, #28a745, #20c997) !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 12px 20px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
        }

        .farmer-navbar .navbar-brand {
            font-size: 1.5rem;
            font-weight: 600;
            color: white;
        }

        .farmer-navbar .nav-link {
            color: white !important;
            margin-left: 15px;
            transition: opacity 0.3s ease;
        }

        .farmer-navbar .nav-link:hover {
            opacity: 0.8;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .dashboard-header h1 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .dashboard-header p {
            font-size: 1rem;
            opacity: 0.9;
        }

        .card {
            border: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            height: 250px;
            margin-bottom: 20px;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .card-body {
            padding: 20px;
            text-align: center;
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
            color: #fff;
        }

        .card-body i {
            font-size: 50px;
            margin-bottom: 20px;
        }

        .card-body h5 {
            font-size: 18px;
            font-weight: bold;
        }

        .card-body p {
            font-size: 14px;
        }

        .card-1 {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .card-2 {
            background: linear-gradient(135deg, #007bff, #0056b3);
        }

        .card-3 {
            background: linear-gradient(135deg, #ffc107, #ff9800);
        }

        .card-link {
            text-decoration: none;
            color: inherit;
        }

        .card-link:hover .card-body {
            opacity: 0.9;
        }

        .recent-orders {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        .recent-orders h3 {
            color: #333;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-info {
            flex: 1;
        }

        .order-id {
            font-weight: bold;
            color: #333;
        }

        .order-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.85rem;
            font-weight: bold;
        }

        .status-pending {
            background-color: #ffc107;
            color: white;
        }

        .status-completed {
            background-color: #28a745;
            color: white;
        }

        .status-cancelled {
            background-color: #dc3545;
            color: white;
        }

        .logout-btn {
            background-color: #dc3545 !important;
            color: white !important;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #c82333 !important;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .dashboard-header h1 {
                font-size: 1.5rem;
            }

            .card {
                height: auto;
            }

            .order-item {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

    <!-- Farmer Navbar -->
    <nav class="navbar navbar-expand-lg farmer-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="farmer_dashboard">
                <i class="bi bi-leaf"></i> Farmer Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#farmerNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="farmerNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="profile"><?php echo $_SESSION['name']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="myorder">My Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="logout-btn" href="logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Welcome, <?php echo $_SESSION['name']; ?>! 👋</h1>
            <p>Manage your farm and track your sales</p>
        </div>

        <div class="row">
            <div class="col-md-4">
                <a href="profile" class="card-link">
                    <div class="card card-1">
                        <div class="card-body">
                            <i class="bi bi-box"></i>
                            <h5><?php echo $product_count; ?></h5>
                            <p>Total Products</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="myorder" class="card-link">
                    <div class="card card-2">
                        <div class="card-body">
                            <i class="bi bi-cart"></i>
                            <h5><?php echo $order_count; ?></h5>
                            <p>Total Orders</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="profile" class="card-link">
                    <div class="card card-3">
                        <div class="card-body">
                            <i class="bi bi-person"></i>
                            <h5>Profile</h5>
                            <p>Manage Your Account</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <?php if ($recent_orders->num_rows > 0): ?>
        <div class="recent-orders">
            <h3><i class="bi bi-clock-history"></i> Recent Orders</h3>
            <?php while ($order = $recent_orders->fetch_assoc()): ?>
                <div class="order-item">
                    <div class="order-info">
                        <div class="order-id">Order #<?php echo $order['id']; ?></div>
                        <div style="color: #666; font-size: 0.9rem;">
                            <?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?>
                        </div>
                    </div>
                    <div class="order-status status-<?php echo strtolower($order['status']); ?>">
                        <?php echo ucfirst($order['status']); ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>
    
    <div class="container mt-4">
        <div class="d-flex gap-2 justify-content-center">
            <a href="index.php" class="btn btn-success">Browse Products</a>
            <a href="cart.php" class="btn btn-primary">View Cart</a>
            <a href="checkout.php" class="btn btn-warning">Checkout</a>
        </div>
    </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
