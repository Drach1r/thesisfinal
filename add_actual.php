<?php
include 'header.php';
include 'sidebar.php';
include 'db_connect.php';

function updateProducedDataAndInsertStockRecord($conn)
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $producedTransactionId = $_POST["transaction_id"];
        $producedActual = $_POST["actual"];

        // Validate and sanitize the input data to prevent SQL injection
        $producedTransactionId = intval($producedTransactionId);
        $producedActual = floatval($producedActual);

        // Convert actual to milliliters
        $producedActualInML = convertToMilliliters($producedActual);

        // Prepare the update statement for the 'actual' field in the produced table
        $updateProducedStmt = $conn->prepare("UPDATE produced SET actual = ? WHERE transaction_id = ?");
        $updateProducedStmt->bind_param("di", $producedActual, $producedTransactionId);

        // Execute the update statement for the produced table
        if ($updateProducedStmt->execute()) {
            $response = '<div class="alert alert-success" role="alert">Data updated successfully.</div>';

            // Get the date from the produced table based on transactionId
            $dateQuery = $conn->prepare("SELECT DATE(date) as transaction_date FROM produced WHERE transaction_id = ?");
            $dateQuery->bind_param("i", $producedTransactionId);
            $dateQuery->execute();

            if ($dateQuery->errno) {
                die("Error getting date: " . $dateQuery->error);
            }

            $dateResult = $dateQuery->get_result();
            $dateRow = $dateResult->fetch_assoc();
            $dateQuery->close();

            $transactionDate = $dateRow['transaction_date'];

            // Insert into the stock table
            $rawMaterialId = 205; // Adjusted variable name to avoid conflict

            $insertStockQuery = "INSERT INTO stock (RawMaterialID, stock_in, unit, transaction_date) VALUES (?, ?, 'ml', ?)";
            $insertStockStmt = mysqli_prepare($conn, $insertStockQuery);
            mysqli_stmt_bind_param($insertStockStmt, "ids", $rawMaterialId, $producedActualInML, $transactionDate);
            $result = mysqli_stmt_execute($insertStockStmt);

            if (!$result) {
                die("Query failed: " . mysqli_error($conn));
            }

            mysqli_stmt_close($insertStockStmt);

            // Close the statement for updating produced data
            $updateProducedStmt->close();

            return $response; // Return the response
        } else {
            $response = '<div class="alert alert-danger" role="alert">Error updating data: ' . $updateProducedStmt->error . '</div>';
            // Close the statement for updating produced data
            $updateProducedStmt->close();
            return $response; // Return the response
        }
    }
}

function getCarabaoDetails($conn, $carabaoId)
{
    // Prepare the select statement
    $selectStmt = $conn->prepare("SELECT id, name FROM carabaos WHERE id = ?");
    $selectStmt->bind_param("i", $carabaoId);

    // Execute the select statement
    $selectStmt->execute();

    // Fetch the result
    $result = $selectStmt->get_result();

    // Fetch the row
    $carabao = $result->fetch_assoc();

    // Close the statement
    $selectStmt->close();

    return $carabao;
}

function convertToMilliliters($quantity)
{
    return $quantity * 1000; // 1 liter = 1000 milliliters
}

// Fetch the data to pre-fill the form
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    // Prepare the select statement
    $selectStmt = $conn->prepare("SELECT p.transaction_id, p.milkslip, p.actual, m.firstname, m.lastname, p.carabao_id FROM produced p INNER JOIN member m ON p.member_id = m.id WHERE p.transaction_id = ?");
    $selectStmt->bind_param("i", $id);

    // Execute the select statement
    $selectStmt->execute();

    // Fetch the result
    $result = $selectStmt->get_result();

    // Fetch the row
    $producedRow = $result->fetch_assoc();

    // Close the statement
    $selectStmt->close();

    // Get carabao details
    $carabaoDetails = getCarabaoDetails($conn, $producedRow['carabao_id']);
}


// Check if the form was submitted and display the response message
$response = updateProducedDataAndInsertStockRecord($conn);

?>
<!doctype html>
<html>

<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">
                        Add Actual
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <body>
        <form name="editUser" method="POST" action="">
            <div class="card card-block">
                <?php echo $response; ?>
                <input type="hidden" name="transaction_id" value="<?php echo $producedRow['transaction_id']; ?>">
                <div class="row form-group">
                    <div class="form-group col-xs-3">
                        <label for="member_id">Member Name:</label>
                        <input type="text" class="form-control" id="member_id" name="member_id" value="<?php echo $producedRow['firstname'] . ' ' . $producedRow['lastname']; ?>" required readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="form-group col-xs-3">
                        <label for="carabao_name">Carabao Name:</label>
                        <input type="text" class="form-control" id="carabao_name" name="carabao_name" value="<?php echo $carabaoDetails['name']; ?>" required readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="form-group col-xs-1">
                        <label for="actual">Add Actual:</label>
                        <input type="number" class="form-control" id="actual" name="actual" value="<?php echo $producedRow['actual']; ?>" step="0.01" required>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <a href="produce.php" class="btn btn-danger">
                        Back
                    </a>
                </div>
            </div>
        </form>
    </body>
</article>

</html>