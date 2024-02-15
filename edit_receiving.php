<?php
include 'header.php';
include 'sidebar.php';
include 'db_connect.php';

// Fetch data related to the inserted ID
function fetchInsertedStockData($conn, $stock_in_id)
{
    $stockInDataQuery = "SELECT * FROM stock_in WHERE id = $stock_in_id";
    $stockInDataResult = mysqli_query($conn, $stockInDataQuery);
    return mysqli_fetch_assoc($stockInDataResult);
}

// Update existing data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['id'])) {
    $stock_in_id = $_GET['id'];
    $qty = $_POST['qty'];
    $qty_unit = $_POST['qty_unit'];

    // Update data in inventory table
    $updateInventoryStmt = $conn->prepare("UPDATE inventory SET qty=?, qty_unit=? WHERE form_id=? AND type=1");
    $updateInventoryStmt->bind_param("ssi", $qty, $qty_unit, $stock_in_id);
    if ($updateInventoryStmt->execute()) {
        echo '<div class="alert alert-success" role="alert">Data updated successfully.</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Error updating data: ' . $updateInventoryStmt->error . '</div>';
    }

    // Close the statement
    $updateInventoryStmt->close();
}

if (isset($_GET['id'])) {
    $stock_in_id = $_GET['id'];
    // Fetch stock_in data for pre-filling the form
    $stockInDataQuery = "SELECT stock_in.*, supplier.supplier_name, products.name AS product_name
                         FROM stock_in
                         INNER JOIN supplier ON stock_in.supplier_id = supplier.id
                         INNER JOIN products ON stock_in.product_id = products.id
                         WHERE stock_in.id = $stock_in_id";
    $stockInDataResult = mysqli_query($conn, $stockInDataQuery);
    $stockInData = mysqli_fetch_assoc($stockInDataResult);

    if (!$stockInData) {
        echo '<div class="alert alert-danger" role="alert">Error fetching stock_in data.</div>';
    }

    // Fetch inventory data
    $inventoryDataQuery = "SELECT * FROM inventory WHERE form_id = $stock_in_id AND type = 1";
    $inventoryDataResult = mysqli_query($conn, $inventoryDataQuery);
    $inventoryData = mysqli_fetch_assoc($inventoryDataResult);

    if (!$inventoryData) {
        echo '<div class="alert alert-danger" role="alert">Error fetching inventory data.</div>';
    }
}
?>
<article class="content item-editor-page">
    <div class="title-block">
        <h3 class="title">Edit Incoming Stocks <span class="sparkline bar" data-type="bar"></span></h3>
        <p>Reference ID: <?php echo $stockInData['ref_no']; ?></p> <!-- Display the reference ID -->
    </div>
    <form name="editItem" method="POST" action="?id=<?php echo $stock_in_id; ?>">
        <div class="card card-block">
            <div class="form-group row">
                <div class="form-group">
                    <label for="fname">Supplier</label>
                    <input type="text" class="form-control" name="supplier" id="supplier" readonly required value="<?php echo $stockInData['supplier_name']; ?>">

                </div>

                <div class="form-group">
                    <label for="contact">Product</label>
                    <input type="text" class="form-control" name="product" id="product" readonly value="<?php echo $stockInData['product_name']; ?>">

                </div>
                <div class="row form-group">
                    <div class="form-group col-xs-6">
                        <label for="address">Qty</label>
                        <input type="text" class="form-control" name="qty" id="qty" placeholder="Enter Quantity" value="<?php echo $inventoryData['qty']; ?>" required>
                    </div>

                    <div class="form-group col-xs-6">
                        <label for="contact">Unit</label>
                        <select class="form-control" name="qty_unit" id="qty_unit">

                            <option value="kg" <?php if ($inventoryData['qty_unit'] === 'kg') echo 'selected'; ?>>Kilograms (kg)</option>
                            <option value="g" <?php if ($inventoryData['qty_unit'] === 'g') echo 'selected'; ?>>Grams (g)</option>
                            <option value="L" <?php if ($inventoryData['qty_unit'] === 'L') echo 'selected'; ?>>Liters (L)</option>
                            <option value="ml" <?php if ($inventoryData['qty_unit'] === 'ml') echo 'selected'; ?>>Milliliters (ml)</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="receiving.php" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </form>
</article>

</html>
</body>

</html>