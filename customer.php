<?php
include 'header.php';
include 'sidebar.php';
?>

<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">
                        Customer
                        <a href="c_customer.php" class="btn btn-primary btn-sm rounded-s">
                            Add New
                        </a>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <?php


    // Check if the 'delete' parameter is provided in the URL
    if (isset($_GET['delete'])) {
        $deleteId = $_GET['delete'];

        // Prepare the delete statement
        $deleteStmt = $conn->prepare("DELETE FROM customers WHERE CustomerID = ?");
        $deleteStmt->bind_param("i", $deleteId);

        // Execute the delete statement
        if ($deleteStmt->execute()) {
            // Check if any rows were affected by the delete operation
            if ($deleteStmt->affected_rows > 0) {
                echo '<div class="alert alert-success" role="alert">Data deleted successfully.</div>';
            } else {
                echo '<div class="alert alert-warning" role="alert">Deleting the record is not allowed.</div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">Error deleting data: ' . $deleteStmt->error . '</div>';
        }

        // Close the statement
        $deleteStmt->close();

        $resetQuery = "ALTER TABLE customers AUTO_INCREMENT = 1";
        if ($conn->query($resetQuery) === TRUE) {
        } else {
            echo '<div class="alert alert-danger" role="alert">Error resetting auto-increment value: ' . $conn->error . '</div>';
        }
    }

    // Execute the select query to fetch the customer data
    $selectQuery = "SELECT * FROM customers";
    $result = mysqli_query($conn, $selectQuery);
    ?>

    <section class="section">
        <div class="row">
            <div class="card col-lg-12">
                <div class="card-body">
                    <div class="card-body">
                        <div class="card-title-body"></div>
                        <section class="example">
                            <table class="table table-bordered col-md-12">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Customer Name</th>
                                        <th>Contact No.</th>
                                        <th>Address</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>{$count}</td>";
                                        echo "<td>{$row['Name']}</td>";
                                        echo "<td>{$row['Phone']}</td>";
                                        echo "<td>{$row['Address']}</td>";
                                        echo "<td>{$row['createdAt']}</td>";
                                        echo "<td>
                                        <a href='?delete={$row['CustomerID']}' class='btn btn-danger btn-sm'>Delete</a>
                                    </td>";

                                        echo "</tr>";
                                        $count++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>