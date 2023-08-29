<?php
include 'connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $customerID = $_GET["id"];
    
    // Prepare and execute the SQL query to delete the customer
    $deleteQuery = "DELETE FROM customers WHERE CustomerID = ?";
    $stmt = $con->prepare($deleteQuery);
    $stmt->bind_param("s", $customerID);
    
    if ($stmt->execute()) {
        // Successful delete
        header("Location: customers.php"); // Redirect back to customers.php
        exit();
    } else {
        // Error in delete
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

$con->close(); // Close the database connection
?>
