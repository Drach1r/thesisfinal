<?php
// Include necessary files and check user access
include 'header.php';
include 'sidebar.php';
$allowedUserTypes = array(3, 5);
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
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['SaleID']) && !isset($_GET['deletionCompleted'])) {
        $saleID = $_GET['SaleID'];

        // Validate SaleID format
        if (!preg_match('/^SO-\d+$/', $saleID)) {

            exit; // Stop execution if SaleID format is invalid
        }

        // Select the product IDs, quantities, and sale dates associated with the deleted sale from salesitems table
        $selectProductInfoStatement = $conn->prepare("SELECT si.ProductID, si.Quantity, s.SaleDate 
                                          FROM salesitems si 
                                          INNER JOIN sales s ON si.SaleID = s.SaleID 
                                          WHERE si.SaleID = ?");
        $selectProductInfoStatement->bind_param("s", $saleID); // Assuming SaleID is a string
        $selectProductInfoStatement->execute();
        $productInfoResult = $selectProductInfoStatement->get_result();

        // Iterate over the result and delete corresponding records from product_stock table
        while ($row = $productInfoResult->fetch_assoc()) {
            $productID = $row['ProductID'];
            $quantity = $row['Quantity'];
            $saleDate = $row['SaleDate'];

            // Prepare the DELETE statement for product_stock table
            $deleteProductStockStatement = $conn->prepare("DELETE FROM product_stock WHERE ProductID = ? AND TransactionDate = ? AND stock_out = ?");
            $deleteProductStockStatement->bind_param("isd", $productID, $saleDate, $quantity);

            // Execute the DELETE statement for product_stock table
            if (!$deleteProductStockStatement->execute()) {
                echo '<div class="alert alert-warning" role="alert">Error deleting records from product_stock table. Please try again.</div>';
            }

            $deleteProductStockStatement->close();
        }

        // Free the result set
        $productInfoResult->free();

        // Now, delete the records from the salesitems table
        // Prepare the DELETE statement for salesitems table
        $deleteSalesItemsStatement = $conn->prepare("DELETE FROM salesitems WHERE SaleID = ?");
        $deleteSalesItemsStatement->bind_param("s", $saleID); // Assuming SaleID is a string

        // Execute the DELETE statement for salesitems table
        if ($deleteSalesItemsStatement->execute()) {
            // Now, delete the sale
            // Prepare the DELETE statement for the sale
            $deleteSaleStatement = $conn->prepare("DELETE FROM sales WHERE SaleID = ?");
            $deleteSaleStatement->bind_param("s", $saleID); // Assuming SaleID is a string

            // Execute the DELETE statement for the sale
            if ($deleteSaleStatement->execute()) {
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
                            <style>
                                .table {
                                    text-align: center;
                                }

                                .table th,
                                .table td {
                                    text-align: center;
                                }
                            </style>

                            <table class="table table-bordered col-md-12">
                                <thead>
                                    <tr>
                                        <th>Sale Date</th>
                                        <th>Sale ID</th>
                                        <th>Customer</th>
                                        <th>Total Amount</th>
                                        <th>Delete</th>
                                        <th>Transaction</th>
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
                        <button type="submit" onclick="return confirm(\'Are you sure you want to delete this sale?\')" class="btn btn-danger btn-sm rounded-s " >Delete</button>
                    </form>
                </td>';
                                        echo '<td>
                    <form method="GET" action="print_sales.php" target="_blank">
                        <input type="hidden" name="SaleID" value="' . $salesRow['SaleID'] . '">
                        <button type="submit" class="btn btn-primary btn-sm rounded-s">View</button>
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