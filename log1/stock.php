<?php
include 'connect.php';

$sql = "SELECT * FROM stock";
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
    <title>Stock</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Stock Details</h1>
        <a href="/log1/create.php" class="btn btn-success mb-3">Add New</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>StockID</th>
                    <th>ProductID</th>
                    <th>GRNDate</th>
                    <th>InvoiceDate</th>
                    <th>PurchaseOrderDate</th>
                    <th>QuantityReceived</th>
                    <th>QuantitySold</th>
                    <th>QuantityRemaining</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row["StockID"]."</td>";
                        echo "<td>".$row["ProductID"]."</td>";
                        echo "<td>".$row["GRNDate"]."</td>";
                        echo "<td>".$row["InvoiceDate"]."</td>";
                        echo "<td>".$row["PurchaseOrderDate"]."</td>";
                        echo "<td>".$row["QuantityReceived"]."</td>";
                        echo "<td>".$row["QuantitySold"]."</td>";
                        echo "<td>".$row["QuantityRemaining"]."</td>";
                        echo "<td>
                                <a href='/log1/edit.php".$row["StockID"]."' class='btn btn-primary btn-sm'>Edit</a>
                                <a href='/log1/delete.php".$row["StockID"]."' class='btn btn-danger btn-sm'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
