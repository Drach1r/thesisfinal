<?php
include 'header.php';
include 'db_connect.php';
include 'sidebar.php';

// Conversion function to convert different units to grams or milliliters
function convertToGramsOrMilliliters($quantity, $unit)
{
    switch ($unit) {
        case 'g':
            return $quantity / 1000; // Convert grams to kilograms
        case 'Kg':
            return $quantity; // Already in kilograms
        case 'ml':
            return $quantity / 1000; // Convert milliliters to liters
        case 'L':
            return $quantity; // Already in liters
        default:
            return $quantity; // Default to return the quantity as is
    }
}

?>

<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">
                        Raw Materials Inventory
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
                                        <th>Raw Materials ID</th>
                                        <th>Product Name</th>
                                        <th>Stock In</th>
                                        <th>Stock Out (Used in Production)</th>
                                        <th>Current Available Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $rawmaterialsResult = $conn->query("
                                        SELECT * FROM rawmaterials
                                        ORDER BY RawMaterialID ASC
                                    ");

                                    if (!$rawmaterialsResult) {
                                        die("Query failed: " . mysqli_error($conn));
                                    }

                                    while ($row = $rawmaterialsResult->fetch_assoc()) {
                                        $rawMaterialID = $row['RawMaterialID'];

                                        // Calculate total stock in
                                        $stockInQuery = $conn->query("SELECT SUM(stock_in) AS totalStockIn, unit FROM stock WHERE RawMaterialID = $rawMaterialID");
                                        $stockInRow = $stockInQuery->fetch_assoc();
                                        $totalStockIn = isset($stockInRow['totalStockIn']) ? $stockInRow['totalStockIn'] : 0;
                                        $unitIn = isset($stockInRow['unit']) ? $stockInRow['unit'] : $row['Unit']; // Use $unitIn for stock_in

                                        // Convert stock in to grams or liters
                                        $totalStockInConverted = convertToGramsOrMilliliters($totalStockIn, $unitIn);

                                        // Calculate total stock out
                                        $stockOutQuery = $conn->query("SELECT SUM(stock_out) AS totalStockOut, unit FROM stock WHERE RawMaterialID = $rawMaterialID");
                                        $stockOutRow = $stockOutQuery->fetch_assoc();
                                        $totalStockOut = isset($stockOutRow['totalStockOut']) ? $stockOutRow['totalStockOut'] : 0;
                                        $unitOut = isset($stockOutRow['unit']) ? $stockOutRow['unit'] : $row['Unit']; // Use $unitOut for stock_out

                                        // Convert stock out to grams or liters
                                        $totalStockOutConverted = convertToGramsOrMilliliters($totalStockOut, $unitOut); // Use $unitOut for conversion

                                        // Calculate current stock
                                        $currentStock = $totalStockInConverted - $totalStockOutConverted;

                                        // Determine the appropriate display unit
                                        $stockInDisplayUnit = $unitIn === 'g' ? 'Kg' : 'L';
                                        // Calculate current stock display unit based on the unit of stock_out
                                        $stockOutDisplayUnit = $unitOut === 'g' ? 'Kg' : 'L';
                                        // Determine the appropriate display unit for current stock based on the unit of stock_in
                                        $currentStockDisplayUnit = $unitIn === 'g' ? 'Kg' : 'L';

                                        // Display stock in, stock out, and current stock in the appropriate units
                                        $stockInDisplay = number_format($totalStockInConverted, 3) . ' ' . $stockInDisplayUnit;
                                        // Display stock out in the appropriate unit
                                        $stockOutDisplay = number_format($totalStockOutConverted, 3) . ' ' . $stockOutDisplayUnit; // Use $currentStockDisplayUnit for display
                                        $currentStockDisplay = number_format($currentStock, 3) . ' ' . $currentStockDisplayUnit;

                                        // Output the table row
                                        echo "<tr>";
                                        echo "<td class='text-center'>$rawMaterialID</td>";
                                        echo "<td>{$row['Name']}</td>";
                                        echo "<td class='text-right'>$stockInDisplay</td>";
                                        echo "<td class='text-right'>$stockOutDisplay</td>";
                                        echo "<td class='text-right'><strong>$currentStockDisplay</strong></td>";
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
</article>