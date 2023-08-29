<?php
include 'connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $productID = $_GET["id"];
    
    // Prepare and execute the SQL query to delete the product
    $deleteQuery = "DELETE FROM products WHERE ProductID = ?";
    $stmt = $con->prepare($deleteQuery);
    $stmt->bind_param("s", $productID);
    
    if ($stmt->execute()) {
        // Successful delete
        header("Location: product.php"); // Redirect back to product.php
        exit();
    } else {
        // Error in delete
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

$con->close(); // Close the database connection
?>
