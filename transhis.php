<?php
include 'header.php';
include 'db_connect.php';
include 'sidebar.php';
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



<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="form-group row">

                <h3>
                    Payout Slips

                    <button class="btn btn-primary btn-sm rounded-s" id="print-button" onclick="printPayoutSlip(<?php echo $memberID; ?>)">Print</button>
                </h3>
            </div>
        </div>
    </div>
    <section id="print-section" class="section">
        <div class="row">
            <div class="card col-lg-12">
                <div class="card-body">
                    <BR>
                    <BR>
                    <div class="col-md-2" style="margin-top: 0;">
                        <!-- Place your logo here -->
                        <img src="assets/logo.jpg" alt="Your Logo" width="100">
                    </div>
                    <div class="text-center">
                        <h2 style="text-align: center; margin-right: 200px; "><strong>CALINOG FARMERS AGRICULTURE COOPERATIVE</strong></h2>
                        <h2 style="text-align: center; margin-right: 200px; "><strong>(CAF AGRI COOP)</strong></h2>
                        <h5 style="text-align: center; margin-right: 200px; "> Brgy. Simsiman, Calinog, Iloilo </h5>
                        <h5 style="text-align: center;"> CDA reg no.9520-1060000000046009 </h5>
                    </div>

                    <br>
                    <br>
                    <br>
                    <div class="row form-group">
                        <div class="col-md-9">
                            <h5>Name of the Cooperator: <strong> <?php echo $memberData['firstname'] . ' ' . $memberData['lastname']; ?> </strong></h5>
                        </div>
                        <div class="col-md-3 text-right">
                            <p>Date Released: <strong><?php echo date('Y-m-d'); ?></strong> </p>
                        </div>
                    </div>
                    <table class="table table-bordered">
                        <tr>
                            <th colspan="5" class="text-center" style="text-align: center; background-color: pink;">MILK PRODUCTION</th>
                        </tr>
                        <tr>
                            <th class="text-center" style="text-align: center; background-color: pink;">DATE</th>
                            <th class="text-center" style="text-align: center; background-color: pink;" colspan="<?php echo mysqli_num_rows($resultAllCarabaos); ?>">ANIMAL ID NUMBER</th>
                            <th class="text-center" style="text-align: center; background-color: pink;">DAILY TOTAL</th>
                        </tr>
                        <?php
                        // Get the starting date of the week (Sunday)
                        $starting_date = date('Y-m-d', strtotime('last Sunday'));
                        $release_date = date('Y-m-d');

                        // Initialize the grand total
                        $grandTotal = 0;

                        // Output the starting date row
                        echo '<tr>';
                        echo '<td style="text-align: center; background-color: pink;"><strong>Starting date: ' . $starting_date . '</strong></td>';

                        // Output the carabao names
                        mysqli_data_seek($resultAllCarabaos, 0); // Reset the pointer to the beginning
                        while ($rowAllCarabaos = mysqli_fetch_assoc($resultAllCarabaos)) {
                            echo '<td class="text-center" style="text-align: center; background-color: pink;"><strong>' . $rowAllCarabaos['name'] . '</strong></td>';
                        }

                        // Leave the "DAILY TOTAL" cell empty for the starting date row
                        echo '<td style="text-align: center; background-color: pink;"></td>';
                        echo '</tr>';

                        // Output each date along with carabao names and calculate the daily total
                        for ($date = $starting_date; $date <= $release_date; $date = date('Y-m-d', strtotime($date . ' +1 day'))) {
                            echo '<tr>';
                            echo '<td class="text-center" style="text-align: center;" >' . $date . '</td>';

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
                            <td class="text-center" style="text-align: right;" colspan="<?php echo mysqli_num_rows($resultAllCarabaos); ?>"><strong>TOTAL:</strong></td>
                            <td class="text-center" style="text-align: right; color: red;"><strong><?php echo number_format($grandTotal, 2); ?> liter </strong></td>
                        </tr>
                        <tr>
                            <td class="text-center" colspan="<?php echo mysqli_num_rows($resultAllCarabaos); ?>">Price Per Liter: ₱
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
                            <td colspan="<?php echo mysqli_num_rows($resultAllCarabaos); ?>"><strong>TOTAL</strong></td>
                            <td></td>
                            <td style="text-align: right;"> <strong> ₱<?php echo number_format($grandTotal * $rowPurchasePrice['price'], 2); ?> </strong> </td>
                        </tr>
                        <tr>
                            <td colspan="<?php echo mysqli_num_rows($resultAllCarabaos); ?>">Prepared By: </td>
                            <td colspan="<?php echo mysqli_num_rows($resultAllCarabaos); ?>"></td>

                        </tr>

                        <tr>

                            <td colspan=" <?php echo mysqli_num_rows($resultAllCarabaos); ?>" style="padding-left: 130px;"><strong>Guarlie Caro</strong></td>
                            <td colspan="<?php echo mysqli_num_rows($resultAllCarabaos); ?>"></td>
                        </tr>



                    </table>

                </div>
            </div>
        </div>
    </section>


</article>
<script>
    function printPayoutSlip(memberID) {
        // Fetch and print the payout slip details using AJAX
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Display the payout slip in a new window or modal
                var payoutSlipContent = this.responseText;
                var printWindow = window.open('', '_blank');
                printWindow.document.write(payoutSlipContent);
                printWindow.document.close();

                // Print the content after it's loaded
                printWindow.onload = function() {
                    printWindow.print();
                };
            }
        };
        xhttp.open('GET', 'print_payout_slip.php?member_id=' + memberID, true);
        xhttp.send();
    }
</script>