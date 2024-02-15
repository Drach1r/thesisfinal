<?php
include 'header.php';
include 'db_connect.php';
include 'sidebar.php';

// Initialize variables for search
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch data from the manufacturing_orders table for the first table
$query = "SELECT mo.*, pl.Name as ProductName, pl.measure, pl.Unit, pl.pack
          FROM manufacturing_orders mo
          LEFT JOIN productlist pl ON mo.ProductID = pl.ProductID
          WHERE mo.status <> 'In Stock'";

// Append search condition if search term is provided
if (!empty($search)) {
    $query .= " AND (mo.order_id LIKE '%$search%' OR pl.Name LIKE '%$search%')";
}

$result = mysqli_query($conn, $query);

// Fetch data from the manufacturing_orders table for the 'In Stock' table
$instockQuery = "SELECT mo.*, pl.Name as ProductName, pl.measure, pl.Unit, pl.pack
                 FROM manufacturing_orders mo
                 LEFT JOIN productlist pl ON mo.ProductID = pl.ProductID
                 WHERE mo.status = 'In Stock'";
$instockResult = mysqli_query($conn, $instockQuery);

if (!$result || !$instockResult) {
    die("Query failed: " . mysqli_error($conn));
}

// Initialize pagination variables for the first table
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page, default is 1
$limit = 6; // Number of results per page
$offset = ($page - 1) * $limit; // Offset for the SQL query

// Fetch data from the manufacturing_orders table for the first table with pagination
$queryWithPagination = $query . " LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $queryWithPagination);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Count total records for pagination
$countQuery = "SELECT COUNT(*) AS total FROM manufacturing_orders WHERE status <> 'In Stock'";
// Append search condition if search term is provided
if (!empty($search)) {
    $countQuery .= " AND (order_id LIKE '%$search%' OR ProductID IN (SELECT ProductID FROM productlist WHERE Name LIKE '%$search%'))";
}
$countResult = mysqli_query($conn, $countQuery);
$countRow = mysqli_fetch_assoc($countResult);
$totalRecords = $countRow['total'];

// Calculate total pages
$totalPages = ceil($totalRecords / $limit);

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
                                                        <button type='button' class='btn btn-secondary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                            Actions
                                                        </button>
                                                        <div class='dropdown-menu'>
                                                            <a class='dropdown-item edit-action' href='edit_production.php?id=$order_id'>Edit <i class='fa fa-pencil'></i></a>
                                                            <div class='dropdown-divider'></div>
                                                            <a class='dropdown-item delete-action' href='#' onclick='return confirm(\"Are you sure you want to delete this data?\")'>Delete <i class='fa fa-trash-o'></i></a>
                                                            <div class='dropdown-divider'></div>
                                                            <button class='dropdown-item instock-action' data-order-id='$order_id' data-due-date='{$row['due_date']}'>Change Status to In Stock</button>
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
                                        <?php
                                        // Iterate through the 'In Stock' result set and populate the table rows
                                        while ($instockRow = mysqli_fetch_assoc($instockResult)) {
                                            echo "<tr id='row-instock-$instockRow[order_id]'>";
                                            echo "<td>{$instockRow['order_id']}</td>";

                                            $instockOrderId =  $instockRow['order_id'];
                                            $instockQuantity =  $instockRow['batch_amount'] . ' ' . $instockRow['pack'];
                                            $instockUnit =   $instockRow['measure'] . ' ' . $instockRow['Unit'];

                                            echo "<td>{$instockRow['ProductName']}</td>";
                                            echo "<td>{$instockQuantity}</td>";
                                            echo "<td>{$instockUnit}</td>";
                                            echo "<td>{$instockRow['o_date']}</td>";
                                            echo "<td>{$instockRow['due_date']}</td>";
                                            echo "<td class='text-success'>{$instockRow['status']}</td>";
                                            echo "<td class='text-center'>
                                                    <div class='btn-group'>
                                                        <button type='button' class='btn btn-secondary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                            Actions
                                                        </button>
                                                        <div class='dropdown-menu'>
                                                            <a class='dropdown-item edit-action' href='edit_production.php?id=$instockOrderId'>Edit <i class='fa fa-pencil'></i></a>
                                                            <div class='dropdown-divider'></div>
                                                            <a class='dropdown-item delete-action' href='#' onclick='return confirm(\"Are you sure you want to delete this data?\")'>Delete <i class='fa fa-trash-o'></i></a>
                                                        </div>
                                                    </div>
                                                </td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <nav class="text-xs-center">
                                    <ul class="pagination">
                                        <?php
                                        // Calculate total number of pages
                                        $totalPagesQuery = "SELECT COUNT(*) AS total FROM produced WHERE actual != 0";
                                        $totalPagesResult = mysqli_query($conn, $totalPagesQuery);
                                        $totalRows = mysqli_fetch_assoc($totalPagesResult)['total'];
                                        $totalPages = ceil($totalRows / $limit);

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