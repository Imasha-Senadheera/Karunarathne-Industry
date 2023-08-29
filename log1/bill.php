<?php
include 'connect.php'; // Include the database connection

// Initialize variables
$successMessage = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerID = $_POST["customerID"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $productID = $_POST["productID"];
    $quantity = $_POST["quantity"];
    $discountPercentage = $_POST["discountPercentage"];
    $cashierID = $_POST["cashier"];

    // Calculate total bill amount after discount
    $productQuery = "SELECT Price FROM products WHERE ProductID = ?";
    $stmt = $con->prepare($productQuery);
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $stmt->bind_result($price);
    $stmt->fetch();
    $stmt->close();

    $totalAmount = $price * $quantity;
    $discountAmount = $totalAmount * ($discountPercentage / 100);
    $finalAmount = $totalAmount - $discountAmount;

    // Insert data into the database and perform other necessary operations
    $insertQuery = "INSERT INTO orders (CustomerID, CashierID, ProductID, Quantity, TotalAmount, DiscountPercentage, FinalAmount) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($insertQuery);
    $stmt->bind_param("iiiiidd", $customerID, $cashierID, $productID, $quantity, $totalAmount, $discountPercentage, $finalAmount);

    if ($stmt->execute()) {
        $successMessage = "Data successfully updated!";
    } else {
        // Error in insertion
        echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Bill Form</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <!-- Display success message if applicable -->
        <?php if (!empty($successMessage)) : ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <h1 class="mb-4">Bill Form</h1>
        <form action="bill.php" method="post">
            <!-- Customer Details Section -->
        <div class="mb-4">
            <h2>Customer Details Section</h2>
            <form action="bill.php" method="post">
                <div class="form-group">
                    <label for="customerID">CustomerID:</label>
                    <input type="text" class="form-control" id="customerID" name="customerID" required>
                </div>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                </div>
            </form>
        </div>
            
            
             
            <!-- Sales Details Section -->
            <div class="mb-4">
                <h2>Select Products</h2>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="product">Product:</label>
                        <select class="form-control" id="product" name="product" required>
                            <?php
                            include 'connect.php'; // Include the database connection

                            // Fetch product names from the database and populate dropdown
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
                    <div class="form-group col-md-6">
                        <label for="quantity">Quantity:</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                    </div>
                </div>
            </div>

            <!-- Discount Section -->
            
            <div class="mb-4">
                <h2>Discount Details</h2>
                <div class="form-group">
                    <label for="productID">Product:</label>
                    <select class="form-control" id="productID" name="productID" required>
                        <?php
                        // Fetch product names from the database and populate dropdown
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
            </div>

                      <!-- Select Cashier Section -->
                      <div class="mb-4">
                <h2>Select Cashier</h2>
                <div class="form-group">
                    <label for="cashier">Cashier:</label>
                    <select class="form-control" id="cashier" name="cashier" required>
                        <?php
                        include 'connect.php'; // Include the database connection

                        // Fetch cashier names from the database and populate dropdown
                        $cashierQuery = "SELECT CashierID, Name FROM cashiers";
                        $cashierResult = $con->query($cashierQuery);

                        if ($cashierResult->num_rows > 0) {
                            while ($cashier = $cashierResult->fetch_assoc()) {
                                echo "<option value='" . $cashier["CashierID"] . "'>" . $cashier["Name"] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

      <!-- Update and Generate Buttons -->
      <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-success" onclick="showBillDetailsPopup()">Generate</button>

            <!-- Cancel and Download Buttons -->
            <a href="bill.php" class="btn btn-secondary">Cancel</a>
            <a href="download_bill.php" class="btn btn-info">Download PDF</a>
        </form>
    </div>
    </div>

    <!-- Include Bootstrap JS (optional, for some interactive features) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function showBillDetailsPopup() {
            // Logic to show a popup with bill details
            // You can use Bootstrap modal components
        }
    </script>
</body>
</html>
