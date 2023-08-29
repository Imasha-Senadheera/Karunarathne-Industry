<?php
include 'connect.php';

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
        <a href="/log1/create.php" class="btn btn-success mb-3">Add New</a>
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
                        echo "<td>
                                <a href='/log1/edit.php".$row["ProductID"]."' class='btn btn-primary btn-sm'>Edit</a>
                                <a href='/log1/delete.php".$row["ProductID"]."' class='btn btn-danger btn-sm'>Delete</a>
                              </td>";
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
