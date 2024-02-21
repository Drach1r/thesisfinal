<?php
include 'header.php';
include 'sidebar.php';
include 'db_connect.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linear Programming Tables</title>

</head>
<article class="content items-list-page">
    <div class="title-search-block"> </div>

    <div class="title-block">
        <div class="row">

            <div class="col-sm-6">
                <h3 class="title">
                    Optimization
                </h3>

            </div>


        </div>

    </div>

    <body>

        <section class="row">
            <div class="card col-lg-12">
                <div class="card-body">
                    <div class="card-body">
                        <div class="card-title-body">
                            <br>
                            <table class="table table-bordered col-md-12">
                                <tr>
                                    <th>Decision Variables</th>
                                    <?php
                                    // Query to fetch product details from the productlist table
                                    $sql = "SELECT ProductID, name, price, batch_amount FROM productlist";
                                    $result = mysqli_query($conn, $sql);

                                    // Check if there are any products
                                    if (mysqli_num_rows($result) > 0) {
                                        echo "<tr>";
                                        echo "<th>Products</th>";
                                        // Output data of each row
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            // Output product name as a table header
                                            echo "<th data-product-id='" . $row['ProductID'] . "'>" . $row['name'] . "</th>";
                                        }
                                        echo "<th>₱100,698.00</th>";
                                        echo "<th>Revenue</th>";
                                        echo "</tr>";

                                        // Output row for price
                                        echo "<tr>";
                                        echo "<td>Price</td>";
                                        mysqli_data_seek($result, 0); // Rewind result set to start from the beginning
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            // Output price as table data
                                            echo "<td>" . $row['price'] . "</td>";
                                        }
                                        echo "<th>₱12,056.55</th>"; // Assuming this is a fixed value
                                        echo "<th>Expenses</th>";
                                        echo "</tr>";

                                        // Output row for batch amount
                                        echo "<tr>";
                                        echo "<td>Amount Per Batch</td>";
                                        mysqli_data_seek($result, 0); // Rewind result set to start from the beginning
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            // Output batch amount as table data
                                            echo "<td class='batch_amount' data-product-id='" . $row['ProductID'] . "'>" . $row['batch_amount'] . "</td>";
                                        }
                                        echo "<th>₱88,641.45</th>"; // Assuming this is a fixed value
                                        echo "<th>Profit</th>";
                                        echo "</tr>";

                                        // Output row for batch input fields
                                        echo "<tr>";
                                        echo "<td>Batch</td>";
                                        mysqli_data_seek($result, 0); // Rewind result set to start from the beginning
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            // Output batch input field for each product
                                            echo "<td><input type='number' class='quantity_input' style='border: none;' value='1' data-product-id='" . $row['ProductID'] . "' data-batch-amount='" . $row['batch_amount'] . "'></td>
                                            ";
                                        }
                                        echo "</tr>";
                                    }
                                    ?>
                                </tr>

                            </table>


                        </div>
                    </div>
                </div>
            </div>
        </section>


        <br>
        <br>


        <section class="row">
            <div class="card col-lg-12">
                <div class="card-body">
                    <div class="card-body">
                        <div class="card-title-body">
                            <br>
                            <table class="table table-bordered col-md-12">
                                <h2>Raw Materials (Quantity/Batch)</h2>
                                <br>
                                <tr>
                                    <th>Raw Material</th>
                                    <?php
                                    // Query to fetch product details from the productlist table
                                    $sql = "SELECT ProductID, name FROM productlist";
                                    $result = mysqli_query($conn, $sql);

                                    // Check if there are any products
                                    if (mysqli_num_rows($result) > 0) {
                                        // Output data of each row
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            // Output product name and ProductID as a table header
                                            echo "<th data-product-id='" . $row['ProductID'] . "'>" . $row['name'] . "</th>";
                                        }
                                    }
                                    ?>
                                    <th>Total Ingredients</th>
                                    <th></th>
                                    <th>Inventory</th>
                                </tr>
                                <?php
                                // Query to fetch raw materials and their quantities
                                $raw_materials_sql = "SELECT rm.Name, IFNULL(b.QuantityRequired, 0) AS QuantityRequired, b.qty_unit, IFNULL(b.ProductID, '') AS ProductID, rm.RawMaterialID
    FROM rawmaterials rm
    LEFT JOIN bom b ON rm.RawMaterialID = b.RawMaterialID";

                                $raw_materials_result = mysqli_query($conn, $raw_materials_sql);
                                $raw_materials = array();

                                // Initialize array to hold quantities for each product, setting all values to 0
                                $product_quantities = array();
                                $sql = "SELECT ProductID FROM productlist";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $product_quantities[$row['ProductID']] = 0;
                                }

                                // Organize raw materials by name
                                while ($row = mysqli_fetch_assoc($raw_materials_result)) {
                                    $raw_materials[$row['Name']][] = $row;
                                }

                                // Output raw materials and their quantities for each product
                                foreach ($raw_materials as $raw_material => $quantities) {
                                    echo "<tr>";
                                    echo "<td>$raw_material</td>";

                                    // Copy the initialized product quantities array
                                    $material_quantities = $product_quantities;

                                    foreach ($quantities as $data) {
                                        $quantity = $data['QuantityRequired'];
                                        $unit = $data['qty_unit'];

                                        // Convert ml to liters and g to kg
                                        if ($unit === 'ml' && $quantity !== 0) {
                                            $converted_quantity = $quantity / 1000; // Convert ml to liters
                                        } elseif ($unit === 'g' && $quantity !== 0) {
                                            $converted_quantity = $quantity / 1000; // Convert g to kg
                                        } else {
                                            // If quantity is zero, set converted_quantity to zero to prevent division by zero error
                                            $converted_quantity = 0;
                                        }

                                        // Add the converted quantity to the array
                                        $product_id = isset($data['ProductID']) ? $data['ProductID'] : ''; // Check if 'ProductID' key exists
                                        if (array_key_exists($product_id, $product_quantities)) { // Check if product ID exists in the quantities array
                                            $material_quantities[$product_id] += $converted_quantity;
                                        }
                                    }


                                    // Output quantities for the current raw material
                                    foreach ($material_quantities as $productID => $quantity) {
                                        // Check if quantity is null and output accordingly
                                        if ($quantity === null) {
                                            echo "<td class='quantity_required' data-product-id='$productID'>0</td>"; // Output zero if quantity is null
                                        } else {
                                            echo "<td class='quantity_required' data-product-id='$productID'>$quantity</td>"; // Output the quantity if not null
                                        }
                                    }


                                    // Calculate and display the total ingredients for the current row
                                    $total_ingredients = array_sum($material_quantities);
                                    echo "<td>$total_ingredients</td>";

                                    // Leave the Constraint column blank
                                    echo "<td>&#8804;</td>";

                                    // Fetch and calculate the current stock for the current raw material
                                    $rawMaterialID = $quantities[0]['RawMaterialID']; // Assuming RawMaterialID is available in your loop
                                    $stock_query = "SELECT IFNULL(SUM(stock_in), 0) AS total_stock_in, IFNULL(SUM(stock_out), 0) AS total_stock_out FROM stock WHERE RawMaterialID = $rawMaterialID";
                                    $stock_result = mysqli_query($conn, $stock_query);
                                    $stock_data = mysqli_fetch_assoc($stock_result);

                                    // Calculate the current stock
                                    $total_stock_in_kg = $stock_data['total_stock_in'] / 1000; // Convert stock in to kg
                                    $total_stock_out_kg = $stock_data['total_stock_out'] / 1000; // Convert stock out to kg
                                    $current_stock_kg = $total_stock_in_kg - $total_stock_out_kg;

                                    // Output the current stock in kilograms
                                    echo "<td>$current_stock_kg</td>";

                                    echo "</tr>";
                                }

                                ?>
                            </table>






                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script>
            document.querySelectorAll('.quantity_input').forEach(function(input) {
                input.addEventListener('input', function() {
                    var batchAmount = parseFloat(this.dataset.batchAmount);
                    var quantity = parseFloat(this.value);
                    var result = batchAmount * quantity;

                    // Get the ProductID from the input element's dataset
                    var productID = this.dataset.productId;

                    // Update the value in the respective cell using the ProductID
                    document.querySelectorAll('.batch_amount[data-product-id="' + productID + '"]').forEach(function(element) {
                        element.textContent = result.toFixed(2);
                    });

                    // Update the Quantity Required based on batch quantity using the ProductID
                    document.querySelectorAll('.quantity_required[data-product-id="' + productID + '"]').forEach(function(element) {
                        var initialQuantity = parseFloat(element.dataset.originalQuantity);
                        var unit = element.dataset.unit;
                        var updatedQuantity;

                        // Check if quantity is a valid number
                        if (!isNaN(quantity) && isFinite(quantity)) {
                            // Convert ml to liters and g to kg
                            if (quantity === 0) {
                                // If quantity is zero, leave updatedQuantity as zero
                                updatedQuantity = 0;
                            } else if ((unit === 'ml' || unit === 'g') && quantity !== 0) {
                                // Convert ml to liters and g to kg
                                updatedQuantity = (initialQuantity / 1000) * quantity;
                            } else {
                                // Leave updatedQuantity as it is
                                updatedQuantity = initialQuantity;
                            }
                            element.textContent = updatedQuantity.toFixed(2);
                        } else {
                            // Handle invalid quantity (e.g., log an error or provide a default value)
                            console.error('Invalid quantity:', quantity);
                            // You might choose to set a default value instead
                            element.textContent = "Invalid Quantity";
                        }
                    });



                });
            });
        </script>

        <br>


        <section class="row">
            <div class="card col-lg-12">
                <div class="card-body">
                    <div class="card-body">
                        <div class="card-title-body">
                            <br>
                            <h2>Raw Materials (COST)</h2>
                            <table class="table table-bordered col-md-12">
                                <tr>
                                    <th>Raw Material</th>
                                    <th>x1</th>
                                    <th>x2</th>
                                    <th>x3</th>
                                    <th>x4</th>
                                    <th>x5</th>
                                    <th>x6</th>
                                    <th>x7</th>
                                    <th>x8</th>
                                    <th>x9</th>
                                    <th></th>
                                    <th>Constraint</th>
                                </tr>
                                <tr>
                                    <td>FRESH MILK L</td>
                                    <td>₱0.08</td>
                                    <td>₱0.08</td>
                                    <td>₱0.08</td>
                                    <td>₱0.08</td>
                                    <td>₱0.08</td>
                                    <td>₱0.08</td>
                                    <td>₱0.08</td>
                                    <td>₱0.08</td>
                                    <td>₱2.28</td>
                                    <td>&#8804;</td>
                                    <td> ₱80.00</td>
                                </tr>
                                <tr>
                                    <td>WATER L</td>
                                    <td>₱0.55</td>
                                    <td>₱0.55</td>
                                    <td>₱0.55</td>
                                    <td>₱0.55</td>
                                    <td>₱0.55</td>
                                    <td>₱0.55</td>
                                    <td>₱0.55</td>
                                    <td>₱0.55</td>
                                    <td>₱77.15</td>
                                    <td>&#8804;</td>
                                    <td> ₱550.00</td>

                                </tr>

                                <tr>
                                    <th colspan="9">Total Expenses (Raw Materials)</th>
                                    <td style="background-color: yellow;">₱2,576.55</td>
                                </tr>
                                <tr>
                                    <th colspan="9">Total Expenses (Other Constraints)</th>
                                    <td style="background-color: yellow;">₱9,480.00</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <br>
        <br>

        <section class="row">
            <div class="card col-lg-12">
                <div class="card-body">
                    <div class="card-body">
                        <div class="card-title-body">
                            <br>
                            <table class="table table-bordered col-md-12">

                                <h2>Other Constraints (Approximate Cost / month)</h2>

                                <tr>
                                    <th>Other Constraints (Approximate Cost / month)</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <td>ELECTRICITY</td>
                                    <td>
                                        <= 530</td>
                                </tr>
                                <tr>
                                    <td>PACKAGING</td>
                                    <td>
                                        <= 2,200</td>
                                </tr>
                                <tr>
                                    <td>LABOR COST</td>
                                    <td>
                                        <= 450 / 6,750</td>
                                </tr>
                                <tr>
                                    <td>LABOR HOURS</td>
                                    <td>
                                        <= 8</td>
                                </tr>
                                <tr>
                                    <td>EMPLOYEES</td>
                                    <td>
                                        <= 15</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>



    </body>
</article>

</html>