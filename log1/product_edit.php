<?php
include 'connect.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productID = $_POST["productID"];
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    
    // Prepare and execute the SQL query to update the product data
    $updateQuery = "UPDATE products SET Name = ?, Description = ?, Price = ?, Quantity = ? WHERE ProductID = ?";
    $stmt = $con->prepare($updateQuery);
    $stmt->bind_param("ssdii", $name, $description, $price, $quantity, $productID);
    
    if ($stmt->execute()) {
        // Successful update
        header("Location: product.php"); // Redirect back to product.php
        exit();
    } else {
        // Error in update
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

// Retrieve product details for pre-filling the form
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $productID = $_GET["id"];
    $selectQuery = "SELECT * FROM products WHERE ProductID = ?";
    $stmt = $con->prepare($selectQuery);
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a product with the given ID exists before accessing its details
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        // Redirect or display an error message if the product doesn't exist
        // For example:
        header("Location: product.php");
        exit();
    }

    $stmt->close();
}

$con->close(); // Close the database connection
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Edit Product</h1>
        <form action="product_edit.php" method="POST">
            <?php if (isset($product)): ?>
            <input type="hidden" name="productID" value="<?php echo $product['ProductID']; ?>">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $product['Name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" class="form-control" id="description" name="description" value="<?php echo $product['Description']; ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?php echo $product['Price']; ?>" required>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $product['Quantity']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="product.php" class="btn btn-danger">Cancel</a>
            <?php else: ?>
            <p>Product not found.</p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
