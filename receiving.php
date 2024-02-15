<?php
include 'header.php';
include 'sidebar.php';
$successMessage = '';

?>

<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">
                        Receiving Raw Materials
                        <a href="c_receiving.php" class="btn btn-primary btn-sm rounded-s">
                            Add New
                        </a>
                    </h3>


                </div>
            </div>
        </div>
        <?php
        // PHP code for querying purchases with status 0
        $queryStatus0 = "SELECT status0_purchases.id, rawmaterials.Name AS raw_material_name, employee.name AS buyer, PurchaseDate, qty_purchased, status0_purchases.unit, p_amount, status0_purchases.status
         FROM purchases AS status0_purchases
         JOIN rawmaterials ON status0_purchases.RawMaterialID = rawmaterials.RawMaterialID
         JOIN employee ON status0_purchases.buyer = employee.id
         WHERE status0_purchases.status = 0
         ORDER BY PurchaseDate DESC";
        $resultStatus0 = mysqli_query($conn, $queryStatus0);

        // Check for errors in the query
        if (!$resultStatus0) {
            die("Query failed: " . mysqli_error($conn));
        }

        // Debugging - Echo out the value of $_GET['id0']
        if (isset($_GET['id0'])) {
            $deleteId = $_GET['id0'];


            // Disable foreign key checks
            mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

            // Check if the record has status = 0
            $status0_checkQuery = "SELECT status FROM purchases WHERE id = ?";
            $status0_checkStmt = $conn->prepare($status0_checkQuery);
            $status0_checkStmt->bind_param("i", $deleteId);
            $status0_checkStmt->execute();
            $status0_checkResult = $status0_checkStmt->get_result();

            if ($status0_checkRow = $status0_checkResult->fetch_assoc()) {
                $status0 = $status0_checkRow['status'];

                if ($status0 == 0) {
                    // Debugging - Fetch additional data from purchases table
                    $purchaseDataQuery = "SELECT RawMaterialID, qty_purchased, unit FROM purchases WHERE id = ?";
                    $purchaseDataStmt = $conn->prepare($purchaseDataQuery);
                    $purchaseDataStmt->bind_param("i", $deleteId);
                    $purchaseDataStmt->execute();
                    $purchaseDataResult = $purchaseDataStmt->get_result();

                    if ($purchaseDataRow = $purchaseDataResult->fetch_assoc()) {
                        $rawMaterialID = $purchaseDataRow['RawMaterialID'];
                        $quantityPurchased = $purchaseDataRow['qty_purchased'];
                        $unit = $purchaseDataRow['unit'];
                    }

                    // Delete the record from the purchases table
                    $deletePurchaseQuery = "DELETE FROM purchases WHERE id = ?";
                    $deletePurchaseStmt = $conn->prepare($deletePurchaseQuery);
                    $deletePurchaseStmt->bind_param("i", $deleteId);
                    $deletePurchaseStmt->execute();

                    // Check if deletion was successful
                    if ($deletePurchaseStmt->affected_rows > 0) {
                        // Delete successful
                        echo '<div class="alert alert-success" role="alert">Data deleted successfully.</div>';
                        // Reload the page after a short delay
                        echo '<script>setTimeout(function(){ location.reload(); }, 1000);</script>';
                    } else {
                        // Deleting the record is not allowed or record not found
                        echo '<div class="alert alert-warning" role="alert">Deleting the record is not allowed or record not found.</div>';
                        // Reload the page after a short delay
                        echo '<script>setTimeout(function(){ location.reload(); }, 1000);</script>';
                    }
                    $deletePurchaseStmt->close();
                } else {
                    // Status is not 0, so no deletion occurs
                    echo '<div class="alert alert-warning" role="alert">Deleting the record is not allowed because status is not equal to 0.</div>';
                }
            }
        }

        ?>

        <section class="section">
            <div class="row">
                <div class="card col-lg-12">
                    <div class="card-body">
                        <div class="card-body">
                            <div class="card-title-body">
                                <section class="example">
                                    <br>
                                    <table class="table table-bordered col-md-12">
                                        <thead>
                                            <tr>
                                                <th>Purchased Date</th>
                                                <th>Raw Material</th>
                                                <th>Buyer</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>Purchased Total</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($row = mysqli_fetch_assoc($resultStatus0)) {
                                                $id = $row['id'];
                                                $raw_material_name = $row['raw_material_name'];
                                                $buyer = $row['buyer'];
                                                $qty_purchased = $row['qty_purchased'];
                                                $unit = $row['unit'];
                                                $PurchaseDate = date('Y-m-d', strtotime($row['PurchaseDate'])); // Format the date
                                                $p_amount = $row['p_amount'];
                                                $status0 = $row['status'];
                                                $status_display0 = $status0 == 0 ? 'Pending' : ''; // Display 'Pending' if status is 0
                                                echo "<tr>";
                                                echo "<td class='text-center'>$PurchaseDate</td>";
                                                echo "<td class='text-center'>$raw_material_name</td>";
                                                echo "<td class='text-center'>$buyer</td>";
                                                echo "<td class='text-center'>$qty_purchased</td>";
                                                echo "<td class='text-center'>$unit</td>";
                                                echo "<td class='text-center'>$p_amount</td>";
                                                echo "<td class='text-center text-warning'>$status_display0</td>";
                                                echo "<td class='text-center'>
                                                <div class='btn-group'>
                                                    <button type='button' class='btn btn-secondary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                        Actions
                                                    </button>
                                                    <div class='dropdown-menu'>                                                
                                                    <a class='dropdown-item delete-action' href='?id0=$id' onclick='return confirm(\"Are you sure you want to delete this data?\")'>Delete <i class='fa fa-trash-o'></i></a>

                                                        <div class='dropdown-divider'></div>
                                                        <button class='dropdown-item instock-action' onclick='changeToInStock($id)'>Change Status to In Stock</button>
                                                    </div>
                                                </div>
                                            </td>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php

        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $limit = 6;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // Count total records
        $countQuery = "SELECT COUNT(*) AS total FROM purchases";
        $countResult = mysqli_query($conn, $countQuery);
        $countRow = mysqli_fetch_assoc($countResult);
        $totalRecords = $countRow['total'];

        // Calculate total pages
        $totalPages = ceil($totalRecords / $limit);

        // Query for table with status 1 (for pagination and search functionality)
        $queryStatus1 = "SELECT status1_purchases.id, rawmaterials.Name AS raw_material_name, employee.name AS buyer, PurchaseDate, qty_purchased, status1_purchases.unit, p_amount
FROM purchases AS status1_purchases
JOIN rawmaterials ON status1_purchases.RawMaterialID = rawmaterials.RawMaterialID
JOIN employee ON status1_purchases.buyer = employee.id
WHERE (rawmaterials.Name LIKE '%$search%' OR employee.name LIKE '%$search%') AND status1_purchases.status = 1
ORDER BY PurchaseDate DESC
LIMIT $limit OFFSET $offset";
        $resultStatus1 = mysqli_query($conn, $queryStatus1);

        // Check for errors in the query
        if (!$resultStatus1) {
            die("Query failed: " . mysqli_error($conn));
        }

        // Check if the delete trigger is activated
        if (isset($_GET['id1'])) {
            $deleteId = $_GET['id1'];


            // Disable foreign key checks
            mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

            // Check if the record has status = 1
            $status1_checkQuery = "SELECT status FROM purchases WHERE id = ?";
            $status1_checkStmt = $conn->prepare($status1_checkQuery);
            $status1_checkStmt->bind_param("i", $deleteId);
            $status1_checkStmt->execute();
            $status1_checkResult = $status1_checkStmt->get_result();

            if ($status1_checkRow = $status1_checkResult->fetch_assoc()) {
                $status1 = $status1_checkRow['status'];
                if ($status1 == 1) {
                    // Get the RawMaterialID and Quantity from the purchase record
                    $purchaseDataQuery = "SELECT RawMaterialID, qty_purchased, unit FROM purchases WHERE id = ?";
                    $purchaseDataStmt = $conn->prepare($purchaseDataQuery);
                    $purchaseDataStmt->bind_param("i", $deleteId);
                    $purchaseDataStmt->execute();
                    $purchaseDataResult = $purchaseDataStmt->get_result();

                    if ($purchaseDataRow = $purchaseDataResult->fetch_assoc()) {
                        $rawMaterialID = $purchaseDataRow['RawMaterialID'];
                        $quantityPurchased = $purchaseDataRow['qty_purchased'];
                        $unit = $purchaseDataRow['unit'];


                        // Convert quantity to milliliters if necessary
                        $quantityMilliliters = $quantityPurchased;
                        if ($unit === 'Kg') {
                            $quantityMilliliters *= 1000; // Convert kilograms to grams
                        }
                        if ($unit === 'L') {
                            $quantityMilliliters *= 1000; // Convert liters to milliliters
                        }


                        // Delete the record from the stock table
                        $deleteStockQuery = "DELETE FROM stock WHERE RawMaterialID = ? AND stock_in = ? LIMIT 1";
                        $deleteStockStmt = $conn->prepare($deleteStockQuery);
                        $deleteStockStmt->bind_param("ii", $rawMaterialID, $quantityMilliliters);
                        $deleteStockStmt->execute();

                        $deleteStockStmt->close();
                    }

                    // Delete the record from the purchases table
                    $deletePurchaseQuery = "DELETE FROM purchases WHERE id = ?";
                    $deletePurchaseStmt = $conn->prepare($deletePurchaseQuery);
                    $deletePurchaseStmt->bind_param("i", $deleteId);
                    $deletePurchaseStmt->execute();

                    // Reset the auto-incremented ID in the purchases table
                    mysqli_query($conn, "ALTER TABLE purchases AUTO_INCREMENT = 1");

                    // Re-enable foreign key checks
                    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");

                    if ($deletePurchaseStmt->affected_rows > 0) {
                        // Delete successful
                        echo '<div class="alert alert-success" role="alert">Data deleted successfully.</div>';
                        // Reload the page after a short delay
                        echo '<script>setTimeout(function(){ location.reload(); }, 1000);</script>';
                    } else {
                        // Deleting the record is not allowed or record not found
                        echo '<div class="alert alert-warning" role="alert">Deleting the record is not allowed or record not found.</div>';
                        // Reload the page after a short delay
                        echo '<script>setTimeout(function(){ location.reload(); }, 1000);</script>';
                    }
                    $deletePurchaseStmt->close();
                }
            }
        }

        ?>
        <section class="section">
            <div class="row">
                <div class="card col-lg-12">
                    <div class="card-body">
                        <div class="card-body">
                            <div class="card-title-body">
                                <section class="example">
                                    <br>
                                    <form id="searchForm" method="GET" action="">
                                        <label for="search">Search:</label>
                                        <input type="text" name="search" id="search" value="<?php echo $search; ?>">
                                        <button type="submit" class="btn btn-primary btn-sm rounded-s">Search</button>
                                        <?php if (!empty($search)) { ?>
                                            <a href="?page=1" class="btn btn-warning btn-sm rounded-s">Cancel Search</a>
                                        <?php } ?>
                                    </form>
                                    <br>
                                    <table class="table table-bordered col-md-12">
                                        <thead>
                                            <tr>
                                                <th>Purchased Date</th>
                                                <th>Raw Material</th>
                                                <th>Buyer</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>Purchased Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($row = mysqli_fetch_assoc($resultStatus1)) {
                                                $id = $row['id'];
                                                $raw_material_name = $row['raw_material_name'];
                                                $buyer = $row['buyer'];
                                                $qty_purchased = $row['qty_purchased'];
                                                $unit = $row['unit'];
                                                $PurchaseDate = date('Y-m-d', strtotime($row['PurchaseDate'])); // Format the date
                                                $p_amount = $row['p_amount'];
                                                echo "<tr>";

                                                echo "<td class='text-center'>$PurchaseDate</td>";

                                                echo "<td class='text-center'>$raw_material_name</td>";
                                                echo "<td class='text-center'>$buyer</td>";
                                                echo "<td class='text-center'>$qty_purchased</td>";
                                                echo "<td class='text-center'>$unit</td>";
                                                echo "<td class='text-center'>$p_amount</td>";
                                                echo "<td class='text-center'>
                                        <a href='?id1=$id' onclick='return confirm(\"Are you sure you want to delete this data?\")' style='color: red; text-decoration: none;'>Delete <i class='fa fa-trash-o'></i> </a>
                                        </td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <nav class="text-xs-center">
                                        <ul class="pagination">
                                            <?php
                                            // Previous page link
                                            echo "<li class='page-item " . ($page == 1 ? 'disabled' : '') . "'>
                                                    <a class='page-link' href='?page=" . max(1, $page - 1) . "'>Prev</a>
                                                </li>";

                                            // Page links
                                            for ($i = 1; $i <= $totalPages; $i++) {
                                                echo "<li class='page-item " . ($page == $i ? 'active' : '') . "'>
                                                        <a class='page-link' href='?page=$i'>$i</a>
                                                    </li>";
                                            }

                                            // Next page link
                                            echo "<li class='page-item " . ($page == $totalPages ? 'disabled' : '') . "'>
                                                    <a class='page-link' href='?page=" . min($totalPages, $page + 1) . "'>Next</a>
                                                </li>";
                                            ?>
                                        </ul>
                                    </nav>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</article>
<!-- Add this JavaScript code within the <head> tag or before the closing </body> tag -->
<script>
    function changeToInStock(id) {
        if (confirm("Are you sure you want to change the status to In Stock?")) {
            // Send AJAX request to update status
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "update_status.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // If status update is successful, reload the page
                    if (xhr.responseText === "success") {
                        location.reload();
                    } else {
                        // Display Bootstrap warning or error message
                        var message = xhr.responseText === "error" ? "Failed to update status." : "Data inserted into stock.";
                        var alertClass = xhr.responseText === "error" ? "alert-danger" : "alert-warning";
                        var alertElement = document.createElement("div");
                        alertElement.classList.add("alert", alertClass);
                        alertElement.setAttribute("role", "alert");
                        alertElement.textContent = message;
                        document.body.appendChild(alertElement);
                        // Hide the alert after 5 seconds
                        setTimeout(function() {
                            alertElement.style.display = "none";
                        }, 5000);
                    }
                }
            };
            xhr.send("id=" + id);
        }
    }
</script>




</body>

</html>