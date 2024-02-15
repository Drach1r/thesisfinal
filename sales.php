<?php
// Include necessary files and check user access
include 'header.php';
include 'sidebar.php';
$allowedUserTypes = array(3);
checkUserTypeAccess($allowedUserTypes, 'login.php', 'You are not allowed to access this page.');


?>

<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">
                        Sales List
                        <a href="c_sales.php" class="btn btn-primary btn-sm rounded-s">
                            Add New
                        </a>
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <?php
    // Check if a sale needs to be deleted and if the deletion process has not already been initiated
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['SaleID']) && !isset($_GET['deletionCompleted'])) {
        $saleID = $_GET['SaleID'];

        // Prepare the DELETE statement for salesitems table
        $deleteSalesItemsStatement = $conn->prepare("DELETE FROM salesitems WHERE SaleID = ?");
        $deleteSalesItemsStatement->bind_param("i", $saleID);

        // Execute the DELETE statement for salesitems table
        if ($deleteSalesItemsStatement->execute()) {
            // Now, delete the sale
            // Prepare the DELETE statement for the sale
            $deleteSaleStatement = $conn->prepare("DELETE FROM sales WHERE SaleID = ?");
            $deleteSaleStatement->bind_param("i", $saleID);

            // Execute the DELETE statement for the sale
            if ($deleteSaleStatement->execute()) {
                // Sale and related items deleted successfully

                // Select the product IDs, quantities, and sale dates associated with the deleted sale from salesitems table
                $selectProductInfoStatement = $conn->prepare("SELECT si.ProductID, si.Quantity, s.SaleDate 
                                                          FROM salesitems si 
                                                          INNER JOIN sales s ON si.SaleID = s.SaleID 
                                                          WHERE si.SaleID = ?");
                $selectProductInfoStatement->bind_param("i", $saleID);
                $selectProductInfoStatement->execute();
                $productInfoResult = $selectProductInfoStatement->get_result();

                // Iterate over the result and delete corresponding records from product_stock table
                while ($row = $productInfoResult->fetch_assoc()) {
                    $productID = $row['ProductID'];
                    $quantity = $row['Quantity'];
                    $saleDate = $row['SaleDate'];

                    // Prepare the DELETE statement for product_stock table
                    $deleteProductStockStatement = $conn->prepare("DELETE FROM product_stock WHERE ProductID = ? AND last_updated_date = ? AND stock_out = ?");
                    $deleteProductStockStatement->bind_param("isd", $productID, $saleDate, $quantity);

                    // Execute the DELETE statement for product_stock table
                    if (!$deleteProductStockStatement->execute()) {
                        echo '<div class="alert alert-warning" role="alert">Error deleting records from product_stock table. Please try again.</div>';
                    } else {
                        echo "Records for product ID $productID deleted successfully from product_stock<br>";
                    }

                    $deleteProductStockStatement->close();
                }

                // Free the result set
                $productInfoResult->free();

                echo '<div class="alert alert-success" role="alert">Data deleted successfully.</div>';
            } else {
                echo '<div class="alert alert-warning" role="alert">Error deleting sale. Please try again.</div>';
            }
        } else {
            echo '<div class="alert alert-warning" role="alert">Error deleting sales items. Please try again.</div>';
        }

        // Close prepared statements
        $deleteSalesItemsStatement->close();
        $deleteSaleStatement->close();
    }
    ?>




    <section class="section">
        <div class="row">
            <div class="card col-lg-12">
                <div class="card-body">
                    <div class="card-title-body">
                        <section class="example">
                            <table class="table table-bordered col-md-12">
                                <thead>
                                    <tr>
                                        <th>Sale Date</th>
                                        <th>Sales ID</th>
                                        <th>Customer</th>
                                        <th>Total Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    // Fetch sales data with customer names using a JOIN
                                    $salesQuery = $conn->query("SELECT s.SaleID, s.CustomerID, s.SaleDate, s.TotalAmount, c.Name AS CustomerName 
                                                                 FROM sales s
                                                                 JOIN customers c ON s.CustomerID = c.CustomerID");

                                    while ($salesRow = $salesQuery->fetch_assoc()) {
                                        echo '<tr>';
                                        echo '<td>' . $salesRow['SaleDate'] . '</td>';
                                        echo '<td>' . $salesRow['SaleID'] . '</td>';
                                        echo '<td>' . $salesRow['CustomerName'] . '</td>';
                                        echo '<td>' . $salesRow['TotalAmount'] . '</td>';
                                        echo '<td>
            <form method="GET" action="' . $_SERVER['PHP_SELF'] . '">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="SaleID" value="' . $salesRow['SaleID'] . '">
                <button type="submit" onclick="return confirm(\'Are you sure you want to delete this sale?\')">Delete</button>
                <button type="submit" formaction="print_sales.php" formtarget="_blank">Print</button>
            </form>
          </td>';
                                        echo '</tr>';
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