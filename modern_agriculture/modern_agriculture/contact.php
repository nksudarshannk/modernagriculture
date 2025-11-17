<?php
include 'db.php';

// Ensure the contact_us table exists (idempotent)
$create_table_sql = "CREATE TABLE IF NOT EXISTS contact_us (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) DEFAULT NULL,
    message TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
if (!$conn->query($create_table_sql)) {
    // If table creation fails, set an error and stop further processing
    $error_msg = "Could not prepare contact table: " . $conn->error;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO contact_us (name, email, phone, message, created_at) 
            VALUES ('$name', '$email', '$phone', '$message', NOW())";
    
    if ($conn->query($sql) === TRUE) {
        $success_msg = "Thank you for contacting us! We'll get back to you soon.";
    } else {
        $error_msg = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #ffefba, #ffd1dc, #d0e8f2, #c6e7d1);
            padding-top: 70px;
        }

        .contact-container {
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .contact-heading {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
            text-align: center;
            margin-bottom: 10px;
        }

        .contact-subheading {
            text-align: center;
            color: #666;
            font-size: 1rem;
            margin-bottom: 50px;
        }

        .contact-form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin-bottom: 40px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 150px;
        }

        .submit-btn {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 12px 40px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            width: 100%;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
            color: white;
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

        .contact-info {
            margin-top: 40px;
        }

        .info-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
        }

        .info-card i {
            font-size: 2rem;
            color: #28a745;
            margin-bottom: 15px;
        }

        .info-card h5 {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .info-card p {
            color: #666;
            margin: 0;
        }

        @media (max-width: 768px) {
            .contact-heading {
                font-size: 1.8rem;
            }

            .contact-form-container {
                padding: 20px;
            }

            .contact-container {
                margin-top: 20px;
            }
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>

    <div class="container contact-container">
        <h1 class="contact-heading">Contact Us</h1>
        <p class="contact-subheading">We'd love to hear from you. Send us a message and we'll get back to you as soon as possible.</p>

        <div class="contact-form-container">
            <?php if (isset($success_msg)): ?>
                <div class="success-msg">
                    <i class="fas fa-check-circle"></i> <?php echo $success_msg; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error_msg)): ?>
                <div class="error-msg">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_msg; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="contact">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                </div>

                <div class="form-group">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control" id="message" name="message" required></textarea>
                </div>

                <button type="submit" class="submit-btn">Send Message</button>
            </form>
        </div>

        <div class="contact-info">
            <h3 style="text-align: center; margin-bottom: 30px; font-weight: bold; color: #333;">Get in Touch</h3>
            <div class="row">
                <div class="col-md-4">
                    <div class="info-card">
                        <i class="fas fa-map-marker-alt"></i>
                        <h5>Address</h5>
                        <p>Modern Agriculture<br>Innovation Center<br>India</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-card">
                        <i class="fas fa-phone"></i>
                        <h5>Phone</h5>
                        <p>+91 7891234567</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-card">
                        <i class="fas fa-envelope"></i>
                        <h5>Email</h5>
                        <p>support@modernagro.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
