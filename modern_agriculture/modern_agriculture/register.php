<?php include 'header.php'; ?>

<div class="container" style="max-width: 700px; margin-top: 50px;">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Card Start -->
            <div class="card" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); background-color:rgb(160, 226, 252);">
                <div class="card-body">
                    <h4 class="text-center mb-4">Register</h4>
                    <?php
                   
                    if (isset($_SESSION['success_message'])) {
                        echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
                        unset($_SESSION['success_message']); 
                    }

                    if (isset($_SESSION['error_message'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
                        unset($_SESSION['error_message']); 
                    }
                    ?>
                    <form method="POST" action="register_process.php">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                        </div>
                      
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone_number" name="phone_number" placeholder="Enter your phone number" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Register as:</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="">-- Select Your Role --</option>
                                <option value="farmer">Farmer</option>
                                <option value="customer">Customer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password (min 8 chars, 1 number, 1 special char)" required>
                        </div>
                        <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter your address" required></textarea>
                </div>


                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                    </form>

               
                    <div class="text-center mt-3">
                        <p>Already have an account? <a href="login">Login here</a></p>
                    </div>
                </div>
            </div>
         
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
