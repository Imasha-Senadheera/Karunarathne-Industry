<?php
include 'connect.php';

$sql = "SELECT * FROM sales";
$result = $con->query($sql);

// Calculate daily profit and total profit
$dailyProfit = 0;
$totalProfit = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dailyProfit += $row["TotalAmount"];
        $totalProfit += $row["TotalAmount"];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profit</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Profit Management</h1>
        <table class="table table-bordered">
            <!-- Table content here -->
        </table>

        <!-- Chart section -->
        <div class="mt-5">
            <h2>Daily Profit and Total Profit Chart</h2>
            <canvas id="profitChart" width="400" height="200"></canvas>
        </div>
    </div>

    <script>
        var ctx = document.getElementById('profitChart').getContext('2d');
        var profitChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Daily Profit', 'Total Profit'],
                datasets: [{
                    label: 'Profit',
                    data: [<?php echo $dailyProfit; ?>, <?php echo $totalProfit; ?>],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
