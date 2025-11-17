<?php 
session_start(); 
include '../db.php';

// Check if the user is an admin
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login');
    exit();
}

// Get the total counts from the tables
$product_count_query = "SELECT COUNT(*) AS total_products FROM modern_new_products";
$order_count_query = "SELECT COUNT(*) AS total_orders FROM modern_new_orders";
$user_count_query = "SELECT COUNT(*) AS total_users FROM modern_new_users";

$product_result = $conn->query($product_count_query);
$order_result = $conn->query($order_count_query);
$user_result = $conn->query($user_count_query);

$product_count = $product_result->fetch_assoc()['total_products'];
$order_count = $order_result->fetch_assoc()['total_orders'];
$user_count = $user_result->fetch_assoc()['total_users'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        #card-container {
            margin-top: 30px;
        }

        .card-section {
            margin-bottom: 30px;
        }

        .card {
            border: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            height: 250px; 
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

        

        .card-link {
            text-decoration: none;
            color: inherit;
        }

  
        .card-1 {
            background-color: #007bff !important; 
        }

        .card-2 {
            background-color: #28a745 !important; 
        }

        .card-3 {
            background-color: #ffc107 !important; 
        }

      
        .card:hover  {
            background-color: black;
            color:white !important;
            cursor: pointer;
            transform: scale(1.05);
        }

        .text-center {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 40px;
        }

      
        @media (max-width: 768px) {
            .col-md-3 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media (max-width: 576px) {
            .col-md-3 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
         .total-list{
           font-size:28px;
            font-weight: bold;
            color:black;
       }
    </style>
</head>

<body>
    <?php include 'slidebar.php'; ?>

    <div class="content" id="content">
        <h3 class="text-center">All List</h3>
        <hr>

        <div class="row" id="card-container">
            <div class="col-md-3 card-section">
                
                    <div class="card card-1"> 
                        <div class="card-body">
                            <i class="bi bi-box"></i>
                            <h5>Total Products</h5>
                           <p class="total-list"> <?php echo $product_count; ?></p>
                        </div>
                    </div>
               
            </div>
            <div class="col-md-3 card-section">

                    <div class="card card-2"> 
                        <div class="card-body">
                            <i class="bi bi-cart"></i>
                            <h5>Total Orders</h5>
                             
                            <p class="total-list"><?php echo $order_count; ?></p>
                        </div>
                    </div>
               
            </div>
            <div class="col-md-3 card-section">
               
                    <div class="card card-3"> 
                        <div class="card-body">
                            <i class="bi bi-person"></i>
                             <h5>Total Users</h5>
                            <p class="total-list"> <?php echo $user_count; ?></p>
                        </div>
                    </div>
               
            </div>
            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <script src="js/slide.js"></script>
</body>

</html>
