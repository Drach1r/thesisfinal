<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected product ID
    $selectedProductId = $_POST['product_id'];

    // Fetch pack, measure_and_unit, and price from productlist for the selected product
    $query = "SELECT pack, CONCAT(measure, ' ', Unit) AS measure_and_unit, price FROM productlist WHERE ProductID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $selectedProductId);
    $stmt->execute();
    $stmt->bind_result($pack, $measureAndUnit, $price);

    // Fetch the result
    $stmt->fetch();

    // Close the statement
    $stmt->close();

    // Fetch available quantity from product_stock
    $query = "SELECT ProductID, SUM(stock_in) - SUM(stock_out) AS available_quantity FROM product_stock WHERE ProductID = ? GROUP BY ProductID";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $selectedProductId);
    $stmt->execute();
    $stmt->bind_result($productId, $availableQuantity);

    // Fetch the result
    $stmt->fetch();

    // Close the statement
    $stmt->close();

    // Create an associative array with the fetched data
    $response = array(
        'pack' => $pack,
        'measure_and_unit' => $measureAndUnit,
        'price' => $price,
        'available_quantity' => $availableQuantity
    );

    // Send the JSON-encoded response
    echo json_encode($response);
}
