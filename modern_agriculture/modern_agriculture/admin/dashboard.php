<?php
session_start(); 

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'admin') {

    header('Location: ../login');
    exit();
}

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

        .card-body p {
            font-size: 14px;
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

      
        .card-link:hover .card-body {
            background-color: black;
            color:white;
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
    </style>
</head>

<body>
    <?php include 'slidebar.php'; ?>

    <div class="content" id="content">
        <h3 class="text-center">Farmer Dashboard Controls</h3>
        <hr>

        <div class="row" id="card-container">
            <div class="col-md-3 card-section">
                <a href="product.php" class="card-link">
                    <div class="card card-1"> 
                        <div class="card-body">
                            <i class="bi bi-box"></i>
                            <h5>View Product</h5>
                          <p>Manage and control all available products</p>

                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 card-section">
                <a href="orders.php" class="card-link">
                    <div class="card card-2"> 
                        <div class="card-body">
                            <i class="bi bi-cart"></i>
                            <h5>View Orders</h5>
                            <p>Manage and track your orders</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 card-section">
                <a href="users.php" class="card-link">
                    <div class="card card-3"> 
                        <div class="card-body">
                            <i class="bi bi-person"></i>
                            <h5>View Users</h5>
                            <p>Manage users and their details</p>
                        </div>
                    </div>
                </a>
            </div>
                 <div class="col-md-3 card-section">
                     <a href="count.php" class="card-link">
                    <div class="card card-1"> 
                        <div class="card-body">
                            <i class="bi bi-list-stars"></i>
                            <h5>View All count</h5>
                          <p> All available products, Orders Users</p>

                        </div>
                    </div>
                </a>
            </div>
                 <div class="col-md-3 card-section">
                     <a href="contact_messages.php" class="card-link">
                    <div class="card card-2"> 
                        <div class="card-body">
                            <i class="bi bi-chat-dots"></i>
                            <h5>Contact Messages</h5>
                          <p>View and manage customer messages</p>

                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <script src="js/slide.js"></script>
</body>

</html>
