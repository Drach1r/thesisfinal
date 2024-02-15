<?php
include 'header.php';
include 'sidebar.php';
include 'db_connect.php';

$allowedUserTypes = array(2, 4, 5);

checkUserTypeAccess($allowedUserTypes, 'login.php', 'You are not allowed to access this page.');
function sanitize($data)
{
    if (is_array($data)) {
        // If $data is an array, sanitize each element
        return array_map('sanitize', $data);
    } else {
        // If $data is a string or other scalar value, apply sanitization
        return is_scalar($data) ? htmlspecialchars(stripslashes(trim($data))) : $data;
    }
}

// Function to generate product ID
function generateProductID($conn)
{
    $query = "SELECT MAX(ProductID) AS maxID FROM productlist";
    $result = mysqli_query($conn, $query);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        $maxID = $row['maxID'];
        $newID = $maxID ? $maxID + 1 : 101;

        return sprintf('%06d', $newID);
    }

    return '101'; // Default value in case of an issue
}




?>

<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">Create Product</h3>

                </div>
            </div>
        </div>
        <?php
        // Function to convert units
        function convertUnits($quantity, $unit)
        {
            // Convert liters to milliliters
            if ($unit === 'L') {
                $quantity *= 1000; // Convert liters to milliliters
            }
            // Convert kilograms to grams
            elseif ($unit === 'Kg') {
                $quantity *= 1000; // Convert kilograms to grams
            }

            return $quantity;
        }

        $generatedID = generateProductID($conn);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Generate the ProductID before inserting into the productlist table
            $productID = $generatedID;
            $name = sanitize($_POST['name']);
            $description = sanitize($_POST['description']);
            $measure = sanitize($_POST['measure']);
            $unit = is_array($_POST['unit']) ? sanitize($_POST['unit'][0]) : sanitize($_POST['unit']);
            $pack = sanitize($_POST['pack']);

            $price = isset($_POST['price']) ? intval($_POST['price']) : 0;
            $hours = isset($_POST['hours']) ? intval($_POST['hours']) : 0;
            $minutes = isset($_POST['minutes']) ? intval($_POST['minutes']) : 0;
            $seconds = isset($_POST['seconds']) ? intval($_POST['seconds']) : 0;
            $prod_time = ($hours * 3600) + ($minutes * 60) + $seconds;
            $batch_amount = sanitize($_POST['batch_amount']);

            // Insert data into productlist table
            $stmtProduct = $conn->prepare("INSERT INTO productlist (ProductID, Name, Description, Unit, Price, prod_time, batch_amount, pack, measure) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmtProduct->bind_param("ssssidisi", $productID, $name, $description, $unit, $price, $prod_time, $batch_amount, $pack, $measure);
            if ($stmtProduct->execute()) {
                // Success message
                $successMessage = 'Product information created successfully.';
                echo "<div class='alert alert-success'>$successMessage</div>";

                foreach ($_POST['raw'] as $index => $rawMaterialID) {
                    $quantity = sanitize($_POST['quantity'][$index]);
                    $qty_unit = sanitize($_POST['qty_unit'][$index]);

                    // Convert quantity based on the unit
                    $quantity = convertUnits($quantity, $qty_unit);

                    // Insert data into bom table with QuantityRequired as a decimal
                    $stmtBOM = $conn->prepare("INSERT INTO bom (ProductID, RawMaterialID, QuantityRequired, qty_unit) VALUES (?, ?, ?, ?)");
                    $stmtBOM->bind_param("siss", $productID, $rawMaterialID, $quantity, $qty_unit);

                    if (!$stmtBOM->execute()) {
                        // Handle error if the BOM insertion fails
                        $errorMessage = 'Error creating BOM: ' . $stmtBOM->error;
                        echo "<div class='alert alert-danger'>$errorMessage</div>";
                    }
                }

                $stmtBOM->close();
            } else {
                // Error message
                $errorMessage = 'Error creating product information: ' . $stmtProduct->error;
                echo "<div class='alert alert-danger'>$errorMessage</div>";
                // Log the error or provide feedback to the user
                // You might also consider rolling back the transaction in case of an error
            }

            $stmtProduct->close();
        }

        ?>


        <form name="item" method="POST" action="">
            <div class="card card-block">
                <div class="form-group row">
                    <div class="form-group col-xs-4">
                        <label for="rawMaterialsID">Product ID:</label>
                        <input type="text" class="form-control" name="rawMaterialsID" id="rawMaterialsID" value="<?php echo $generatedID; ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group col-xs-4">
                        <label for="name">Product Name:</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Product Name" required>
                    </div>
                </div>
                <div class="form-group row">

                    <div class="form-group col-xs-3">
                        <label for="price"> Measure per Unit:</label>
                        <input type="number" class="form-control" name="measure" id="measure" placeholder="Enter Measure" required>
                    </div>
                    <div class="form-group col-xs-4">
                        <label for="unit">Unit:</label>
                        <select class="form-control" name="unit">
                            <option value="">---Select Unit---</option>
                            <option value="Kg">Kilograms (kg)</option>
                            <option value="g">Gram (g)</option>
                            <option value="L">Liter (l)</option>
                            <option value="ml">Millilitre (ml)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group col-xs-6">
                        <label for="created Date">Description:</label>
                        <input type="text" class="form-control" name="description" id="description" placeholder="Describe Product" required>
                    </div>

                </div>


                <br>

                <hr>
                <br>
                <h3> Create Bill of Materials</h3>
                <br>
                <h4> For 1 Preparation:</h4>
                <div class="card card-block">
                    <div class="form-group row">
                        <div class="form-group col-xs-4">
                            <label for="rawMaterial">Raw Material:</label>
                            <select class="form-control" name="raw[]" required>
                                <option value="" disabled selected>---Select Raw Material---</option>
                                <?php
                                // Retrieve raw materials from the rawmaterials table
                                $rawmaterialQuery = $conn->query("SELECT * FROM rawmaterials ORDER BY name ");
                                while ($rawMaterialRow = $rawmaterialQuery->fetch_assoc()) {
                                    echo '<option value="' . $rawMaterialRow['RawMaterialID'] . '">' . $rawMaterialRow['Name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-xs-3">
                            <label for="Quantity">Quantity Required:</label>
                            <input type="text" class="form-control" name="quantity[]" placeholder="Enter Required Quantity" required>
                        </div>

                        <div class="form-group col-xs-3">
                            <label for="qty_unit">Unit:</label>
                            <select class="form-control" name="qty_unit[]" required>
                                <option value="">---Select Unit---</option>

                                <option value="g">Gram (g)</option>

                                <option value="ml">Millilitre (ml)</option>
                            </select>
                        </div>
                    </div>
                    <div id="additionalFormGroups"></div>

                </div>


                <div class="form-group col-xs-2">
                    <button type="button" class="btn btn-success btn-sm" onclick="addFormGroup()">Add More</button>
                </div>

                <br>






                <div style="float: right;" class="form-group row">
                    <div class="form-group col-xs-2">
                        <label for="batch_amount">Result amount:</label>
                        <input type="number" class="form-control" name="batch_amount" id="batch_amount" placeholder="Amount" required>
                    </div>

                    <div class="form-group col-xs-2">
                        <label for="batch_amount">Package:</label>
                        <select class="form-control" name="pack" id="pack" required>
                            <option value="">-Select Package-</option>
                            <option value="Pouch">Pouch</option>
                            <option value="Bottle">Bottle</option>
                            <option value="Container">Container</option>

                        </select>
                    </div>

                    <div style="float: right;" class="form-group col-xs-4">
                        <label for="price"> Selling Price:</label>
                        <input type="number" class="form-control" name="price" id="price" placeholder="Enter Price" required>
                    </div>
                    <div style="float: right;" class="form-group col-xs-4">
                        <label for="prod_time">Production Time:</label>
                        <div class="input-group">
                            <select class="form-control" name="hours" required>
                                <?php
                                for ($i = 0; $i <= 24; $i++) {
                                    echo "<option value='$i'>$i hour(s)</option>";
                                }
                                ?>
                            </select>
                            <span class="input-group-addon">:</span>
                            <select class="form-control" name="minutes" required>
                                <?php
                                for ($i = 0; $i <= 59; $i++) {
                                    echo "<option value='$i'>$i minutes</option>";
                                }
                                ?>
                            </select>
                            <span class="input-group-addon">:</span>
                            <select class="form-control" name="seconds" required>
                                <?php
                                for ($i = 0; $i <= 59; $i++) {
                                    echo "<option value='$i'>$i seconds</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div style="float: right;" class="form-group row">
                    <a href="product.php" class="btn btn-danger">
                        Back
                    </a>
                    <button type="submit" class="btn btn-success">Submit</button>

                </div>
            </div>
    </div>
    </form>
    </div>

    <script>
        function addFormGroup() {
            var additionalFormGroups = document.getElementById('additionalFormGroups');
            var newFormGroup = document.createElement('div');
            newFormGroup.className = 'form-group row';
            newFormGroup.innerHTML = `
            <div class="form-group col-xs-4">
                <label for="rawMaterial">Raw Material</label>
                <select class="form-control" name="raw[]" required>
                    <option value="" disabled selected>-Select Raw Material-</option>
                    <?php
                    // Retrieve raw materials from the rawmaterials table
                    $rawmaterialQuery = $conn->query("SELECT * FROM rawmaterials ORDER BY name ");
                    while ($rawMaterialRow = $rawmaterialQuery->fetch_assoc()) {
                        echo '<option value="' . $rawMaterialRow['RawMaterialID'] . '">' . $rawMaterialRow['Name'] . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col-xs-3">
                <label for="Quantity">Quantity Required</label>
                <input type="text" class="form-control" name="quantity[]" placeholder="Enter Required Quantity" required>
            </div>
            <div class="form-group col-xs-3">
                <label for="qty_unit">Unit</label>
                <select class="form-control" name="qty_unit[]" required>
                    <option value="">---Select Unit---</option>
                 
                    <option value="g">Gram (g)</option>
             
                   
                    <option value="ml">Millilitre (ml)</option>
                </select>
            </div>
        `;
            additionalFormGroups.appendChild(newFormGroup);
        }
    </script>


</article>