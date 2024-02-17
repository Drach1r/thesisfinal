<?php
// Include necessary files and database connection
include 'db_connect.php';

// Check if the SaleID is provided in the URL
if (isset($_GET['SaleID'])) {
    $saleID = $_GET['SaleID'];

    // Fetch sale details from the sales table
    $saleQuery = $conn->prepare("SELECT SaleID, CustomerID, SaleDate, TotalAmount FROM sales WHERE SaleID = ?");
    $saleQuery->bind_param("s", $saleID); // Assuming SaleID is a string
    $saleQuery->execute();
    $saleResult = $saleQuery->get_result();
    $saleData = $saleResult->fetch_assoc();

    // Fetch sale items from the salesitems table for the specified SaleID
    $salesItemsQuery = $conn->prepare("SELECT ProductID, Quantity, Price FROM salesitems WHERE SaleID = ?");
    $salesItemsQuery->bind_param("s", $saleID); // Assuming SaleID is a string
    $salesItemsQuery->execute();
    $salesItemsResult = $salesItemsQuery->get_result();

    // Start building the HTML content for the receipt
    $htmlContent = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Sales Receipt</title>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <style>
                /* Your custom CSS styles for the receipt */
                body {
                    font-family: Arial, sans-serif;
                }
                .container {
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 20px;
                    border: 1px solid #ccc;
                    border-radius: 10px;
                }
                h1, h2, h3, h5 {
                    text-align: center;
                }
                p {
                    margin-bottom: 5px;
                }
                .table th {
                    background-color: black;
                    color: white;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>CALINOG FARMERS AGRICULTURE COOPERATIVE</h2>
                <p>Simsiman, Calinog, Iloilo</p>
                <p>Non-VAT Reg. TIN: 743-830-645-00000</p>
                <br>
                <h3>OFFICIAL RECEIPT</h3>
                <p class="text-right"><strong>Sale Date:</strong> ' . $saleData['SaleDate'] . '</p>
                <h6><strong>Sale Order:</strong> ' . $saleData['SaleID'] . '</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Tin</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>';

    // Fetch customer details
    $customerQuery = $conn->prepare("SELECT Name, Address, tin FROM customers WHERE CustomerID = ?");
    $customerQuery->bind_param("s", $saleData['CustomerID']);
    $customerQuery->execute();
    $customerResult = $customerQuery->get_result();
    $customerData = $customerResult->fetch_assoc();

    $htmlContent .= '
    <tr>
        <td>' . $customerData['Name'] . '</td>
        <td>' . $customerData['tin'] . '</td>
        <td>' . $customerData['Address'] . '</td> <!-- Display the address -->
    </tr>
</tbody>
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
    <tbody>';

    // Initialize total amount variable
    $totalAmount = 0;

    // Loop through the sale items and add them to the receipt content
    while ($row = $salesItemsResult->fetch_assoc()) {
        // Fetch product name
        $productQuery = $conn->prepare("SELECT Name FROM productlist WHERE ProductID = ?");
        $productQuery->bind_param("s", $row['ProductID']);
        $productQuery->execute();
        $productResult = $productQuery->get_result();
        $productData = $productResult->fetch_assoc();

        // Calculate amount
        $amount = $row['Quantity'] * $row['Price'];
        $totalAmount += $amount; // Add amount to total amount

        // Add product details to the HTML content
        $htmlContent .= '
                        <tr>
                            <td>' . $productData['Name'] . '</td>
                            <td>' . $row['Quantity'] . '</td>
                            <td>' . $row['Price'] . '</td>
                            <td>' . $amount . '</td>
                        </tr>';
    }

    // Finish building the HTML content
    $htmlContent .= '
                    </tbody>
                </table>
                <p class="text-right"><strong>Total Amount:</strong> ' . $saleData['TotalAmount'] . '</p>
                <div class="row mt-4">
                <div class="col">
                    <p class="text-right"><strong>Cashier/Authorize Representative:</strong> ________________________</p>
                </div>
            </div>
            </div>
        </body>
        </html>';

    // Send the generated HTML content as the response
    echo $htmlContent;
} else {
    // If SaleID is not provided, redirect the user
    header("Location: sales_list.php");
    exit();
}
