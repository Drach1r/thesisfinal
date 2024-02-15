<?php

function getMaxDate($conn)
{
    $latestDateQuery = "SELECT MAX(DATE(transaction_day)) AS latestDate FROM rawmilk";
    $latestDateResult = mysqli_query($conn, $latestDateQuery);

    if (!$latestDateResult) {
        die("Query failed: " . mysqli_error($conn));
    }

    $latestDateRow = mysqli_fetch_assoc($latestDateResult);
    return $latestDateRow['latestDate'];
}

function getDailyTotalForDate($conn, $date)
{
    $dailyTotalQuery = "SELECT daily_total FROM rawmilk WHERE DATE(transaction_day) = '$date'";
    $dailyTotalResult = mysqli_query($conn, $dailyTotalQuery);

    if (!$dailyTotalResult) {
        die("Query failed: " . mysqli_error($conn));
    }

    $dailyTotalRow = mysqli_fetch_assoc($dailyTotalResult);
    return $dailyTotalRow['daily_total'];
}

function getTotalDailyTotalForDate($conn, $date)
{
    $dailyTotalQuery = "SELECT SUM(daily_total) AS totalDailyTotal FROM rawmilk WHERE DATE(transaction_day) = '$date'";
    $dailyTotalResult = mysqli_query($conn, $dailyTotalQuery);

    if (!$dailyTotalResult) {
        die("Query failed: " . mysqli_error($conn));
    }

    $dailyTotalRow = mysqli_fetch_assoc($dailyTotalResult);
    return $dailyTotalRow['totalDailyTotal'];
}

function checkStockRecordExists($conn, $rawMaterialID, $date)
{
    $checkStockQuery = "SELECT * FROM stock WHERE RawMaterialID = ? AND DATE(transaction_date) = ?";
    $stmt = mysqli_prepare($conn, $checkStockQuery);
    mysqli_stmt_bind_param($stmt, "is", $rawMaterialID, $date);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    return mysqli_num_rows($result) > 0;
}

function updateStockRecord($conn, $rawMaterialID, $date, $totalDailyTotal)
{
    $updateStockQuery = "UPDATE stock SET stock_in = ?, unit = 'ml' WHERE RawMaterialID = ? AND DATE(transaction_date) = ?";
    $stmt = mysqli_prepare($conn, $updateStockQuery);
    mysqli_stmt_bind_param($stmt, "iss", $totalDailyTotal, $rawMaterialID, $date);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    mysqli_stmt_close($stmt);
}

function insertStockRecord($conn, $rawMaterialID, $date, $totalDailyTotal)
{
    // Convert totalDailyTotal to milliliters
    $totalDailyTotalInML = convertToMilliliters($totalDailyTotal);

    $insertStockQuery = "INSERT INTO stock (RawMaterialID, stock_in, unit, transaction_date) VALUES (?, ?, 'ml', ?)";
    $stmt = mysqli_prepare($conn, $insertStockQuery);
    mysqli_stmt_bind_param($stmt, "ids", $rawMaterialID, $totalDailyTotalInML, $date);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    mysqli_stmt_close($stmt);
}

// Function to convert liters to milliliters
function convertToMilliliters($quantity)
{
    return $quantity * 1000; // 1 liter = 1000 milliliters
}


function displayDailyReceived($conn)
{
    $latestDate = getMaxDate($conn);
    $dailyTotal = getDailyTotalForDate($conn, $latestDate);
    echo "<p class='info-text'><strong>Daily Total Recieved:</strong> $dailyTotal L</p>";
}

function updateStockForLatestDate($conn)
{

    $latestDate = getMaxDate($conn);
    $totalDailyTotal = getTotalDailyTotalForDate($conn, $latestDate);


    $rawMaterialID = 205;

    if (checkStockRecordExists($conn, $rawMaterialID, $latestDate)) {
        updateStockRecord($conn, $rawMaterialID, $latestDate, $totalDailyTotal);
    } else {
        insertStockRecord($conn, $rawMaterialID, $latestDate, $totalDailyTotal);
    }
}
