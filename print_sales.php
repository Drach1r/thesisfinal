    <?php
    include 'header.php';
    include 'sidebar.php';
    include 'db_connect.php';

    // Check if the SaleID is provided in the URL
    if (isset($_GET['SaleID'])) {
        $saleID = $_GET['SaleID'];

        // Fetch sale details from the sales table
        $saleQuery = $conn->prepare("SELECT SaleID, CustomerID, SaleDate, TotalAmount FROM sales WHERE SaleID = ?");
        $saleQuery->bind_param("s", $saleID); // Use "s" for string parameter
        $saleQuery->execute();
        $saleResult = $saleQuery->get_result();
        $saleData = $saleResult->fetch_assoc();

        echo "SaleID: " . $saleID; // Debugging line to check the SaleID

        // Fetch sale items from the salesitems table for the specified SaleID
        $salesItemsQuery = $conn->prepare("SELECT ProductID, Quantity, Price FROM salesitems WHERE SaleID = ?");
        $salesItemsQuery->bind_param("s", $saleID); // Use "s" for string parameter
        $salesItemsQuery->execute();
        $salesItemsResult = $salesItemsQuery->get_result();
    } else {
        // Redirect the user if SaleID is not provided
        header("Location: sales_list.php");
        exit();
    }

    ?>

    <article class="content items-list-page">
        <div class="title-search-block">
            <div class="title-block">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="title">
                            Sales Receipt
                        </h3>
                        <!-- Print button -->
                        <button onclick="printReceipt('<?php echo $saleID; ?>')" class="btn btn-success ">Print Receipt</button>
                    </div>
                </div>
            </div>
        </div>


        <section class="section">
            <div class="row">
                <div class="card col-lg-12">
                    <div class="card-body">
                        <BR>
                        <BR>
                        <h2><strong>CALINOG FARMERS AGRICULTURE COOPERATIVE</strong></h2>
                        <h5> Simsiman, Calinog, Iloilo </h5>
                        <h5> Non-VAT Reg. TIN: 743-830-645-00000 </h5>
                        <br>

                        <h3><strong>OFFICIAL RECEIPT </strong></h3>
                        <p style="float: right; margin-right: 250px"> <strong>Sale Date:</strong> <?php echo $saleData['SaleDate']; ?></p>
                        <h6>
                            <p><strong>Sale Order:</strong> <?php echo $saleData['SaleID']; ?></p>
                        </h6>

                        <table class="table table-bordered">
                            <tr style="background-color: black; color: white;">
                                <th>Customer Name</th>
                                <th>Tin</th>
                                <th>Address</th>
                            </tr>
                            <tr>
                                <?php
                                $customerQuery = $conn->prepare("SELECT Name, Tin, Address FROM customers WHERE CustomerID = ?");
                                $customerQuery->bind_param("s", $saleData['CustomerID']);
                                $customerQuery->execute();
                                $customerResult = $customerQuery->get_result();
                                $customerData = $customerResult->fetch_assoc();
                                ?>
                                <td><?php echo $customerData['Name']; ?></td>
                                <td><?php echo $customerData['Tin']; ?></td> <!-- Tin column -->
                                <td><?php echo $customerData['Address']; ?></td> <!-- Display the address -->
                            </tr>
                        </table>


                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalAmount = 0; // Initialize total amount variable

                                while ($row = $salesItemsResult->fetch_assoc()) :
                                    // Fetch product name
                                    $productQuery = $conn->prepare("SELECT Name FROM productlist WHERE ProductID = ?");
                                    $productQuery->bind_param("s", $row['ProductID']);
                                    $productQuery->execute();
                                    $productResult = $productQuery->get_result();
                                    $productData = $productResult->fetch_assoc();

                                    // Calculate amount
                                    $amount = $row['Quantity'] * $row['Price'];
                                    $totalAmount += $amount; // Add amount to total amount
                                ?>
                                    <tr>
                                        <td><?php echo $productData['Name']; ?></td>
                                        <td><?php echo $row['Quantity']; ?></td>
                                        <td><?php echo $row['Price']; ?></td>
                                        <td><?php echo $amount; ?></td> <!-- Display calculated amount -->
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>

                        <div class="container" style="float: right;">
                            <!-- Your existing HTML content -->
                            <p class=" text-right"><strong>Total Amount:</strong> <?php echo $saleData['TotalAmount']; ?></p>

                            <!-- Added section for Cashier/Authorize Representative -->
                            <div class="row mt-4">
                                <div class="col">
                                    <p class="text-right"><strong>Cashier/Authorize Representative:</strong> ________________________</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </article>


    <script>
        function printReceipt(saleID) {
            // Fetch and print the receipt details using AJAX
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Display the receipt in a new window or modal
                    var receiptContent = this.responseText;
                    var printWindow = window.open('', '_blank');
                    printWindow.document.write(receiptContent);
                    printWindow.document.close();

                    // Print the content
                    printWindow.print();
                }
            };
            xhttp.open('GET', 'print_sales_receipt.php?SaleID=' + saleID, true);
            xhttp.send();
        }
    </script>

    <?php include 'footer.php'; ?>