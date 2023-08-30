<?php
session_start();
if (!isset($_SESSION['cashier_id'])) {
    header('Location: login.php');
    exit();
}

include 'connect.php';

$cashierID = $_SESSION['cashier_id'];

$sql = "SELECT cashiers.CashierID, cashiers.Name, cashiers.Username, cashiers.Password, cashiers.FlowID, COUNT(sales.SaleID) AS TotalSales
        FROM cashiers
        LEFT JOIN sales ON cashiers.CashierID = sales.CashierID
        WHERE cashiers.CashierID = $cashierID
        GROUP BY cashiers.CashierID";
$result = $con->query($sql);

// ... Display the rest of your HTML and table as before
