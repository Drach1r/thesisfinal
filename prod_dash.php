<?php
include 'header.php';
include 'sidebar.php';
include 'db_connect.php';

$query = "SELECT actual AS daily_milk_received FROM produced WHERE date = (SELECT MAX(date) FROM produced)";

$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $dailyMilkReceived = $row['daily_milk_received'];
} else {
    // Handle the error if the query fails
    $dailyMilkReceived = "Error fetching daily milk received";
}
// Assuming you have a connection to the database in db_connect.php
$query = "SELECT SUM(actual) AS monthly_milk_received FROM produced WHERE MONTH(date) = MONTH(NOW())";

$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $monthlyMilkReceived = $row['monthly_milk_received'];
} else {
    // Handle the error if the query fails
    $monthlyMilkReceived = "Error fetching monthly milk received";
}
?>


<style>
    .card-body {
        font-size: 16px;
        padding: 20px;
    }
</style>


<article class="content item-editor-page">
    <div class="col-xl-3 col-md-4 mb-4 ">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Daily Milk Received</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $dailyMilkReceived; ?>L</div>
                    </div>
                    <i class="fa-solid fa-bottle-droplet" style="font-size: 1em;"></i>
                    <div class="col-auto"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-4 mb-4 ">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Monthly Milk Received</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $monthlyMilkReceived; ?>L</div>
                    </div>
                    <i class="fa-solid fa-bottle-droplet" style="font-size: 1em;"></i>
                    <div class="col-auto"></div>
                </div>
            </div>
        </div>
    </div>
    <?php
    // Assuming you have a connection to the database in db_connect.php
    $query = "SELECT SUM(actual) AS yearly_milk_received FROM produced WHERE YEAR(date) = YEAR(NOW())";

    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $yearlyMilkReceived = $row['yearly_milk_received'];
    } else {
        // Handle the error if the query fails
        $yearlyMilkReceived = "Error fetching yearly milk received";
    }
    ?>
    <div class="col-xl-3 col-md-4 mb-4 ">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Yearly Milk Received</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $yearlyMilkReceived; ?>L</div>
                    </div>
                    <i class="fa-solid fa-bottle-droplet" style="font-size: 1em;"></i>
                    <div class="col-auto"></div>
                </div>
            </div>
        </div>
    </div>

    <?php

    $queryPurchases = "SELECT SUM(p_amount) AS total_purchase_amount FROM purchases WHERE MONTH(PurchaseDate) = MONTH(CURRENT_DATE())";
    $resultPurchases = mysqli_query($conn, $queryPurchases);

    if ($resultPurchases) {
        $rowPurchases = mysqli_fetch_assoc($resultPurchases);
        $totalPurchaseAmount = $rowPurchases['total_purchase_amount'];
    } else {
        // Handle the error if the query fails
        $totalPurchaseAmount = "Error fetching total purchase amount";
    }
    ?>

    <div class="col-xl-3 col-md-4 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Monthly Purchase </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">₱<?php echo $totalPurchaseAmount; ?></div>
                    </div>
                    <i class="fa-solid fa-money-bill" style="font-size: 1em;"></i>
                    <div class="col-auto"></div>
                </div>
            </div>
        </div>
    </div>


    <?php

    $queryCustomers = "SELECT COUNT(CustomerID) AS customer_count FROM customers";
    $resultCustomers = mysqli_query($conn, $queryCustomers);

    if ($resultCustomers) {
        $rowCustomers = mysqli_fetch_assoc($resultCustomers);
        $customerCount = $rowCustomers['customer_count'];
    } else {
        // Handle the error if the query fails
        $customerCount = "Error fetching customer count";
    }
    ?>
    <div class="col-xl-3 col-md-4 mb-4">

        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Rawmaterials</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $customerCount; ?></div>
                    </div>
                    <i class="fa-solid fa-pen-clip" style="font-size: 1em;"></i>
                    <div class="col-auto"></div>
                </div>
            </div>
        </div>

    </div>

    <?php
    // Replace 'sales' with the actual table name
    $querySales = "SELECT COUNT(SaleID) AS sales_count FROM sales";
    $resultSales = mysqli_query($conn, $querySales);

    if ($resultSales) {
        $rowSales = mysqli_fetch_assoc($resultSales);
        $salesCount = $rowSales['sales_count'];
    } else {
        // Handle the error if the query fails
        $salesCount = "Error fetching sales count";
    }
    ?>
    <div class="col-xl-3 col-md-4 mb-4">

        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sales</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $salesCount; ?></div>
                    </div>
                    <i class="fa-solid fa-boxes-stacked" style="font-size: 1em;"></i>
                    <div class="col-auto"></div>
                </div>
            </div>
        </div>

    </div>
    <?php


    // SQL query to retrieve the most popular products based on sales items
    $query = "SELECT ProductID, COUNT(*) AS total_sales
          FROM salesitems
          GROUP BY ProductID
          ORDER BY total_sales DESC";

    // Execute the query
    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if ($result) {
    ?>
        <table>
            <tr>
                <th>Product ID</th>
                <th>Total Sales</th>
            </tr>
            <?php
            // Fetch and display the results
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['ProductID'] . "</td>";
                echo "<td>" . $row['total_sales'] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        </body>

        </html>
    <?php
    } else {
        // Handle the error if the query fails
        echo "Error: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
    ?>

    <br>
</article>