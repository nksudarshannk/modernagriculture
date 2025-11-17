<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to view your cart.'); window.location='login';</script>";
    exit();
}
 
 
$user_id = $_SESSION['user_id'];

// Fetch cart items
$cart_sql = "SELECT c.id as cart_id, p.id as product_id, p.title, p.price, p.image_path 
             FROM modern_new_cart c 
             JOIN modern_new_products p ON c.product_id = p.id 
             WHERE c.user_id = ?";


$stmt = $conn->prepare($cart_sql);
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_price = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #F8BBD0, #9C27B0, #03A9F4, #4CAF50); 
            min-height: 100vh !important;
            display: flex;
            flex-direction: column;
        }

        .container {
            max-width: 1200px;
            min-height: 54vh !important;
            margin: 50px auto;
            padding: 20px;
        }

        .cart-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .cart-container h2 {
            font-size: 30px;
            font-weight: 700;
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 40px;
        }

        .table thead {
            background-color: #9C27B0;
            color: white;
            font-weight: 600;
        }

        .table tbody tr {
            transition: background-color 0.3s;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .table img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .btn-danger {
            background-color: #E91E63;
            color: white;
            border-radius: 8px;
            font-size: 14px;
            padding: 8px 15px;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-danger:hover {
            background-color: #C2185B;
        }

        .btn-success {
            background-color: #4CAF50;
            color: white;
            border-radius: 8px;
            font-size: 16px;
            padding: 10px 20px;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-success:hover {
            background-color: #388E3C;
        }

        .total-price {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-top: 20px;
        }

        .checkout-btn {
            font-size: 18px;
            padding: 12px 25px;
            border-radius: 8px;
            background-color: #03A9F4;
            color: white;
            border: none;
            transition: background-color 0.3s ease;
        }

        .checkout-btn:hover {
            background-color: #0288D1;
        }

        .empty-cart {
            font-size: 18px;
            color: #e74c3c;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="cart-container">
            <h2>Your Shopping Cart</h2>

            <?php if ($result->num_rows > 0) : ?>
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($cart_item = $result->fetch_assoc()) : ?>
                                <tr>
                                    <td><img src="./admin/<?php echo $cart_item['image_path']; ?>" alt="Product Image"></td>
                                    <td><?php echo $cart_item['title']; ?></td>
                                    <td>₹<?php echo number_format($cart_item['price'], 2); ?></td>
                                    <td>
                                        <form method="POST" action="cart">
                                            <input type="hidden" name="cart_id" value="<?php echo $cart_item['cart_id']; ?>">
                                            <button type="submit" name="remove_item" class="btn btn-danger btn-sm">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php $total_price += $cart_item['price']; ?>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="total-price text-end">Total: ₹<?php echo number_format($total_price, 2); ?></div>

                <div class="text-end mt-3">
                    <a href="checkout" class="checkout-btn text-decoration-none">Proceed to Checkout</a>
                </div>
            <?php else : ?>
                <p class="empty-cart">Your cart is empty.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>




<?php 
$stmt->close(); 
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_item"])) {
    $cart_id = $_POST['cart_id'];

 
    $delete_sql = "DELETE FROM modern_new_cart WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $cart_id);

    if ($stmt->execute()) {
        echo "<script>alert('Item removed from cart'); window.location='cart';</script>";
    } else {
        echo "<script>alert('Failed to remove item'); window.location='cart';</script>";
    }

    $stmt->close();
    $conn->close(); 
}
?>
