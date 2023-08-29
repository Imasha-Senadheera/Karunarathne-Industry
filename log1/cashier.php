<?php
include 'connect.php';

$sql = "SELECT cashiers.CashierID, cashiers.Name, cashiers.Username, cashiers.Password, cashiers.FlowID, COUNT(sales.SaleID) AS TotalSales
        FROM cashiers
        LEFT JOIN sales ON cashiers.CashierID = sales.CashierID
        GROUP BY cashiers.CashierID";
$result = $con->query($sql);

if ($result === false) {
    die("Error in SQL query: " . $con->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cashier</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Cashier Details and Sales Tracking</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>CashierID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>FlowID</th>
                    <th>Total Sales</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row["CashierID"]."</td>";
                        echo "<td>".$row["Name"]."</td>";
                        echo "<td>".$row["Username"]."</td>";
                        echo "<td>".$row["Password"]."</td>";
                        echo "<td>".$row["FlowID"]."</td>";
                        echo "<td>".$row["TotalSales"]."</td>";
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
