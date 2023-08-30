<?php
include 'connect.php';

$searchCashierID = isset($_GET['search_cashier_id']) ? $_GET['search_cashier_id'] : '';
$searchCashierName = isset($_GET['search_cashier_name']) ? $_GET['search_cashier_name'] : '';
$searchFlowID = isset($_GET['search_flow_id']) ? $_GET['search_flow_id'] : '';

// Initialize variables
$sortColumn = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'CashierID';
$sortOrder = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';

// Build the SQL query with sorting and filtering
$sql = "SELECT cashiers.CashierID, cashiers.Name, cashiers.FlowID, COUNT(sales.SaleID) AS TotalSales
        FROM cashiers
        LEFT JOIN sales ON cashiers.CashierID = sales.CashierID
        WHERE cashiers.CashierID LIKE '%$searchCashierID%' AND cashiers.Name LIKE '%$searchCashierName%' AND cashiers.FlowID LIKE '%$searchFlowID%'
        GROUP BY cashiers.CashierID
        ORDER BY $sortColumn $sortOrder";
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
    <style>
        .mb-4 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-5">Cashier Details and Sales Tracking</h1>
        
        <!-- Sorting and filtering form -->
        <form class="mb-5" method="GET">
            
            <label for="search_cashier_id">Search by Cashier ID:</label>
            <input type="text" name="search_cashier_id" value="<?php echo $searchCashierID; ?>">
            
            <label for="search_cashier_name">Search by Cashier Name:</label>
            <input type="text" name="search_cashier_name" value="<?php echo $searchCashierName; ?>">
            
            <label for="search_flow_id">Search by Flow ID:</label>
            <input type="text" name="search_flow_id" value="<?php echo $searchFlowID; ?>">
            
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="cashier.php" class="btn btn-secondary">All</a>
        </form>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Cashier ID</th>
                    <th>Cashier Name</th>
                    <th>Flow ID</th>
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
                        echo "<td>".$row["FlowID"]."</td>";
                        echo "<td>".$row["TotalSales"]."</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>
</body>
</html>
