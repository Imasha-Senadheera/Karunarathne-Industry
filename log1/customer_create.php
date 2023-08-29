<?php
            include 'connect.php'; // Include the database connection
            
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $customerID = $_POST["customerID"];
                $name = $_POST["name"];
                $email = $_POST["email"];
                $phone = $_POST["phone"];
                
                
                // Prepare and execute the SQL query to insert the new customer data
                $insertQuery = "INSERT INTO customers (CustomerID, Name, Email, Phone) VALUES (?, ?, ?, ?)";
                $stmt = $con->prepare($insertQuery);
                $stmt->bind_param("ssss", $customerID, $name, $email, $phone);
                
                if ($stmt->execute()) {
                    // Successful insertion
                    header("Location: customers.php"); // Redirect back to product.php
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
    <title>New Customer</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Add New Customer</h1>
        <form action="customer_create.php" method="POST">
            
            <div class="form-group">
                <label for="customerID">CustomerID:</label>
                <input type="text" class="form-control" id="customerID" name="customerID" required>
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Customer</button>
            <a href="customers.php" class="btn btn-danger">Cancel</a>
        </form>
    </div>
</body>
</html>
