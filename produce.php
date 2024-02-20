<?php
include 'header.php';
include 'sidebar.php';
include 'db_connect.php';
?>

<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">
                        Daily Rawmilk Received List
                        <a href="c_produced.php" class="btn btn-primary btn-sm rounded-s">
                            Add Transaction
                        </a>
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <?php


    // Initialize $search, $limit, and $offset
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $limit = 6; // Number of results per page
    $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page, default is 1
    $offset = ($page - 1) * $limit; // Offset for the SQL query

    // Initialize $totalRowsWithActual to avoid undefined variable warning
    $totalRowsWithActual = 0;

    // Modify your SQL query to include search condition, LIMIT, and OFFSET for the "With Actual" table
    $actualSql = "SELECT p.transaction_id, p.member_id, m.firstname, m.lastname, p.carabao_id, c.name AS carabaoName, p.milkslip, p.actual, p.date
FROM produced p
INNER JOIN member m ON p.member_id = m.id
LEFT JOIN carabaos c ON p.carabao_id = c.id
WHERE (p.actual != 0) AND (m.firstname LIKE '%$search%' OR m.lastname LIKE '%$search%')
ORDER BY p.date DESC
LIMIT $limit OFFSET $offset";

    $actualResult = mysqli_query($conn, $actualSql);

    // Check for errors in the query
    if (!$actualResult) {
        die("Query failed: " . mysqli_error($conn));
    }

    // Count the total number of rows for the search term in the "With Actual" table
    $totalRowsActualQuery = "SELECT COUNT(*) as total FROM produced p
