<?php
include 'connect.php';

$sql = "SELECT * FROM stock";
$result = $con->query($sql);

//$currentTime = date("H:i");
//$allowedStartTime = strtotime("08:00");
//$allowedEndTime = strtotime("18:00");

//if (strtotime($currentTime) < $allowedStartTime || strtotime($currentTime) > $allowedEndTime) {
//    die("Access to this page is restricted outside of the allowed time range (8:00 AM - 6:00 PM).");
//}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Stock</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
<div class="container mt-5">
        <h1 class="mb-4">Stock Details</h1>
        <a href="stock_create.php" class="btn btn-success mb-3">Add New</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>StockID</th>
                    <th>ProductID</th>
                    <th>GRN</th>
                    <th>InvoiceDate</th>
                    <th>PurchaseOrderDate</th>
                    <th>Quty Received</th>
                    <th>Quty Sold</th>
                    <th>Qty Remaining</th>
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
                        echo "<td>".$row["GRN"]."</td>";
                        echo "<td>".$row["InvoiceDate"]."</td>";
                        echo "<td>".$row["PurchaseOrderDate"]."</td>";
                        echo "<td>".$row["QuantityReceived"]."</td>";
                        echo "<td>".$row["QuantitySold"]."</td>";
                        echo "<td>".$row["QuantityRemaining"]."</td>";
                        echo "<td>
                                <a href='stock_edit.php?id=".$row["StockID"]."' class='btn btn-primary btn-sm'>Edit</a>
                                <a href='stock_delete.php?id=".$row["StockID"]."' class='btn btn-danger btn-sm'>Delete</a>
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

    <?php

$sql = "SELECT * FROM stock";
$result = $con->query($sql);

$chartData = [['StockID', 'QuantityReceived', 'QuantityRemaining']];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $chartData[] = [intval($row["StockID"]), intval($row["QuantityReceived"]), intval($row["QuantityRemaining"])];
    }
}
?>

<div class="container mt-5">
        <h1 class="mb-4">Stock Management Generated Chart</h1>
    </div>

    <div class="container mt-4">
        <h2>Quantity Chart</h2>
        <div id="chart_div"></div>
    </div>

    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(<?php echo json_encode($chartData); ?>);

            var options = {
                title: 'Quantity Received and Remaining',
                chartArea: {width: '50%'},
                hAxis: {
                    title: 'Quantity',
                    minValue: 0
                },
                vAxis: {
                    title: 'Stock ID'
                }
            };

            var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
</body>
</html>
