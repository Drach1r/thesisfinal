<?php
include_once 'db_connect.php';

function insertStockRecord($conn, $transactionId, $date, $totalDailyTotal)
{
    // Assign RawMaterialID as 205
    $rawMaterialId = 205;

    // Convert totalDailyTotal to milliliters
    $totalDailyTotalInML = $totalDailyTotal;

    $insertStockQuery = "INSERT INTO stock (RawMaterialID, stock_in, unit, transaction_date) VALUES (?, ?, 'ml', ?)";
    $stmt = mysqli_prepare($conn, $insertStockQuery);
    mysqli_stmt_bind_param($stmt, "ids", $rawMaterialId, $totalDailyTotalInML, $date);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    mysqli_stmt_close($stmt);
}

function fetchActual($conn, $transactionId)
{
    $selectStmt = $conn->prepare("SELECT actual FROM produced WHERE transaction_id = ?");
    $selectStmt->bind_param("i", $transactionId);
    $selectStmt->execute();
    $result = $selectStmt->get_result();
    $row = $result->fetch_assoc();
    $selectStmt->close();

    return $row['actual'];
}

function convertToMilliliters($quantity)
{
    return $quantity * 1000; // 1 liter = 1000 milliliters
}
