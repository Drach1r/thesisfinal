<?php
include 'header.php';
include 'sidebar.php';
include 'db_connect.php';
$allowedUserTypes = array(3);

checkUserTypeAccess($allowedUserTypes, 'login.php', 'You are not allowed to access this page.');

function generateSaleID($conn)
{
    // Get the last used SaleID from the database
    $query = "SELECT SaleID FROM sales ORDER BY SaleID DESC LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $lastSaleID = mysqli_fetch_assoc($result)['SaleID'];

        // Extract the numeric part from the last SaleID
        $lastIDNumber = intval(substr($lastSaleID, 3));

        // Increment the numeric part
        $newIDNumber = $lastIDNumber + 1;

        // Create the new SaleID
        $newSaleID = "SO-" . str_pad($newIDNumber, 2, '0', STR_PAD_LEFT);

        return $newSaleID;
    } else {
        // If no SaleID is found, start with SO-01
        return "SO-01";
    }
}



?>

<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">
                        Create Sales Order
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get data from the form
        $saleID = $_POST['SaleID'];
        $customerID = $_POST['customer'];
        $saleDate = $_POST['o_date'];
        $totalAmount = $_POST['TotalAmount'];
        $productIDs = isset($_POST['product_ids']) ? $_POST['product_ids'] : array();
        $quantities = isset($_POST['quantities']) ? $_POST['quantities'] : array();
        $prices = isset($_POST['prices']) ? $_POST['prices'] : array();

        // Check if SaleID already exists
        $checkSaleIDQuery = $conn->prepare("SELECT SaleID FROM sales WHERE SaleID = ?");
        $checkSaleIDQuery->bind_param("s", $saleID);
        $checkSaleIDQuery->execute();
        $checkSaleIDResult = $checkSaleIDQuery->get_result();

        if ($checkSaleIDResult->num_rows > 0) {
            // SaleID already exists, generate a new one
            $saleID = generateSaleID($conn);
        }

        // Insert data into the sales table
        $insertSalesQuery = $conn->prepare("INSERT INTO sales (SaleID, CustomerID, SaleDate, TotalAmount) VALUES (?, ?, ?, ?)");
        $insertSalesQuery->bind_param("sssd", $saleID, $customerID, $saleDate, $totalAmount);

        if ($insertSalesQuery->execute()) {
            // Insert data into the salesitems table
            $insertItemsQuery = $conn->prepare("INSERT INTO salesitems (SaleID, ProductID, Quantity, Price) VALUES (?, ?, ?, ?)");

            // Ensure all arrays have the same length before iterating
            $length = min(count($productIDs), count($quantities), count($prices));

            for ($i = 0; $i < $length; $i++) {
                $insertItemsQuery->bind_param("ssdd", $saleID, $productIDs[$i], $quantities[$i], $prices[$i]);
                $insertItemsQuery->execute();

                // Insert new data into product_stock table with stock_out incremented and TransactionDate
                $insertStockQuery = $conn->prepare("INSERT INTO product_stock (ProductID, TransactionDate, stock_in, stock_out) VALUES (?, ?, 0, ?)");
                $currentDate = date("Y-m-d"); // Get current date
                $insertStockQuery->bind_param("ssd", $productIDs[$i], $currentDate, $quantities[$i]);
                $insertStockQuery->execute();
                $insertStockQuery->close();
            }

            $insertItemsQuery->close();

            echo '<div class="alert alert-success" role="alert">Sale created successfully.</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">Error creating sale: ' . $insertSalesQuery->error . '</div>';
        }

        $insertSalesQuery->close();
        $checkSaleIDQuery->close();
    }
    ?>







    <form method="POST" action="" id="productionForm">
        <div class="card card-block">
            <div style="margin: 20px;" class="form-group row">
                <div class="form-group col-xs-3">
                    <label for="product">Sales order:</label>
                    <?php
                    echo '<input type="text" class="form-control" name="SaleID" value="' . generateSaleID($conn) . '" readonly>';
                    ?>
                </div>
                <div style="float: right;" class="form-group col-xs-4">
                    <label for="o_date">Created Date:</label>
                    <input type="date" class="form-control" name="o_date" required>
                </div>


            </div>
            <div style="margin: 20px;" class="form-group row">
                <div class="form-group col-xs-4">
                    <label for="customer">Customer Name:</label>
                    <select class="form-control" name="customer" id="customer" required>
                        <option value="" disabled selected>---Select Customer---</option>

                        <?php
                        // Fetch customer names from the customers table
                        $customerQuery = $conn->query("SELECT CustomerID, Name FROM customers");

                        while ($customerRow = $customerQuery->fetch_assoc()) {
                            echo '<option value="' . $customerRow['CustomerID'] . '">' . $customerRow['Name'] . '</option>';
                        }
                        ?>

                    </select>
                </div>
            </div>
            <div class="card card-block">
                <div style="margin: 20px;" class="form-group row">
                    <div class="form-group col-xs-4">
                        <label for="product">Product:</label>
                        <select class="form-control" name="product_id" id="product_id">
                            <option value="" disabled selected>---Select Product---</option>
                            <?php
                            // Fetch products from the product_stock table that have data
                            $productlistQuery = $conn->query("SELECT pl.ProductID, pl.Name FROM productlist pl
                                            INNER JOIN product_stock ps ON pl.ProductID = ps.ProductID
                                            GROUP BY pl.ProductID, pl.Name
                                            ORDER BY pl.Name");

                            while ($productlistRow = $productlistQuery->fetch_assoc()) {
                                echo '<option value="' . $productlistRow['ProductID'] . '">' . $productlistRow['Name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div style="margin: 20px;" class="form-group row">
                        <div class="form-group col-xs-2">
                            <label for="stock">Stock Available:</label>
                            <input type="number" class="form-control" name="available_quantity" id="available_quantity" value="" readonly>
                        </div>
                    </div>



                </div>

                <div style="margin: 20px;" class="form-group row">
                    <div class="form-group col-xs-2">
                        <label for="Quantity">Quantity:</label>
                        <input type="number" class="form-control" name="Quantity" id="Quantity" value="1">
                    </div>
                    <div class="form-group col-xs-2">
                        <label for="pack">Pack:</label>
                        <input type="text" class="form-control" name="pack" id="pack" required readonly>
                    </div>
                    <div class="form-group col-xs-2">
                        <label for="measure_and_unit">Unit:</label>
                        <input type="text" class="form-control" name="measure_and_unit" id="measure_and_unit" required readonly>
                    </div>
                    <div style="float: right;" class="form-group col-xs-2">
                        <label for="price">Price:</label>
                        <input type="text" class="form-control" name="price" id="price" required readonly>
                    </div>
                </div>
            </div>
            <br>
            <br>
            <div style="float: right;" class="form-group col-xs-2">
                <label>&nbsp;</label>
                <button type="button" class="btn btn-primary" onclick="addToCart()">Add To Basket</button>
            </div>


            <br>
            <br>
            <br>
            <!-- Table to display selected products -->
            <div class="card card-block">
                <table class="table table-bordered" id="cartTable">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Pack</th>
                            <th>Unit</th>
                            <th>Price</th>
                            <th>Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        <!-- Table body content will be generated dynamically by JavaScript -->
                    </tbody>
                </table>
                <div style="float: right;" class="form-group row">
                    <div class="form-group col-xs-6">
                        <label for="TotalAmount">Total Price Amount:</label>
                    </div>
                    <div class="form-group col-xs-5">
                        <input type="text" class="form-control" name="TotalAmount" id="TotalAmount" required readonly>
                    </div>
                </div>
            </div>
            <div style="float: right;" class="row form-group">
                <a href="sales.php" class="btn btn-danger">Back</a>
                <button type="submit" class="btn btn-success">Submit Sale</button>
            </div>

        </div>



    </form>
</article>
<script>
    $(document).ready(function() {
        // Event handler when product selection changes
        $('select[name="product_id"]').change(function() {
            // Get the selected product ID
            var selectedProductId = $(this).val();

            // Make an AJAX request to fetch pack, measure_and_unit, price, and available quantity for the selected product
            $.ajax({
                type: 'POST',
                url: 'fetch_product_details.php',
                data: {
                    product_id: selectedProductId
                },
                success: function(response) {
                    // Parse the JSON response
                    var productDetails = JSON.parse(response);

                    // Update the input fields with the fetched data
                    $('input[name="pack"]').val(productDetails.pack);
                    $('input[name="measure_and_unit"]').val(productDetails.measure_and_unit);
                    $('input[name="price"]').val(productDetails.price);
                    $('input[name="available_quantity"]').val(productDetails.available_quantity);

                    // Set the maximum quantity based on available quantity
                    $('#Quantity').attr('max', productDetails.available_quantity);
                },
                error: function() {
                    console.log('Error fetching product details.');
                }
            });
        });
    });

    function updateAvailableQuantity() {
        var totalQuantityAdded = 1; // Initialize with 1 to account for the new quantity

        // Iterate through the quantities of items already added to the cart
        $('input[name="quantities[]"]').each(function() {
            totalQuantityAdded += parseInt($(this).val());
        });

        // Get the quantity of the item being added to the cart
        var newQuantity = parseInt($('#Quantity').val());

        // Subtract the total quantity added from the available quantity, excluding the new quantity
        var availableQuantity = parseInt($('input[name="available_quantity"]').val()) - (totalQuantityAdded - newQuantity);

        // Return the updated available quantity without updating the field
        return availableQuantity;
    }


    function addToCart() {
        // Get the selected product ID, name, quantity, pack, measure_and_unit, and price
        var productId = $('#product_id').val();
        var productName = $('#product_id option:selected').text();
        var quantity = $('#Quantity').val();
        var pack = $('#pack').val();
        var measureAndUnit = $('#measure_and_unit').val();
        var price = $('#price').val();

        // Get the available quantity
        var availableQuantity = updateAvailableQuantity();

        // Check if quantity exceeds available quantity or if quantity is less than or equal to 0
        if (parseInt(quantity) <= 0 || parseInt(quantity) > availableQuantity) {
            alert('Please enter a valid quantity.');
            return;
        }

        // Calculate the amount for the product based on its quantity and price
        var amount = quantity * price;

        // Append a new row to the table with product details and calculated amount
        var newRow = '<tr>' +
            '<td>' + productName + '<input type="hidden" name="product_ids[]" value="' + productId + '"></td>' +
            '<td>' + quantity + '<input type="hidden" name="quantities[]" value="' + quantity + '"></td>' +
            '<td>' + pack + '</td>' +
            '<td>' + measureAndUnit + '</td>' +
            '<td class="price">' + price + '<input type="hidden" name="prices[]" value="' + price + '"></td>' +
            '<td class="amount">' + amount.toFixed(2) + '</td>' + // Display the calculated amount
            '</tr>';
        $('#cartTable tbody').append(newRow);

        // Update the total price amount
        updateTotalAmount();

        // Update the available quantity input field
        $('input[name="available_quantity"]').val(availableQuantity);

        // Clear the input fields
        $('#product_id').val('');
        $('#Quantity').val('');
        $('#pack').val('');
        $('#measure_and_unit').val('');
        $('#price').val('');
    }



    function updateTotalAmount() {
        var totalPrice = 0;
        $('#cartTable tbody tr').each(function() {
            var amount = parseFloat($(this).find('.amount').text());
            totalPrice += isNaN(amount) ? 0 : amount;
        });

        // Update the Total Amount input field
        $('#TotalAmount').val(totalPrice.toFixed(2));
    }
</script>