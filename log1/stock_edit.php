<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stockID = $_POST["stockID"]; // Update this field to match your stock table
    $productID = $_POST["productID"]; // Update this field as well
    $grn = $_POST["grn"]; // Update this field as well
    $invoiceDate = $_POST["invoiceDate"]; // Update this field as well
    $purchaseOrderDate = $_POST["purchaseOrderDate"]; // Update this field as well
    $quantityReceived = $_POST["quantityReceived"]; // Update this field as well
    
    // Prepare and execute the SQL query to update the stock data
    $updateQuery = "UPDATE stock SET ProductID = ?, GRN = ?, InvoiceDate = ?, PurchaseOrderDate = ?, QuantityReceived = ? WHERE StockID = ?";
    $stmt = $con->prepare($updateQuery);
    $stmt->bind_param("isssii", $productID, $grn, $invoiceDate, $purchaseOrderDate, $quantityReceived, $stockID);
    
    if ($stmt->execute()) {
        // Successful update
        header("Location: stock.php"); // Redirect back to stock.php
        exit();
    } else {
        // Error in update
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

// Retrieve stock details for pre-filling the form
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $stockID = $_GET["id"];
    $selectQuery = "SELECT * FROM stock WHERE StockID = ?";
    $stmt = $con->prepare($selectQuery);
    $stmt->bind_param("i", $stockID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a stock with the given ID exists before accessing its details
    if ($result->num_rows > 0) {
        $stock = $result->fetch_assoc();
    } else {
        // Redirect or display an error message if the stock doesn't exist
        // For example:
        header("Location: stock.php");
        exit();
    }

    $stmt->close();
}

$con->close(); // Close the database connection
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Stock</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Edit Stock</h1>
        <form action="stock_edit.php" method="POST">
            <?php if (isset($stock)): ?>
            <input type="hidden" name="stockID" value="<?php echo $stock['StockID']; ?>">
            <div class="form-group">
                <label for="productID">Product ID:</label>
                <input type="text" class="form-control" id="productID" name="productID" value="<?php echo $stock['ProductID']; ?>" required>
            </div>
            <div class="form-group">
                <label for="grn">GRN:</label>
                <input type="text" class="form-control" id="grn" name="grn" value="<?php echo $stock['GRN']; ?>" required>
            </div>
            <div class="form-group">
                <label for="invoiceDate">Invoice Date:</label>
                <input type="date" class="form-control" id="invoiceDate" name="invoiceDate" value="<?php echo $stock['InvoiceDate']; ?>" required>
            </div>
            <div class="form-group">
                <label for="purchaseOrderDate">Purchase Order Date:</label>
                <input type="date" class="form-control" id="purchaseOrderDate" name="purchaseOrderDate" value="<?php echo $stock['PurchaseOrderDate']; ?>" required>
            </div>
            <div class="form-group">
                <label for="quantityReceived">Quantity Received:</label>
                <input type="number" class="form-control" id="quantityReceived" name="quantityReceived" value="<?php echo $stock['QuantityReceived']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Stock</button>
            <a href="stock.php" class="btn btn-danger">Cancel</a>
            <?php else: ?>
            <p>Stock not found.</p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
