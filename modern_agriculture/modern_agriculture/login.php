<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Selection</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            flex-direction: column;
        }

        .login-container {
            padding-top: 110px;
            padding-bottom: 50px;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .login-header {
            text-align: center;
            color: white;
            margin-bottom: 50px;
        }

        .login-header h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .login-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .login-cards {
            display: flex;
            gap: 40px;
            justify-content: center;
            flex-wrap: wrap;
            max-width: 1000px;
            margin: 0 auto;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            width: 100%;
            max-width: 350px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .login-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .login-card a {
            text-decoration: none;
            color: inherit;
        }

        .login-card i {
            font-size: 4rem;
            margin-bottom: 20px;
            display: block;
        }

        .admin-card i {
            color: #dc3545;
        }

        .farmer-card i {
            color: #28a745;
        }

        .customer-card i {
            color: #0dcaf0;
        }

        .login-card h2 {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        .login-card p {
            color: #666;
            font-size: 1rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .login-btn {
            padding: 12px 40px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            width: 100%;
            transition: all 0.3s ease;
            color: white;
        }

        .admin-btn {
            background: linear-gradient(135deg, #dc3545, #c82333);
        }

        .admin-btn:hover {
            background: linear-gradient(135deg, #c82333, #a71d2a);
            transform: scale(1.05);
        }

        .farmer-btn {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .farmer-btn:hover {
            background: linear-gradient(135deg, #20c997, #1aa179);
            transform: scale(1.05);
        }

        .customer-btn {
            background: linear-gradient(135deg, #0dcaf0, #0aa2c0);
        }

        .customer-btn:hover {
            background: linear-gradient(135deg, #0aa2c0, #088395);
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .login-header h1 {
                font-size: 1.8rem;
            }

            .login-cards {
                gap: 20px;
            }

            .login-card {
                max-width: 100%;
                padding: 30px 20px;
            }

            .login-card i {
                font-size: 3rem;
            }

            .login-card h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-header">
        <h1>Welcome to Modern Agriculture</h1>
        <p>Select your login type to continue</p>
    </div>

    <div class="login-cards">
        <!-- Admin Login Card -->
        <div class="login-card admin-card">
            <a href="admin_login">
                <i class="fas fa-user-shield"></i>
                <h2>Admin Login</h2>
                <p>Manage products, orders, and users from the admin dashboard</p>
                <button class="login-btn admin-btn" type="button">Login as Admin</button>
            </a>
        </div>

        <!-- Farmer Login Card -->
        <div class="login-card farmer-card">
            <a href="farmer_login">
                <i class="fas fa-leaf"></i>
                <h2>Farmer Login</h2>
                <p>Access your farm account and manage your products</p>
                <button class="login-btn farmer-btn" type="button">Login as Farmer</button>
            </a>
        </div>

        <!-- Customer Login Card -->
        <div class="login-card customer-card">
            <a href="customer_login">
                <i class="fas fa-shopping-cart"></i>
                <h2>Customer Login</h2>
                <p>Buy organic products from local farmers</p>
                <button class="login-btn customer-btn" type="button">Login as Customer</button>
            </a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
