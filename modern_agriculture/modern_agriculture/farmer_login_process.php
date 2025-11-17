<?php
session_start();
include 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($email) || empty($password)) {
        $_SESSION['error_message'] = "Email and password are required.";
        header('Location: farmer_login.php');
        exit();
    }

    $sql = "SELECT * FROM modern_new_users WHERE email = ? AND role = 'farmer'";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        $_SESSION['error_message'] = "Database error: " . $conn->error;
        header('Location: farmer_login.php');
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Correct password
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name']; 
            $_SESSION['user_role'] = $user['role']; 
            $_SESSION['success_message'] = "Login successful!";

            header('Location: farmer_dashboard.php');
            exit();
        } else {
            $_SESSION['error_message'] = "Invalid password. Please check your credentials.";
            header('Location: farmer_login.php');
            exit();
        }
    } else {
        $_SESSION['error_message'] = "No farmer account found with email: " . htmlspecialchars($email) . ". Please register as a farmer first or check your email.";
        header('Location: farmer_login.php');
        exit();
    }
}
?>
?>
