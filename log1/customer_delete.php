<?php
include 'connect.php';

if (isset($_GET['id'])) {
    $stockID = $_GET['id'];

    // Construct SQL DELETE query
    $deleteSql = "DELETE FROM stock WHERE StockID = $stockID";

    // Execute the query and handle errors
    if ($con->query($deleteSql) === TRUE) {
        // Redirect back to index.php
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting stock: " . $con->error;
    }
} else {
    echo "Invalid request";
}
?>
