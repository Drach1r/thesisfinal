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
                        Raw Materials
                        <a href="c_rawmaterial.php" class="btn btn-primary btn-sm rounded-s">
                            Create New
                        </a>
                    </h3>

                </div>
            </div>
        </div>
        <?php
        include 'db_connect.php';
        if (isset($_GET['delete'])) {
            $deleteId = $_GET['delete'];

            // Prepare the delete statement
            $deleteStmt = $conn->prepare("DELETE FROM rawmaterials WHERE RawMaterialID = ?");
            $deleteStmt->bind_param("i", $deleteId);

            // Execute the delete statement
            if ($deleteStmt->execute()) {
                // Check if any rows were affected by the delete operation
                if ($deleteStmt->affected_rows > 0) {
                    // Delete successful, now reset the auto-incremented ID
                    mysqli_query($conn, "ALTER TABLE ProductList AUTO_INCREMENT = 1");
                    echo '<div class="alert alert-success" role="alert">Data deleted successfully.</div>';
                } else {
                    echo '<div class="alert alert-warning" role="alert">Deleting the record is not allowed.</div>';
                }
            } else {
                echo '<div class="alert alert-danger" role="alert">Error deleting data: ' . $deleteStmt->error . '</div>';
            }

            // Close the statement
            $deleteStmt->close();
        }


        $query = "SELECT RawMaterialID, Name, Unit, price FROM rawmaterials";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        } ?>
        <section class="section">
            <div class="row">
                <div class="card col-lg-12">
                    <div class="card-body">
                        <div class="card-body">
                            <div class="card-title-body">
                                <section class="example">
                                    <table class="table table-bordered col-md-12">
                                        <thead>
                                            <tr>
                                                <th>Raw Materials ID</th>
                                                <th>Material Name</th>
                                                <th>Unit</th>
                                                <th>Purchase Price</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Iterate through each row of data
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $id = $row['RawMaterialID'];
                                                $name = $row['Name'];
                                                $unit = $row['Unit'];
                                                $price = $row['price']; // Fix the array key here

                                                echo "<tr>";
                                                echo "<td class='text-center'>$id</td>";
                                                echo "<td>$name</td>";
                                                echo "<td>$unit</td>";
                                                echo "<td>$price</td>"; // Update the variable here
                                                echo "<td class='text-center'>
        <a href='edit_rawmaterials.php?id=$id'><i class='fa fa-pencil'></i></a> | 
        <a href='?delete=$id' onclick='return confirm(\"Are you sure you want to delete this data?\")'><i class='fa fa-trash-o'></i></a>
    </td>";
                                                echo "</tr>";
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