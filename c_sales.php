<?php
include 'header.php';
include 'sidebar.php';
include 'db_connect.php';
$allowedUserTypes = array(3, 5);

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
        // Assuming $conn is your database connection

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
                if ($insertItemsQuery->execute()) {
                    // Insert new data into product_stock table with stock_out incremented and TransactionDate
                    $insertStockQuery = $conn->prepare("INSERT INTO product_stock (ProductID, TransactionDate, stock_in, stock_out) VALUES (?, ?, 0, ?)");
                    $currentDate = date("Y-m-d"); // Get current date
                    $insertStockQuery->bind_param("ssd", $productIDs[$i], $currentDate, $quantities[$i]);
                    if (!$insertStockQuery->execute()) {
                        // Handle error inserting into product_stock table
                        echo '<div class="alert alert-danger" role="alert">Error inserting into product_stock table: ' . $conn->error . '</div>';
                    }
                    $insertStockQuery->close();
                } else {
                    // Handle error inserting into salesitems table
                    echo '<div class="alert alert-danger" role="alert">Error inserting into salesitems table: ' . $conn->error . '</div>';
                }
            }

            $insertItemsQuery->close();
            echo '<div class="alert alert-success" role="alert">Sale created successfully.</div>';
        } else {
            // Handle error inserting into sales table
            echo '<div class="alert alert-danger" role="alert">Error creating sale: ' . $conn->error . '</div>';
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
                                    <th>Pack</th>
                                    <th>Unit</th>
                                    <th>Stock qty</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
                                    <th>Action</th>

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
            </div>
        </div>

    </form>
</article>



<script>
    $(document).ready(function() {
        // Event listener for product selection changes
        $('select[name="product_id"]').change(function() {
            var selectedProductId = $(this).val();

            // Make an AJAX request to fetch product details
            $.ajax({
                type: 'POST',
                url: 'fetch_product_details.php',
                data: {
                    product_id: selectedProductId
                },
                success: function(response) {
                    var productDetails = JSON.parse(response);
                    if (!isNaN(parseFloat(productDetails.price)) && !isNaN(parseInt(productDetails.available_quantity))) {
                        $('input[name="pack"]').val(productDetails.pack);
                        $('input[name="measure_and_unit"]').val(productDetails.measure_and_unit);
                        $('input[name="price"]').val(productDetails.price);
                        $('input[name="available_quantity"]').val(productDetails.available_quantity);

                        // Set the maximum quantity based on available quantity
                        $('#Quantity').attr('max', productDetails.available_quantity);
                    } else {
                        console.log("Error: Invalid product details received.");
                    }
                },
                error: function() {
                    console.log('Error fetching product details.');
                }
            });
        });

        // Event listener for quantity input changes
        $('#cartTable tbody').on('input', 'input[name="quantities[]"]', function() {
            var quantity = parseInt($(this).val());
            var availableQuantity = parseInt($(this).closest('tr').find('td:eq(3)').text());

            // Validate against negative quantity
            if (isNaN(quantity) || quantity < 0) {
                $(this).val(1);
                return;
            }

            // Update maximum allowed quantity
            $(this).attr('max', availableQuantity);

            // Limit quantity to available quantity
            if (quantity > availableQuantity) {
                $(this).val(availableQuantity);
            }

            // Update amount and total price
            updateAmountAndTotalPrice($(this).closest('tr'));
        });
    });

    function addToCart() {
        var productId = $('#product_id').val();
        var productName = $('#product_id option:selected').text();
        var existingProduct = $('#cartTable tbody').find('td:first-child:contains("' + productName + '")').closest('tr');

        if (existingProduct.length > 0) {
            alert('This product is already added to the cart.');
            return;
        }

        $.ajax({
            type: 'POST',
            url: 'fetch_product_details.php',
            data: {
                product_id: productId
            },
            success: function(response) {
                var productDetails = JSON.parse(response);
                var availableQuantity = parseInt(productDetails.available_quantity);

                var newRow = '<tr>' +
                    '<td> <strong>' + productName + ' </strong> </td>' +
                    '<td>' + productDetails.pack + '</td>' +
                    '<td>' + productDetails.measure_and_unit + '</td>' +
                    '<td style="color: green;">' + availableQuantity + '</td>' +
                    '<td>' + productDetails.price + '</td>' +
                    '<td><input type="number" class="form-control" name="quantities[]" value="0" min="1" max="' + availableQuantity + '"></td>' +
                    '<td class="amount">' + (productDetails.price * 1).toFixed(2) + '</td>' +
                    '<td><button type="button" class="btn btn-danger btn-sm" onclick="removeFromCart(this)">Remove</button></td>' + // Add remove button
                    // Add hidden input fields for product ID and price
                    '<input type="hidden" name="product_ids[]" value="' + productId + '">' +
                    '<input type="hidden" name="prices[]" value="' + productDetails.price + '">' +
                    '</tr>';
                $('#cartTable tbody').append(newRow);

                updateTotalAmount();
            },
            error: function() {
                console.log('Error fetching product details.');
            }
        });
    }

    // Function to remove product from the cart
    function removeFromCart(button) {
        $(button).closest('tr').remove(); // Remove the row from the table
        updateTotalAmount(); // Update the total amount
    }


    function updateAmountAndTotalPrice(row) {
        var quantity = parseInt(row.find('input[name="quantities[]"]').val());
        var price = parseFloat(row.find('td:eq(4)').text());
        var amount = isNaN(quantity) || isNaN(price) ? 0 : quantity * price;
        row.find('.amount').text(amount.toFixed(2));

        updateTotalAmount();
    }

    function updateTotalAmount() {
        var totalPrice = 0;
        $('#cartTable tbody tr').each(function() {
            var amount = parseFloat($(this).find('.amount').text());
            totalPrice += isNaN(amount) ? 0 : amount;
        });

        $('#TotalAmount').val(totalPrice.toFixed(2));
    }
</script>