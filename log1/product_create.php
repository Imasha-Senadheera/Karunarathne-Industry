<?php
include 'connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    
    // Prepare and execute the SQL query to insert the new product data
    $insertQuery = "INSERT INTO products (Name, Description, Price, Quantity) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($insertQuery);
    $stmt->bind_param("ssdi", $name, $description, $price, $quantity);
    
    if ($stmt->execute()) {
        // Successful insertion
        header("Location: product.php"); // Redirect back to product.php
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
    <title>New Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Add New Product</h1>
        <form action="product_create.php" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" class="form-control" id="description" name="description" required>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Product</button>
            <a href="product.php" class="btn btn-danger">Cancel</a>
        </form>
    </div>
</body>
</html>
