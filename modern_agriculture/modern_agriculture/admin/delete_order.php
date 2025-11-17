<?php

session_start();

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    include '../db.php';

    $sql = "DELETE FROM modern_new_orders WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $order_id);

        if ($stmt->execute()) {
        
            $_SESSION['message'] = "Order deleted successfully!";
            $_SESSION['message_type'] = "success";
            header("Location: orders"); 
            exit;
        } else {
          
            $_SESSION['message'] = "Error deleting record: " . $conn->error;
            $_SESSION['message_type'] = "error";
        }

        $stmt->close();
    } else {
     
        $_SESSION['message'] = "Error preparing query: " . $conn->error;
        $_SESSION['message_type'] = "error";
    }

    $conn->close();
}
?>
