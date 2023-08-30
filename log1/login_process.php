<?php
$invalid = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'connect.php';
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM `Cashiers` WHERE Username = '$username'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['Password'])) {
            session_start();
            $_SESSION['username'] = $row['Username'];
            $_SESSION['role'] = $row['Role'];
            $_SESSION['lastLoginTime'] = $row['LastLoginTime'];
            header('Location: stock.php');
            exit();
        } else {
            $invalid = true;
        }
    } else {
        // Debugging: Output the MySQL error if there is one
        echo "MySQL Error: " . mysqli_error($con);
    }
}
?>
