<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get order_id from POST data
    $orderId = $_POST['order_id'];

    // Fetch details for the specified order_id
    $selectQuery = "SELECT mo.*, pl.ProductID
                    FROM manufacturing_orders mo
                    LEFT JOIN productlist pl ON mo.ProductID = pl.ProductID
                    WHERE mo.order_id = ?";
    $stmt = mysqli_prepare($conn, $selectQuery);
    mysqli_stmt_bind_param($stmt, 's', $orderId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        // Extract relevant details
        $productId = $row['ProductID'];
        $batchAmount = $row['batch_amount'];
        $transactionDate = date('Y-m-d H:i:s');

        // Insert into product_stock
        $insertQuery = "INSERT INTO product_stock (ProductID, stock_in, stock_out, TransactionDate)
                        VALUES (?, ?, 0, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, 'sds', $productId, $batchAmount, $transactionDate);
        $insertResult = mysqli_stmt_execute($stmt);

        if ($insertResult) {
            // Update the status in manufacturing_orders to 'In Stock'
            $updateQuery = "UPDATE manufacturing_orders SET status = 'In Stock' WHERE order_id = ?";
            $stmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($stmt, 's', $orderId);
            $updateResult = mysqli_stmt_execute($stmt);

            if ($updateResult) {
                // Insert into stock table with the transaction date
                $insertStockQuery = "INSERT INTO stock (RawMaterialID, stock_out, unit, transaction_date) 
                                     SELECT RawMaterialID, issued_quantity, qty_unit, ? FROM manufacturing_mat WHERE order_id = ?";
                $stmtInsertStock = $conn->prepare($insertStockQuery);
                $stmtInsertStock->bind_param("ss", $transactionDate, $orderId);
                $stmtInsertStock->execute();
                $stmtInsertStock->close();

                // Convert quantities if necessary (Kg to g, L to ml)
                $convertQuery = "UPDATE stock SET stock_out = CASE
                                     WHEN qty_unit = 'Kg' THEN issued_quantity * 1000
                                     WHEN qty_unit = 'L' THEN issued_quantity * 1000
                                     ELSE issued_quantity
                                 END,
                                 unit = CASE
                                     WHEN qty_unit = 'Kg' THEN 'g'
                                     WHEN qty_unit = 'L' THEN 'ml'
                                     ELSE qty_unit
                                 END
                                 WHERE order_id = ?";
                $stmtConvert = $conn->prepare($convertQuery);
                $stmtConvert->bind_param("s", $orderId);
                $stmtConvert->execute();
                $stmtConvert->close();

                echo 'success';
            } else {

                echo 'error_update_status: ' . mysqli_error($conn);
                error_log('Error updating status: ' . mysqli_error($conn));
            }
        } else {
            echo 'error_insert_product_stock: ' . mysqli_error($conn);
            error_log('Error inserting into product_stock: ' . mysqli_error($conn));
        }
    } else {
        echo 'error_fetch_details: ' . mysqli_error($conn);
        error_log('Error fetching details: ' . mysqli_error($conn));
    }
} else {
    echo 'invalid_request';
}
