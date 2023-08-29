<?php
include 'connect.php';

$sql = "SELECT * FROM sales";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Sales Management</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>SaleID</th>
                    <th>CashierID</th>
                    <th>ProductID</th>
                    <th>SaleDate</th>
                    <th>Quantity</th>
                    <th>TotalAmount</th>
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
