<?php
include 'header.php';
include 'sidebar.php';

$allowedUserTypes = array(4, 5);

checkUserTypeAccess($allowedUserTypes, 'login.php', 'You are not allowed to access this page.');

?>

<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">
                        Bills
                    </h3>
                </div>
            </div>
        </div>
        <?php
        include 'db_connect.php';
        function insertbillsData($conn)
        {

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $date = $_POST['date'];
                $bill_id = $_POST['bill_id'];
                $amount = $_POST['amount'];


                $insertStmt = $conn->prepare("INSERT INTO bill_records (date, bill_id, amount) VALUES (?, ?, ?)");
                $insertStmt->bind_param("sss", $date, $bill_id, $amount);


                if ($insertStmt->execute()) {

                    echo "<div class='alert alert-success' role='alert'>New record created successfully</div>";
                } else {
                    // Display error alert
                    echo "<div class='alert alert-danger' role='alert'>Error: " .  $insertStmt->error . "</div>";
                }

                $insertStmt->close();
            }
        }
        insertbillsData($conn);
        ?>


        <body>
            <form name="item" method="POST" action="">
                <div class="card card-block">
                    <div class="row form-group">
                        <div class="form-group col-xs-4">
                            <label for="date">Bill for the Month of:</label>
                            <input type="date" class="form-control" name="date" id="date" required>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="form-group col-xs-4">
                            <label for="bill_id">Bill Name:</label>
                            <select class="form-control" name="bill_id" id="bill_id" required>
                                <option value=""> --- SELECT ---</option>
                                <?php
                                $members = $conn->query("SELECT * FROM bills ORDER BY bill_id ASC"); // Order by last name alphabetically
                                while ($row = $members->fetch_assoc()) {
                                    echo '<option value="' . $row['bill_id'] . '">' . $row['bill_name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="form-group col-xs-4">
                            <label for="amount">Amount</label>
                            <input type="text" class="form-control" name="amount" id="amount" placeholder="Enter Amount " required>
                        </div>
                    </div>

                    <div class="row form-group" style="float: right">
                        <a href="bills.php" class="btn btn-danger">
                            Back
                        </a>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </div>
            </form>
        </body>
    </div>
</article>