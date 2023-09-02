<?php
include 'connect.php';

$dateFilter = "";
if (isset($_GET['date'])) {
    $selectedDate = $_GET['date'];
    $dateFilter = "WHERE DATE(sales.SaleDate) = '$selectedDate'";
}

$sql = "SELECT sales.* FROM sales
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
                    <th>UserID</th>
                    <th>ProductID</th>
                    <th>StockID</th>
                    <th>CustomerID</th>
                    <th>SaleDate</th>
                    <th>SaleTime</th>
                    <th>QuantitySold</th>
                    <th>TotalAmount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row["SaleID"]."</td>";
                        echo "<td>".$row["UserID"]."</td>";
                        echo "<td>".$row["ProductID"]."</td>";
                        echo "<td>".$row["StockID"]."</td>";
                        echo "<td>".$row["CustomerID"]."</td>";
                        echo "<td>".$row["SaleDate"]."</td>";
                        echo "<td>".$row["SaleTime"]."</td>";
                        echo "<td>".$row["QuantitySold"]."</td>";
                        echo "<td>".$row["TotalAmount"]."</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Go to Dashboard Button -->
        <a href="manager_dashboard.php" class="btn btn-warning">Go to Dashboard</a>
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
