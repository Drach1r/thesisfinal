<?php
include 'header.php';
include 'sidebar.php';
include 'db_connect.php';

// Fetch data from the product_stock table with product details
$query = "SELECT ps.ProductID, pl.Name as ProductName, pl.measure, pl.Unit, pl.pack,
                 SUM(ps.stock_in) as total_stock_in,
                 SUM(ps.stock_out) as total_stock_out
          FROM product_stock ps
          LEFT JOIN productlist pl ON ps.ProductID = pl.ProductID
          GROUP BY ps.ProductID";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

?>

<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">
                        Inventory
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
                        <div class="card-title-body"></div>
                        <section class="example">
                            <table class="table table-bordered col-md-12">
                                <thead>
                                    <tr>
                                        <th>Product ID</th>
                                        <th>Product Name</th>
                                        <th>Stock In</th>
                                        <th>Stock Out</th>
                                        <th>Current Stock</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Iterate through the result set and populate the table rows
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>{$row['ProductID']}</td>";
                                        echo "<td>{$row['ProductName']}</td>";

                                        // Combine 'batch_amount' with 'pack' for 'stock_in' and 'stock_out'
                                        $stockIn = $row['total_stock_in'] . ' ' . $row['pack'];
                                        $stockOut = $row['total_stock_out'] . ' ' . $row['pack'];

                                        // Explicitly cast to decimal in PHP
                                        $stockInAmount = floatval($row['total_stock_in']);
                                        $stockOutAmount = floatval($row['total_stock_out']);
                                        $currentStockAmount = $stockInAmount - $stockOutAmount;
                                        $currentStock = $currentStockAmount . ' ' . $row['pack'];

                                        echo "<td>{$stockIn}</td>";
                                        echo "<td>{$stockOut}</td>";
                                        echo "<td><strong>{$currentStock}</strong></td>";

                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    include 'footer.php';
    ?>