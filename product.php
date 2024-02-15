<?php
include 'header.php';
include 'sidebar.php';



?>

<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class=" form-group ">

                    <h3 class="title">
                        Products
                        <a href="c_product.php" class="btn btn-primary btn-sm rounded-s">
                            Create Product
                        </a>

                    </h3>


                </div>
            </div>
        </div>
    </div>
    <?php
    include 'db_connect.php';
    if (isset($_GET['delete'])) {
        $deleteId = $_GET['delete'];

        // Fetch ProductID from the productlist table before deletion
        $fetchProductIDStmt = $conn->prepare("SELECT ProductID FROM productlist WHERE ProductID = ?");
        $fetchProductIDStmt->bind_param("i", $deleteId);
        $fetchProductIDStmt->execute();
        $fetchProductIDResult = $fetchProductIDStmt->get_result();
        $productIDRow = $fetchProductIDResult->fetch_assoc();
        $productID = $productIDRow['ProductID'];
        $fetchProductIDStmt->close();

        // Prepare the delete statement for the bom table
        $deleteBOMStmt = $conn->prepare("DELETE FROM bom WHERE ProductID = ?");
        $deleteBOMStmt->bind_param("i", $productID);

        // Execute the delete statement for the bom table
        if ($deleteBOMStmt->execute()) {
            // Check if any rows were affected by the delete operation
            if ($deleteBOMStmt->affected_rows > 0) {
                // Reset the auto-incremented BOMID
                mysqli_query($conn, "ALTER TABLE bom AUTO_INCREMENT = 1");

                echo '<div class="alert alert-success" role="alert">Related data in BOM table deleted successfully.</div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">Error deleting related data in BOM table: ' . $deleteBOMStmt->error . '</div>';
        }
        $deleteBOMStmt->close();

        // Prepare the delete statement for the productlist table
        $deleteProductStmt = $conn->prepare("DELETE FROM productlist WHERE ProductID = ?");
        $deleteProductStmt->bind_param("i", $deleteId);

        // Execute the delete statement for the productlist table
        if ($deleteProductStmt->execute()) {
            // Check if any rows were affected by the delete operation
            if ($deleteProductStmt->affected_rows > 0) {
                // Reset the auto-incremented ProductID
                mysqli_query($conn, "ALTER TABLE productlist AUTO_INCREMENT = 1");

                echo '<div class="alert alert-success" role="alert">Data deleted successfully.</div>';
            } else {
                echo '<div class="alert alert-warning" role="alert">Deleting the record is not allowed.</div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">Error deleting data: ' . $deleteProductStmt->error . '</div>';
        }
        $deleteProductStmt->close();
    }


    // Fetch data from the "ProductList" table
    $query = "SELECT ProductID, Name, Unit, Description, price, prod_time    FROM ProductList";
    $result = mysqli_query($conn, $query);

    // Check for errors in the query
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
                                            <th>Product ID</th>
                                            <th>Name</th>
                                            <th>Unit</th>
                                            <th>Description</th>
                                            <th>Sales Price</th>

                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Iterate through each row of data
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $id = $row['ProductID'];
                                            $name = $row['Name'];
                                            $unit = $row['Unit'];
                                            $description = $row['Description'];
                                            $price = $row['price'];


                                            echo "<tr>";
                                            echo "<td class='text-center'>$id</td>";
                                            echo "<td>$name</td>";
                                            echo "<td>$unit</td>";
                                            echo "<td>$description</td>";
                                            echo "<td>$price</td>";

                                            echo "<td class='text-center'>
                                        <a href='edit_product.php?id=$id'><i class='fa fa-pencil'></i></a> | 
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