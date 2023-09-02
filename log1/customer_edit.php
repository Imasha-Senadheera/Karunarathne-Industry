<?php
include 'connect.php'; // Include the database connection

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerID = $_POST["customerID"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $loyaltyStatus = $_POST["loyaltyStatus"]; // Get the updated LoyaltyStatus
    
    // Prepare and execute the SQL query to update the customer data
    $updateQuery = "UPDATE customers SET Name = ?, Email = ?, Phone = ?, LoyaltyStatus = ? WHERE CustomerID = ?";
    $stmt = $con->prepare($updateQuery);
    $stmt->bind_param("sssss", $name, $email, $phone, $loyaltyStatus, $customerID);
    
    if ($stmt->execute()) {
        // Successful update
        header("Location: customers.php"); // Redirect back to customers.php
        exit();
    } else {
        // Error in update
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

// Retrieve customer details for pre-filling the form
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $customerID = $_GET["id"];
    $selectQuery = "SELECT * FROM customers WHERE CustomerID = ?";
    $stmt = $con->prepare($selectQuery);
    $stmt->bind_param("s", $customerID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a customer with the given ID exists before accessing its details
    if ($result->num_rows > 0) {
        $customer = $result->fetch_assoc();
    } else {
        // Redirect or display an error message if the customer doesn't exist
        // For example:
        header("Location: customers.php");
        exit();
    }

    $stmt->close();
}

$con->close(); // Close the database connection
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Customer</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Edit Customer</h1>
        <form action="customer_edit.php" method="POST">
            <?php if (isset($customer)): ?>
            <input type="hidden" name="customerID" value="<?php echo $customer['CustomerID']; ?>">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $customer['Name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $customer['Email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $customer['Phone']; ?>" required>
            </div>
            <div class="form-group">
                <label for="loyaltyStatus">Loyalty Status:</label>
                <input type="text" class="form-control" id="loyaltyStatus" name="loyaltyStatus" value="<?php echo $customer['LoyaltyStatus']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Customer</button>
            <a href="customers.php" class="btn btn-danger">Cancel</a>
            <?php else: ?>
            <p>Customer not found.</p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
