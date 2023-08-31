<?php
include 'connect.php';

// Check if the user is logged in and has the necessary role
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'Manager' && $_SESSION['role'] != 'Cashier')) {
    header('Location: login.php'); // Redirect unauthorized users to the login page
    exit();
}

$sql = "SELECT * FROM customers";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customers</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Customer Details</h1>
        <?php if ($_SESSION['role'] == 'Manager' || $_SESSION['role'] == 'Cashier'): ?>
            <a href="customer_create.php" class="btn btn-success mb-3">Add New</a>
        <?php endif; ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>CustomerID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row["CustomerID"]."</td>";
                        echo "<td>".$row["Name"]."</td>";
                        echo "<td>".$row["Email"]."</td>";
                        echo "<td>".$row["Phone"]."</td>";
                        echo "<td>";
                        echo "<a href='customer_edit.php?id=".$row["CustomerID"]."' class='btn btn-primary btn-sm'>Edit</a>";
                        if ($_SESSION['role'] == 'Manager') {
                            echo "<a href='customer_delete.php?id=".$row["CustomerID"]."' class='btn btn-danger btn-sm'>Delete</a>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
