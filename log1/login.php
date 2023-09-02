<?php
date_default_timezone_set("Asia/Colombo");

$invalid = false;

include 'connect.php';  // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Check if the user is a manager and restrict login time
    if ($role == 'Manager') {
        $isAllowed = true;
    } else {
        $currentHour = date("H");
        if ($currentHour >= 8 && $currentHour < 18) {
            $isAllowed = true;
        } else {
            $isAllowed = false;
            $invalid = true;
        }
    }

    if ($isAllowed) {
        // Authenticate user using MySQLi
        $query = "SELECT UserID, Username, Role FROM users WHERE Username = ? AND Password = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            session_start();
            $_SESSION['user_id'] = $user['UserID'];
            $_SESSION['username'] = $user['Username'];
            $_SESSION['role'] = $user['Role'];
        
            // Insert login details into the database
            $insertQuery = "INSERT INTO login (UserID, Username, Role, LoginTime) VALUES (?, ?, ?, NOW())";
            $insertStmt = $con->prepare($insertQuery);
            $insertStmt->bind_param("sss", $user['UserID'], $user['Username'], $user['Role']);
            $insertStmt->execute();
        
            if ($user['Role'] == 'Cashier') {
                header('Location: cashier_dashboard.php');
                exit();
            } elseif ($user['Role'] == 'Manager') {
                header('Location: manager_dashboard.php');
                exit();
            }
        } else {
            $invalid = true;
        }        
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
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
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
                <div class="mb-5">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" name="role" required>
                        <option value="Cashier">Cashier</option>
                        <option value="Manager">Manager</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
