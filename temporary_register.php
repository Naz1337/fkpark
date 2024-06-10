<?php

require_once 'bootstrap.php';




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FKPark - Temporary Register</title>
    <?php vite_asset('js/main.js') ?>
</head>
<body>
    <div class="login-container">
        <div class="card w-50">
            <div class="card-body d-flex flex-column">
                <h3 class="text-center mb-4">Register</h3>
                <form action="process_register.php" method="post">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" placeholder="cb22159" name="username" id="username">
                        <label for="username">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" placeholder="password1234" name="password" id="password">
                        <label for="password">Password</label>
                    </div>

                    <select name="user_type" id="userType" class="form-select mb-3">
                        <option selected disabled>User Type</option>
                        <option value="student">Student</option>
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                    </select>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" placeholder="Mohamad" name="first_name" id="firstName">
                        <label for="firstName">First Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" placeholder="bin" name="last_name" id="lastName">
                        <label for="lastName">Last Name</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" placeholder="+60-123-4567" name="contact_number" id="contactNumber">
                        <label for="contactNumber">Contact Number</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" placeholder="123 Main St" name="address" id="address">
                        <label for="address">Address</label>
                    </div>

                    <div class="d-flex justify-content-center mt-1">
                        <button class="btn btn-primary" type="submit">
                            Register
                        </button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</body>
</html>