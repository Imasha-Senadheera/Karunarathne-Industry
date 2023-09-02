<?php
include 'connect.php';

session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'Manager' && $_SESSION['role'] != 'Cashier')) {
    header('Location: login.php'); // Redirect unauthorized users to the login page
    exit();
}

$searchProductName = isset($_GET['search_product_name']) ? $_GET['search_product_name'] : '';

// Initialize variables
$sortColumn = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'ProductID';
$sortOrder = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';

// Define the dashboard URL based on the user's role
$dashboardURL = ($_SESSION['role'] == 'Cashier') ? 'cashier_dashboard.php' : 'manager_dashboard.php';

// Build the SQL query with sorting and filtering
$sql = "SELECT products.ProductID, products.Name, products.Description, products.Price, products.Quantity
        FROM products
        WHERE products.Name LIKE '%$searchProductName%'
        ORDER BY $sortColumn $sortOrder";
$result = $con->query($sql);

if ($result === false) {
    die("Error in SQL query: " . $con->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Product Details</h1>
        <div class="mb-3">
            <?php if ($_SESSION['role'] == 'Manager' || $_SESSION['role'] == 'Cashier'): ?>
                <a href="product_create.php" class="btn btn-success mr-2">Add New</a>
                <a href="<?php echo $dashboardURL; ?>" class="btn btn-warning">Go to Dashboard</a>
            <?php endif; ?>
        </div>
        <form class="mb-5" method="GET">
            <label for="search_product_name">Search by Product Name:</label>
            <input type="text" name="search_product_name" value="<?php echo $searchProductName; ?>">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="product.php" class="btn btn-secondary">All</a>
        </form>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row["ProductID"]."</td>";
                        echo "<td>".$row["Name"]."</td>";
                        echo "<td>".$row["Description"]."</td>";
                        echo "<td>".$row["Price"]."</td>";
                        echo "<td>".$row["Quantity"]."</td>";
                        echo "<td>";
                        
                        // Cashiers can only edit
                        if ($_SESSION['role'] == 'Cashier') {
                            echo "<a href='product_edit.php?id=".$row["ProductID"]."' class='btn btn-primary btn-sm'>Edit</a>";
                        }
                        
                        // Managers can edit and delete
                        if ($_SESSION['role'] == 'Manager') {
                            echo "<a href='product_edit.php?id=".$row["ProductID"]."' class='btn btn-primary btn-sm'>Edit</a>";
                            echo "<a href='product_delete.php?id=".$row["ProductID"]."' class='btn btn-danger btn-sm'>Delete</a>";
                        }
                        
                        echo "</td>";
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
