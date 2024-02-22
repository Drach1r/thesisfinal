<?php
include 'header.php';
include 'sidebar.php';
include 'db_connect.php';



// Fetch data from the bills table
$sql = "SELECT * FROM bills";
$result = mysqli_query($conn, $sql);

?>

<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">
                        Bills
                        <a href="c_bills.php" class="btn btn-primary btn-sm rounded-s">
                            Add New
                        </a>
                    </h3>
                </div>
            </div>
        </div>


        <section class="section">
            <div class="row">
                <?php
                // Check if the 'delete' parameter is set in the URL
                if (isset($_GET['delete'])) {
                    // Retrieve the 'id' value from the URL
                    $id = $_GET['delete'];

                    // Construct the SQL query to delete the record
                    $sql = "DELETE FROM bills WHERE id = $id";

                    // Execute the SQL query
                    if (mysqli_query($conn, $sql)) {
                        // Display success message
                        echo "<div class='alert alert-success' role='alert'>Record with ID $id has been deleted successfully</div>";
                    } else {
                        // Display error message
                        echo "<div class='alert alert-danger' role='alert'>Error deleting record: " . mysqli_error($conn) . "</div>";
                    }
                } ?>
                <div class="card col-lg-12">

                    <div class="card-body">
                        <div class="card-body">
                            <div class="card-title-body">
                                <section class="example">
                                    <table class="table table-bordered col-md-12">
                                        <thead>
                                            <tr>
                                                <th>Bills</th>
                                                <th>Date</th>
                                                <th>Name</th>
                                                <th>Amount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Check if there are rows in the result
                                            if (mysqli_num_rows($result) > 0) {
                                                // Loop through each row and display data
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row['id'] . "</td>";
                                                    echo "<td>" . $row['date'] . "</td>";
                                                    echo "<td>" . $row['name'] . "</td>";
                                                    echo "<td>" . $row['amount'] . "</td>";
                                                    echo "<td>";
                                                    // Add delete link with confirmation
                                                    echo "<a href='?delete=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this data?\")'' class='btn btn-danger btn-sm'>Delete</a>";
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
                </div>
            </div>
        </section>
    </div>
</article>

</body>

</html>