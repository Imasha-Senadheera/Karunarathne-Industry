<?php
include 'connect.php';

$sql = "SELECT * FROM customers";
$result = $con->query($sql);

$currentTime = date("H:i");
$allowedStartTime = strtotime("08:00");
$allowedEndTime = strtotime("18:00");

if (strtotime($currentTime) < $allowedStartTime || strtotime($currentTime) > $allowedEndTime) {
    die("Access to this page is restricted outside of the allowed time range (8:00 AM - 6:00 PM).");
}

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
        <a href="/log1/create.php" class="btn btn-success mb-3">Add New</a>
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
                        echo "<td>
                                <a href='/log1/customer_edit.php".$row["CustomerID"]."' class='btn btn-primary btn-sm'>Edit</a>
                                <a href='/log1/customer_delete.php".$row["CustomerID"]."' class='btn btn-danger btn-sm'>Delete</a>
                              </td>";
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
