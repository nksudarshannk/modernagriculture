<?php
session_start();
include 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM modern_new_users WHERE email = ?";
    $stmt = $conn->prepare($sql);
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

            // Redirect to role-specific dashboards
            $_SESSION['success_message'] = "Login successful!";
            if ($user['role'] === 'admin') {
                header('Location: admin/dashboard.php');
                exit();
            } elseif ($user['role'] === 'farmer') {
                header('Location: farmer_dashboard.php');
                exit();
            } elseif ($user['role'] === 'customer') {
                header('Location: customer_dashboard.php');
                exit();
            } else {
                // Unknown role: fallback to home
                header('Location: index.php');
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Invalid password.";
            header('Location: login');
            exit();
        }
    } else {
        $_SESSION['error_message'] = "No user found with that email address.";
        header('Location: login');
        exit();
    }
}
?>
