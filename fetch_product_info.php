<?php
include 'db_connect.php';

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch product information from the database based on $product_id
    $query = "SELECT Pack, Unit, Measure, batch_amount FROM productlist WHERE ProductID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // You may adjust this logic based on how you determine the available quantity
        $availableQuantity = $row['batch_amount'];

        // Create an associative array with the relevant information
        $response = [
            'pack' => $row['Pack'],
            'unit' => $row['Unit'],
            'measure' => (int)$row['Measure'], // Cast to int if needed
            'batch_amount' => $availableQuantity,
        ];

        // Output the JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        echo "Product not found";
    }

    $stmt->close();
} else {
    echo "Invalid request";
}
