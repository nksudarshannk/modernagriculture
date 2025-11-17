<?php
include '../db.php';

$message = '';
$messageType = '';

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle truncate requests for different tables
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->query("SET FOREIGN_KEY_CHECKS = 0");  // Disable foreign key checks temporarily

    // Truncate sensor_data table
    if (isset($_POST['truncate_sensor_data'])) {
        if (!$conn->query("TRUNCATE TABLE modern_new_data")) {
            $message = "Failed to truncate sensor_data table: " . $conn->error;
            $messageType = "danger";
        } else {
            $message = "Sensor Data table truncated successfully!";
            $messageType = "success";
        }
    }

    // Other truncation logic
    if (isset($_POST['truncate_cart'])) {
        if (!$conn->query("TRUNCATE TABLE modern_new_cart")) {
            $message = "Failed to truncate cart table: " . $conn->error;
            $messageType = "danger";
        } else {
            $message = "Cart table truncated successfully!";
            $messageType = "success";
        }
    }

    if (isset($_POST['truncate_products'])) {
        if (!$conn->query("TRUNCATE TABLE modern_new_products")) {
            $message = "Failed to truncate products table: " . $conn->error;
            $messageType = "danger";
        } else {
            $message = "Products table truncated successfully!";
            $messageType = "success";
        }
    }

    if (isset($_POST['truncate_orders'])) {
        if (!$conn->query("TRUNCATE TABLE modern_new_orders")) {
            $message = "Failed to truncate orders table: " . $conn->error;
            $messageType = "danger";
        } else {
            $message = "Orders table truncated successfully!";
            $messageType = "success";
        }
    }

    if (isset($_POST['truncate_order_items'])) {
        if (!$conn->query("TRUNCATE TABLE modern_new_order_items")) {
            $message = "Failed to truncate order_items table: " . $conn->error;
            $messageType = "danger";
        } else {
            $message = "Order items table truncated successfully!";
            $messageType = "success";
        }
    }

    $conn->query("SET FOREIGN_KEY_CHECKS = 1");  // Re-enable foreign key checks
}

// Fetch sensor data (use existing timestamp column)
$sensorDataQuery = "SELECT * FROM modern_new_data ORDER BY `timestamp` DESC";
$sensorDataResult = $conn->query($sensorDataQuery);
if (!$sensorDataResult) {
    // Fallback: try ordering by block_index if timestamp is not available
    $sensorDataQuery = "SELECT * FROM modern_new_data ORDER BY block_index DESC";
    $sensorDataResult = $conn->query($sensorDataQuery);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Truncate Tables</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <?php include 'slidebar.php'; ?>

    <div class="content" id="content">
        <div class="container">
            <h3 class="text-center mt-5">Truncate All Tables</h3>
            <p class="text-center">Click the buttons below to truncate the corresponding tables.</p>

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?> text-center">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <!-- Form to trigger truncation -->
            <form action="" method="POST">
                <button type="submit" name="truncate_sensor_data" class="btn btn-danger btn-lg w-100 mb-3">Truncate Sensor Data</button>
                <button type="submit" name="truncate_cart" class="btn btn-danger btn-lg w-100 mb-3">Truncate Cart Table</button>
                <button type="submit" name="truncate_products" class="btn btn-danger btn-lg w-100 mb-3">Truncate Products Table</button>
                <button type="submit" name="truncate_orders" class="btn btn-danger btn-lg w-100 mb-3">Truncate Orders Table</button>
                <button type="submit" name="truncate_order_items" class="btn btn-danger btn-lg w-100 mb-3">Truncate Order Items Table</button>
            </form>

            

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/slide.js"></script>

</body>
</html>
