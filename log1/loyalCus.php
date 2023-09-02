<?php
include 'connect.php'; // Include your database connection script

// Query to fetch loyal customers
$sql = "SELECT * FROM customers WHERE LoyaltyStatus = 'Yes'";
$result = mysqli_query($con, $sql);

if (!$result) {
    die("Error: " . mysqli_error($con)); // Add error handling
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Loyal Customers</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
<body>

    <div class="container mt-5">
        <h1 class="text-center">Loyal Customers</h1></br></br>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Loyalty Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>' . $row['CustomerID'] . '</td>';
                        echo '<td>' . $row['Name'] . '</td>';
                        echo '<td>' . $row['Email'] . '</td>';
                        echo '<td>' . $row['Phone'] . '</td>';
                        echo '<td>' . $row['LoyaltyStatus'] . '</td>';
                        echo '</tr>';
                    }
                    mysqli_free_result($result); // Free the result set
                }
                ?>
            </tbody>
        </table>
        <a href="manager_dashboard.php" class="btn btn-warning">Go to Dashboard</a>
    </div>

    
</body>
</html>
