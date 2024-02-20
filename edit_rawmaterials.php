<?php
include 'header.php';
include 'sidebar.php';
include 'db_connect.php';

$allowedUserTypes = array(2, 4, 5);
checkUserTypeAccess($allowedUserTypes, 'login.php', 'You are not allowed to access this page.');


?>

<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">Edit Raw Materials</h3>
                </div>
            </div>
        </div>
        <?php function sanitize($input)
        {
            return htmlspecialchars(strip_tags(trim($input)));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {


            $rawMaterialsID = isset($_POST['rawMaterialsID']) ? sanitize($_POST['rawMaterialsID']) : '';
            $unit = isset($_POST['unit']) ? sanitize($_POST['unit']) : '';
            $price = isset($_POST['price']) ? sanitize($_POST['price']) : '';




            $updateQuery = "UPDATE rawmaterials SET Unit = ?, Price = ? WHERE RawMaterialID = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("ssi", $unit, $price, $rawMaterialsID);



            if ($updateStmt->execute()) {
                echo '<div class="alert alert-success" role="alert">Data updated successfully.</div>';
            } else {
                echo '<div class="alert alert-warning" role="alert">Error updating data: ' . $updateStmt->error . '</div>';
            }


            $updateStmt->close();
        }

        $rawMaterialID = isset($_GET['id']) ? sanitize($_GET['id']) : '';


        $query = "SELECT RawMaterialID, Name, Unit, Price FROM rawmaterials WHERE RawMaterialID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $rawMaterialID);
        $stmt->execute();
        $stmt->bind_result($existingID, $existingName, $existingUnit, $existingPrice);
        $stmt->fetch();
        $stmt->close();
        ?>
        <form name="item" method="POST" action="">
            <div class="card card-block">
                <div class="form-group row">
                    <div class="form-group col-xs-4">
                        <label for="rawMaterialsID">RawMaterialsID:</label>
                        <input type="text" class="form-control" name="rawMaterialsID" id="rawMaterialsID" value="<?php echo $existingID; ?>" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group col-xs-4">
                        <label for="name">Material Name:</label>
                        <input type="text" class="form-control" name="name" id="name" value="<?php echo $existingName; ?>" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group col-xs-3">
                        <label for="unit">Unit:</label>
                        <select class="form-control" name="unit" id="unit">

                            <option value="Kg"> <?php echo ($existingUnit === 'Kg') ? 'selected' : ''; ?>Kilogram (kg)</option>
                            <option value="Mg"><?php echo ($existingUnit === 'mg') ? 'selected' : ''; ?>Milligram (mg)</option>
                            <option value="L"><?php echo ($existingUnit === 'L') ? 'selected' : ''; ?>Liter (L)</option>
                            <option value="Ml"><?php echo ($existingUnit === 'ml') ? 'selected' : ''; ?>Millilitre (ml)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group col-xs-3">
                        <label for="price">Purchase Price:</label>
                        <input type="number" class="form-control" name="price" id="price" value="<?php echo $existingPrice; ?>" required step="any">
                    </div>
                </div>


                <button type="submit" class="btn btn-success">Save Changes</button>
                <a href="rawmaterials.php" class="btn btn-danger">Back</a>
            </div>
        </form>
    </div>
</article>
</body>

</html>