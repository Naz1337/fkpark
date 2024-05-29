<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FKPark - Login</title>
    <?php
    require_once 'bootstrap.php';
    vite_asset('js/main.js');
    
    if (isset($_SESSION['username']))
        header('location: index.php');

    ?>
</head>
<body>
    <div class="login-container">
        <div class="card">
            <div class="card-body d-flex flex-column">
                <h3 class="text-center mb-4">Login</h3>
                <form action="process_login.php" method="post">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" placeholder="cb22159" name="username" id="username">
                        <label for="username">Username</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" placeholder="cb22159" name="password" id="password">
                        <label for="password">Password</label>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-primary" type="submit">
                            Log In
                        </button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</body>
</html>