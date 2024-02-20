<?php
include 'header.php';
include 'db_connect.php';
include 'sidebar.php';


?>


<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">
                        Producing End Products
                        <a href="c_production.php" class="btn btn-primary btn-sm rounded-s">
                            Add New
                        </a>
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <?php
    // Fetch data from the manufacturing_orders table for the first table (In Production)
    $query = "SELECT mo.*, pl.Name as ProductName, pl.measure, pl.Unit, pl.pack
              FROM manufacturing_orders mo
              LEFT JOIN productlist pl ON mo.ProductID = pl.ProductID
              WHERE mo.status = 'In Production'";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // Check if the delete button is clicked
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete_in_production') {
        // Ensure the order_id is set and not empty
        if (isset($_POST['order_id']) && !empty($_POST['order_id'])) {
            $order_id = $_POST['order_id'];

            // Begin transaction
            mysqli_begin_transaction($conn);

            try {
                // Fetch status of the order
                $checkStatusQuery = "SELECT status FROM manufacturing_orders WHERE order_id = ?";
                $checkStatusStatement = $conn->prepare($checkStatusQuery);
                $checkStatusStatement->bind_param("s", $order_id);
                $checkStatusStatement->execute();
                $checkStatusResult = $checkStatusStatement->get_result();
                $row = $checkStatusResult->fetch_assoc();
                $status = $row['status'];
                $checkStatusStatement->close();

                if ($status == 'In Production') {
                    // Delete corresponding data in manufacturing_mat table
                    $deleteMatQuery = "DELETE FROM manufacturing_mat WHERE order_id = ?";
                    $deleteMatStatement = $conn->prepare($deleteMatQuery);
                    $deleteMatStatement->bind_param("s", $order_id);
                    $deleteMatStatement->execute();
                    $deleteMatStatement->close();

                    // Prepare the DELETE query for manufacturing_orders
                    $deleteQuery = "DELETE FROM manufacturing_orders WHERE order_id = ?";
                    $deleteStatement = $conn->prepare($deleteQuery);
                    $deleteStatement->bind_param("s", $order_id);

                    // Execute the DELETE statement for manufacturing_orders
                    if ($deleteStatement->execute()) {
                        // Commit transaction
                        mysqli_commit($conn);
                        // Display success message
                        echo '<div class="alert alert-success" role="alert">Record deleted successfully.</div>';
                        // Redirect to the same page after 3 seconds to prevent form resubmission
                        echo '<script>setTimeout(function(){ window.location.href = window.location.pathname; }, 1000);</script>';
                        exit(); // Exit to prevent further execution
                    } else {
                        // Rollback transaction
                        mysqli_rollback($conn);
                        // Display error message
                        echo '<div class="alert alert-warning" role="alert">Error deleting record. Please try again.</div>';
                    }

                    // Close the prepared statement
                    $deleteStatement->close();
                } else {
                    // Rollback transaction
                    mysqli_rollback($conn);
                    // Display error message if the status is not 'In Production'
                    echo '<div class="alert alert-warning" role="alert">Error: This record cannot be deleted because its status is not "In Production".</div>';
                }
            } catch (Exception $e) {
                // Rollback transaction on exception
                mysqli_rollback($conn);
                echo '<div class="alert alert-danger" role="alert">An error occurred. Please try again.</div>';
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
                        </div>
                        <section class="example">
                            <br>
                            <br>
                            <div class="card card-block">
                                <table class="table table-bordered" id="inProductionTable"> <!-- Unique ID for the table -->
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                            <th>Production Date</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Iterate through the result set and populate the table rows
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr id='row-$row[order_id]'>";
                                            echo "<td>{$row['order_id']}</td>";

                                            $order_id =  $row['order_id'];
                                            $quantity =  $row['batch_amount'] . ' ' . $row['pack'];
                                            $unit =   $row['measure'] . ' ' . $row['Unit'];

                                            echo "<td>{$row['ProductName']}</td>";
                                            echo "<td>{$quantity}</td>";
                                            echo "<td>{$unit}</td>";
                                            echo "<td>{$row['o_date']}</td>";
                                            echo "<td>{$row['due_date']}</td>";
                                            echo "<td class='text-warning'>{$row['status']}</td>";
                                            echo "<td class='text-center'>
                                                <div class='btn-group'>
                                                    <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                        Actions
                                                    </button>
                                                    <div class='dropdown-menu'>
                                                        <form method='POST' action='{$_SERVER['PHP_SELF']}'>
                                                            <input type='hidden' name='action' value='delete_in_production'>
                                                            <input type='hidden' name='order_id' value='$order_id'>
                                                            <button type='submit' class='dropdown-item delete-action' style='color: red;' onclick='return confirm(\"Are you sure you want to delete this data?\")'>Delete <i class='fa fa-trash-o'></i></button>
                                                        </form>
                                                        <div class='dropdown-divider'></div>
                                                        <button class='dropdown-item instock-action' data-order-id='$order_id' data-due-date='{$row['due_date']}' style='color: green;'>Change Status to In Stock</button>
                                                    </div>
                                                </div>
                                            </td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <?php

    // Initialize variables for search
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // Fetch data from the manufacturing_orders table for the 'In Stock' table
    $query = "SELECT mo.*, pl.Name as ProductName, pl.measure, pl.Unit, pl.pack
          FROM manufacturing_orders mo
          LEFT JOIN productlist pl ON mo.ProductID = pl.ProductID
          WHERE mo.status = 'In Stock'";

    // Append search condition if search term is provided
    if (!empty($search)) {
        $query .= " AND (mo.order_id LIKE '%$search%' OR pl.Name LIKE '%$search%')";
    }

    // Initialize pagination variables for the 'In Stock' table
    $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page, default is 1
    $limit = 6; // Number of results per page
    $offset = ($page - 1) * $limit; // Offset for the SQL query

    // Fetch data from the 'In Stock' table with pagination
    $queryWithPagination = $query . " LIMIT $limit OFFSET $offset";
    $instockResult = mysqli_query($conn, $queryWithPagination);

    if (!$instockResult) {
        die("Query failed: " . mysqli_error($conn));
    }

    // Count total records for pagination
    $countQuery = "SELECT COUNT(*) AS total FROM manufacturing_orders WHERE status = 'In Stock'";
    // Append search condition if search term is provided
    if (!empty($search)) {
        $countQuery .= " AND (order_id LIKE '%$search%' OR ProductID IN (SELECT ProductID FROM productlist WHERE Name LIKE '%$search%'))";
    }
    $countResult = mysqli_query($conn, $countQuery);
    $countRow = mysqli_fetch_assoc($countResult);
    $totalRecords = $countRow['total'];

    // Calculate total pages
    $totalPages = ceil($totalRecords / $limit);

    // Handle form submissions for 'instock' table
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete') {
        // Ensure the order_id is set and not empty
        if (isset($_POST['order_id']) && !empty($_POST['order_id']) && isset($_GET['table']) && $_GET['table'] === 'instock') {
            $order_id = $_POST['order_id'];
            // Delete from stock table
            $deleteStockQuery = "
            DELETE s
            FROM stock s
            INNER JOIN manufacturing_mat mm ON s.RawMaterialID = mm.RawMaterialID
            INNER JOIN manufacturing_orders mo ON mm.order_id = mo.order_id
            WHERE mm.order_id = ? 
            AND s.stock_out = mm.issued_quantity
            AND s.transaction_date = mo.o_date
        ";
            $deleteStockStatement = $conn->prepare($deleteStockQuery);
            if (!$deleteStockStatement) {
                die('Error preparing query: ' . $conn->error);
            }
            $deleteStockStatement->bind_param("s", $order_id);
            if (!$deleteStockStatement->execute()) {
                die('Error executing query: ' . $deleteStockStatement->error);
            }
            // Check if any rows were affected
            if ($deleteStockStatement->affected_rows > 0) {
                echo "Rows deleted successfully.";
            } else {
                echo "No rows deleted.";
            }
            $deleteStockStatement->close();
            // Specify the table name for the deletion query (product_stock)
            $productStockTable = 'product_stock'; // Change this to the correct table name

            // Perform the deletion query for 'product_stock' table
            $deleteProductStockQuery = "DELETE FROM $productStockTable WHERE ProductID IN (SELECT ProductID FROM manufacturing_orders WHERE order_id = ? AND status = 'In Stock') ORDER BY TransactionDate DESC LIMIT 1";
            $deleteProductStockStatement = $conn->prepare($deleteProductStockQuery);
            $deleteProductStockStatement->bind_param("s", $order_id);
            $deleteProductStockStatement->execute();
            $deleteProductStockStatement->close();

            // Specify the table name for the deletion query (manufacturing_mat)
            $manufacturingMatTable = 'manufacturing_mat'; // Change this to the correct table name

            // Perform the deletion query for 'manufacturing_mat' table
            $deleteManufacturingMatQuery = "DELETE FROM $manufacturingMatTable WHERE order_id = ?";
            $deleteManufacturingMatStatement = $conn->prepare($deleteManufacturingMatQuery);
            $deleteManufacturingMatStatement->bind_param("s", $order_id);
            $deleteManufacturingMatStatement->execute();
            $deleteManufacturingMatStatement->close();

            // Specify the table name for the deletion query (manufacturing_orders)
            $manufacturingOrdersTable = 'manufacturing_orders'; // Change this to the correct table name

            // Perform the deletion query for 'manufacturing_orders' table
            $deleteManufacturingOrdersQuery = "DELETE FROM $manufacturingOrdersTable WHERE order_id = ?";
            $deleteManufacturingOrdersStatement = $conn->prepare($deleteManufacturingOrdersQuery);
            $deleteManufacturingOrdersStatement->bind_param("s", $order_id);
            $deleteManufacturingOrdersStatement->execute();
            $deleteManufacturingOrdersStatement->close();

            // Display success message
            echo '<div class="alert alert-success" role="alert">Data deleted successfully.</div>';
        }
    }


    ?>

    <section class="section">
        <div class="row">
            <div class="card col-lg-12">
                <div class="card-body">
                    <div class="card-body">
                        <div class="card-title-body">
                        </div>
                        <section class="example">
                            <br>
                            <br>
                            <form id="searchForm" method="GET" action="">
                                <label for="search">Search:</label>
                                <input type="text" name="search" id="search" value="<?php echo $search; ?>">
                                <button type="submit" class="btn btn-primary btn-sm rounded-s">Search</button>
                                <a href="?page=1" class="btn btn-warning btn-sm rounded-s">Cancel Search</a>
                            </form>
                            <br>
                            <div class="card card-block">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                            <th>Production Date</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="instock-table-body">
                                        <?php while ($instockRow = mysqli_fetch_assoc($instockResult)) { ?>
                                            <tr>
                                                <!-- Output table data for each column -->
                                                <td><?php echo $instockRow['order_id']; ?></td>
                                                <td><?php echo $instockRow['ProductName']; ?></td>
                                                <td><?php echo $instockRow['batch_amount'] . ' ' . $instockRow['pack']; ?></td>
                                                <td><?php echo $instockRow['measure'] . ' ' . $instockRow['Unit']; ?></td>
                                                <td><?php echo $instockRow['o_date']; ?></td>
                                                <td><?php echo $instockRow['due_date']; ?></td>
                                                <td class="text-success"><?php echo $instockRow['status']; ?></td>
                                                <td class="text-center">
                                                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>?table=instock">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="order_id" value="<?php echo $instockRow['order_id']; ?>">
                                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this record?')" class="btn btn-danger btn-sm rounded-s">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav class="text-xs-center">
                                    <ul class="pagination">
                                        <!-- Previous page link -->
                                        <li class="page-item <?php echo $page == 1 ? 'disabled' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo max(1, $page - 1); ?>">Prev</a>
                                        </li>
                                        <!-- Page links -->
                                        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                            <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <!-- Next page link -->
                                        <li class="page-item <?php echo $page == $totalPages || $totalPages == 0 ? 'disabled' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>


</article>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add click event listeners to the 'Change Status to In Stock' buttons
        var instockButtons = document.querySelectorAll('.instock-action');
        instockButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var orderId = this.getAttribute('data-order-id');
                var dueDate = this.getAttribute('data-due-date');
                changeStatusToInStock(orderId, dueDate);
            });
        });

        // Add click event listeners to the 'Delete' buttons
        var deleteButtons = document.querySelectorAll('.delete-action');
        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var orderId = this.getAttribute('data-order-id');
                deleteProduction(orderId);
            });
        });

        function changeStatusToInStock(orderId, dueDate) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState === 4) {
                    if (this.status === 200) {
                        // Update the UI or provide feedback as needed
                        alert('Status changed to In Stock successfully!');

                        // Move the row to the 'In Stock' table
                        var row = document.getElementById('row-' + orderId);
                        var instockTableBody = document.getElementById('instock-table-body');
                        instockTableBody.appendChild(row);

                        // Update the status in the moved row
                        var statusCell = row.querySelector('.text-warning');
                        statusCell.textContent = 'In Stock';

                        // Call the function to insert into stock table
                        insertIntoTables(orderId, dueDate);
                    } else {
                        console.error('Error changing status. Status:', this.status);
                    }
                }
            };

            xhr.open('POST', 'change_status_to_instock.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('order_id=' + orderId);
        }

        // Function to send an asynchronous request to insert into both product_stock and stock tables
        function insertIntoTables(orderId, dueDate) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState === 4) {
                    if (this.status === 200 && this.responseText === 'success') {
                        // Update the UI or provide feedback as needed
                        console.log('Data inserted successfully into product_stock and stock tables!');

                        // Set a 1-second timer to refresh the page after successful insertion
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    } else {
                        console.error('Error inserting data into tables. Status:', this.status);
                    }
                }
            };

            xhr.open('POST', 'insert_into_tables.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('order_id=' + orderId + '&due_date=' + dueDate);
        }

        // Function to send an asynchronous request to delete production and related data
        function deleteProduction(orderId) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState === 4) {
                    if (this.status === 200 && this.responseText === 'success') {
                        // Update the UI or provide feedback as needed
                        alert('Production record deleted successfully!');

                        // Remove the deleted row from the table
                        var row = document.getElementById('row-' + orderId);
                        row.parentNode.removeChild(row);
                    } else {
                        console.error('Error deleting production record. Status:', this.status);
                    }
                }
            };

            xhr.open('POST', 'delete_production.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('order_id=' + orderId);
        }

    });
</script>



<?php
include 'footer.php';
?>