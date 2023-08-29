<?php
include 'connect.php';

$sql = "SELECT sales.*, products.Price, discounts.DiscountPercentage FROM sales
        JOIN products ON sales.ProductID = products.ProductID
        LEFT JOIN discounts ON sales.ProductID = discounts.ProductID";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Sales Details</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>SaleID</th>
                    <th>CashierID</th>
                    <th>ProductID</th>
                    <th>SaleDate</th>
                    <th>Quantity</th>
                    <th>TotalAmount</th>
                    <th>Discount (%)</th> <!-- New column -->
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row["SaleID"]."</td>";
                        echo "<td>".$row["CashierID"]."</td>";
                        echo "<td>".$row["ProductID"]."</td>";
                        echo "<td>".$row["SaleDate"]."</td>";
                        echo "<td>".$row["Quantity"]."</td>";
                        echo "<td>".$row["TotalAmount"]."</td>";
                        echo "<td>".$row["DiscountPercentage"]."</td>"; // Display discount percentage
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
