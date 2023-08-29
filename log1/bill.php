<?php
include 'connect.php';

// Process bill submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cashierID = $_POST["cashierID"];
    $productID = $_POST["productID"];
    $quantity = $_POST["quantity"];

    // Fetch product details for price and discount
    $productQuery = "SELECT Price, DiscountPercentage FROM products LEFT JOIN discounts ON products.ProductID = discounts.ProductID WHERE products.ProductID = ?";
    $stmt = $con->prepare($productQuery);
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    $price = $product["Price"];
    $discountPercentage = $product["DiscountPercentage"];

    // Calculate total amount and apply discount
    $totalAmount = $price * $quantity;
    if ($discountPercentage > 0) {
        $discountAmount = ($totalAmount * $discountPercentage) / 100;
        $totalAmount -= $discountAmount;
    }

    // Insert sale record into the Sales table
    $insertQuery = "INSERT INTO sales (CashierID, ProductID, SaleDate, Quantity, TotalAmount) VALUES (?, ?, NOW(), ?, ?)";
    $stmt = $con->prepare($insertQuery);
    $stmt->bind_param("iiid", $cashierID, $productID, $quantity, $totalAmount);

    if ($stmt->execute()) {
        // Update stock quantity
        $updateStockQuery = "UPDATE stock SET QuantitySold = QuantitySold + ?, QuantityRemaining = QuantityRemaining - ? WHERE ProductID = ?";
        $stmt = $con->prepare($updateStockQuery);
        $stmt->bind_param("iii", $quantity, $quantity, $productID);
        $stmt->execute();

        // Successful sale and update
        header("Location: sales.php"); // Redirect back to sales.php
        exit();
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
    <title>Bill</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Billing Section</h1>
        <form action="bill.php" method="POST">
            <div class="form-group">
                <label for="cashierID">Cashier:</label>
                <select class="form-control" id="cashierID" name="cashierID" required>
                    <?php
                    // Fetch cashier details for dropdown
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
                <label for="quantity">Quantity:</label>
                <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Generate Bill</button>
        </form>
    </div>
</body>
</html>
