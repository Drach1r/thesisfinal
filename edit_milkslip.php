<?php
include 'header.php';
include 'sidebar.php';
include 'db_connect.php';

function updateMilkslipData($conn)
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $transaction_id = $_POST["transaction_id"];
        $milkslip = $_POST["milkslip"];

        // Validate and sanitize the input data to prevent SQL injection
        $transaction_id = intval($transaction_id);
        $milkslip = floatval($milkslip);

        // Prepare the update statement for 'produced' table
        $updateStmt = $conn->prepare("UPDATE produced SET milkslip = ? WHERE transaction_id = ?");
        $updateStmt->bind_param("di", $milkslip, $transaction_id);

        // Execute the update statement
        if ($updateStmt->execute()) {
            $response = '<div class="alert alert-success" role="alert">Data updated successfully.</div>';
        } else {
            $response = '<div class="alert alert-danger" role="alert">Error updating data: ' . $updateStmt->error . '</div>';
        }

        // Close the statement
        $updateStmt->close();

        return $response;
    }
}

function getMilkslipDetails($conn, $transaction_id)
{
    // Prepare the select statement
    $selectStmt = $conn->prepare("SELECT p.transaction_id, p.member_id, p.carabao_id, p.milkslip, m.firstname, m.lastname FROM produced p INNER JOIN member m ON p.member_id = m.id WHERE p.transaction_id = ?");
    $selectStmt->bind_param("i", $transaction_id);

    // Execute the select statement
    $selectStmt->execute();

    // Fetch the result
    $result = $selectStmt->get_result();

    // Fetch the row
    $milkslip = $result->fetch_assoc();

    // Close the statement
    $selectStmt->close();

    return $milkslip;
}

// Add this function in your PHP code
function getCarabaoName($conn, $carabao_id)
{
    // Prepare the select statement
    $selectStmt = $conn->prepare("SELECT name FROM carabaos WHERE id = ?");
    $selectStmt->bind_param("i", $carabao_id);

    // Execute the select statement
    $selectStmt->execute();

    // Fetch the result
    $result = $selectStmt->get_result();

    // Fetch the row
    $carabao = $result->fetch_assoc();

    // Close the statement
    $selectStmt->close();

    return $carabao['name'];
}
?>



<!doctype html>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">
                        Edit Milkslip
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <body>
        <?php
        // Fetch the data to pre-fill the form
        if (isset($_GET["id"])) {
            $transaction_id = $_GET["id"];
            $milkslipDetails = getMilkslipDetails($conn, $transaction_id);

            // Fetch carabao name
            $carabaoName = getCarabaoName($conn, $milkslipDetails['carabao_id']);
        }

        // Check if the form was submitted and display the response message
        $response = updateMilkslipData($conn);
        ?>

        <form name="editMilkslip" method="POST" action="">
            <div class="card card-block">
                <?php echo $response; ?>
                <input type="hidden" name="transaction_id" value="<?php echo $milkslipDetails['transaction_id']; ?>">
                <div class="row form-group">
                    <div class="form-group col-xs-4">
                        <label for="member_id">Cooperator:</label>
                        <input type="text" class="form-control" value="<?php echo $milkslipDetails['firstname'] . ' ' . $milkslipDetails['lastname']; ?>" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="form-group col-xs-4">
                        <label for="carabao_name">Carabao:</label>
                        <input type="text" class="form-control" value="<?php echo $carabaoName; ?>" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="form-group col-xs-4">
                        <label for="milkslip">Milkslip:</label>
                        <input type="number" class="form-control" name="milkslip" id="milkslip" placeholder="Enter milkslip" step="0.01" value="<?php echo $milkslipDetails['milkslip']; ?>" required>
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
    <script>
        // Function to get Carabaos by Member ID (still necessary to display the initial data)
        function getCarabaosByMember(member_id) {
            $.ajax({
                url: "get_carabaos.php",
                type: "POST",
                data: {
                    member_id: member_id
                },
                dataType: "json",
                success: function(data) {
                    // Display the carabao name
                    var carabaoName = data.find(carabao => carabao.id === <?php echo json_encode($milkslipDetails['carabao_id']); ?>).name;
                    $("#carabao_name").text(carabaoName);
                }
            });
        }

        // Initialize with the member_id from PHP
        $(document).ready(function() {
            var member_id = <?php echo json_encode($milkslipDetails['member_id']); ?>;
            getCarabaosByMember(member_id);
        });
    </script>

    </body>
</article>

</html>