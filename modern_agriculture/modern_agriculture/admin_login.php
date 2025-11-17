<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background: linear-gradient(135deg, #dc3545, #c82333);
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            max-width: 500px;
            width: 100%;
            margin: auto;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            background: linear-gradient(135deg, #dc3545, #c82333);
            padding: 20px;
            border: none;
        }

        .card-body {
            padding: 20px;
        }

        .card-body h4 {
            color: white;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-label {
            color: white;
            font-weight: 600;
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid #e0e0e0;
            padding: 10px 15px;
        }

        .form-control:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .btn-primary {
            background: white;
            color: #dc3545;
            border: none;
            font-weight: bold;
            padding: 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #f8f9fa;
            transform: scale(1.05);
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link p {
            color: white;
            margin: 0;
        }

        .back-link a {
            color: #ffd700;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        .icon-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .icon-container i {
            font-size: 3rem;
            color: white;
        }

        .footer {
            margin-top: auto;
        }
    </style>
</head>

<body>

<main>
    <div class="login-container">
        <div class="card">
            <div class="card-body">
                <div class="icon-container">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h4>Admin Login</h4>

                <?php
                if (isset($_SESSION['success_message'])) {
                    echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
                    unset($_SESSION['success_message']);
                }

                if (isset($_SESSION['error_message'])) {
                    echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
                    unset($_SESSION['error_message']); 
                }
                ?>

                <form method="POST" action="admin_login_process.php">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your admin email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Login as Admin</button>
                    </div>
                </form>

                <div class="back-link">
                    <p><a href="login">Back to Login Selection</a></p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>