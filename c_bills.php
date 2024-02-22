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

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve data from the form
            $date = $_POST['date'];
            $name = $_POST['name']; // Added line to retrieve name from the form
            $amount = $_POST['amount'];

            // SQL query to insert data into bills table
            $sql = "INSERT INTO bills (date, name, amount) VALUES ('$date', '$name', '$amount')"; // Modified to include name

            // Execute the query
            if (mysqli_query($conn, $sql)) {
                // Display success alert
                echo "<div class='alert alert-success' role='alert'>New record created successfully</div>";
            } else {
                // Display error alert
                echo "<div class='alert alert-danger' role='alert'>Error: " . $sql . "<br>" . mysqli_error($conn) . "</div>";
            }

            // Close the database connection
            mysqli_close($conn);
        }
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
                            <label for="name">Bill For:</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name " required>
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