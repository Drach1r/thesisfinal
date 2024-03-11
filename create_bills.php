<?php
include 'header.php';
include 'sidebar.php';
include 'db_connect.php';


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $name = $_POST['name']; // Retrieve only the bill_name from the form

    // SQL query to insert data into bills table
    $sql = "INSERT INTO bills (bill_name) VALUES ('$name')"; // Insert only bill_name

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        // Display success alert
        echo "<div class='alert alert-success' role='alert'>New record created successfully</div>";
    } else {
        // Display error alert
        echo "<div class='alert alert-danger' role='alert'>Error: " . $sql . "<br>" . mysqli_error($conn) . "</div>";
    }
}

?>



<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js">
<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">
                        Bill Category


                    </h3>
                </div>
            </div>
        </div>
    </div>


    <section class="section">
        <div class="row">
            <div class="card col-lg-4">
                <br>


                <div class="card-title-body">
                    <form name="item" method="POST" action="">
                        <section class="example">
                            <div class="row">
                                <label style="margin-left: 15px;">
                                    Bill Name:
                                    <input type="text" name="name" value="" placeholder="Enter Bill Name">

                                </label>
                                <button type="submit" class="btn btn-success btn-sm ">Submit</button>
                            </div>
                        </section>

                    </form>
                </div>


            </div>

            <?php

            if (isset($_GET['delete'])) {

                $id = $_GET['delete'];


                $sql = "DELETE FROM bills WHERE id = $id";


                if (mysqli_query($conn, $sql)) {

                    echo "<div class='alert alert-success' role='alert'>Record with ID $id has been deleted successfully</div>";
                } else {

                    echo "<div class='alert alert-danger' role='alert'>Error deleting record: " . mysqli_error($conn) . "</div>";
                }
            } ?>
            <div class="card col-lg-5" style="margin-left: 30vh; ">
                <br>
                <div class="card-title-body">
                    <section class="example">

                        <table class="table table-bordered table-hover w-100" id="recordstable">
                            <thead class="table-dark">
                                <tr>

                                    <th class='text-center'>#</th>
                                    <th class='text-center'>Bills</th>
                                    <th class='text-center'>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $sql = "SELECT * FROM bills ORDER BY bill_id DESC";
                                $result = mysqli_query($conn, $sql);


                                // Check if there are rows in the result
                                if (mysqli_num_rows($result) > 0) {
                                    // Loop through each row and display data
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>" . $row['bill_id'] . "</td>";
                                        echo "<td class='text-center'>" . $row['bill_name'] . "</td>";

                                        echo "<td class='text-center'>";
                                        echo "<a href='?delete=" . $row['bill_id'] . "' onclick='return confirm(\"Are you sure you want to delete this data?\")'' class='btn btn-danger btn-sm'>Delete</a>";
                                        echo "</td>";

                                        echo "</tr>";
                                    }
                                } else {
                                    // If no rows found, display message
                                    echo "<tr><td colspan='5'>No bills found</td></tr>";
                                }

                                ?>
                            </tbody>
                        </table>

                    </section>
                </div>


            </div>
        </div>
    </section>

</article>

</body>

</html>