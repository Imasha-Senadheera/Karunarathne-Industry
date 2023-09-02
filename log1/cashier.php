<?php
include 'connect.php';

session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'Manager' && $_SESSION['role'] != 'Cashier')) {
    header('Location: login.php'); // Redirect unauthorized users to the login page
    exit();
}

$searchUserID = isset($_GET['search_userid']) ? $_GET['search_userid'] : '';
$searchUsername = isset($_GET['search_username']) ? $_GET['search_username'] : '';
$searchFlowID = isset($_GET['search_flow_id']) ? $_GET['search_flow_id'] : '';

// Initialize variables
$sortColumn = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'UserID';
$sortOrder = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';

// Define the dashboard URL based on the user's role
$dashboardURL = ($_SESSION['role'] == 'Cashier') ? 'cashier_dashboard.php' : 'manager_dashboard.php';

// Build the SQL query with sorting and filtering
$sql = "SELECT cashiers.UserID, cashiers.Name, cashiers.FlowID
        FROM cashiers
        WHERE cashiers.UserID LIKE '%$searchUserID%' AND cashiers.Name LIKE '%$searchUsername%' AND cashiers.FlowID LIKE '%$searchFlowID%'
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
        <h1 class="mb-5">Cashier Details</h1>
        
        <!-- Sorting and filtering form -->
        <form class="mb-5" method="GET">
            
            <label for="search_userid">Search by User ID:</label>
            <input type="text" name="search_userid" value="<?php echo $searchUserID; ?>">
            
            <label for="search_username">Search by Username:</label>
            <input type="text" name="search_username" value="<?php echo $searchUsername; ?>">
            
            <label for="search_flow_id">Search by Flow ID:</label>
            <input type="text" name="search_flow_id" value="<?php echo $searchFlowID; ?>">
            
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="cashier.php" class="btn btn-secondary">All</a>
            <a href="<?php echo $dashboardURL; ?>" class="btn btn-warning">Go to Dashboard</a>
        </form>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Flow ID</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row["UserID"]."</td>";
                        echo "<td>".$row["Name"]."</td>";
                        echo "<td>".$row["FlowID"]."</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>
</body>
</html>
