<?php
include 'connect.php';

$dateFilter = "";
if (isset($_GET['date'])) {
    $selectedDate = $_GET['date'];
    $dateFilter = "WHERE DATE(sales.SaleDate) = '$selectedDate'";
}

$sql = "SELECT sales.*, products.Price, discounts.DiscountPercentage FROM sales
        JOIN products ON sales.ProductID = products.ProductID
        LEFT JOIN discounts ON sales.ProductID = discounts.ProductID
        $dateFilter";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Sales Details</h1>
        
        <!-- Date Picker and Search Form -->
        <div class="mb-3">
            <label for="datepicker">Search by Date:</label>
            <input type="text" id="datepicker" class="form-control" placeholder="Select a date">
            <button class="btn btn-primary mt-2" id="searchButton">Search</button>
            <a href="sales.php" class="btn btn-secondary mt-2">View All</a>
        </div>

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

    <script>
        $(function() {
            $("#datepicker").datepicker({
                dateFormat: "yy-mm-dd" // Set the desired date format
            });
            
            $("#searchButton").click(function() {
                var selectedDate = $("#datepicker").val();
                if (selectedDate) {
                    window.location.href = "sales.php?date=" + selectedDate;
                }
            });
        });
    </script>
</body>
</html>

