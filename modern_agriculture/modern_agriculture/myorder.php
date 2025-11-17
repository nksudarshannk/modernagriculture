<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to view your orders.'); window.location='login';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
 
// Check if an order_id is provided in the URL (to show order details)
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Fetch order details
    $order_sql = "SELECT * FROM modern_new_orders WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($order_sql);
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $order_result = $stmt->get_result();
    $order = $order_result->fetch_assoc();
    $stmt->close();

    if (!$order) {
        echo "<script>alert('Order not found.'); window.location='myorder';</script>";
        exit();
    }

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

} else {
    // Fetch all orders for the logged-in user
    $order_sql = "SELECT * FROM modern_new_orders WHERE user_id = ? ORDER BY created_at ASC";
    $stmt = $conn->prepare($order_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $order_result = $stmt->get_result();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
         body {
            background: linear-gradient(135deg, #1a2e8b, #4CAF50); 
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            flex: 1;
            max-width: 800px;
        }

        .order-container {
            background: #9becf9  ;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        .table-dark {
            background-color: #0d6efd !important;
        }

        .btn-info {
            background: linear-gradient(45deg, #17a2b8, #138496);
            border: none;
        }

        .btn-info:hover {
            background: linear-gradient(45deg, #138496, #17a2b8);
            transform: scale(1.05);
        }

        .btn-primary {
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #0056b3, #007bff);
            transform: scale(1.05);
        }

        .btn-secondary {
            background: linear-gradient(45deg, #6c757d, #495057);
            border: none;
        }

        .btn-secondary:hover {
            background: linear-gradient(45deg, #495057, #6c757d);
            transform: scale(1.05);
        }

        .order-card {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .list-group-item {
            background-color: #ffffff;
            border: none;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <div class="order-container">

            <?php if (isset($_GET['order_id'])) : ?>
                <!-- Order Details View -->
                <h2 class="text-center text-primary " style="font-weight: bold;"> Order Details</h2>
               <div class="order-card" style="padding: 20px; border: 1px solid black; border-radius: 8px; background-color: #f9f9f9; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <tr>
            <td style="padding: 10px; font-weight: bold; width: 30%; border: 1px solid black;">Order ID:</td>
            <td style="padding: 10px; border: 1px solid black;">#<?php echo $order['id']; ?></td>
        </tr>
        <tr>
            <td style="padding: 10px; font-weight: bold; border: 1px solid black;">Name:</td>
            <td style="padding: 10px; border: 1px solid black;"><?php echo htmlspecialchars($order['name']); ?></td>
        </tr>
        <tr>
            <td style="padding: 10px; font-weight: bold; border: 1px solid black;">Phone:</td>
            <td style="padding: 10px; border: 1px solid black;"><?php echo htmlspecialchars($order['phone']); ?></td>
        </tr>
        <tr>
            <td style="padding: 10px; font-weight: bold; border: 1px solid black;">Address:</td>
            <td style="padding: 10px; border: 1px solid black;"><?php echo htmlspecialchars($order['address']); ?></td>
        </tr>
        <tr>
            <td style="padding: 10px; font-weight: bold; border: 1px solid black;">Payment Method:</td>
            <td style="padding: 10px; border: 1px solid black;"><?php echo htmlspecialchars($order['payment_method']); ?></td>
        </tr>
        <tr>
            <td style="padding: 10px; font-weight: bold; border: 1px solid black;">Total Price:</td>
            <td style="padding: 10px; border: 1px solid black;">₹<?php echo number_format($order['total_price'], 2); ?></td>
        </tr>
        <tr>
            <td style="padding: 10px; font-weight: bold; border: 1px solid black;">Order Date:</td>
            <td style="padding: 10px; border: 1px solid black;"><?php echo date("d M Y, H:i", strtotime($order['created_at'])); ?></td>
        </tr>
    </table>
</div>



                <h4 class="text-center mt-4 fw-bold">Ordered Items</h4>
                <ul class="list-group">
                    <?php while ($item = $order_items_result->fetch_assoc()) : ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                            <img src="admin/<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" width="80" height="60">
                            <?php echo htmlspecialchars($item['title']); ?>
                            <span>₹<?php echo number_format($item['price'], 2); ?></span>
                        </li>
                    <?php endwhile; ?>
                </ul>

                <div class="text-center mt-4">
                    <a href="myorder" class="btn btn-secondary">Back to My Orders</a>
                </div>

            <?php else : ?>
                <!-- Order List View -->
                <h2 class="text-center text-primary fw-bold"> My Orders</h2>

                <?php if ($order_result->num_rows > 0) : ?>
                    <div class="table-responsive">
                        <table class="table table-bordered mt-4">
                            <thead class="table-dark text-white">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Total Price </th>
                                    <th>Status</th>
                                    <th>Payment Method</th>
                                    <th>Order Date</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($order = $order_result->fetch_assoc()) : ?>
                                    <tr>
                                        <td>#<?php echo $order['id']; ?></td>
                                        <td><?php echo number_format($order['total_price'], 2); ?></td>
                                        <td><?php echo $order['status']; ?></td>
                                        <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                                        <td><?php echo date("d M Y, H:i", strtotime($order['created_at'])); ?></td>
                                        <td><a href="myorder?order_id=<?php echo $order['id']; ?>" class="btn btn-info btn-sm">View</a></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="alert alert-warning text-center">You have not placed any orders yet.</div>
                <?php endif; ?>
                
                <div class="text-center mt-3">
                    <a href="index" class="btn btn-primary">Continue Shopping</a>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

