<?php
include 'header.php';
include 'sidebar.php';
include 'db_connect.php';


?>

<article class="content item-editor-page">
    <div class="title-block">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="title">
                    Edit Bill Of Material
                </h3>
            </div>
        </div>
    </div>

    <form name="item" method="POST" action="">
        <div class="card card-block">
            <div class="form-group row">
                <div class="form-group col-xs-6">
                    <label for="product">Product</label>
                    <select class="form-control" name="product" id="product" required>
                        <option value="" disabled selected>-Select Product-</option>
                        <?php
                        // Retrieve products from the productlist table
                        $productQuery = $conn->query("SELECT * FROM productlist ORDER BY Name");
                        while ($productRow = $productQuery->fetch_assoc()) {
                            $selected = ($productRow['ProductID'] == $bomData['ProductID']) ? 'selected' : '';
                            echo '<option value="' . $productRow['ProductID'] . '" ' . $selected . '>' . $productRow['Name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="card card-block">
                <div class="form-group row">
                    <div class="form-group col-xs-4">
                        <label for="rawMaterial">Raw Material</label>
                        <select class="form-control" name="raw" required>
                            <option value="" disabled selected>---Select Raw Material---</option>
                            <?php
                            // Retrieve raw materials from the rawmaterials table
                            $rawmaterialQuery = $conn->query("SELECT * FROM rawmaterials ORDER BY name ");
                            while ($rawMaterialRow = $rawmaterialQuery->fetch_assoc()) {
                                $selected = ($rawMaterialRow['RawMaterialID'] == $bomData['RawMaterialID']) ? 'selected' : '';
                                echo '<option value="' . $rawMaterialRow['RawMaterialID'] . '" ' . $selected . '>' . $rawMaterialRow['Name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-xs-3">
                        <label for="Quantity">Quantity Required</label>
                        <input type="text" class="form-control" name="quantity" value="<?php echo $bomData['QuantityRequired']; ?>" placeholder="Enter Required Quantity" required>
                    </div>
                    <div class="form-group col-xs-3">
                        <label for="unit">Unit</label>
                        <select class="form-control" name="unit" required>
                            <option value="" disabled selected>---Select Unit---</option>
                            <option value="Kg" <?php echo ($bomData['Unit'] == 'Kilogram') ? 'selected' : ''; ?>>Kilograms (kg)</option>
                            <option value="g" <?php echo ($bomData['Unit'] == 'Gram') ? 'selected' : ''; ?>>Gram (g)</option>
                            <option value="mg" <?php echo ($bomData['Unit'] == 'Milligram') ? 'selected' : ''; ?>>Milligram (mg)</option>
                            <option value="L" <?php echo ($bomData['Unit'] == 'Liter') ? 'selected' : ''; ?>>Liter (l)</option>
                            <option value="ml" <?php echo ($bomData['Unit'] == 'Millilitre') ? 'selected' : ''; ?>>Millilitre (ml)</option>
                        </select>
                    </div>

                </div>

                <div id="additionalFormGroups"></div>
                <div class="form-group col-xs-2">
                    <button type="button" class="btn btn-success btn-sm" onclick="addFormGroup()">Add More</button>
                </div>

            </div>


            <div class="form-group" style="float: right;">
                <a href="bom.php" class="btn btn-danger ">Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>

    </form>


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
                    <label for="unit">Unit</label>
                    <select class="form-control" name="unit[]">
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
</article>

</html>
</body>

</html>