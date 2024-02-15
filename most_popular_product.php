<?php
include "db_connect.php";
$response = array();

$queryProducts = "SELECT p.Name, SUM(si.quantity) as totalQuantity
                  FROM salesitems si
                  JOIN productlist p ON si.ProductID = p.ProductID
                  GROUP BY p.Name
                  ORDER BY totalQuantity DESC";

$resultProducts = mysqli_query($conn, $queryProducts);

if ($resultProducts) {
    $products = array();
    while ($rowProduct = mysqli_fetch_assoc($resultProducts)) {
        $product = array(
            'productName' => $rowProduct['Name'],
            'quantity' => $rowProduct['totalQuantity']
        );
        $products[] = $product;
    }

    $response['success'] = true;
    $response['products'] = $products;
} else {
    // Handle the error if the query fails
    $response['success'] = false;
    $response['message'] = "Error fetching product data: " . mysqli_error($conn); // Include the actual error message
}

header('Content-Type: application/json');
echo json_encode($response);
