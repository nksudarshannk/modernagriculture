<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

// Check if order ID exists in session
if (!isset($_SESSION['order_id'])) {
    echo "<script>alert('No recent order found!'); window.location='index.php';</script>";
    exit();
}

$order_id = $_SESSION['order_id'];

// Fetch order details
$order_sql = "SELECT * FROM modern_new_orders WHERE id = ?";
$stmt = $conn->prepare($order_sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();
$stmt->close();

// Fetch ordered items
$order_items_sql = "SELECT oi.*, p.title, p.image_path 
                    FROM modern_new_order_items oi 
                    JOIN modern_new_products p ON oi.product_id = p.id 
                    WHERE oi.order_id = ?";
$stmt = $conn->prepare($order_items_sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_items_result = $stmt->get_result();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a2e8b, #4CAF50); 
            font-family: 'Arial', sans-serif;
            color: #fff;
        }

        .container {
            background: #fbebbb ;
            border-radius: 12px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            padding: 40px;
            margin-top: 50px;
        }

        .alert-success {
            background-color: #f39c12;
            color: #fff;
            border-radius: 10px;
            padding: 20px;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .alert-success h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .order-summary {
            background-color:#bb8fce 
;
            border-radius: 10px;
            padding: 30px;
            margin-top: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .table-dark {
            background-color: white; 
            border-color: #555;
        }

        .table th {
            background-color: #4CAF50;
            color: #fff;
            font-weight: bold;
            text-align: left;
            padding: 12px;
        }

        .table td {
            color: #fff;
            padding: 12px;
        }

        .text-warning {
            color: #f39c12;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .list-group-item {
            border: none;
            padding: 15px;
            background-color: #444;
            border-radius: 8px;
            margin-bottom: 15px;
            color: #fff;
            font-weight: 500;
        }

        .list-group-item img {
            border-radius: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff5e5e, #f39c12);
            border: none;
            padding: 16px 32px;
            font-size: 1.2rem;
            font-weight: 700;
            border-radius: 10px;
            text-transform: uppercase;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #e74c3c, #f39c12);
            transition: background 0.3s ease;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .alert-success h2 {
                font-size: 1.8rem;
            }

            .order-summary p,
            .table td,
            .table th {
                font-size: 1rem;
            }

            .btn-primary {
                font-size: 1.1rem;
                padding: 14px 28px;
            }
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <div class="alert alert-success text-center">
            <h2>🎉 Order Placed Successfully!</h2>
            <p>Thank you for your order. Your order ID is <strong>#<?php echo $order['id']; ?></strong>.</p>
        </div>

        <div class="order-summary">
            <h2 class="text-center fw-bold text-light">📝 Order Details</h2>
            <table class="table table-dark table-bordered">
                <tbody>
                    <tr>
                        <th>Name</th>
                        <td><?php echo htmlspecialchars($order['name']); ?></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td><?php echo htmlspecialchars($order['phone']); ?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?php echo htmlspecialchars($order['address']); ?></td>
                    </tr>
                    <tr>
                        <th>Payment Method</th>
                        <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                    </tr>
                    <tr>
                        <th>Total Price</th>
                        <td><strong class="text-warning">₹<?php echo number_format($order['total_price'], 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

       
         <h2 class="text-center fw-bold mt-4">📦 Ordered Items</h2>

        <ul class="list-group">
            <?php while ($item = $order_items_result->fetch_assoc()) : ?>
                <li class="list-group-item d-flex justify-content-between align-items-center fw-bold fs-4">
                    <img src="admin/<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" width="80" height="60">
                    <?php echo htmlspecialchars($item['title']); ?>
                    <span>₹<?php echo number_format($item['price'], 2); ?></span>
                </li>
            <?php endwhile; ?>
        </ul>

        <div class="text-center mt-4">
            <a href="index" class="btn btn-primary">Continue Shopping</a>
            <a href="myorder" class="btn btn-success">My Orders</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
