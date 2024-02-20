<?php
include 'header.php';
include 'sidebar.php';
include 'db_connect.php';

$allowedUserTypes = array(2, 4, 5);
checkUserTypeAccess($allowedUserTypes, 'login.php', 'You are not allowed to access this page.');

function generateOrderId($conn)
{
    // Fetch the latest order_id from the manufacturing_orders table
    $result = $conn->query("SELECT MAX(order_id) AS max_order_id FROM manufacturing_orders");
    $row = $result->fetch_assoc();
    $maxOrderId = $row['max_order_id'];

    // Extract the numeric part of the order_id and increment it
    $numericPart = intval(substr($maxOrderId, 3)) + 1;

    // Generate the new order_id with the "MO" prefix
    $newOrderId = 'MO-' . str_pad($numericPart, 3, '0', STR_PAD_LEFT);

    return $newOrderId;
}

function sanitize($data)
{
    if (is_array($data)) {
        return array_map('sanitize', $data);
    } else {
        return is_scalar($data) ? htmlspecialchars(stripslashes(trim($data))) : $data;
    }
}

?>
<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">Produce Product</h3>
                </div>
            </div>
        </div>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $product_id = $_POST['product_id'];
        $o_quantity = $_POST['o_quantity'];
        $o_date = $_POST['o_date'];
        $due_date = $_POST['due_date'];


        $order_id = generateOrderId($conn);

        $batch_amount = $_POST['batch_amount'];

        // Insert data into manufacturing_orders table
        $stmtManufacturingOrder = $conn->prepare("INSERT INTO manufacturing_orders (order_id, ProductID, o_quantity, o_date, due_date, batch_amount) VALUES (?, ?, ?, ?, ?, ?)");
        $stmtManufacturingOrder->bind_param("ssssss", $order_id, $product_id, $o_quantity, $o_date, $due_date, $batch_amount);

        if ($stmtManufacturingOrder->execute()) {

            // Success message
            $successMessage = 'Product produced successfully.';
            echo "<div class='alert alert-success'>$successMessage</div>";

            // Echo the Manufacturing Order ID in the form
            echo '<div class="form-group row">
                    <div class="form-group col-xs-4">
                        <label for="product">Manufacturing order:</label>
                        <span>' . $order_id . '</span>
                    </div>
                </div>';

            // Fetch materials for the given product from the bom table
            $stmtMaterials = $conn->prepare("SELECT * FROM bom WHERE ProductID = ?");
            $stmtMaterials->bind_param("s", $product_id);
            $stmtMaterials->execute();
            $resultMaterials = $stmtMaterials->get_result();

            // Insert data into manufacturing_mat table
            while ($row = $resultMaterials->fetch_assoc()) {
                $rawMaterialID = $row['RawMaterialID'];
                $issued_quantity = $row['QuantityRequired']; // You may need to adjust this based on your requirements
                $qty_unit = $row['qty_unit']; // Include qty_unit in the insertion

                $stmtManufacturingMat = $conn->prepare("INSERT INTO manufacturing_mat (order_id, RawMaterialID, issued_quantity, qty_unit) VALUES (?, ?, ?, ?)");
                $stmtManufacturingMat->bind_param("ssss", $order_id, $rawMaterialID, $issued_quantity, $qty_unit);
                $stmtManufacturingMat->execute();
                $stmtManufacturingMat->close();
            }

            $stmtMaterials->close();
        } else {
            // Error message
            $errorMessage = 'Error producing product: ' . $stmtManufacturingOrder->error;
            echo "<div class='alert alert-danger'>$errorMessage</div>";
            // Log the error or provide feedback to the user
        }

        $stmtManufacturingOrder->close();
    }



    ?>


    <form method="POST" action="" id="productionForm">
        <div class="card card-block">
            <div style="margin: 20px;" class="form-group row">
                <div class="form-group col-xs-3">
                    <label for="product">Manufacturing order:</label>
                    <?php
                    // Echo the Manufacturing Order ID and make it readonly
                    echo '<input type="text" class="form-control" name="order_id" value="' . generateOrderId($conn) . '" readonly>';
                    ?>
                </div>
                <div style="float: right;" class="form-group col-xs-4">
                    <label for="due_date">Production Deadline:</label>
                    <input type="date" class="form-control" name="due_date" required>
                </div>
            </div>
            <div style="margin: 20px;" class="form-group row">
                <div class="form-group col-xs-4">
                    <label for="product">Product:</label>
                    <select class="form-control" name="product_id" required>
                        <option value="" disabled selected>---Select Product---</option>
                        <?php
                        // Fetch products from the productlist table
                        $productlistQuery = $conn->query("SELECT * FROM productlist ORDER BY Name ");

                        while ($productlistRow = $productlistQuery->fetch_assoc()) {
                            echo '<option value="' . $productlistRow['ProductID'] . '">' . $productlistRow['Name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div style="float: right;" class="form-group col-xs-4">
                    <label for="o_date">Created Date:</label>
                    <input type="date" class="form-control" name="o_date" required>
                </div>
            </div>

            <div style="margin: 20px;" class="form-group row">
                <div class="form-group col-xs-2">
                    <label for="o_quantity">Batch To Produce:</label>
                    <input type="number" class="form-control" name="o_quantity" id="o_quantity" value="1">
                </div>
            </div>

            <div id="dynamicFields" style="display: none;">
                <div style="margin: 20px;" class="form-group row">
                    <div class="form-group col-xs-2">
                        <label for="batch_amount">Amount:</label>
                        <input type="number" class="form-control" name="batch_amount" id="batch_amount" required readonly>
                    </div>
                    <div class="form-group col-xs-3">
                        <label for="pack">Pack:</label>
                        <input type="text" class="form-control" name="pack" id="pack" required readonly>

                    </div>
                    <div class="form-group col-xs-3">
                        <label for="measure_and_unit">Unit:</label>
                        <input type="text" class="form-control" name="measure_and_unit" id="measure_and_unit" required readonly>
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
                                                <th>Quantity Required</th>
                                                <th>Cost (PHP)</th>
                                                <th>Availability</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tablemats">
                                            <!-- Table content will be dynamically populated here -->
                                        </tbody>
                                    </table>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div style="float: right;" class="form-group row">
                <a href="production.php" class="btn btn-danger">
                    Back
                </a>
                <button type="submit" class="btn btn-success" onclick="return checkAllAvailability()">Create Manufacturing Order</button>


            </div>

        </div>
    </form>









</article>
<script>
    document.getElementById('productionForm').addEventListener('change', function() {
        var productId = document.getElementsByName('product_id')[0].value;
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState === 4) {
                if (this.status === 200) {
                    var response = JSON.parse(this.responseText);

                    // Log the response to debug
                    console.log(response);

                    if (response.pack !== undefined && response.unit !== undefined && response.measure !== undefined && response.batch_amount !== undefined) {
                        var batchAmountElement = document.getElementById('batch_amount');
                        var packElement = document.getElementById('pack');
                        var measureAndUnitElement = document.getElementById('measure_and_unit');
                        var dynamicFieldsElement = document.getElementById('dynamicFields');

                        // Check if the elements exist before setting their values
                        if (batchAmountElement && packElement && measureAndUnitElement && dynamicFieldsElement) {
                            batchAmountElement.value = response.batch_amount || 0;
                            packElement.value = response.pack;
                            measureAndUnitElement.value = response.measure + ' ' + response.unit; // Combine measure and unit
                            dynamicFieldsElement.style.display = 'block';

                            // Trigger input event on o_quantity to update batch_amount
                            var oQuantityElement = document.getElementById('o_quantity');
                            if (oQuantityElement) {
                                var event = new Event('input');
                                oQuantityElement.dispatchEvent(event);

                                // Fetch materials and populate the table
                                fetchMaterials(productId);
                            }
                        } else {
                            console.error('One or more elements not found.');
                        }
                    } else {
                        console.error('Pack, unit, measure, or batch_amount not defined in the response');
                    }
                } else {
                    console.error('Error fetching product info. Status:', this.status);
                }
            }
        };

        xhr.open('GET', 'fetch_product_info.php?product_id=' + productId, true);
        xhr.send();
    });


    function fetchMaterials(productId) {
        var tableBody = document.getElementById('tablemats');
        tableBody.innerHTML = ''; // Clear existing table content

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState === 4) {
                if (this.status === 200) {
                    var materials = JSON.parse(this.responseText);

                    // Log the materials to debug
                    console.log(materials);

                    // Populate the table with materials
                    materials.forEach(function(material) {
                        var row = document.createElement('tr');

                        var nameCell = document.createElement('td');
                        nameCell.textContent = material.RawMaterialName;
                        row.appendChild(nameCell);

                        var quantityCell = document.createElement('td');
                        var quantityInKgOrL = convertToKgOrL(material.QuantityRequired, material.qty_unit);
                        quantityCell.textContent = quantityInKgOrL; // Display the quantity in Kg or L
                        row.appendChild(quantityCell);

                        // Calculate total cost
                        var totalCost = calculateCost(material.QuantityRequired, material.qty_unit, material.RawMaterialPrice);

                        // Log total cost for debugging
                        console.log("Total Cost: " + totalCost);

                        var costCell = document.createElement('td');
                        costCell.textContent = totalCost.toFixed(2); // Display total cost with 2 decimal places
                        row.appendChild(costCell);

                        var availabilityCell = document.createElement('td');
                        // Implement logic for availability
                        checkAvailability(material.RawMaterialID, material.QuantityRequired, availabilityCell);
                        row.appendChild(availabilityCell);

                        var actionCell = document.createElement('td');
                        actionCell.innerHTML = `<button class="btn btn-primary" onclick="redirectToReceiving()">Add Purchase Order</button>`;


                        row.appendChild(actionCell);

                        tableBody.appendChild(row);
                    });
                } else {
                    console.error('Error fetching materials. Status:', this.status);
                }
            }
        };

        xhr.open('GET', 'fetch_materials.php?product_id=' + productId, true);
        xhr.send();
    }

    function calculateCost(quantity, unit, price) {
        // Convert the quantity to kilograms for uniformity if the unit is grams
        if (unit === 'g') {
            quantity = quantity / 1000; // Convert grams to kilograms
        } else if (unit === 'ml') {
            quantity = quantity / 1000; // Convert milliliters to liters
        }

        // Calculate the total cost
        var totalCost = quantity * price;

        return totalCost;
    }


    // Define a global variable to store availability data
    var availabilityData = {};

    // Function to check availability
    function checkAvailability(rawMaterialID, requiredQuantity, availabilityCell) {
        // Check if availability data for this raw material has been fetched
        if (availabilityData.hasOwnProperty(rawMaterialID)) {
            // Use the stored availability data
            updateAvailabilityDisplay(rawMaterialID, requiredQuantity, availabilityCell);
        } else {
            // Fetch availability data asynchronously
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState === 4) {
                    if (this.status === 200) {
                        var availableQuantity = parseInt(this.responseText);
                        availabilityData[rawMaterialID] = availableQuantity; // Store availability data
                        updateAvailabilityDisplay(rawMaterialID, requiredQuantity, availabilityCell);
                    } else {
                        console.error('Error checking availability. Status:', this.status);
                    }
                }
            };

            xhr.open('GET', 'check_availability.php?raw_material_id=' + rawMaterialID, true);
            xhr.send();
        }
    }

    // Function to update the availability display
    function updateAvailabilityDisplay(rawMaterialID, requiredQuantity, availabilityCell) {
        var availableQuantity = availabilityData[rawMaterialID];
        console.log("Raw Material ID:", rawMaterialID);
        console.log("Required Quantity:", requiredQuantity);
        console.log("Available Quantity:", availableQuantity);
        // Compare available quantity with required quantity
        if (availableQuantity >= requiredQuantity) {
            // Raw material is available
            availabilityCell.textContent = 'Available';
            availabilityCell.classList.add('text-success'); // Add a success class for styling
        } else {
            // Raw material is not available
            availabilityCell.textContent = 'Not Available';
            availabilityCell.classList.add('text-danger'); // Add a danger class for styling
        }
    }


    function updateQuantityRequired(quantityMultiplier) {
        var rows = document.querySelectorAll('#tablemats tbody tr');
        rows.forEach(function(row) {
            var originalQuantityElement = row.querySelector('td:nth-child(2)');
            var originalQuantity = parseFloat(originalQuantityElement.dataset.originalQuantity) || 0;
            var updatedQuantity = originalQuantity * quantityMultiplier;
            originalQuantityElement.textContent = convertToKgOrL(updatedQuantity, row.dataset.qtyUnit);

            // Log the changes
            console.log('Original Quantity:', originalQuantity);
            console.log('Multiplier:', quantityMultiplier);
            console.log('Updated Quantity:', updatedQuantity);

            // Update the required quantity attribute for further calculations
            originalQuantityElement.dataset.originalQuantity = updatedQuantity;
        });
    }
    document.getElementById('o_quantity').addEventListener('input', function() {
        var batchAmountElement = document.getElementById('batch_amount');
        var availableQuantity = parseInt(batchAmountElement.value) || 0;
        var quantityMultiplier = parseInt(this.value) || 1;
        console.log('Available Quantity:', availableQuantity);
        console.log('Quantity Multiplier:', quantityMultiplier);
        batchAmountElement.value = availableQuantity * (quantityMultiplier > 0 ? quantityMultiplier : 1);

        // Update availability display for each raw material
        var rows = document.querySelectorAll('#tablemats tbody tr');
        rows.forEach(function(row) {
            var rawMaterialID = row.dataset.rawMaterialId;
            var requiredQuantity = parseFloat(row.querySelector('td:nth-child(2)').dataset.originalQuantity) * quantityMultiplier;
            var availabilityCell = row.querySelector('td:nth-child(4)');

            checkAvailability(rawMaterialID, requiredQuantity, availabilityCell);
        });

        // Call function to update quantity required
        updateQuantityRequired(quantityMultiplier);
    });




    // Function to convert quantity to Kg or L based on unit
    function convertToKgOrL(quantity, unit) {
        switch (unit) {
            case 'g':
                // Convert grams to kilograms and append 'Kg' to the quantity
                return (quantity / 1000).toFixed(3) + ' Kg';
            case 'ml':
                // Convert milliliters to liters and append 'L' to the quantity
                return (quantity / 1000).toFixed(3) + ' L';
            default:
                // For other units, return the quantity as is
                return quantity;
        }
    }






    // Modify the addPurchaseOrder function to check displayed availability directly
    function addPurchaseOrder() {
        var availabilityCells = document.querySelectorAll('#tablemats td:last-child'); // Get all availability cells

        var anyMaterialNotAvailable = false; // Flag to track if any material is not available

        // Loop through each availability cell to check if any material is not available
        availabilityCells.forEach(function(availabilityCell) {
            console.log("Availability Cell Content:", availabilityCell.textContent.trim().toLowerCase());
            if (availabilityCell.textContent.trim().toLowerCase() !== 'Available') {
                // Raw material is not available
                anyMaterialNotAvailable = true;
            }
        });

        if (anyMaterialNotAvailable) {
            // If any material is not available, proceed to receiving
            console.log("Not all materials are available. Proceeding to receiving.");
            window.location.href = 'c_receiving.php';
        } else {
            // If all materials are available, prompt the user for confirmation
            var confirmPurchase = confirm("All materials are available. Are you sure you want to proceed with the purchase?");
            if (confirmPurchase) {
                console.log("User confirmed purchase. Proceeding to manufacturing.");
                // Proceed with the purchase
            } else {
                console.log("User canceled the purchase.");
                // Optionally, you can provide more information to the user or take other actions.
            }
        }
    }
</script>

<?php include 'footer.php'; ?>