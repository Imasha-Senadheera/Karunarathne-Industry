<?php
$invalid = false;

// Set the desired time zone
date_default_timezone_set("Asia/Colombo"); // Time zone in Sri Lanka (GMT+5:30)

// Get the current time and date
$currentTimestamp = time();
$currentHour = date("H", $currentTimestamp);
$currentMinute = date("i", $currentTimestamp);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'connect.php';
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM `Cashiers` WHERE Username = '$username' AND Password = '$password'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $num = mysqli_num_rows($result);
        if ($num > 0) {
            $row = mysqli_fetch_assoc($result);
            
            // Check if it's within allowed login hours (8am - 6pm)
            if (($currentHour > 8 || ($currentHour == 8 && $currentMinute >= 0)) &&
                $currentHour < 18) {
                session_start();
                $_SESSION['username'] = $row['Username'];
                $_SESSION['role'] = $row['Role'];
                $_SESSION['lastLoginTime'] = $row['LastLoginTime'];
                header('Location: stock.php');
                exit();
            } else {
                // Outside allowed login hours
                $invalid = true;
            }
        } else {
            $invalid = true;
        }
    } else {
        // Debugging: Output the MySQL error if there is one
        echo "MySQL Error: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <style>
        /* Center the form */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Adjust the height as needed */
        }
        
        /* Add border around the form */
        .login-form {
            border: 5px solid #ccc;
            padding: 150px;
            border-radius: 50px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h1 class="mb-4">Login here!</h1>
            <?php if ($invalid): ?>
                <div class="alert alert-danger" role="alert">
                    Invalid username or password, or login outside allowed hours.
                </div>
            <?php endif; ?>
            <form action="login.php" method="post">
                <div class="mb-5">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" placeholder="Enter your username" name="username" required>
                </div>
                <div class="mb-5">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" placeholder="Enter your password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

