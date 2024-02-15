<?php
include 'header.php';
include 'sidebar.php';
?>

<style>
    .card-body {
        font-size: 16px;
        padding: 20px;
    }
</style>

<article class="content item-editor-page">
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


    <?php
    // Replace 'sales' with the actual table name
    $queryTotalOrders = "SELECT COUNT(saleID) AS total_orders FROM sales";
    $resultTotalOrders = mysqli_query($conn, $queryTotalOrders);

    if ($resultTotalOrders) {
        $rowTotalOrders = mysqli_fetch_assoc($resultTotalOrders);
        $total_orders = $rowTotalOrders['total_orders'];
    } else {
        // Handle the error if the query fails
        $total_orders = "Error fetching total order count";
    }
    ?>
    <div class="col-xl-3 col-md-4 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Order</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_orders; ?></div>
                    </div>
                    <i class="fa-solid fa-boxes-stacked" style="font-size: 1em;"></i>
                    <div class="col-auto"></div>
                </div>
            </div>
        </div>
    </div>

    <?php

    $query = "SELECT SUM(quantity) AS totalQuantity FROM salesitems";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $totalQuantity = $row['totalQuantity'];
    } else {
        // Handle the query error if needed
        $totalQuantity = 0;
    }

    ?>

    <div class="col-xl-3 col-md-4 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Product Sold</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalQuantity; ?></div>
                    </div>
                    <i class="fa-solid fa-pen-clip" style="font-size: 1em;"></i>
                    <div class="col-auto"></div>
                </div>
            </div>
        </div>
    </div>

    <?php
    // Replace 'customers' with the actual table name
    $queryCustomers = "SELECT COUNT(CustomerID) AS customer_count FROM customers";
    $resultCustomers = mysqli_query($conn, $queryCustomers);

    if ($resultCustomers) {
        $rowCustomers = mysqli_fetch_assoc($resultCustomers);
        $customer_count = $rowCustomers['customer_count'];
    } else {
        // Handle the error if the query fails
        $customer_count = "Error fetching Customer count";
    }
    ?>
    <div class="col-xl-3 col-md-4 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Customers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $customer_count; ?></div>
                    </div>
                    <i class="fa-solid fa-users" style="font-size: 1em;"></i>
                    <div class="col-auto"></div>
                </div>
            </div>
        </div>
    </div>


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

    <section class="section">
        <div class="row">
            <div class="card col-lg-5" style="height: 500px;">
                <div class="card-body">
                    <div class="card-body">

                        <div class="card-title-body">
                            <h3 class="title">
                                Most Popular Products
                            </h3>
                        </div>
                        <div class="flot-chart">
                            <div id="flot-pie-chart-legend"></div>
                            <div id="flot-pie-chart" style="height: 300px;"></div>

                        </div>
                    </div>


                </div>
            </div>



        </div>
    </section>

    <script>
        $(document).ready(function() {
            // Fetch and display product data
            $.ajax({
                url: 'most_popular_product.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('Response:', response);

                    if (response.success) {
                        // Display the pie chart
                        displayPieChart(response.products);
                    } else {
                        console.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching product data:", error);
                }
            });

            // Function to display the pie chart
            function displayPieChart(products) {
                var data = [];

                // Prepare data for the pie chart and calculate total quantity
                var totalQuantity = 0;
                for (var i = 0; i < products.length; i++) {
                    var quantity = parseInt(products[i].quantity);
                    totalQuantity += quantity;

                    data.push({
                        label: products[i].productName,
                        data: [
                            [i, quantity]
                        ] // Ensure data is in the format [index, value]
                    });
                }

                console.log('Data for Pie Chart:', data);
                // Function to format label with quantity
                // Function to format label with quantity
                function labelFormatter(label, series) {
                    var quantity = series.data[0][1];

                    // Check if quantity is a valid number
                    if (!isNaN(quantity)) {
                        return '<div style="font-size:8pt; text-align:center; padding:2px; color: black;">' +
                            label + '<br>Quantity: ' + quantity + '</div>';
                    }

                    // Default label if quantity is not valid
                    return '<div style="font-size:8pt; text-align:center; padding:2px; color: black;">' +
                        label + '<br>Quantity: N/A</div>';
                }



                // Options for the pie chart
                var options = {
                    series: {
                        pie: {
                            show: true,
                            label: {
                                show: true,
                                radius: 1,
                                formatter: labelFormatter
                            }
                        }
                    },
                    legend: {
                        show: true,
                        container: $('#flot-pie-chart-legend')
                    }
                };

                // Generate the pie chart
                console.log('Before Plotting:', data);
                $.plot($("#flot-pie-chart"), data, options);
                console.log('After Plotting:', data);
            }
        });
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