<?php
include 'db.php';

if ($conn->connect_error) {
    die("Database Connection failed: " . $conn->connect_error);
}

$encryption_key = "78ct8RYGMsOKtNvB";

$sql = "SELECT token, 
               AES_DECRYPT(temperature, ?) AS temperature, 
               AES_DECRYPT(ph, ?) AS ph, 
               AES_DECRYPT(gas, ?) AS gas, 
               AES_DECRYPT(moisture, ?) AS moisture, 
               recorded_date 
        FROM `sensor_data`";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $encryption_key, $encryption_key, $encryption_key, $encryption_key);
$stmt->execute();
$result = $stmt->get_result();
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sensor Data Table</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
    
     .navbar {
        font-weight: bold;
        font-size: 1.2rem;
    }



    .navbar-nav .nav-link {
        color: white;
        transition: color 0.3s ease-in-out;
    }

    .navbar-nav .nav-link:hover {
        color: orange;
        text-decoration: none;
    }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand text-uppercase" href="#">
              Blockchain In Modern Agriculture
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="get">Track</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    
   
    <table class="mt-5">
         <h2 class="mt-8">Sensor Data</h2>
        <tr>
            <th>Token</th>
            <th>Temperature</th>
            <th>pH</th>
            <th>Gas</th>
            <th>Moisture</th>
            <th>Recorded Date</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row["token"]); ?></td>
                <td><?php echo htmlspecialchars($row["temperature"]); ?></td>
                <td><?php echo htmlspecialchars($row["ph"]); ?></td>
                <td><?php echo htmlspecialchars($row["gas"]); ?></td>
                <td><?php echo htmlspecialchars($row["moisture"]); ?></td>
                <td><?php echo htmlspecialchars($row["recorded_date"]); ?></td>
            </tr>
        <?php } ?>

    </table>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
