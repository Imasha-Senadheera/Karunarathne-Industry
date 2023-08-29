<?php
include 'connect.php';

if (isset($_GET['id'])) {
    $stockID = $_GET['id'];

    // Retrieve existing stock data based on $stockID
    $sql = "SELECT * FROM stock WHERE StockID = $stockID";
    $result = $con->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Display form pre-filled with existing data
        ?>

        <!DOCTYPE html>
        <html>
        <head>
            <title>Edit Stock</title>
        </head>
        <body>
            <h1>Edit Stock</h1>
            <form method="POST">
                <!-- Display form fields pre-filled with existing data -->
                <input type="hidden" name="stockID" value="<?php echo $row['StockID']; ?>">
                <!-- Add other form fields here -->
                <button type="submit">Save Changes</button>
            </form>
        </body>
        </html>

        <?php

        // When form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve and validate updated data
            // Example: $newProductID = $_POST['productID'];

            // Construct SQL UPDATE query
            // Example: $updateSql = "UPDATE stock SET ProductID = $newProductID WHERE StockID = $stockID";

            // Execute the query and handle errors
            // Example: if ($con->query($updateSql) === TRUE) { ... } else { ... }

            // Redirect back to index.php
            header("Location: index.php");
            exit();
        }
    } else {
        echo "Stock not found";
    }
} else {
    echo " Invalid request";
}
?>
