<?php
include 'connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $stockID = $_GET["id"];
    
    // Prepare and execute the SQL query to delete the stock
    $deleteQuery = "DELETE FROM stock WHERE StockID = ?";
    $stmt = $con->prepare($deleteQuery);
    $stmt->bind_param("i", $stockID);
    
    if ($stmt->execute()) {
        // Successful delete
        header("Location: stock.php"); // Redirect back to stock.php
        exit();
    } else {
        // Error in delete
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

$con->close(); // Close the database connection
?>
