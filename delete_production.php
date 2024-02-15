<?php
include 'db_connect.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $orderId = $_POST['order_id'];

    // Delete from manufacturing_orders table
    $deleteOrdersQuery = "DELETE FROM manufacturing_orders WHERE order_id = ?";
    $deleteOrdersStmt = $conn->prepare($deleteOrdersQuery);
    $deleteOrdersStmt->bind_param("i", $orderId);

    if ($deleteOrdersStmt->execute()) {
        // Delete from manufacturing_mat table
        include 'delete_from_manufacturing_mat.php';
        echo 'success';
    } else {
        echo 'error';
    }

    $deleteOrdersStmt->close();
} else {
    echo 'error';
}
