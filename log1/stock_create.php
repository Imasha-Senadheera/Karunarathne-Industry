<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productID = $_POST["productID"]; // Make sure to update this field to match your stock table
    $grn = $_POST["grn"]; // Update this field as well
    $invoiceDate = $_POST["invoiceDate"]; // Update this field as well
    $purchaseOrderDate = $_POST["purchaseOrderDate"]; // Update this field as well
    $quantityReceived = $_POST["quantityReceived"]; // Update this field as well
    
    // Prepare and execute the SQL query to insert the new stock data
    $insertQuery = "INSERT INTO stock (ProductID, GRN, InvoiceDate, PurchaseOrderDate, QuantityReceived) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($insertQuery);
    $stmt->bind_param("isssi", $productID, $grn, $invoiceDate, $purchaseOrderDate, $quantityReceived);
    
    if ($stmt->execute()) {
        // Successful insertion
        header("Location: stock.php"); // Redirect back to stock.php
        exit();
    } else {
        // Error in insertion
        echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
    }
    
    $stmt->close();
}

$con->close(); // Close the database connection
?>

<!DOCTYPE html>
<html>
<head>
    <title>New Stock</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Add New Stock</h1>
        <form action="stock_create.php" method="POST">
            <div class="form-group">
                <label for="productID">Product ID:</label>
                <input type="text" class="form-control" id="productID" name="productID" required>
            </div>
            <div class="form-group">
                <label for="grn">GRN:</label>
                <input type="text" class="form-control" id="grn" name="grn" required>
            </div>
            <div class="form-group">
                <label for="invoiceDate">Invoice Date:</label>
                <input type="date" class="form-control" id="invoiceDate" name="invoiceDate" required>
            </div>
            <div class="form-group">
                <label for="purchaseOrderDate">Purchase Order Date:</label>
                <input type="date" class="form-control" id="purchaseOrderDate" name="purchaseOrderDate" required>
            </div>
            <div class="form-group">
                <label for="quantityReceived">Quantity Received:</label>
                <input type="number" class="form-control" id="quantityReceived" name="quantityReceived" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Stock</button>
            <a href="stock.php" class="btn btn-danger">Cancel</a>
        </form>
    </div>
</body>
</html>
