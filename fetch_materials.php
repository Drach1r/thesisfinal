<?php
include 'db_connect.php';

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch materials from the BOM based on $product_id, including name, quantity, unit, and price
    $query = "SELECT rm.RawMaterialID, r.Name AS RawMaterialName, rm.QuantityRequired, rm.qty_unit, r.Price AS RawMaterialPrice
              FROM bom AS rm
              INNER JOIN rawmaterials AS r ON rm.RawMaterialID = r.RawMaterialID
              WHERE rm.ProductID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $materials = array();

    while ($row = $result->fetch_assoc()) {
        $materials[] = $row;
    }

    // Output the JSON response
    header('Content-Type: application/json');
    echo json_encode($materials);
} else {
    echo "Invalid request";
}
