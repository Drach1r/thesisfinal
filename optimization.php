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
            <div class="card col-lg-5">
                <div class="card-body">
                    <div class="card-body">
                        <div class="card-title-body">
                            <br>
                            <table class="table table-bordered col-md-12">
                                <h4>Results Based on Planning:</h4>
                                <br>
                                <tr>

                                    <?php
                                    echo "<tr>";
                                    echo "<th>Revenue</th>";
                                    echo "<th id='total_revenue'>₱<span id='revenue_value'>0.00</span></th>";
                                    echo "</tr>";
                                    echo "<tr>";
                                    echo "<th>Expenses</th>";
                                    echo "<th id='total-expenses'> ₱<span class='total-expenses-placeholder'></span></th>";
                                    echo "</tr>";
                                    echo "<tr>";
                                    echo "<th>Profit</th>";
                                    echo "<th id='profit' style='background-color: yellow;'>₱<span id='profit_value'>0.00</span></th>";

                                    echo "</tr>";
                                    ?>


                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
            </div>


            <div class="card col-lg-5" style="margin-left: 200px;">
                <div class="card-body">
                    <div class="card-body">
                        <div class="card-title-body">
                            <br>
                            <table class="table table-bordered col-md-12">
                                <h4>Results Based on Inventory:</h4>
                                <br>
                                <tr>

                                    <?php

                                    // Output the total current stock value in HTML table cell with background color
                                    echo "<tr>";
                                    echo "<th>Revenue</th>";
                                    echo "<th id='total_revenue'>₱0.00</th>";
                                    echo "</tr>";
                                    echo "<tr>";
                                    echo "<th>Expenses</th>";
                                    echo "<td style='background-color: skyblue;'></td>";

                                    echo "</tr>";
                                    echo "<tr>";
                                    echo "<th>Profit</th>";
                                    echo "<th>₱88,641.45</th>";
                                    echo "</tr>";



                                    ?>


                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

        </section>


        <section class="row">
            <div class="card col-lg-12">
                <div class="card-body">
                    <div class="card-body">
                        <div class="card-title-body">
                            <br>
                            <div class="table-responsive"> <!-- Add this div to make the table scrollable -->
                                <table class="table table-bordered col-md-12">
                                    <tr>
                                        <th colspan="2">Decision Variables</th>
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
                                            echo "</tr>";

                                            // Output row for batch input fields
                                            echo "<tr>";
                                            echo "<td>Batch</td>";

                                            // Rewind result set to start from the beginning
                                            mysqli_data_seek($result, 0);

                                            while ($row = mysqli_fetch_assoc($result)) {
                                                // Output batch input field for each product
                                                echo "<td><input type='number' class='quantity_input' style='border: none;' value='1' data-product-id='" . $row['ProductID'] . "' data-batch-amount='" . $row['batch_amount'] . "'></td>";
                                            }

                                            echo "</tr>";

                                            // Output row for price
                                            echo "<tr>";
                                            echo "<td>Price</td>";

                                            // Rewind result set to start from the beginning
                                            mysqli_data_seek($result, 0);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<td class='price'>" . $row['price'] . "</td>";
                                            }

                                            echo "</tr>";

                                            // Output row for batch amount
                                            echo "<tr>";

                                            echo "<td>Amount Per Batch</td>";
                                            mysqli_data_seek($result, 0); // Rewind result set to start from the beginning
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                // Output batch amount with appropriate class and any additional attributes if needed
                                                echo "<td class='batch_amount' data-product-id='" . $row['ProductID'] . "'>" . $row['batch_amount'] . "</td>";
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
                                <h2>Raw Materials Quantity per Batch</h2>
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
                                // Query to fetch raw materials and their quantities along with the unit
                                $raw_materials_sql = "SELECT rm.Name, rm.Unit, IFNULL(b.QuantityRequired, 0) AS QuantityRequired, b.qty_unit, IFNULL(b.ProductID, '') AS ProductID, rm.RawMaterialID
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
                                    // Output raw material name and unit in the same table cell
                                    echo "<td>{$raw_material} ({$quantities[0]['Unit']})</td>";

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
                                            echo "<td class='quantity_required' data-product-id='$productID' data-initial-quantity='$quantity'>$quantity</td>";
                                        }
                                    }

                                    // Output total ingredients with a class for JavaScript to identify it
                                    echo "<th class='total-ingredients'></th>";

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

        <section class="row">
            <div class="card col-lg-12">
                <div class="card-body">
                    <div class="card-body">
                        <div class="card-title-body">
                            <br>

                            <tr>

                                <?php
                                // Query to fetch raw materials and their quantities along with the unit and price
                                $raw_materials_sql = "SELECT rm.Name, rm.Unit, rm.Price, rm.RawMaterialID FROM rawmaterials rm";
                                $raw_materials_result = mysqli_query($conn, $raw_materials_sql);
                                $raw_materials = array();

                                // Organize raw materials by ID
                                while ($row = mysqli_fetch_assoc($raw_materials_result)) {
                                    $raw_materials[$row['RawMaterialID']] = $row;
                                }
                                ?>

                                <table class="table table-bordered col-md-12">
                                    <h2>Raw Materials Cost per Batch</h2>
                                    <br>
                                    <tr>
                                        <th>Raw Materials</th>
                                        <?php
                                        // Query to fetch product details from the productlist table
                                        $sql = "SELECT ProductID, name FROM productlist";
                                        $result = mysqli_query($conn, $sql);

                                        // Check if there are any products
                                        if (mysqli_num_rows($result) > 0) {
                                            // Initialize an empty array to store product list
                                            $product_list = array();

                                            // Output data of each row and fetch product data
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                // Output product name and ProductID as a table header
                                                echo "<th data-product-id='" . $row['ProductID'] . "'>" . $row['name'] . "</th>";

                                                // Store product data in the $product_list array
                                                $product_list[] = $row;
                                            }
                                        } else {
                                            // Handle the case where no products are found
                                            // For example, you can initialize $product_list as an empty array
                                            $product_list = array();
                                        }
                                        ?>

                                        <th>Total Purchase</th>
                                        <th></th>
                                        <th>Inventory </th>

                                    </tr>

                                    <?php
                                    // Output raw materials and their details
                                    foreach ($raw_materials as $raw_material_id => $raw_material_data) {
                                        echo "<tr>";
                                        echo "<td>{$raw_material_data['Name']} ({$raw_material_data['Unit']})</td>";

                                        // Initialize product prices array with default values of 0 for all products
                                        $product_prices = array_fill_keys(array_column($product_list, 'ProductID'), 0);

                                        // Fetch quantity required and ProductID from the bom table for the current RawMaterialID
                                        $bom_query = "SELECT b.QuantityRequired, b.ProductID
              FROM bom b
              WHERE b.RawMaterialID = $raw_material_id";
                                        $bom_result = mysqli_query($conn, $bom_query);

                                        while ($bom_row = mysqli_fetch_assoc($bom_result)) {
                                            // Replace NULL with 0 for QuantityRequired
                                            $quantity_required = $bom_row['QuantityRequired'] ?? 0; // Use null coalescing operator to replace NULL with 0
                                            $product_id = $bom_row['ProductID'];

                                            // Fetch the price of the raw material
                                            $raw_material_price = $raw_material_data['Price'];
                                        }


                                        foreach ($product_prices as $product_id => $price) {
                                            // Display the price with currency symbol and data attributes
                                            echo "<td class='price-placeholder' data-product-id='$product_id' data-initial-cost='$raw_material_price'>₱</td>";
                                        }


                                        echo "<th class='total-cost'></th>";

                                        echo "<td>&#8804;</td>";

                                        // Fetch and calculate the current stock for the current raw material
                                        $stock_query = "SELECT IFNULL(SUM(stock_in), 0) AS total_stock_in, IFNULL(SUM(stock_out), 0) AS total_stock_out 
                        FROM stock WHERE RawMaterialID = $raw_material_id"; // Using $raw_material_id
                                        $stock_result = mysqli_query($conn, $stock_query);
                                        $stock_data = mysqli_fetch_assoc($stock_result);

                                        // Calculate the current stock
                                        $total_stock_in_kg = $stock_data['total_stock_in'] / 1000; // Convert stock in to kg
                                        $total_stock_out_kg = $stock_data['total_stock_out'] / 1000; // Convert stock out to kg
                                        $current_stock_kg = $total_stock_in_kg - $total_stock_out_kg;

                                        // Output the current stock value (current stock * price) in PHP
                                        $current_stock_value = $current_stock_kg * $raw_material_data['Price'];

                                        // Output the current stock value in HTML table cell
                                        echo "<td>₱" . number_format($current_stock_value, 2) . "</td>";


                                        echo "</tr>";
                                    }



                                    ?>
                                    <tr>
                                        <th colspan="9">Total Expenses (Raw Materials)</th>
                                        <th style="background-color: yellow;" id="total-expenses-raw-materials"></th>
                                        <td>&#8804;</td>

                                        <?php
                                        // Fetch and calculate the current stock value for each raw material
                                        $total_current_stock_value = 0;

                                        // Loop through each raw material to calculate its current stock value
                                        foreach ($raw_materials as $raw_material) {
                                            // Fetch and calculate the current stock value for the current raw material
                                            $stock_query = "SELECT IFNULL(SUM(stock_in), 0) AS total_stock_in, IFNULL(SUM(stock_out), 0) AS total_stock_out 
                    FROM stock WHERE RawMaterialID = {$raw_material['RawMaterialID']}";
                                            $stock_result = mysqli_query($conn, $stock_query);
                                            $stock_data = mysqli_fetch_assoc($stock_result);

                                            // Calculate the current stock
                                            $total_stock_in_kg = $stock_data['total_stock_in'] / 1000; // Convert stock in to kg
                                            $total_stock_out_kg = $stock_data['total_stock_out'] / 1000; // Convert stock out to kg
                                            $current_stock_kg = $total_stock_in_kg - $total_stock_out_kg;

                                            // Calculate the current stock value for the current raw material
                                            $current_stock_value = $current_stock_kg * $raw_material['Price'];

                                            // Add the current stock value to the total
                                            $total_current_stock_value += $current_stock_value;
                                        }

                                        // Output the total current stock value in HTML table cell with background color
                                        echo "<td style='background-color: skyblue;'>₱" . number_format($total_current_stock_value, 2) . "</td>";
                                        ?>

                                    </tr>

                                    <tr>

                                        <th colspan="9">Total Expenses (Other Constraints)</th>
                                        <th style="background-color: yellow;" id="total-other-expenses">
                                            <?php
                                            // Query to fetch the total amount for other constraints (latest bill amount for each name)
                                            $sql = "SELECT SUM(amount) AS total FROM (
                    SELECT MAX(amount) AS amount FROM bills GROUP BY name
                ) AS subquery";

                                            // Execute the query
                                            $result = mysqli_query($conn, $sql);

                                            // Check if the query was successful
                                            if ($result) {
                                                // Fetch the total amount
                                                $row = mysqli_fetch_assoc($result);
                                                $totalOtherExpenses = $row['total'];
                                                echo '₱' . number_format($totalOtherExpenses, 2);
                                            } else {
                                                echo "Error executing query: " . mysqli_error($conn);
                                            }
                                            ?></th>
                                        <td>&#8804;</td>
                                        <td style="background-color: skyblue; "> <?php
                                                                                    // Query to fetch the total amount for other constraints (latest bill amount for each name)
                                                                                    $sql = "SELECT SUM(amount) AS total FROM (
                    SELECT MAX(amount) AS amount FROM bills GROUP BY name
                ) AS subquery";

                                                                                    // Execute the query
                                                                                    $result = mysqli_query($conn, $sql);

                                                                                    // Check if the query was successful
                                                                                    if ($result) {
                                                                                        // Fetch the total amount
                                                                                        $row = mysqli_fetch_assoc($result);
                                                                                        $totalOtherExpenses = $row['total'];
                                                                                        echo '₱' . number_format($totalOtherExpenses, 2);
                                                                                    } else {
                                                                                        echo "Error executing query: " . mysqli_error($conn);
                                                                                    }
                                                                                    ?>
                                        </td>
                                    </tr>




                                </table>


                            </tr>






                        </div>
                    </div>
                </div>
            </div>
        </section>


        <br>



        <section class="row">
            <div class="card col-lg-12">
                <div class="card-body">
                    <div class="card-body">
                        <div class="card-title-body">
                            <br>
                            <table class="table table-bordered col-md-12">

                                <h2>Other Constraints (Approximate Cost / month)</h2>
                                <br>
                                <tr>
                                    <th></th>
                                    <th>Date</th>

                                    <th>Amount</th>
                                </tr>
                                <tr>
                                    <th>ELECTRICITY</th>


                                    <?php
                                    // Query to fetch bills from the bills table
                                    $sql = "SELECT * FROM bills WHERE name = 'Electric bill' ORDER BY date DESC LIMIT 1";

                                    // Execute the query
                                    $result = mysqli_query($conn, $sql);

                                    // Check if the query was successful
                                    if ($result) {
                                        // Fetch the data from the result set
                                        $bill = mysqli_fetch_assoc($result);

                                        // Check if a bill was found
                                        if ($bill) {
                                            echo "<td> " . $bill['date'] . "</td>";

                                            echo "<td> " . $bill['amount'] . "</td>";
                                        } else {
                                            echo "No electric bill found.";
                                        }
                                    } else {
                                        echo "Error executing query: " . mysqli_error($conn);
                                    }
                                    ?>

                                </tr>


                                <tr>
                                    <th>WATER</th>
                                    <?php
                                    // Query to fetch bills from the bills table
                                    $sql = "SELECT * FROM bills WHERE name = 'Water bill' ORDER BY date DESC LIMIT 1";

                                    // Execute the query
                                    $result = mysqli_query($conn, $sql);

                                    // Check if the query was successful
                                    if ($result) {
                                        // Fetch the data from the result set
                                        $bill = mysqli_fetch_assoc($result);

                                        // Check if a bill was found
                                        if ($bill) {
                                            echo "<td> " . $bill['date'] . "</td>";

                                            echo "<td> " . $bill['amount'] . "</td>";
                                        } else {
                                            echo "No electric bill found.";
                                        }
                                    } else {
                                        echo "Error executing query: " . mysqli_error($conn);
                                    }
                                    ?>
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


            // Add event listeners to batch amount elements to recalculate total revenue when they change
            document.querySelectorAll('.batch_amount').forEach(function(element) {
                element.addEventListener('input', updateTotalRevenue);
            });

        });
    });
    // Function to calculate total revenue
    function updateTotalRevenue() {
        var totalRevenue = 0;
        var priceElements = document.querySelectorAll('.price');
        var batchAmountElements = document.querySelectorAll('.batch_amount');

        // Assuming both price and batch amount arrays are of equal length
        for (var i = 0; i < priceElements.length; i++) {
            var price = parseFloat(priceElements[i].textContent);
            var amount = parseFloat(batchAmountElements[i].textContent);
            var revenue = price * amount;
            totalRevenue += revenue;
        }

        // Update the total revenue cell
        document.querySelector('#total_revenue').textContent = '₱' + totalRevenue.toFixed(2);
    }

    // Call the function initially to calculate total revenue
    updateTotalRevenue();

    // Add event listeners to relevant input elements to recalculate total revenue when they change
    document.querySelectorAll('.quantity_input, .batch_amount').forEach(function(input) {
        input.addEventListener('input', updateTotalRevenue);
    });



    // Define an array to store the calculated results
    var quantityResults = [];

    function updateQuantityRequired() {
        // Reset the quantityResults array before recalculating
        quantityResults = [];

        // Iterate over each element with class 'quantity_required'
        document.querySelectorAll('.quantity_required').forEach(function(element) {
            var productID = element.dataset.productId; // Get the product ID
            var initialQuantity = parseFloat(element.dataset.initialQuantity); // Get the initial quantity
            var batchAmount = parseFloat(document.querySelector('.quantity_input[data-product-id="' + productID + '"]').value); // Get the batch amount

            // Check if the initial quantity and batch amount are valid numbers
            if (!isNaN(initialQuantity) && isFinite(initialQuantity) && !isNaN(batchAmount) && isFinite(batchAmount)) {
                var result = initialQuantity * batchAmount; // Calculate the result
                element.textContent = result.toFixed(2); // Update the displayed quantity

                // Store the result in the quantityResults array
                quantityResults.push(result);
            } else {
                element.textContent = "0"; // Set quantity required to 0 if initial quantity or batch amount is invalid

                // Store 0 in the quantityResults array
                quantityResults.push(0);
            }
        });

        // Call the function to update total ingredients after updating quantities required
        updateTotalIngredients();

        // Call the function to update raw material costs after updating quantities required
        updateRawMaterialCost();

        // Calculate total cost after updating raw material costs
        calculateTotalCost();
        calculateTotalRowCost();

        handleDOMContentLoaded()
    }


    // Add event listener to quantity input fields
    document.querySelectorAll('.quantity_input').forEach(function(input) {
        input.addEventListener('input', function() {
            updateQuantityRequired(); // Call the function to update quantity required
        });
    });

    // Initial call to update quantity required when the page loads
    updateQuantityRequired();

    // Function to update total ingredients dynamically
    function updateTotalIngredients() {
        // Select all elements with the class 'total-ingredients'
        var totalIngredientsElements = document.querySelectorAll('.total-ingredients');

        // Loop through each total ingredients element
        totalIngredientsElements.forEach(function(element) {
            var totalIngredients = 0;

            // Select all quantity required elements in the same row
            var quantityRequiredElements = element.parentElement.querySelectorAll('.quantity_required');

            // Loop through each quantity required element in the same row
            quantityRequiredElements.forEach(function(quantityElement) {
                // Add the quantity to the total ingredients
                totalIngredients += parseFloat(quantityElement.textContent);
            });

            // Update the content of the total ingredients element with the calculated total
            element.textContent = totalIngredients.toFixed(2);
        });
    }
    // Call the function to update quantity required
    updateQuantityRequired();


    function updateRawMaterialCost() {
        // Iterate over each raw material price placeholder
        document.querySelectorAll('.price-placeholder').forEach(function(element, index) {
            var productID = element.dataset.productId; // Get the product ID
            var initialCost = parseFloat(element.dataset.initialCost); // Get the initial cost
            var initialTotalQuantity = parseFloat(element.dataset.initialTotalQuantity); // Get the initial total quantity

            // Get the corresponding result from the quantityResults array
            var result = quantityResults[index] || initialTotalQuantity; // Use initial total quantity if result is not available

            // Check if initial cost and result are valid numbers
            if (!isNaN(initialCost) && isFinite(initialCost) && !isNaN(result) && isFinite(result)) {
                var rawMaterialCost = initialCost * result; // Multiply initial cost by result to get raw material cost
                element.textContent = '₱' + rawMaterialCost.toFixed(2); // Update the displayed content
            } else {
                element.textContent = '₱0.00'; // Set raw material cost to 0 if initial cost or result is invalid
            }
        });
    }

    // Call updateRawMaterialCost() to update raw material costs
    updateRawMaterialCost();

    function calculateTotalCost() {
        var totalCost = 0;
        // Select all total cost placeholders
        document.querySelectorAll('.total-cost').forEach(function(totalCostElement) {
            var row = totalCostElement.closest('tr');
            var rowTotalCost = 0;
            // Select all price placeholders in the same row
            row.querySelectorAll('.price-placeholder').forEach(function(placeholder) {
                var rawMaterialCost = parseFloat(placeholder.textContent.replace('₱', '')); // Get the raw material cost
                if (!isNaN(rawMaterialCost)) {
                    rowTotalCost += rawMaterialCost; // Add the raw material cost to the row total
                } else {
                    console.error('Invalid raw material cost:', placeholder.textContent);
                }
            });
            // Update the total cost display in the row
            totalCostElement.textContent = rowTotalCost.toFixed(2);
            // Add the row total cost to the overall total cost
            totalCost += rowTotalCost;
        });




    }
    updateQuantityRequired();

    function calculateTotalRowCost() {
        var totalRowCost = 0;
        // Select all total cost placeholders per row
        document.querySelectorAll('.total-cost').forEach(function(totalCostElement) {
            // Get the numeric value of the total cost for the row
            var rowTotalCost = parseFloat(totalCostElement.textContent);
            if (!isNaN(rowTotalCost)) {
                totalRowCost += rowTotalCost; // Add row total cost to the overall total row cost
            }
        });

        // Update the total expenses (raw materials) cell with the calculated total row cost
        var totalExpensesRawMaterials = document.querySelector('#total-expenses-raw-materials');
        if (totalExpensesRawMaterials) {
            totalExpensesRawMaterials.textContent = '₱' + totalRowCost.toFixed(2);
        } else {
            console.error('Total expenses (raw materials) element not found.');
        }
    }

    updateQuantityRequired();

    function handleDOMContentLoaded() {
        // Fetch values from hidden HTML elements
        var totalExpensesRawMaterialsElement = document.getElementById('total-expenses-raw-materials');

        // Calculate total other expenses directly in JavaScript
        var totalOtherExpenses = <?php echo json_encode($totalOtherExpenses); ?>;

        // Check if the elements were found
        if (totalExpensesRawMaterialsElement) {
            var totalExpensesRawMaterialsContent = totalExpensesRawMaterialsElement.textContent.trim(); // Remove leading/trailing whitespace
            totalExpensesRawMaterialsContent = totalExpensesRawMaterialsContent.replace('₱', ''); // Remove currency symbol
            var totalExpensesRawMaterials = parseFloat(totalExpensesRawMaterialsContent);

            // Check if the values are valid numbers
            if (!isNaN(totalExpensesRawMaterials) && !isNaN(totalOtherExpenses)) {
                // Calculate total expenses
                var totalExpenses = (parseFloat(totalExpensesRawMaterials) + parseFloat(totalOtherExpenses)).toFixed(2);

                // Output the total expenses
                var expensesPlaceholder = document.querySelector('.total-expenses-placeholder');
                if (expensesPlaceholder) {
                    expensesPlaceholder.textContent = totalExpenses;
                } else {
                    console.error('Total expenses placeholder not found.');
                }

                // Calculate profit
                var totalRevenueElement = document.querySelector('#total_revenue');
                if (totalRevenueElement) {
                    var totalRevenue = parseFloat(totalRevenueElement.textContent.replace('₱', ''));
                    if (!isNaN(totalRevenue)) {
                        var profit = totalRevenue - parseFloat(totalExpenses);
                        // Update the profit cell
                        document.querySelector('#profit').textContent = '₱' + profit.toFixed(2);
                    } else {
                        console.error('Invalid total revenue.');
                    }
                } else {
                    console.error('Total revenue element not found.');
                }
            } else {
                console.error('Invalid total expenses values.');
            }
        } else {
            console.error('Total expenses raw materials element not found.');
        }
    }

    // Call the function when the DOM content is loaded
    document.addEventListener("DOMContentLoaded", function() {
        handleDOMContentLoaded(); // Call the function to handle DOMContentLoaded event
    });
</script>