INNER JOIN member m ON p.member_id = m.id
WHERE (p.actual != 0) AND (m.firstname LIKE '%$search%' OR m.lastname LIKE '%$search%')";
    $totalRowsActualResult = mysqli_query($conn, $totalRowsActualQuery);

    // Check if the query was successful before fetching the result
    if ($totalRowsActualResult) {
        $totalRowsWithActual = mysqli_fetch_assoc($totalRowsActualResult)['total'];
    }
    if (isset($_GET['delete'])) {
        $deleteId = $_GET['delete'];

        // Fetch the 'actual' value and date for the deleted record in produced table
        $producedInfoQuery = $conn->prepare("SELECT actual, date FROM produced WHERE transaction_id = ?");
        $producedInfoQuery->bind_param("i", $deleteId);
        $producedInfoQuery->execute();
        $producedInfoResult = $producedInfoQuery->get_result();

        if ($producedInfoRow = $producedInfoResult->fetch_assoc()) {
            $actualValue = $producedInfoRow['actual'];
            $deleteDate = $producedInfoRow['date'];

            // Perform deletion using a function
            if (deleteProducedRecord($conn, $deleteId, $actualValue, $deleteDate)) {
                echo '<div class="alert alert-success" role="alert">Data deleted successfully from produced table. Rawmilk table updated successfully.</div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Error deleting data from produced table.</div>';
            }
        }
    }
    function deleteProducedRecord($conn, $deleteId, $actualValue, $deleteDate)
    {
        // Convert the actual value to milliliters
        $actualValueMilliliters = $actualValue * 1000;

        // Prepare and execute the DELETE query for the stock record
        $deleteStockStmt = $conn->prepare("DELETE FROM stock WHERE RawMaterialID = 205 AND stock_in = ? AND transaction_date = ? LIMIT 1");
        $deleteStockStmt->bind_param("is", $actualValueMilliliters, $deleteDate);

        // Execute the query
        if ($deleteStockStmt->execute()) {
            // If stock deletion was successful, proceed with deleting the produced record
            $deleteProducedStmt = $conn->prepare("DELETE FROM produced WHERE transaction_id = ?");
            $deleteProducedStmt->bind_param("i", $deleteId);

            if ($deleteProducedStmt->execute()) {
                // Reset the auto-increment value for the produced table
                $resetProducedStmt = $conn->prepare("ALTER TABLE produced AUTO_INCREMENT = 1");
                $resetProducedStmt->execute();
                $resetProducedStmt->close();

                // Alter the stock table if necessary
                $alterStockStmt = $conn->prepare("ALTER TABLE stock MODIFY COLUMN StockID INT auto_increment");
                $alterStockStmt->execute();
                $alterStockStmt->close();

                // Update the rawmilk table
                $updateRawMilkStmt = $conn->prepare("UPDATE rawmilk SET daily_total = daily_total - ? WHERE DATE(transaction_day) = ?");
                $updateRawMilkStmt->bind_param("is", $actualValue, $deleteDate);

                if ($updateRawMilkStmt->execute()) {
                    $updateRawMilkStmt->close();
                    $deleteProducedStmt->close();
                    $deleteStockStmt->close();
                    return true;
                } else {
                    $updateRawMilkStmt->close();
                    $deleteProducedStmt->close();
                    $deleteStockStmt->close();
                    return false;
                }
            } else {
                $deleteProducedStmt->close();
                $deleteStockStmt->close();
                return false;
            }
        } else {
            // Error in deletion
            echo "Error deleting stock record: " . $deleteStockStmt->error;
            $deleteStockStmt->close();
            return false;
        }
    }

    $query = "SELECT p.transaction_id, p.member_id, m.firstname, m.lastname, p.carabao_id, c.name AS carabaoName, p.milkslip, p.actual, p.date
    FROM produced p
    INNER JOIN member m ON p.member_id = m.id
    LEFT JOIN carabaos c ON p.carabao_id = c.id
    WHERE p.actual IS NULL OR p.actual = 0
    ORDER BY p.transaction_id desc";

    $result = mysqli_query($conn, $query);


    // Check for errors in the query
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    ?>
    <style>
        /* Set the table header as fixed */
        .table-container thead {
            position: sticky;
            top: 0;
            background-color: #fff;
            z-index: 1;
        }

        /* Set the height and scroll the table body */
        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }

        /* Additional styles for the exported table */
        .table-export tr th,
        .table-export tr td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }
    </style>

    <script>
        $(document).ready(function() {
            // Search function for With Actual table
            $("#searchForm").submit(function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Get the search value
                var value = $("#search").val().toLowerCase();

                // Filter the table rows based on the search value
                $("#tableBodyWithActual tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>

    <section class="section">
        <div class="row">
            <div class="card col-lg-12">
                <div class="card-body">
                    <div class="card-body">
                        <div class="card-title-body"></div>

                        <section class="example">
                            <br>
                            <br>
                            <h3> Daily Milk Slip: </h3>
                            <br>
                            <div class="table-container" style="max-height: 400px; overflow-y: auto;">

                                <table class="table table-bordered col-md-12">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Cooperator</th>
                                            <th>Carabao ID</th>
                                            <th>Milk Recieved</th>

                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBodyMilkSlip">
                                        <?php
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $id = $row['transaction_id'];
                                            $member = $row['firstname'] . ' ' . $row['lastname'];
                                            $carabaoName = $row['carabaoName'];
                                            $milkslip = $row['milkslip'];
                                            $date = $row['date'];

                                            echo "<tr>";
                                            echo "<td class='text-center'>$date</td>";
                                            echo "<td class='text-center'>$member</td>";
                                            echo "<td class='text-center'>$carabaoName</td>";
                                            echo "<td class='text-center'>$milkslip L</td>";

                                            echo "<td class='text-center'> 
        <a href='edit_milkslip.php?id=$id'><i class='fa fa-pencil'></i></a> | 
        <a href='?delete=$id' onclick='return confirm(\"Are you sure you want to delete this data?\")'><i class='fa fa-trash-o'></i></a>
        | <a href='add_actual.php?id=$id'> ADD </i></a>
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

                        <div class="card-title-body"></div>
                        <section class="example">
                            <br>
                            <br>
                            <?php
                            $query = "SELECT actual AS daily_milk_received 
FROM produced 
JOIN (SELECT MAX(date) AS max_date FROM produced) AS max_prod 
ON produced.date = max_prod.max_date";

                            $result = mysqli_query($conn, $query);

                            if ($result) {
                                // Initialize total variable
                                $totalDailyMilkReceived = 0;

                                // Fetch all rows and sum up the values
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $totalDailyMilkReceived += $row['daily_milk_received'];
                                }
                            } else {
                                // Handle the error if the query fails
                                $totalDailyMilkReceived = "Error fetching daily milk received";
                            }
                            ?>


                            <h3>Daily Received:</h3>

                            <div class="form-group" style="float: right; margin-right: 250px"> <strong>Daily Total: <?php echo $totalDailyMilkReceived; ?>L</strong></div>




                            <br>

                            <div class=" table-container" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-bordered col-md-12">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Cooperator</th>
                                            <th>Carabao ID</th>
                                            <th>Milk Recieved</th>
                                            <th>Actual Recieved</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableDailyResults">
                                        <?php
                                        $latestDateQuery = "SELECT MAX(DATE(p.date)) AS latestDate FROM produced p WHERE p.actual != 0";
                                        $latestDateResult = mysqli_query($conn, $latestDateQuery);

                                        if (!$latestDateResult) {
                                            die("Query failed: " . mysqli_error($conn));
                                        }

                                        $latestDateRow = mysqli_fetch_assoc($latestDateResult);
                                        $latestDate = $latestDateRow['latestDate'];

                                        $actualQuery = "SELECT p.transaction_id, p.member_id, m.firstname, m.lastname, p.carabao_id, c.name AS carabaoName, p.milkslip, p.actual, DATE(p.date) AS formattedDate
                                        FROM produced p
                                        INNER JOIN member m ON p.member_id = m.id
                                        LEFT JOIN carabaos c ON p.carabao_id = c.id
                                        WHERE p.actual != 0 AND DATE(p.date) = '$latestDate'
                                        ORDER BY p.date DESC";

                                        $actualResult = mysqli_query($conn, $actualQuery);

                                        if (!$actualResult) {
                                            die("Query failed: " . mysqli_error($conn));
                                        }

                                        while ($actualRow = mysqli_fetch_assoc($actualResult)) {
                                            $actualId = $actualRow['transaction_id'];
                                            $actualMember = $actualRow['firstname'] . ' ' . $actualRow['lastname'];
                                            $actualCarabaoName = $actualRow['carabaoName'];
                                            $actualMilkslip = $actualRow['milkslip'];
                                            $actualActual = $actualRow['actual'];
                                            $formattedDate = $actualRow['formattedDate'];

                                            echo "<tr>";
                                            echo "<td class='text-center'>$formattedDate</td>"; // Displaying formatted date column without time
                                            echo "<td class='text-center'>$actualMember</td>";
                                            echo "<td class='text-center'>$actualCarabaoName</td>";
                                            echo "<td class='text-center'>$actualMilkslip L</td>";
                                            echo "<td class='text-center'>$actualActual L</td>";

                                            echo "<td class='text-center'> 
                     
                                        <a href='?delete=$actualId' onclick='return confirm(\"Are you sure you want to delete this data?\")'><i class='fa fa-trash-o'></i></a>
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
                        <div class="card-title-body"></div>
                        <section class="example">
                            <br>
                            <br>
                            <h3>All Received:</h3>
                            <br>
                            <form id="searchForm" method="GET" action="">
                                <label for="search">Search:</label>
                                <input type="text" name="search" id="search" value="<?php echo $search; ?>">
                                <button type="submit" class="btn btn-primary btn-sm rounded-s">Search</button>
                                <a href="?page=1" class="btn btn-warning btn-sm rounded-s">Cancel Search</a>
                            </form>

                            <br>
                            <br>


                            <div class="table-container" style="max-height: 400px; overflow-y: auto;">

                                <table class="table table-bordered col-md-12">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Cooperator</th>
                                            <th>Carabao ID</th>
                                            <th>Milk Recieved</th>
                                            <th>Actual Recieved</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBodyWithActual">
                                        <?php
                                        // Assuming you have another query to fetch the actual data, modify the query accordingly
                                        $actualQuery = "SELECT p.transaction_id, p.member_id, m.firstname, m.lastname, p.carabao_id, c.name AS carabaoName, p.milkslip, p.actual, p.date
FROM produced p
INNER JOIN member m ON p.member_id = m.id
LEFT JOIN carabaos c ON p.carabao_id = c.id
WHERE p.actual != 0
ORDER BY p.date DESC
LIMIT $limit OFFSET $offset";

                                        $actualResult = mysqli_query($conn, $actualQuery);

                                        if (!$actualResult) {
                                            die("Query failed: " . mysqli_error($conn));
                                        }

                                        while ($actualRow = mysqli_fetch_assoc($actualResult)) {
                                            $actualId = $actualRow['transaction_id'];
                                            $actualMember = $actualRow['firstname'] . ' ' . $actualRow['lastname'];
                                            $actualCarabaoName = $actualRow['carabaoName'];
                                            $actualMilkslip = $actualRow['milkslip'];
                                            $actualActual = $actualRow['actual'];
                                            $actualDate = $actualRow['date']; // Added date column

                                            echo "<tr>";
                                            echo "<td class='text-center'>$actualDate</td>"; // Displaying date column
                                            echo "<td class='text-center'>$actualMember</td>";
                                            echo "<td class='text-center'>$actualCarabaoName</td>";
                                            echo "<td class='text-center'>$actualMilkslip L</td>";
                                            echo "<td class='text-center'>$actualActual L</td>";

                                            echo "<td class='text-center'> 
       
        <a href='?delete=$actualId' onclick='return confirm(\"Are you sure you want to delete this data?\")'><i class='fa fa-trash-o'></i></a>
    </td>";
                                            echo "</tr>";
                                        }
                                        ?>

                                    </tbody>
                                </table>





                            </div>
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
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>

</body>

</html>