<?php
include 'connect.php';

// Process bill submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cashierID = $_POST["cashierID"];
    $productID = $_POST["productID"];
    $quantity = $_POST["quantity"];

    // Fetch product details for price and discount
    $productQuery = "SELECT Price, DiscountPercentage FROM products LEFT JOIN discounts ON products.ProductID = discounts.ProductID WHERE products.ProductID = ?";
    $stmt = $con->prepare($productQuery);
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    $price = $product["Price"];
    $discountPercentage = $product["DiscountPercentage"];

    // Calculate total amount and apply discount
    $totalAmount = $price * $quantity;
    if ($discountPercentage > 0) {
        $discountAmount = ($totalAmount * $discountPercentage) / 100;
        $totalAmount -= $discountAmount;
    }

    // Insert sale record into the Sales table
    $insertQuery = "INSERT INTO sales (CashierID, ProductID, SaleDate, Quantity, TotalAmount) VALUES (?, ?, NOW(), ?, ?)";
    $stmt = $con->prepare($insertQuery);
    $stmt->bind_param("iiid", $cashierID, $productID, $quantity, $totalAmount);

    if ($stmt->execute()) {
        // Update stock quantity
        $updateStockQuery = "UPDATE stock SET QuantitySold = QuantitySold + ?, QuantityRemaining = QuantityRemaining - ? WHERE ProductID = ?";
        $stmt = $con->prepare($updateStockQuery);
        $stmt->bind_param("iii", $quantity, $quantity, $productID);
        $stmt->execute();

        // Successful sale and update
        header("Location: sales.php"); // Redirect back to sales.php
        exit();
    } else {
        // Error in insertion
        echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bill</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Billing Section</h1>
        <form action="bill.php" method="POST">
            <div class="form-group">
                <label for="cashierID">Cashier:</label>
                <select class="form-control" id="cashierID" name="cashierID" required>
                    <?php
                    // Fetch cashier details for dropdown
                    $cashierQuery = "SELECT CashierID, Name FROM cashiers";
                    $cashierResult = $con->query($cashierQuery);

                    if ($cashierResult->num_rows > 0) {
                        while ($cashier = $cashierResult->fetch_assoc()) {
                            echo "<option value='" . $cashier["CashierID"] . "'>" . $cashier["Name"] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="productID">Product:</label>
                <select class="form-control" id="productID" name="productID" required>
                    <?php
                    // Fetch product details for dropdown
                    $productQuery = "SELECT ProductID, Name FROM products";
                    $productResult = $con->query($productQuery);

                    if ($productResult->num_rows > 0) {
                        while ($product = $productResult->fetch_assoc()) {
                            echo "<option value='" . $product["ProductID"] . "'>" . $product["Name"] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
            </div>
            <button type="button" class="btn btn-primary" id="generateBillButton">Generate Bill</button>
        </form>
        
        <div class="modal fade" id="billModal" tabindex="-1" role="dialog" aria-labelledby="billModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="billModalLabel">Bill Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="billModalBody">
                        <!-- Bill details will be displayed here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="downloadButton">Download PDF</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(function() {
            $("#generateBillButton").click(function() {
                var cashierName = $("#cashierID option:selected").text();
                var productName = $("#productID option:selected").text();
                var quantity = $("#quantity").val();
                var price = parseFloat($("#productID option:selected").data("price"));
                var discountPercentage = parseFloat($("#productID option:selected").data("discount"));
                
                var totalAmount = price * quantity;
                if (discountPercentage > 0) {
                    var discountAmount = (totalAmount * discountPercentage) / 100;
                    totalAmount -= discountAmount;
                }
                
                var billDetailsHtml = "<p>Cashier: " + cashierName + "</p>" +
                                      "<p>Product: " + productName + "</p>" +
                                      "<p>Quantity: " + quantity + "</p>" +
                                      "<p>Total Amount: " + totalAmount.toFixed(2) + "</p>";
                $("#billModalBody").html(billDetailsHtml);
                
                $("#billModal").modal("show");
            });
            
            $("#downloadButton").click(function() {
                var doc = new jsPDF();
                doc.text("Bill Details", 10, 10);
                var cashierName = $("#cashierID option:selected").text();
                var productName = $("#productID option:selected").text();
                var quantity = $("#quantity").val();
                var price = parseFloat($("#productID option:selected").data("price"));
                var discountPercentage = parseFloat($("#productID option:selected").data("discount"));
                
                var totalAmount = price * quantity;
                if (discountPercentage > 0) {
                    var discountAmount = (totalAmount * discountPercentage) / 100;
                    totalAmount -= discountAmount;
                }
                
                doc.text("Cashier: " + cashierName, 10, 20);
                doc.text("Product: " + productName, 10, 30);
                doc.text("Quantity: " + quantity, 10, 40);
                doc.text("Total Amount: " + totalAmount.toFixed(2), 10, 50);
                doc.save("bill_details.pdf");
            });
        });
    </script>
</body>
</html>
</body>
</html>
