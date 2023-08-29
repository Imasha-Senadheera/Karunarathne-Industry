<?php
include 'connect.php';

// Handle discount submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productID = $_POST["productID"];
    $discountPercentage = $_POST["discountPercentage"];

    // Prepare and execute the SQL query to insert the new discount data
    $insertQuery = "INSERT INTO discounts (ProductID, DiscountPercentage) VALUES (?, ?)";
    $stmt = $con->prepare($insertQuery);
    $stmt->bind_param("id", $productID, $discountPercentage);

    if ($stmt->execute()) {
        // Successful insertion
        header("Location: discount.php"); // Redirect back to discount.php
        exit();
    } else {
        // Error in insertion
        echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
    }

    $stmt->close();
}

// Retrieve existing discounts
$sql = "SELECT * FROM discounts";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Discounts</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Manage Discounts</h1>
        <!-- Add New Discount Form -->
        <form action="discount.php" method="POST">
            <div class="form-group">
                <label for="productID">Product:</label>
                <select class="form-control" id="productID" name="productID" required>
                    <?php
                    // Fetch product details for dropdown
                    $productQuery = "SELECT ProductID, Name FROM products";
                    $productResult = $con->query($productQuery);

                    if ($productResult->num_rows > 0) {
                        while ($product = $productResult->fetch_assoc()) {
                            echo "<option value='" . $product["ProductID"] . "'>" . $product["Name"] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="discountPercentage">Discount Percentage:</label>
                <input type="number" class="form-control" id="discountPercentage" name="discountPercentage" min="0" max="100" step="0.01" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Discount</button>
        </form>

        <!-- Existing Discounts Table -->
        <h2 class="mt-4">Existing Discounts</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>DiscountID</th>
                    <th>ProductID</th>
                    <th>Product Name</th>
                    <th>Discount Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["DiscountID"] . "</td>";
                        echo "<td>" . $row["ProductID"] . "</td>";
                        echo "<td>" . getProductDisplayName($row["ProductID"]) . "</td>";
                        echo "<td>" . $row["DiscountPercentage"] . "%</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No discounts found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php
// Function to get product display name by ProductID
function getProductDisplayName($productID)
{
    global $con;
    $productQuery = "SELECT Name FROM products WHERE ProductID = ?";
    $stmt = $con->prepare($productQuery);
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    return $product["Name"];
}
?>
