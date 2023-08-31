<!DOCTYPE html>
<html>
<head>
    <title>Manager Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f8f9fa; /* Set a background color if needed */
            font-family: 'Arial', sans-serif; /* Set a custom font style */
        }

        .page-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 80vh;
        }

        .company-name {
            font-size: 2.5rem;
            font-weight: bold;
            color: #007bff;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .dashboard-title {
            font-size: 1.8rem;
            font-weight: bold;
            color: #333;
            margin-top: 40px;
        }

        .dashboard-cards {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50vh;
        }

        .dashboard-card {
            text-align: center;
            padding: 20px;
            margin: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .dashboard-icon {
            font-size: 3rem;
            margin-bottom: 30px;
        }

        .dashboard-button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            font-size: 1rem;
            cursor: pointer;
        }

        .logout-button {
            margin-top: 20px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
            font-size: 1rem;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="company-name">Karunarathne Industry</div>
        <div class="dashboard-title">Manager Dashboard</div>
        <div class="dashboard-cards">
            <div class="dashboard-card">
                <i class="fas fa-users dashboard-icon"></i>
                <h3>Customers</h3>
                <a href="customers.php" class="dashboard-button">View Customers</a>
            </div>
            <div class="dashboard-card">
                <i class="fas fa-box-open dashboard-icon"></i>
                <h3>Products</h3>
                <a href="product.php" class="dashboard-button">View Products</a>
            </div>
            <div class="dashboard-card">
                <i class="fas fa-boxes dashboard-icon"></i>
                <h3>Stock</h3>
                <a href="stock.php" class="dashboard-button">View Stock</a>
            </div>
            <div class="dashboard-card">
                <i class="fas fa-cash-register dashboard-icon"></i>
                <h3>Sales</h3>
                <a href="sales.php" class="dashboard-button">View Sales</a>
            </div>
            <div class="dashboard-card">
                <i class="fas fa-cash-register dashboard-icon"></i>
                <h3>Cashiers</h3>
                <a href="cashier.php" class="dashboard-button">View Cashiers</a>
            </div>
            <div class="dashboard-card">
                <i class="fas fa-chart-bar dashboard-icon"></i>
                <h3>Reports</h3>
                <a href="reports.php" class="dashboard-button">Generate Reports</a>
            </div>
            <div class="dashboard-card">
                <i class="fas fa-star dashboard-icon"></i>
                <h3>Loyal Customers</h3>
                <a href="loyal_customers.php" class="dashboard-button">View Loyal Customers</a>
            </div>
        </div>
        <a href="login.php" class="logout-button">Logout</a>
    </div>
</body>
</html>
