<?php
include "db_connect.php"; // Assuming this file contains $host, $dbname, $username, and $password

// Explicitly initialize $dbname
$dbname = "buffalo_db"; // Replace "your_database_name" with your actual database name

try {
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch total sales data
    $stmt = $conn->prepare("SELECT SaleDate, TotalAmount FROM sales");

    $stmt->execute();

    // Fetch the data
    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = [
            'y' => $row['SaleDate'],
            'a' => $row['TotalAmount']
        ];
    }

    // Close the database connection
    $conn = null;

    // Return the data as JSON
    echo json_encode(['success' => true, 'data' => $data]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
