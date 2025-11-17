 <?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secured Organic agro using BlockChain </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Web3.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/web3@1.6.1/dist/web3.min.js"></script>

    <!-- Ethers.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/ethers@5.6.0/dist/ethers.umd.min.js"></script>

    <!-- Moralis SDK CDN -->
    <script src="https://cdn.jsdelivr.net/npm/moralis@2.17.0/dist/moralis.js"></script>

    <!-- IPFS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/ipfs@0.58.0/dist/index.min.js"></script>

    <style>
        body {
            padding-top: 70px;

            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background: linear-gradient(90deg, #1fb6ff 0%, #4fc08d 100%) !important;
            padding: 12px 20px;
        }

        .navbar-brand {
           font-size: 1.5rem;
            font-weight: 600;
            color: white;
            text-decoration: none;
        }

        .navbar-brand img {
            height: 70px;
            width: 150px;
            border-radius: 170px;
        }

        .navbar-head {
            font-size: 1.5rem;
            font-weight: 600;
            color: white;
            text-decoration: none;
            margin-left: 15px;
            text-align: center;
            flex-grow: 7;
        }

        .navbar-head:hover {
            color: #ff6347;
            text-decoration: none;
        }

        .nav-item .nav-link {
            font-size: 1rem;
            color: white !important;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .nav-item .nav-link:hover {
            color: orange !important;
        }

        .dropdown-menu {
            background-color: black !important;
            border-radius: 18px;
            width: 90px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .dropdown-item {
            color: white !important;
            font-weight: 600;
            font-size: 1rem;
            padding: 8px 16px;
            transition: background-color 0.3s ease;
        }

        .dropdown-item:hover {
            background: none;
            color: orange !important;
        }

        .navbar-collapse {
            display: flex;
            justify-content: center;
        }

        @media (max-width: 992px) {
            .navbar-collapse {
                justify-content: flex-end;

            }

            .navbar-nav {
                width: 100%;
                display: flex;
                justify-content: center;

            }

            .nav-item {
                text-align: center;
            }


            .navbar-toggler {
                margin-left: end;
            }

            .navbar-head {
                font-size: 1.2rem;

            }
        }
    </style>

</head>

<body>

  

<nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark">
    <div class="container-fluid d-flex align-items-center">
        <a class="navbar-brand" href="index">
            Secured Organic agro using BlockChain
        </a>

        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index">Home</a>
                </li>
                <?php if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'farmer'): ?>
                 <li class="nav-item">
                    <a class="nav-link" href="detection">Detection</a>
                </li>
                <?php endif; ?>
               <li class="nav-item">
                    <a class="nav-link" href="cart">Cart</a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link" href="contact">Contact Us</a>
                </li>
                <?php if (isset($_SESSION['name'])): ?>
                  
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <?php echo $_SESSION['name']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="profile">Profile</a></li>
                              <li><a class="dropdown-item" href="myorder">My Order</a></li>
                            <li><a class="dropdown-item text-danger" href="logout">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>

</html>