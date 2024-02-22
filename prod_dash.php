<?php
include 'header.php';
include 'sidebar.php';
include 'db_connect.php';





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
    <div class="col-xl-3 col-md-4 mb-4 ">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1"> Daily Milk Received</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalDailyMilkReceived; ?>L</div>
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
    // Assuming you have a connection to the database in db_connect.php

    $query = "SELECT SUM(actual) AS total_milk_received FROM produced";

    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $totalMilkReceived = $row['total_milk_received'];
    } else {
        // Handle the error if the query fails
        $totalMilkReceived = "Error fetching total milk received";
    }
    ?>

    <div class="col-xl-3 col-md-4 mb-4 ">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Milk Received</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalMilkReceived; ?>L</div>
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
    // Assuming you have a connection to the database in db_connect.php

    // Query to get the total count of raw materials
    $query = "SELECT COUNT(RawMaterialID) AS rawmaterial_count FROM rawmaterials";

    // Execute the query
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Fetch the result row as an associative array
        $row = mysqli_fetch_assoc($result);
        // Extract the raw material count from the result
        $rawMaterialsTotal = $row['rawmaterial_count'];
    } else {
        // Handle the error if the query fails
        $rawMaterialsTotal = "Error fetching raw material count";
    }
    ?>

    <div class="col-xl-3 col-md-4 mb-4">

        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Rawmaterials</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $rawMaterialsTotal; ?></div>
                    </div>
                    <i class="fa-solid fa-pen-clip" style="font-size: 1em;"></i>
                    <div class="col-auto"></div>
                </div>
            </div>
        </div>

    </div>


    <?php
    // Assuming you have a connection to the database in db_connect.php

    // Query to get the total count of products
    $query = "SELECT COUNT(ProductID) AS product_count FROM productlist";

    // Execute the query
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Fetch the result row as an associative array
        $row = mysqli_fetch_assoc($result);
        // Extract the product count from the result
        $totalProducts = $row['product_count'];
    } else {
        // Handle the error if the query fails
        $totalProducts = "Error fetching product count";
    }
    ?>

    <div class="col-xl-3 col-md-4 mb-4">

        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Products</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalProducts; ?></div>
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



    <section class="">
        <div class="card col-lg-5" style=" margin-top: 60px;">
            <div class="card-body">
                <div class="card-body">
                    <div class="card-title-body">
                        <br>
                        <table class="table table-bordered col-md-12">
                            <h4>Most Popular Product:</h4>
                            <tr>
                                <th>Rank</th>
                                <th>Product Name</th>
                                <th>Total Sales</th>
                                <th>Total Quantity</th>

                            </tr>

                            <?php
                            // SQL query to retrieve the most popular products based on sales items
                            $query = "SELECT p.Name, COUNT(*) AS total_sales, SUM(s.Quantity) AS total_quantity
              FROM salesitems s
              JOIN productlist p ON s.ProductID = p.ProductID
              GROUP BY s.ProductID
              ORDER BY total_sales DESC";

                            // Execute the query
                            $result = mysqli_query($conn, $query);

                            // Check if the query was successful
                            if ($result) {
                                // Initialize rank counter
                                $rank = 1;
                                // Fetch and display the results
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    // Display the rank number
                                    echo "<td>" . $rank++ . "</td>";
                                    echo "<td>" . $row['Name'] . "</td>";
                                    echo "<td>" . $row['total_sales'] . "</td>";
                                    echo "<td>" . $row['total_quantity'] . "</td>";

                                    echo "</tr>";
                                }
                            } else {
                                // Handle the error if the query fails
                                echo "<tr>
                <td colspan='5'>Error: " . mysqli_error($conn) . "</td>
            </tr>";
                            }

                            // Close the database connection
                            mysqli_close($conn);
                            ?>
                        </table>



                    </div>
                </div>
            </div>
        </div>
    </section>


    <br>
</article>