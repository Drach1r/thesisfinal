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

    // Fetch transactions for the current week
    $queryTransactions = "SELECT * FROM produced WHERE member_id = $memberID AND WEEK(date) = WEEK(CURDATE()) ORDER BY date ASC";
    $resultTransactions = mysqli_query($conn, $queryTransactions);

    if (!$resultTransactions) {
        die("Error: " . mysqli_error($conn));
    }
}
?>

<article class="content items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="title">
                        Payout Slips
                    </h3>
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
                    <div class="col-md-2" style="margin-top: 0;">
                        <!-- Place your logo here -->
                        <img src="assets/logo.jpg" alt="Your Logo" width="100">
                    </div>
                    <div class="text-center">
                        <h2><strong>CALINOG FARMERS AGRICULTURE COOPERATIVE</strong></h2>
                        <h2 style="margin-left: 400px;"><strong>(CAF AGRI COOP)</strong></h2>
                        <h5 style="margin-left: 403px;"> Brgy. Simsiman, Calinog, Iloilo </h5>
                        <h5 style="margin-left: 365px;"> CDA reg no.9520-1060000000046009 </h5>
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
                            <th colspan="3" class="text-center">MILK PRODUCTION</th>
                        </tr>
                        <tr>
                            <th class="text-center">DATE</th>
                            <th class="text-center">ANIMAL ID NUMBER</th>
                            <th class="text-center">DAILY TOTAL</th>
                        </tr>
                        <?php
                        // Get the starting date of the week (Sunday)
                        $starting_date = date('Y-m-d', strtotime('last Sunday'));

                        echo '<tr>';
                        echo '<td>' . $starting_date . '</td>';

                        // Fetch all carabaos attached to the member
                        $queryAllCarabaos = "SELECT id, name FROM carabaos WHERE member_id = $memberID";
                        $resultAllCarabaos = mysqli_query($conn, $queryAllCarabaos);

                        // Start the "Animal ID" cell
                        echo '<td class="text-center">';

                        // Output each carabao name in a separate <td> element
                        while ($rowAllCarabaos = mysqli_fetch_assoc($resultAllCarabaos)) {
                            echo '<td>' . $rowAllCarabaos['name'] . '</td>';
                        }

                        // End the "Animal ID" cell
                        echo '</td>';

                        // Leave the "Daily Total" column blank
                        echo '<td></td>';
                        echo '</tr>';
                        ?>
                    </table>

                </div>
            </div>
        </div>
    </section>
</article>