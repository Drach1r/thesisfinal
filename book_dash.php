<?php
include 'header.php';
include 'sidebar.php';
include 'db_connect.php';

// Assuming you have a connection to the database in db_connect.php
// Replace 'member' with the actual table name
// Count only members with status not equal to 'Pending'
$queryMembers = "SELECT COUNT(id) AS member_count FROM member WHERE stat <> 'Pending'";
$resultMembers = mysqli_query($conn, $queryMembers);

if ($resultMembers) {
    $rowMembers = mysqli_fetch_assoc($resultMembers);
    $memberCount = $rowMembers['member_count'];
} else {
    // Handle the error if the query fails
    $memberCount = "Error fetching member count";
}


// Fetch count of members with 'Pending' status
$queryPending = "SELECT COUNT(id) AS pendingCount FROM member WHERE stat = 'Pending'";
$resultPending = mysqli_query($conn, $queryPending);

if ($resultPending) {
    $rowPending = mysqli_fetch_assoc($resultPending);
    $pendingCount = $rowPending['pendingCount'];
} else {
    // Handle the error if the query fails
    $pendingCount = "Error fetching pending count";
}

$queryCustomers = "SELECT COUNT(CustomerID) AS customer_count FROM customers";
$resultCustomers = mysqli_query($conn, $queryCustomers);

if ($resultCustomers) {
    $rowCustomers = mysqli_fetch_assoc($resultCustomers);
    $customerCount = $rowCustomers['customer_count'];
} else {
    // Handle the error if the query fails
    $customerCount = "Error fetching customer count";
}

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
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Registered Members</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $memberCount; ?></div>
                    </div>
                    <i class="fa-solid fa-users" style="font-size: 1em;"></i>
                    <div class="col-auto"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-4 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pending For Approval </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $pendingCount; ?></div>
                    </div>
                    <i class="fa-solid fa-user-plus" style="font-size: 1em;"></i>
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

    <div class="col-xl-3 col-md-4 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Customers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $customerCount; ?></div>
                    </div>
                    <i class="fa-solid fa-person-circle-plus" style="font-size: 1em;"></i>
                    <div class="col-auto"></div>
                </div>
            </div>
        </div>
    </div>

    <?php
    // Replace 'sales' with the actual table name
    $queryTotalSales = "SELECT SUM(TotalAmount) AS total_sales FROM sales";
    $resultTotalSales = mysqli_query($conn, $queryTotalSales);

    if ($resultTotalSales) {
        $rowTotalSales = mysqli_fetch_assoc($resultTotalSales);
        $total_sales = $rowTotalSales['total_sales'];
    } else {
        // Handle the error if the query fails
        $total_sales = "Error fetching total sales";
    }
    ?>
    <div class="col-xl-3 col-md-4 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Sales</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">₱<?php echo $total_sales; ?></div>

                    </div>
                    <i class="fa-solid fa-money-bill-trend-up" style="font-size: 1em;"></i>

                    <div class="col-auto"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-4 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sales</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $salesCount; ?></div>
                    </div>
                    <i class="fa-solid fa-check" style="font-size: 1em;"></i>
                    <div class="col-auto"></div>
                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="col col-xs-12 col-sm-12 col-md-6 col-xl-7 history-col">
        <div class="card sameheight-item" data-exclude="xs">
            <div class="card-header card-header-sm bordered">
                <div class="header-block">
                    <h3 class="title">Sales </h3>
                </div>
                <ul class="nav nav-tabs pull-right" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#dashboard-monthly-chart" role="tab" data-toggle="tab">Monthly</a>
                    </li>
                </ul>
            </div>
            <div class="card-block">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active fade in" id="dashboard-monthly-chart">
                        <p class="title-description">
                            Sales Results last 30 days
                        </p>
                        <div id="dashboard-monthly-chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>

<script>
    $(document).ready(function() {
        // Function to fetch monthly sales data from the server
        function fetchMonthlySalesData() {
            $.ajax({
                url: 'monthly_chart.php', // Replace with the actual path to your server script
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Update the Morris Line Chart with the fetched data
                        updateMonthlyChart(response.data);
                    } else {
                        console.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching monthly sales data:", error);
                }
            });
        }

        // Initial fetch and chart update
        fetchMonthlySalesData();

        // Function to update the Morris Line Chart with new data
        function updateMonthlyChart(data) {
            Morris.Line({
                element: 'dashboard-monthly-chart',
                data: data,
                xkey: 'y',
                ykeys: ['a'],
                labels: ['Sales'],
                lineColors: ['#3c8dbc'],
                parseTime: false,
                hideHover: 'auto',
                resize: true,
                pointSize: 6,
                gridTextSize: 12,
                gridTextFamily: 'Arial, sans-serif',
                gridTextSpacing: 50,
            });
        }
    });
</script>