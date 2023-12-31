<?php
include 'connect.php';

// Check if the user is logged in and has the necessary role
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'Manager' && $_SESSION['role'] != 'Cashier')) {
    header('Location: login.php'); // Redirect unauthorized users to the login page
    exit();
}

$searchGRN = isset($_GET['search_grn']) ? $_GET['search_grn'] : '';
$searchInvoiceDate = isset($_GET['search_invoice_date']) ? $_GET['search_invoice_date'] : '';
$searchStockRenewedDate = isset($_GET['search_stock_renewed_date']) ? $_GET['search_stock_renewed_date'] : '';

// Build the SQL query with filters
$sql = "SELECT * FROM stock WHERE GRN LIKE '%$searchGRN%' AND Invoice LIKE '%$searchInvoiceDate%' AND StockRenewedDate LIKE '%$searchStockRenewedDate'";
$result = $con->query($sql);

// Check for database query errors
if ($result === false) {
    die("Database query error: " . $con->error);
}

// Define the dashboard URL based on the user's role
$dashboardURL = ($_SESSION['role'] == 'Cashier') ? 'cashier_dashboard.php' : 'manager_dashboard.php';
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
    <h1 class="mb-5">Stock Details</h1>
    <?php if ($_SESSION['role'] == 'Manager' || $_SESSION['role'] == 'Cashier'): ?>
        <a href="stock_create.php" class="btn btn-success mb-3">Add New</a>
        <a href="<?php echo $dashboardURL; ?>" class="btn btn-warning mb-3 ml-2">Go to Dashboard</a>
    <?php endif; ?>
   
    <!-- Add search and filter form -->
    <form class="mb-5" method="GET">
        <div class="row">
            <div class="col">
                <input type="text" class="form-control" placeholder="Search by GRN" name="search_grn">
            </div>
            <div class="col">
                <input type="text" class="form-control" placeholder="Search by Invoice" name="search_invoice_date">
            </div>
            <div class="col">
                <input type="text" class="form-control" placeholder="Search by Stock Renewed Date" name="search_stock_renewed_date">
            </div>
            <div class="col">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="stock.php" class="btn btn-secondary ml-2">All</a>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>StockID</th>
                <th>ProductID</th>
                <th>GRN</th>
                <th>Invoice</th>
                <th>Stock Renewed Date</th>
                <th>Qty</th>
                <th>Qty Sold</th>
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
                echo "<td>".$row["Invoice"]."</td>";
                echo "<td>".$row["StockRenewedDate"]."</td>";
                echo "<td>".$row["Quantity"]."</td>";
                echo "<td>".$row["QuantitySold"]."</td>";
                echo "<td>".$row["QuantityRemaining"]."</td>";
                echo "<td>";
                
                // Cashiers can only edit
                if ($_SESSION['role'] == 'Cashier') {
                    echo "<a href='stock_edit.php?id=".$row["StockID"]."' class='btn btn-primary btn-sm'>Edit</a>";
                }
                
                // Managers can edit and delete
                if ($_SESSION['role'] == 'Manager') {
                    echo "<a href='stock_edit.php?id=".$row["StockID"]."' class='btn btn-primary btn-sm'>Edit</a>";
                    echo "<a href='stock_delete.php?id=".$row["StockID"]."' class='btn btn-danger btn-sm'>Delete</a>";
                }
                
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='9'>No records found</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>
