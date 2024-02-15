<?php
// Include your database connection file or write the connection code here
include 'db_connect.php';

// Fetch production quantities over time
$productionQuantitiesQuery = $conn->query("SELECT date, flavored_milk_quantity, tone_milk_quantity FROM production_data");
$productionQuantities = $productionQuantitiesQuery->fetch_all(MYSQLI_ASSOC);

// Fetch expenses over time
$expensesQuery = $conn->query("SELECT date, total_expenses FROM expenses_data");
$expenses = $expensesQuery->fetch_all(MYSQLI_ASSOC);

// Close the database connection
$conn->close();

// Prepare data for response
$response = [
    'dates' => array_column($productionQuantities, 'date'),
    'productionQuantities' => [
        'x1' => array_column($productionQuantities, 'flavored_milk_quantity'),
        'x2' => array_column($productionQuantities, 'tone_milk_quantity'),
        // Add keys for other products
    ],
    'expenses' => [
        'dates' => array_column($expenses, 'date'),
        'amount' => array_column($expenses, 'total_expenses'),
    ],
];

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
