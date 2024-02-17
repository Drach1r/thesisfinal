<?php

include 'db_connect.php';

// Assuming member_id is passed through GET method
if (isset($_GET['member_id'])) {
    $memberID = $_GET['member_id'];

    // Fetch member details
    $queryMember = "SELECT * FROM member WHERE id = $memberID";
    $resultMember = mysqli_query($conn, $queryMember);

    if (!$resultMember) {
        die("Error: " . mysqli_error($conn));
    }

    $memberData = mysqli_fetch_assoc($resultMember);

    // Fetch carabaos associated with the member
    $queryAllCarabaos = "SELECT id, name FROM carabaos WHERE member_id = $memberID";
    $resultAllCarabaos = mysqli_query($conn, $queryAllCarabaos);

    if (!$resultAllCarabaos) {
        die("Error: " . mysqli_error($conn));
    }

    // Check if there are any carabaos associated with the member
    $numCarabaos = mysqli_num_rows($resultAllCarabaos);

    // Display message if no carabaos are associated with the member
    if ($numCarabaos == 0) {
        echo '<p>No carabaos associated with this member.</p>';
    }

    // Get the starting date of the week (Sunday)
    $starting_date = date('Y-m-d', strtotime('last Sunday'));

    // Fetch actual milk received data for transactions during the starting date
    $queryActualMilkReceived = "SELECT carabao_id, actual FROM produced WHERE member_id = $memberID AND DATE(date) = '$starting_date'";
    $resultActualMilkReceived = mysqli_query($conn, $queryActualMilkReceived);

    if (!$resultActualMilkReceived) {
        die("Error: " . mysqli_error($conn));
    }

    // Initialize an array to store actual milk received data for each carabao
    $actualMilkReceivedData = array();

    // Store actual milk received data in the array
    while ($rowActualMilkReceived = mysqli_fetch_assoc($resultActualMilkReceived)) {
        $carabaoID = $rowActualMilkReceived['carabao_id'];
        $actualMilkReceived = $rowActualMilkReceived['actual'];

        // Store actual milk received data in the array using carabao ID as key
        $actualMilkReceivedData[$carabaoID] = $actualMilkReceived;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Window</title>
    <style>
        /* Define CSS for table borders */
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid black;
            /* Add borders to table cells */
            padding: 8px;
            text-align: center;
        }

        /* Other CSS styles */
        @page {
            size: landscape;
            margin: 0;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #print-section,
            #print-section * {
                visibility: visible;
            }

            #print-section {
                width: 100%;
                /* Viewport width */
                height: 100%;
                /* Viewport height */
                margin: 0;
                padding: 0;
            }

            .card {
                margin: auto;
                width: 95vw;
                /* Adjust as needed */
                page-break-inside: avoid;
            }

            .text-center {
                text-align: center;
            }
        }
    </style>

</head>

