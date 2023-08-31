<?php
include 'connect.php';

// Check if the user is logged in and has the necessary role
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'Manager' && $_SESSION['role'] != 'Cashier')) {
    header('Location: login.php'); // Redirect unauthorized users to the login page
    exit();
}

$sql = "SELECT * FROM products";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Product Details</h1>
        <?php if ($_SESSION['role'] == 'Manager' || $_SESSION['role'] == 'Cashier'): ?>
            <a href="product_create.php" class="btn btn-success mb-3">Add New</a>
        <?php endif; ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ProductID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row["ProductID"]."</td>";
                        echo "<td>".$row["Name"]."</td>";
                        echo "<td>".$row["Description"]."</td>";
                        echo "<td>".$row["Price"]."</td>";
                        echo "<td>".$row["Quantity"]."</td>";
                        echo "<td>";
                        
                        // Cashiers can only edit
                        if ($_SESSION['role'] == 'Cashier') {
                            echo "<a href='product_edit.php?id=".$row["ProductID"]."' class='btn btn-primary btn-sm'>Edit</a>";
                        }
                        
                        // Managers can edit and delete
                        if ($_SESSION['role'] == 'Manager') {
                            echo "<a href='product_edit.php?id=".$row["ProductID"]."' class='btn btn-primary btn-sm'>Edit</a>";
                            echo "<a href='product_delete.php?id=".$row["ProductID"]."' class='btn btn-danger btn-sm'>Delete</a>";
                        }
                        
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
