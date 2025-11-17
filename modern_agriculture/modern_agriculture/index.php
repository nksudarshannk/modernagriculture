<?php
include 'db.php';

$encryption_key = "78ct8RYGMsOKtNvB";

$sql = "SELECT * FROM modern_new_products LIMIT 12";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching products: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en"> 

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
      body {
    background: linear-gradient(135deg, #ffefba, #ffd1dc, #d0e8f2, #c6e7d1);
}


        .product-card {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-radius: 12px;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(34, 193, 195, 0.7), rgba(253, 187, 45, 0.7));
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            height: 220px;
            object-fit: cover;
            width: 100%;
            transition: transform 0.3s ease-in-out;
        }

        .product-card:hover .product-image {
            transform: scale(1.1);
        }

        .card-body {
            padding: 15px;
            color: #fff;
            text-align: center;
            flex-grow: 1;
        }

        .card-body h6 {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .card-body hr {
            border-color: #fff;
            width: 60%;
            margin: 10px auto;
        }

        .text-primary {
            font-size: 1.3rem;
            font-weight: bold;
            margin-top: 10px;
        }

        .card-footer {
            padding: 12px;
            background-color: #fff;
            border-radius: 0 0 12px 12px;
        }

        .card-footer a {
            color: #fff;
            background-color: #28a745;
            padding: 10px;
             font-weight: bold;
            border-radius: 12px;
            transition: background-color 0.3s;
        }

        .card-footer a:hover {
            background-color: #218838;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
        }

        .container {
            margin-top: 50px;
        }

        h1 {
            font-family: 'Arial', sans-serif;
            font-weight: 600;
            color: #333;
        }

        @media (max-width: 768px) {
            .product-card {
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center fw-bold">All Products</h1>

        <div class="row">
            <?php $return = isset($_GET['return']) ? $_GET['return'] : ''; ?>
            <?php $returnParam = $return ? '&return=' . urlencode($return) : ''; ?>
            <?php while ($product = $result->fetch_assoc()) : ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card product-card">
                        <img src="./admin/<?php echo htmlspecialchars($product['image_path']); ?>" class="card-img-top product-image" alt="Product Image">
                        <div class="card-body">
                            <h6 class="card-title"><?php echo htmlspecialchars($product['title']); ?></h6>
                            <hr>
                                <p class="small text-white-50 mb-2" style="line-height:1.2;">
                                    <?php echo htmlspecialchars(mb_strimwidth(strip_tags($product['description']), 0, 90, '...')); ?>
                                </p>
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-primary fw-bold">Rs. <?php echo number_format($product['price'], 2); ?></div>
                                        <div class="btn-group" role="group">
                                            <a href="details.php?product_id=<?php echo $product['id']; ?><?php echo $returnParam; ?>" class="btn btn-sm btn-light">View</a>
                                            <form method="POST" action="details.php" style="display:inline-block;margin:0;">
                                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                <button type="submit" name="add_to_cart" class="btn btn-sm btn-success">Add</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="card-footer text-center">
                            <?php $returnParam = $return ? '&return=' . urlencode($return) : ''; ?>
                                <!-- The View button is now included in the card body -->
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
