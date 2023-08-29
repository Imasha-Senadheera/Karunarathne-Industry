<?php
include 'connect.php';

$sql = "SELECT * FROM stock";
$result = $con->query($sql);

$chartData = [['StockID', 'QuantityReceived', 'QuantityRemaining']];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $chartData[] = [intval($row["StockID"]), intval($row["QuantityReceived"]), intval($row["QuantityRemaining"])];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Stock Management Generated Chart</h1>
        <table class="table table-bordered">
            <!-- ... your existing table ... -->
        </table>
    </div>

    <div class="container mt-5">
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
