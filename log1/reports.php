<?php
include 'connect.php';

// Query to fetch sales data
$sql_sales = "SELECT DATE(SaleDate) AS SaleDate, SUM(TotalAmount) AS TotalAmount
              FROM sales
              GROUP BY DATE(SaleDate)
              ORDER BY DATE(SaleDate)";

$result_sales = mysqli_query($con, $sql_sales); // Change $conn to $con here

$dates = [];
$amounts = [];

if ($result_sales) {
    while ($row = mysqli_fetch_assoc($result_sales)) { // Change $conn to $con here
        $dates[] = $row['SaleDate'];
        $amounts[] = $row['TotalAmount'];
    }
    mysqli_free_result($result_sales); // Free the result set
}

// Query to fetch stock data
$sql_stock = "SELECT p.ProductID, s.Quantity, s.QuantitySold, s.QuantityRemaining
              FROM stock s
              JOIN products p ON s.ProductID = p.ProductID
              ORDER BY p.ProductID";

$result_stock = mysqli_query($con, $sql_stock); // Change $conn to $con here

$productID = [];
$quantities = [];
$quantitiesSold = [];
$quantitiesRemaining = [];

if ($result_stock) {
    while ($row = mysqli_fetch_assoc($result_stock)) { // Change $conn to $con here
        $productID[] = $row['ProductID'];
        $quantities[] = $row['Quantity'];
        $quantitiesSold[] = $row['QuantitySold'];
        $quantitiesRemaining[] = $row['QuantityRemaining'];
    }
    mysqli_free_result($result_stock); // Free the result set
}

// Close the database connection
mysqli_close($con); // Change $conn to $con here
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales and Stock Report</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <!-- Navigation bar with "Go to Dashboard" button -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Sales Report</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="btn btn-warning" href="manager_dashboard.php">Go to Dashboard</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center">Sales Report</h1>

        <!-- Sales table -->
        <div class="mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($dates); $i++) { ?>
                        <tr>
                            <td><?php echo $dates[$i]; ?></td>
                            <td><?php echo '$' . number_format($amounts[$i], 2); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Stock chart -->
        <div class="mt-5">
            <h2 class="text-center">Stock Details</h2>
            <canvas id="stockChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Include Chart.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>

    <script>
        // JavaScript code to create the stock chart using Chart.js
        var ctx = document.getElementById('stockChart').getContext('2d');
        var stockChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($productID); ?>,
                datasets: [
                    {
                        label: 'Quantity',
                        data: <?php echo json_encode($quantities); ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)'
                    },
                    {
                        label: 'Quantity Sold',
                        data: <?php echo json_encode($quantitiesSold); ?>,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)'
                    },
                    {
                        label: 'Quantity Remaining',
                        data: <?php echo json_encode($quantitiesRemaining); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)'
                    }
                ]
            },
            options: {
                scales: {
                    xAxes: [{
                        stacked: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Product Name'
                        }
                    }],
                    yAxes: [{
                        stacked: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Quantity'
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                legend: {
                    display: true,
                    position: 'top'
                },
                title: {
                    display: false
                }
            }
        });
    </script>
</body>
</html>
