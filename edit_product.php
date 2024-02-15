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



$productId = null;
$productDetails = array(); // Initialize $productDetails as an empty array
$BOMDetails = array(); // Initialize $BOMDetails as an empty array

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $productId = sanitize($_GET['id']);
    echo "Product ID: $productId";

    // Fetch product details from the database based on the product ID
    $stmtProductDetails = $conn->prepare("SELECT * FROM productlist WHERE ProductID = ?");
    $stmtProductDetails->bind_param("s", $productId);
    $stmtProductDetails->execute();
    $productDetailsResult = $stmtProductDetails->get_result();
    $productDetails = $productDetailsResult->fetch_assoc();

    // Fetch BOM details from the database based on the product ID
    $stmtBOMDetails = $conn->prepare("SELECT * FROM bom WHERE ProductID = ?");
    $stmtBOMDetails->bind_param("s", $productId);
    $stmtBOMDetails->execute();
    $BOMDetailsResult = $stmtBOMDetails->get_result();
    $BOMDetails = $BOMDetailsResult->fetch_all(MYSQLI_ASSOC);

    $stmtProductDetails->close();
    $stmtBOMDetails->close();
} else {
    echo <<<HTML
<div class="alert alert-warning" role="alert">
<strong>Warning!</strong> No product ID provided.
</div>
HTML;
}
?>

