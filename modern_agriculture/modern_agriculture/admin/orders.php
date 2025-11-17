<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'admin') {

    header('Location: ../login');
    exit();
}

include '../db.php';


$query = "SELECT o.id, o.name AS customer_name, o.phone, o.address, o.total_price, 
                 o.payment_method, o.created_at, o.status, 
                 p.title AS product_name, oi.price, oi.quantity
          FROM modern_new_orders o
          JOIN modern_new_order_items oi ON o.id = oi.order_id
          JOIN modern_new_products p ON oi.product_id = p.id
          ORDER BY o.id Asc";

$result = mysqli_query($conn, $query);


if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #fbebbb; font-family: Arial, sans-serif;">
    <?php include 'slidebar.php'; ?>
<?php
if (isset($_SESSION['message'])) {
    
    $message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'info';

  
    echo '<div class="alert alert-' . $message_type . '">' . $_SESSION['message'] . '</div>';

   
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>


<div class="content" id="content">
    <div class="container">
        <h2 class="text-center fw-bold">Orders List</h2>
        <table class="table table-bordered table-hover bg-white">
            <thead class="table-dark text-center">
                <tr>
                    <th>OrderID</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>TotalPrice</th>
                    <th>OrderedDate</th>
                    <th>OrderStatus</th>
                    <th>Actions</th>
                </tr>
            </thead>
           <tbody>
    <?php 
   
    if (mysqli_num_rows($result) > 0) {
     
        while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr class="text-center">
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['customer_name']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['address']; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td>₹<?php echo number_format($row['total_price'], 2); ?></td>
                <td><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></td>

                <td>
                    <select class="form-select form-select-sm status-dropdown" data-id="<?php echo $row['id']; ?>">
                        <option value="Pending" <?php echo ($row['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="Processing" <?php echo ($row['status'] == 'Processing') ? 'selected' : ''; ?>>Processing</option>
                        <option value="Completed" <?php echo ($row['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                        <option value="Delivered" <?php echo ($row['status'] == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                        <option value="Cancelled" <?php echo ($row['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </td>
                <td>
                    <button class="btn btn-sm btn-success update-btn" data-id="<?php echo $row['id']; ?>">Update</button>
                    <a href="delete_order.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php } 
    } else { ?>
        <!-- Display this message if no rows are found -->
        <tr>
            <td colspan="9" class="text-center">No orders found</td>
        </tr>
    <?php } ?>
</tbody>

        </table>
    </div>

   


 </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    $('.update-btn').click(function() {
        var orderId = $(this).data('id');
        var newStatus = $(this).closest('tr').find('.status-dropdown').val();

        $.ajax({
            url: 'update_status.php', 
            type: 'POST',
            data: { id: orderId, status: newStatus },
            success: function(response) {
                alert(response); 
            },
            error: function() {
                alert("Error updating status!");
            }
        });
    });
});

</script>
    <script src="js/slide.js"></script>
</body>

</html>
