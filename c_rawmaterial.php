<?php
include 'header.php';
include 'sidebar.php';
include 'db_connect.php';

$allowedUserTypes = array(2, 4, 5);

checkUserTypeAccess($allowedUserTypes, 'login.php', 'You are not allowed to access this page.');

function sanitize($input)
{
    return htmlspecialchars(strip_tags(trim($input)));
}

function generateRawMaterialsID($conn)
{
    // Fetch the latest RawMaterialsID from the database
    $query = "SELECT MAX(RawMaterialID) AS maxID FROM rawmaterials";
    $result = mysqli_query($conn, $query);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        $maxID = $row['maxID'];

        // If there are existing records, increment the ID; otherwise, start from 201
        $newID = $maxID ? $maxID + 1 : 201;

        // Format the ID as 000201, 000202, etc.
        return sprintf('%06d', $newID);
    }

    return false;
}

// Fetch the generated RawMaterialsID
$generatedID = generateRawMaterialsID($conn);


// Define $insertStatus to handle success/failure message
$insertStatus = false;

?>

<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">Create Raw Materials</h3>
                </div>
            </div>
        </div>
        <?php


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rawMaterialsID = sanitize($_POST['rawMaterialsID']); // Added RawMaterialsID input
            $name = sanitize($_POST['name']);
            $unit = sanitize($_POST['unit']);
            $price = sanitize($_POST['price']);  // Corrected typo: 'price]' to 'price'

            // Your database insertion code here...
            $stmt = $conn->prepare("INSERT INTO rawmaterials (RawMaterialID, Name, Unit, Price) VALUES (?, ?, ?, ?)");

            // Bind the variables to the placeholders
            $stmt->bind_param("ssss", $rawMaterialsID, $name, $unit, $price);

            if ($stmt->execute()) {
                // Set the insertion status to true
                $insertStatus = true;
                echo '<div class="alert alert-success" role="alert">Data saved to the database.</div>';
            } else {
                // Additional error handling
                echo '<div class="alert alert-danger" role="alert">Error executing statement: ' . $stmt->error . '</div>';
            }

            $stmt->close();
        }
        ?>

        <form name="item" method="POST" action="">
            <div class="card card-block">
                <div class="form-group row">
                    <div class="form-group col-xs-3">
                        <label for="rawMaterialsID">RawMaterialsID:</label>
                        <input type="text" class="form-control" name="rawMaterialsID" id="rawMaterialsID" value="<?php echo $generatedID; ?>" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group col-xs-4">
                        <label for="name">Material Name:</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Material Name" required>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group col-xs-3">
                        <label for="unit">Unit:</label>
                        <select class="form-control" name="unit" id="unit">
                            <option value="">---Select Unit---</option>

                            <option value="g">Gram (g)</option>

                            <option value="ml">Millilitre (ml)</option>
                        </select>
                    </div>
                    <div class="form-group col-xs-3">
                        <label for="price">Purchase Price:</label>
                        <input type="number" class="form-control" name="price" id="price" placeholder="Enter Purchase Price">
                    </div>
                </div>

                <button type="submit" class="btn btn-success" style="float: right;">Submit</button>
                <a href="rawmaterials.php" class="btn btn-danger" style="float: right;">
                    Back
                </a>
            </div>
        </form>
    </div>
</article>
</body>

</html>