<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">Edit Product</h3>
                </div>
            </div>
        </div>

        <form name="item" method="POST" action="">
            <div class="card card-block">
                <div class="form-group row">
                    <div class="form-group col-xs-4">
                        <label for="rawMaterialsID">Product ID:</label>
                        <input type="text" class="form-control" name="rawMaterialsID" id="rawMaterialsID" value="<?php echo $productDetails['ProductID']; ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group col-xs-4">
                        <label for="name">Product Name:</label>
                        <input type="text" class="form-control" name="name" id="name" value="<?php echo $productDetails['Name']; ?>" placeholder="Enter Product Name" readonly required>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group col-xs-4">
                        <label for="unit">Unit:</label>
                        <select class="form-control" name="unit">
                            <option value="" <?php echo ($productDetails['Unit'] == '') ? 'selected' : ''; ?>>---Select Unit---</option>
                            <option value="Kg" <?php echo ($productDetails['Unit'] == 'Kilogram') ? 'selected' : ''; ?>>Kilograms (kg)</option>
                            <option value="g" <?php echo ($productDetails['Unit'] == 'Gram') ? 'selected' : ''; ?>>Gram (g)</option>
                            <option value="mg" <?php echo ($productDetails['Unit'] == 'Milligram') ? 'selected' : ''; ?>>Milligram (mg)</option>
                            <option value="L" <?php echo ($productDetails['Unit'] == 'Liter') ? 'selected' : ''; ?>>Liter (l)</option>
                            <option value="ml" <?php echo ($productDetails['Unit'] == 'Millilitre') ? 'selected' : ''; ?>>Millilitre (ml)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group col-xs-6">
                        <label for="created Date">Description:</label>
                        <input type="text" class="form-control" name="description" id="description" value="<?php echo $productDetails['Description']; ?>" placeholder="Describe Product" required>
                    </div>
                </div>

                <hr>
                <br>
                <h3>Edit Bill of Materials</h3>
                <br>
                <h4>For 1 Preparation:</h4>
                <div class="card card-block">
                    <?php
                    foreach ($BOMDetails as $index => $BOMDetail) {
                        echo '<div class="form-group row">';
                        echo '<div class="form-group col-xs-4">';
                        echo '<label for="rawMaterial">Raw Material:</label>';
                        echo '<select class="form-control" name="raw[]" required>';

                        // Retrieve raw materials from the rawmaterials table
                        $rawmaterialQuery = $conn->query("SELECT * FROM rawmaterials ORDER BY name ");
                        while ($rawMaterialRow = $rawmaterialQuery->fetch_assoc()) {
                            $selected = ($rawMaterialRow['RawMaterialID'] == $BOMDetail['RawMaterialID']) ? 'selected' : '';
                            echo '<option value="' . $rawMaterialRow['RawMaterialID'] . '" ' . $selected . '>' . $rawMaterialRow['Name'] . '</option>';
                        }

                        echo '</select>';
                        echo '</div>';

                        echo '<div class="form-group col-xs-3">';
                        echo '<label for="Quantity">Quantity Required:</label>';
                        echo '<input type="text" class="form-control" name="quantity[]" value="' . $BOMDetail['QuantityRequired'] . '" placeholder="Enter Required Quantity" required>';
                        echo '</div>';

                        echo '<div class="form-group col-xs-3">';
                        echo '<label for="qty_unit">Unit:</label>';
                        echo '<select class="form-control" name="qty_unit[]" required>';
                        echo '<option value="Kilogram" ' . ($BOMDetail['qty_unit'] == 'Kilogram' ? 'selected' : '') . '>Kilograms (kg)</option>';
                        echo '<option value="Gram" ' . ($BOMDetail['qty_unit'] == 'Gram' ? 'selected' : '') . '>Gram (g)</option>';
                        echo '<option value="Milligram" ' . ($BOMDetail['qty_unit'] == 'Milligram' ? 'selected' : '') . '>Milligram (mg)</option>';
                        echo '<option value="Liter" ' . ($BOMDetail['qty_unit'] == 'Liter' ? 'selected' : '') . '>Liter (l)</option>';
                        echo '<option value="Millilitre" ' . ($BOMDetail['qty_unit'] == 'Millilitre' ? 'selected' : '') . '>Millilitre (ml)</option>';
                        echo '</select>';
                        echo '</div>';

                        echo '</div>';
                    }
                    ?>
                    <div id="additionalFormGroups"></div>
                </div>

                <div class="form-group col-xs-2">
                    <button type="button" class="btn btn-success btn-sm" onclick="addFormGroup()">Add More</button>
                </div>

                <br>

                <div style="float: right;" class="form-group row">
                    <div class="form-group col-xs-4">
                        <label for="batch_amount">Result amount:</label>
                        <input type="number" class="form-control" name="batch_amount" id="batch_amount" value="<?php echo $productDetails['batch_amount']; ?>" placeholder="Amount" required>
                    </div>

                    <div style="float: right;" class="form-group col-xs-4">
                        <label for="price">Selling Price:</label>
                        <input type="number" class="form-control" name="price" id="price" value="<?php echo isset($productDetails['Price']) ? $productDetails['Price'] : 0; ?>" placeholder="Enter Price" required>
                    </div>



                    <div style="float: right;" class="form-group col-xs-4">
                        <label for="prod_time">Production Time:</label>
                        <div class="input-group">
                            <select class="form-control" name="hours" required>
                                <?php
                                for ($i = 0; $i <= 24; $i++) {
                                    $selected = ($i == floor($productDetails['prod_time'] / 3600)) ? 'selected' : '';
                                    echo "<option value='$i' $selected>$i hour(s)</option>";
                                }
                                ?>
                            </select>
                            <span class="input-group-addon">:</span>
                            <select class="form-control" name="minutes" required>
                                <?php
                                for ($i = 0; $i <= 59; $i++) {
                                    $selected = ($i == floor(($productDetails['prod_time'] % 3600) / 60)) ? 'selected' : '';
                                    echo "<option value='$i' $selected>$i minutes</option>";
                                }
                                ?>
                            </select>
                            <span class="input-group-addon">:</span>
                            <select class="form-control" name="seconds" required>
                                <?php
                                for ($i = 0; $i <= 59; $i++) {
                                    $selected = ($i == $productDetails['prod_time'] % 60) ? 'selected' : '';
                                    echo "<option value='$i' $selected>$i seconds</option>";
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
        </form>
    </div>


    <script>
        function addFormGroup() {
            var additionalFormGroups = document.getElementById('additionalFormGroups');
            var newFormGroup = document.createElement('div');
            newFormGroup.className = 'form-group row';
            newFormGroup.innerHTML = `
                <div class="form-group col-xs-4">
                    <label for="rawMaterial">Raw Material:</label>
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
                        <option value="Kilogram">Kilograms (kg)</option>
                        <option value="Gram">Gram (g)</option>
                        <option value="Milligram">Milligram (mg)</option>
                        <option value="Liter">Liter (l)</option>
                        <option value="Millilitre">Millilitre (ml)</option>
                    </select>
                </div>
            `;
            additionalFormGroups.appendChild(newFormGroup);
        }
    </script>
    </articl