<body>
    <!-- Your HTML content here -->
    <section id="print-section" class="section">
        <div class="row">
            <div class="card col-lg-12">
                <div class="card-body">
                    <!-- Card content to be printed -->

                    <div class="row">
                        <div class="col-md-2">

                            <img src="assets/logo.jpg" alt="Your Logo" width="80">
                        </div>
                        <div class="col-md-10">
                            <div class="text-center">
                                <h3><strong>CALINOG FARMERS AGRICULTURE COOPERATIVE</strong></h3>
                                <h3><strong>(CAF AGRI COOP)</strong></h3>
                                <h6>Brgy. Simsiman, Calinog, Iloilo</h6>
                                <h6>CDA reg no.9520-1060000000046009</h6>
                            </div>
                        </div>
                    </div>




                    <div class="row form-group">
                        <div class="col-md-5">
                            <h4>Name of the Cooperator: <strong><?php echo $memberData['firstname'] . ' ' . $memberData['lastname']; ?></strong></h4>
                        </div>
                        <div class="col-md-3 text-right">
                            <p>Date Released: <strong><?php echo date('Y-m-d'); ?></strong></p>
                        </div>
                    </div>


                    <table class="table table-bordered" style="font-size: 12px;">
                        <tr>
                            <th colspan="5" class="text-center" style="background-color: pink;">MILK PRODUCTION</th>
                        </tr>
                        <tr>
                            <th class="text-center" style="background-color: pink;">DATE</th>
                            <th class="text-center" style="background-color: pink;" colspan="<?php echo mysqli_num_rows($resultAllCarabaos); ?>">ANIMAL ID NUMBER</th>
                            <th class="text-center" style="background-color: pink;">DAILY TOTAL</th>
                        </tr>
                        <?php
                        // Get the starting date of the week (Sunday)
                        $starting_date = date('Y-m-d', strtotime('last Sunday'));
                        $release_date = date('Y-m-d');

                        // Initialize the grand total
                        $grandTotal = 0;

                        // Output the starting date row
                        echo '<tr>';
                        echo '<td style="background-color: pink;"><strong>Starting date: ' . $starting_date . '</strong></td>';

                        // Output the carabao names
                        mysqli_data_seek($resultAllCarabaos, 0); // Reset the pointer to the beginning
                        while ($rowAllCarabaos = mysqli_fetch_assoc($resultAllCarabaos)) {
                            echo '<td class="text-center" style="background-color: pink;"><strong>' . $rowAllCarabaos['name'] . '</strong></td>';
                        }

                        // Leave the "DAILY TOTAL" cell empty for the starting date row
                        echo '<td style="background-color: pink;"></td>';
                        echo '</tr>';

                        // Output each date along with carabao names and calculate the daily total
                        for ($date = $starting_date; $date <= $release_date; $date = date('Y-m-d', strtotime($date . ' +1 day'))) {
                            echo '<tr>';
                            echo '<td class="text-center">' . $date . '</td>';

                            // Fetch transactions for the current date
                            $queryTransactions = "SELECT carabao_id, actual FROM produced WHERE member_id = $memberID AND DATE(date) = '$date'";
                            $resultTransactions = mysqli_query($conn, $queryTransactions);

                            if (!$resultTransactions) {
                                die("Error: " . mysqli_error($conn));
                            }

                            // Initialize the daily total for this row
                            $dailyTotal = 0;

                            // Output each carabao's transaction data and calculate the daily total
                            mysqli_data_seek($resultAllCarabaos, 0); // Reset the pointer to the beginning
                            while ($rowAllCarabaos = mysqli_fetch_assoc($resultAllCarabaos)) {
                                $carabaoID = $rowAllCarabaos['id'];
                                $actualMilkReceived = 0;

                                // Check if there is a transaction for the current carabao on the current date
                                while ($rowTransaction = mysqli_fetch_assoc($resultTransactions)) {
                                    if ($rowTransaction['carabao_id'] == $carabaoID) {
                                        $actualMilkReceived = $rowTransaction['actual'];
                                        break;
                                    }
                                }

                                // Output actual milk received in the corresponding table cell
                                echo '<td class="text-center" style="text-align: right;" >' . $actualMilkReceived . ' liter </td>';

                                // Add actual milk received to the daily total
                                $dailyTotal += $actualMilkReceived;

                                // Reset the pointer of the transactions result set
                                mysqli_data_seek($resultTransactions, 0);
                            }

                            // Output the daily total for the current row
                            echo '<td class="text-center" style="text-align: right;" >' . $dailyTotal . ' liter </td>';

                            // Add the daily total to the grand total
                            $grandTotal += $dailyTotal;

                            echo '</tr>';
                        }
                        ?>
                        <tr>
                            <td class="text-center"> </td>
                            <td class="text-center" colspan="<?php echo mysqli_num_rows($resultAllCarabaos); ?>  " style="text-align: right;"><strong>TOTAL:</strong></td>
                            <td class="text-center" style="color: red; text-align: right; "><strong><?php echo number_format($grandTotal, 2); ?> liter</strong></td>
                        </tr>
                        <tr>
                            <td colspan="<?php echo mysqli_num_rows($resultAllCarabaos); ?>">Price Per Liter: ₱
                                <?php
                                // Fetch the purchase price from the rawmaterials table for RawmaterialID=205
                                $queryPurchasePrice = "SELECT price FROM rawmaterials WHERE RawmaterialID = 205";
                                $resultPurchasePrice = mysqli_query($conn, $queryPurchasePrice);
                                if ($resultPurchasePrice && mysqli_num_rows($resultPurchasePrice) > 0) {
                                    $rowPurchasePrice = mysqli_fetch_assoc($resultPurchasePrice);
                                    echo number_format($rowPurchasePrice['price'], 2);
                                } else {
                                    echo "Price not available";
                                }
                                ?>

                            </td>
                            <td></td>
                            <td style="text-align: right;"><strong><?php echo number_format($rowPurchasePrice['price'], 2); ?></strong></td>
                        </tr>
                        <tr>
                            <td colspan="<?php echo mysqli_num_rows($resultAllCarabaos); ?>" style="text-align: left;"><strong>TOTAL</strong></td>
                            <td></td>
                            <td style="text-align: right;"> <strong> ₱<?php echo number_format($grandTotal * $rowPurchasePrice['price'], 2); ?> </strong> </td>
                        </tr>
                        <tr>
                            <td colspan="<?php echo mysqli_num_rows($resultAllCarabaos); ?>" style="text-align: left;">Prepared By: </td>
                            <td colspan="<?php echo mysqli_num_rows($resultAllCarabaos); ?>"></td>

                        </tr>

                        <tr>

                            <td colspan=" <?php echo mysqli_num_rows($resultAllCarabaos); ?>"><strong>Guarlie Caro</strong></td>
                            <td colspan="<?php echo mysqli_num_rows($resultAllCarabaos); ?>"></td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
    </section>
</body>


</html>