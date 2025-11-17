<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orderId = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $status = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : '';

    if ($orderId > 0 && !empty($status)) {
        $query = "UPDATE modern_new_orders SET status = '$status' WHERE id = $orderId";
        if (mysqli_query($conn, $query)) {
            echo "Success: Order status updated!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Invalid input!";
    }
} else {
    echo "Invalid request!";
}
?>
