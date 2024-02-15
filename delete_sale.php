<?php
include 'db_connect.php';

if (isset($_GET['SaleID'])) {
    $saleID = $_GET['SaleID'];

    // Delete sales items first
    $deleteItemsQuery = $conn->prepare("DELETE FROM salesitems WHERE SaleID = ?");
    $deleteItemsQuery->bind_param("s", $saleID);
    $deleteItemsQuery->execute();
    $deleteItemsQuery->close();

    // Delete the sale
    $deleteSaleQuery = $conn->prepare("DELETE FROM sales WHERE SaleID = ?");
    $deleteSaleQuery->bind_param("s", $saleID);

    if ($deleteSaleQuery->execute()) {
        header('Location: sales_list.php'); // Redirect to the sales list page after successful deletion
        exit();
    } else {
        echo 'Error deleting sale: ' . $deleteSaleQuery->error;
    }

    $deleteSaleQuery->close();
}
