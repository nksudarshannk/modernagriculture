<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to view your profile.'); window.location='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT * FROM modern_new_users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle undefined fields
$name = isset($user['name']) ? $user['name'] : '';
$phone = isset($user['phone_number']) ? $user['phone_number'] : '';

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone_number']);
    
    $update_sql = "UPDATE modern_new_users SET name = ?, phone_number = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssi", $name, $phone, $user_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location='profile.php';</script>";
    } else {
        echo "<script>alert('Error updating profile!');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
      
        

       
        .card {
            background-color: #1e1e1e !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3) !important;
            padding: 20px !important;
            border: 1px solid #333 !important;
        }

     
        .form-control {
            background-color: #2a2a2a !important;
            color: #ffffff !important;
            border: 1px solid #444 !important;
        }
        .form-control:focus {
            background-color: #333 !important;
            border-color: #007bff !important;
            color: #ffffff !important;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5) !important;
        }

       
        .form-label {
            font-weight: bold !important;
            color: white !important;
        }

     
        .btn-primary {
            background-color: #007bff !important;
            border-color: #007bff !important;
            color: #ffffff !important;
            font-weight: bold !important;
        }
        .btn-primary:hover {
            background-color: orange !important;
            border-color: #0056b3 !important;
        }

        .btn-secondary {
            background-color: #333 !important;
            border-color: #444 !important;
            color: #ffffff !important;
        }
        .btn-secondary:hover {
            background-color: #555 !important;
            border-color: #666 !important;
        }

       
        h2 {
            color: black !important;
            font-weight: bold !important;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center">👤 My Profile</h2>
        
        <div class="card">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email (cannot be changed)</label>
                    <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone_number" value="<?php echo htmlspecialchars($phone); ?>" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">Update Profile</button>
            </form>
        </div>

        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
