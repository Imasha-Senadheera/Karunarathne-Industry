<?php
$login = 0;
$invalid = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'connect.php';
    $username = $_POST['UserName'];
    $password = $_POST['Password'];
    $role = $_post['Role'];
    $lastLogTime = $_post['LastLoginTime'];

    $sql = "SELECT * FROM `login` WHERE UserName = '$username' AND Password = '$password'";

    $result = mysqli_query($con, $sql);

    if ($result) {
        $num = mysqli_num_rows($result);
        if ($num > 0) {
            $login = 1;
            $row = mysqli_fetch_assoc($result);
            session_start();
            $_SESSION['UserName'] = $row['UserName'];
            $_SESSION['Role'] = $row['Role'];
            $_SESSION['LastLoginTime'] = $row['LastLoginTime'];
            header('location:home.php');
        } else {
            $invalid = 1;
        }    
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>sign Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>
<body>

<?php
if ($invalid) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Ohh NO Sorry!</strong> Invalid data!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}
?>

<?php
if ($login) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success!</strong> You are successfully logged in!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}
?>

<div class="container mt-5">
    <h1>Sign here!</h1> </br>
    <form action="login.php" method="post">
        <div class="mb-3">
            <label for="userName" class="form-label">Name</label>
            <input type="text" class="form-control" placeholder="Enter your username" name="UserName">
        </div>

        <div class="mb-3">
            <label for="Role" class="form-label">Role</label>
            <input type="text" class="form-control" placeholder="Enter your role" name="Role">
        </div>

        <div class="mb-3">
            <label for="LastLoginTime" class="form-label">Last Login Time</label>
            <input type="text" class="form-control" placeholder="Enter your Last Login Time" name="LastLoginTime">
        </div>

        <div class="mb-3">
            <label for="userPassword" class="form-label">Password</label>
            <input type="password" class="form-control" placeholder="Enter your password" name="Password">
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>
</html>
