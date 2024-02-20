<?php
include 'header.php';
include 'sidebar.php';
include 'db_connect.php';

function insertMilkslipData($conn)
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $member_id = $_POST["member_id"];
        $carabao_id = $_POST["carabao_id"];
        $milkslip = $_POST["milkslip"];

        // Prepare the insert statement for 'produced' table
        $insertStmt = $conn->prepare("INSERT INTO produced (member_id, carabao_id, milkslip, date) VALUES (?, ?, ?, CURRENT_TIMESTAMP)");
        $insertStmt->bind_param("iss", $member_id, $carabao_id, $milkslip);

        // Execute the insert statement
        if ($insertStmt->execute()) {
            $response = '<div class="alert alert-success" role="alert">Data inserted successfully.</div>';
        } else {
            $response = '<div class="alert alert-danger" role="alert">Error inserting data: ' . $insertStmt->error . '</div>';
        }

        // Close the statement
        $insertStmt->close();

        return $response;
    }
}

?>

<!doctype html>
<html>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">
                        Create Milkslip
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <body>
        <form name="createProduce" method="POST" action="" onsubmit="return validateForm()">
            <div class="card card-block">
                <?php echo insertMilkslipData($conn); ?>
                <div class="row form-group">
                    <div class="form-group col-xs-4">
                        <label for="member_id">Cooperator:</label>
                        <select class="form-control" name="member_id" id="member_id" required onchange="getCarabaosByMember(this.value)">
                            <option value=""> --- SELECT ---</option>
                            <?php
                            $members = $conn->query("SELECT * FROM member ORDER BY lastname ASC"); // Order by last name alphabetically
                            while ($row = $members->fetch_assoc()) {
                                echo '<option value="' . $row['id'] . '">' . $row['lastname'] . ', ' . $row['firstname'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="form-group col-xs-4">
                        <label for="carabao_id">Carabao:</label>
                        <select class="form-control" name="carabao_id" id="carabao_id" required>
                            <option value=""> --- SELECT ---</option>
                            <!-- Carabao options will be loaded dynamically based on the selected member_id using JavaScript -->
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="form-group col-xs-4">
                        <label for="milkslip">Milkslip:</label>
                        <input type="number" class="form-control" name="milkslip" id="milkslip" placeholder="Enter milkslip" step="0.01" required>
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

        <script>
            function getCarabaosByMember(member_id) {
                $.ajax({
                    url: "get_carabaos.php",
                    type: "POST",
                    data: {
                        member_id: member_id
                    },
                    dataType: "json",
                    success: function(data) {
                        var carabaoDropdown = $("#carabao_id");
                        carabaoDropdown.empty().append('<option value=""> --- SELECT ---</option>');
                        $.each(data, function(index, carabao) {
                            carabaoDropdown.append('<option value="' + carabao.id + '">' + carabao.name + '</option>');
                        });
                    }
                });
            }

            function validateForm() {
                var carabaoDropdown = $("#carabao_id");
                if (carabaoDropdown.val() === "") {
                    alert("Please select a carabao.");
                    return false;
                }
                return true;
            }
        </script>
    </body>
</article>

</html>