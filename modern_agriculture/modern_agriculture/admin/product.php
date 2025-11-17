<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'admin') {

    header('Location: ../login');
    exit();
}

include '../db.php';

$error = $success = '';
$title = $description = '';
$image_path = '';
$price = '';
$edit_id = null; 

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Get image path to delete
    $sql = "SELECT image_path FROM modern_new_products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $image_path = $row['image_path'];
        $full_image_path = __DIR__ . '/' . $image_path;
        if (file_exists($full_image_path) && !empty($image_path)) {
            unlink($full_image_path); // Delete image
        }

        // Delete product from DB
        $sql = "DELETE FROM modern_new_products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("SQL Error: " . $conn->error);
        }
        $stmt->bind_param("i", $delete_id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Product deleted successfully.";
            header("Location: product");
            exit;
        } else {
             $_SESSION['error'] = "Error deleting product.";
        }
    }
}

// Handle edit request (Pre-fill form)
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];

    $sql = "SELECT * FROM modern_new_products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $title = htmlspecialchars($row['title']);
        $price = htmlspecialchars($row['price']); // Add this line to define $price
        $description = htmlspecialchars($row['description']);
        $image_path = $row['image_path'];
    }
}

// Handle insert or update logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $image = isset($_FILES['image']) ? $_FILES['image'] : null;
    $edit_id = isset($_POST['edit_id']) ? $_POST['edit_id'] : null;

    if (empty($title) || empty($description)) {
        $_SESSION['error'] = "Title and description are required.";
    } else {
        if ($image && $image['error'] == 0) {
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . basename($image['name']);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                if (move_uploaded_file($image['tmp_name'], $target_file)) {
                    if ($edit_id && !empty($image_path) && file_exists(__DIR__ . '/' . $image_path)) {
                        unlink(__DIR__ . '/' . $image_path);
                    }
                    $image_path = $target_file;
                } else {
                    $_SESSION['error'] = "Error uploading the image.";
                }
            } else {
                $_SESSION['error'] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
            }
        }

        if (!isset($_SESSION['error'])) {
            if ($edit_id) {
                if (!empty($image_path)) {
                    $sql = "UPDATE modern_new_products SET title = ?, price = ?, description = ?, image_path = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssssi", $title, $price, $description, $image_path, $edit_id);
                } else {
                    $sql = "UPDATE modern_new_products SET title = ?, price = ?, description = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssi", $title, $price, $description, $edit_id);
                }
            } else {
                $sql = "INSERT INTO modern_new_products (title, price, description, image_path) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $title, $price, $description, $image_path);
            }

            if ($stmt->execute()) {
                $_SESSION['success'] = $edit_id ? "Product updated successfully." : "Product added successfully.";
                header("Location: product");
                exit();
            } else {
                $_SESSION['error'] = "Error saving product.";
            }
        }
    }
}



$sql = "SELECT * FROM modern_new_products";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Products</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
       td {
        background-color: #fff!important;
        border-bottom: 1px solid black;
         font-weight: bold;
    }
     tr:hover td {
          font-weight: bold;
        background-color: blue !important;
        color:white;
        cursor: pointer;
    }
    </style>
   
</head>
<body style="background-color: #fbebbb; font-family: Arial, sans-serif;">

    <?php include 'slidebar.php'; ?>
     <div class="content"  id="content">
    
        
       <div class="container ">
        <h3 class="text-center mb-4" style="color: #333;">Manage Products</h3>
       
        <div class="card p-4 shadow-sm" style="background-color: #e3f2fd; border-radius: 8px;">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                 <?php
                
                if (isset($_SESSION['success'])) {
                    echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
                    unset($_SESSION['success']);
                }
                if (isset($_SESSION['error'])) {
                    echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
                    unset($_SESSION['error']);
                }
                ?>

                <div class="mb-3">
                    <label class="form-label fw-bold">Product Name</label>
                    <input type="text" name="title" class="form-control" value="<?php echo $title; ?>" required
                           style="border-radius: 5px; border: 1px solid #007bff;">
                </div>

                   <div class="mb-3">
                    <label class="form-label fw-bold">Price</label>
                    <input type="text" name="price" class="form-control" value="<?php echo $price; ?>" required
                           style="border-radius: 5px; border: 1px solid #007bff;">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <textarea name="description" class="form-control" required
                              style="border-radius: 5px; border: 1px solid #007bff;"><?php echo $description; ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Image</label>
                    <input type="file" name="image" class="form-control"
                           style="border-radius: 5px; border: 1px solid #007bff;">
                    <?php if (!empty($image_path)): ?>
                        <div class="mt-2">
                            <p class="small text-muted">Current Image:</p>
                            <img src="<?php echo htmlspecialchars($image_path); ?>" class="img-thumbnail" width="150"
                                 style="border: 2px solid #007bff;">
                        </div>
                    <?php endif; ?>
                </div>

               <div class="d-flex justify-content-center">
    <button type="submit" class="btn w-50 <?php echo $edit_id ? 'btn-success' : 'btn-warning'; ?>" style="color: white; border-radius: 5px;">
        <?php echo $edit_id ? "Update Product" : "Add Product"; ?>
    </button>
</div>


            </form>
        </div>

        <div class="mt-5">
         <h4 class="mb-3 text-center fw-bold" style="color: black;">Product List</h4>

            <table class="table table-bordered table-striped" style="background-color: #e3f2fd;">
                <thead style="background-color: black; color: white;">
                    <tr>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr >
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['price']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td>
                            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" class="img-thumbnail" width="100"
                                 style="border: 2px solid #007bff;">
                        </td>
                        <td>
                            <a href="?edit_id=<?php echo $row['id']; ?>" class="btn btn-sm"
                               style="background-color: #ffcc00; color: black; border-radius: 5px;">Edit</a>
                        </td>
                        <td>
                            <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-sm"
                               style="background-color: #dc3545; color: white; border-radius: 5px;"
                               onclick="return confirm('Delete this product?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <script src="js/slide.js"></script>
</body>
</html>
