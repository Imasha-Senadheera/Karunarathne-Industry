<?php
include 'connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cashierID = $_POST["cashierID"];
    $productID = $_POST["productID"];
    $quantity = $_POST["quantity"];

    // Update stock quantity
    $updateStockQuery = "UPDATE stock SET QuantitySold = QuantitySold + ?, QuantityRemaining = QuantityRemaining - ? WHERE ProductID = ?";
    $updateStockStmt = $con->prepare($updateStockQuery);
    $updateStockStmt->bind_param("iii", $quantity, $quantity, $productID);

    if ($updateStockStmt->execute()) {
        echo "Success"; // Send a success message back to the AJAX request
    } else {
        echo "Error updating database: " . $updateStockStmt->error; // Send an error message back to the AJAX request
    }

    $updateStockStmt->close();
}
?>
