<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'admin') {

    header('Location: ../login');
    exit();
}

include '../db.php';

$error = $success = '';
$name = $email = $phone_number = $role = $address = '';
$edit_id = null;

// Handle Delete
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $sql = "DELETE FROM modern_new_users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $success = "User deleted successfully!";
    } else {
        $error = "Error deleting user.";
    }
}

// Handle Edit (Pre-fill form)
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];

    $sql = "SELECT * FROM modern_new_users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $name = htmlspecialchars($row['name']);
        $email = htmlspecialchars($row['email']);
        $phone_number = htmlspecialchars($row['phone_number']);
        $role = htmlspecialchars($row['role']);
        $address = htmlspecialchars($row['address']);
    }
}

// Handle Add / Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);
    $role = trim($_POST['role']);
    $address = trim($_POST['address']);
    $edit_id = isset($_POST['edit_id']) ? $_POST['edit_id'] : null;

    if (empty($name) || empty($email) || empty($phone_number) || empty($role)) {
        $error = "All fields are required!";
    } else {
        if ($edit_id) {
            // Update user
            $sql = "UPDATE modern_new_users SET name = ?, email = ?, phone_number = ?, role = ?, address = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $name, $email, $phone_number, $role, $address, $edit_id);
        } else {
            // Insert new user
            $password = password_hash("123456", PASSWORD_BCRYPT); // Default password
            $sql = "INSERT INTO modern_new_users (name, email, phone_number, password, role, address) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $name, $email, $phone_number, $password, $role, $address);
        }

        if ($stmt->execute()) {
            $success = $edit_id ? "User updated successfully!" : "User added successfully!";
        } else {
            $error = "Error saving user.";
        }
    }
}

// Fetch users
$sql = "SELECT * FROM modern_new_users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-color: #fbebbb; font-family: Arial, sans-serif;">

    <?php include 'slidebar.php'; ?>
   <div class="content"  id="content">
    <div class="container ">
        <h3 class="text-center fw-bold">Manage Users</h3>

       
        <form method="POST" class="mb-4 p-4 bg-white shadow rounded">
            <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
              <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone_number" class="form-control" value="<?php echo $phone_number; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-control" required>
                    <option value="user" <?php echo ($role == 'user') ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo ($role == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control" value="<?php echo $address; ?>">
            </div>
            <button type="submit" class="btn w-100 <?php echo $edit_id ? 'btn-success' : 'btn-warning'; ?>">
                <?php echo $edit_id ? "Update User" : "Add User"; ?>
            </button>
        </form>

        <!-- Users Table -->
        <div class="table-responsive bg-white shadow p-3 rounded">
            <table class="table table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['phone_number']; ?></td>
                            <td><?php echo $row['role']; ?></td>
                            <td><?php echo $row['address']; ?></td>
                            <td>
                                <a href="?edit_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
  <script src="js/slide.js"></script>
</body>
</html>
