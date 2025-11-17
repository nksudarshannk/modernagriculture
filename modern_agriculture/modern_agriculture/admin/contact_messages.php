<?php
session_start(); 

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login');
    exit();
}

include '../db.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM contact_us WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $success_msg = "Message deleted successfully!";
    } else {
        $error_msg = "Error deleting message: " . $conn->error;
    }
}

// Fetch all contact messages
$sql = "SELECT * FROM contact_us ORDER BY created_at DESC";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching messages: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .message-container {
            margin-top: 30px;
            margin-bottom: 50px;
        }

        .message-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 5px solid #28a745;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .message-card:hover {
            transform: translateX(5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .sender-info {
            flex: 1;
            min-width: 200px;
        }

        .sender-info h5 {
            margin: 0;
            color: #333;
            font-weight: 600;
        }

        .sender-info p {
            margin: 5px 0;
            color: #666;
            font-size: 0.9rem;
        }

        .message-date {
            color: #999;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .message-content {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 3px solid #007bff;
            line-height: 1.6;
            color: #333;
        }

        .message-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .btn-action {
            padding: 6px 15px;
            font-size: 0.9rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }

        .btn-reply {
            background-color: #28a745;
            color: white;
        }

        .btn-reply:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        .header-section {
            margin-bottom: 30px;
        }

        .header-section h2 {
            color: #333;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .success-msg {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        .error-msg {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #ddd;
        }

        .empty-state p {
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .message-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .message-actions {
                justify-content: flex-start;
            }

            .message-card {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <?php include 'slidebar.php'; ?>

    <div class="content" id="content">
        <div class="header-section">
            <h2><i class="bi bi-chat-dots"></i> Contact Messages</h2>
            <hr>
        </div>

        <?php if (isset($success_msg)): ?>
            <div class="success-msg">
                <i class="bi bi-check-circle"></i> <?php echo $success_msg; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_msg)): ?>
            <div class="error-msg">
                <i class="bi bi-exclamation-circle"></i> <?php echo $error_msg; ?>
            </div>
        <?php endif; ?>

        <div class="message-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($message = $result->fetch_assoc()): ?>
                    <div class="message-card">
                        <div class="message-header">
                            <div class="sender-info">
                                <h5><?php echo htmlspecialchars($message['name']); ?></h5>
                                <p><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($message['email']); ?></p>
                                <p><i class="bi bi-telephone"></i> <?php echo htmlspecialchars($message['phone']); ?></p>
                            </div>
                            <div class="message-date">
                                <i class="bi bi-calendar"></i> <?php echo date('d M Y, h:i A', strtotime($message['created_at'])); ?>
                            </div>
                        </div>

                        <div class="message-content">
                            <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                        </div>

                        <div class="message-actions">
                            <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>" class="btn-action btn-reply">
                                <i class="bi bi-reply"></i> Reply
                            </a>
                            <a href="?delete=<?php echo $message['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure you want to delete this message?');">
                                <i class="bi bi-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <p>No contact messages yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="js/slide.js"></script>
</body>

</html>
