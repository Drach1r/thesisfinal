<!-- print_receipt.php -->
<?php
include 'header.php';
include 'db_connect.php';
include 'sidebar.php';
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

    <?php
    // Assuming you have a 'member' table with 'id', 'firstname', and 'lastname' columns
    // Assuming you have a 'carabaos' table with 'id' and 'name' columns
    // Assuming you have a 'produced' table with 'member_id' (foreign key to 'id' in 'member' table),
    // 'carabao_id' (foreign key to 'id' in 'carabaos' table), 'actual', and 'date' columns

    // Get distinct member IDs
    $queryMembers = "SELECT DISTINCT p.member_id, m.firstname, m.lastname FROM produced p
                    INNER JOIN member m ON p.member_id = m.id";
    $resultMembers = mysqli_query($conn, $queryMembers);

    // Create a weekly payout table
    echo '<section class="section">';
    echo '<div class="row">';
    echo '<div class="card col-lg-12">';
    echo '<div class="card-body">';
    echo '<div class="card-body">';
    echo '<div class="card-title-body"></div>';
    echo '<section class="example">';
    echo '<table class="table table-bordered col-md-12">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>#</th>';
    echo '<th>Member Name</th>';
    echo '<th>Carabao Name</th>';
    echo '<th>Weekly Transactions Count</th>';
    echo '<th>Weekly Actual Amount</th>';
    echo '<th>Action</th>'; // Added this column for the action
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Loop through each member
    $count = 1;
    while ($rowMember = mysqli_fetch_assoc($resultMembers)) {
        $memberID = $rowMember['member_id'];

        // Get weekly transaction count and actual amount for each member
        $queryWeekly = "SELECT COUNT(*) AS weekly_count, SUM(p.actual) AS weekly_actual, c.name AS carabao_name FROM produced p
                        INNER JOIN carabaos c ON p.carabao_id = c.id
                        WHERE p.member_id = '$memberID' AND WEEK(p.date) = WEEK(CURDATE())";
        $resultWeekly = mysqli_query($conn, $queryWeekly);
        $rowWeekly = mysqli_fetch_assoc($resultWeekly);

        echo '<tr>';
        echo '<td>' . $count . '</td>';
        echo '<td>' . $rowMember['firstname'] . ' ' . $rowMember['lastname'] . '</td>';
        echo '<td>' . $rowWeekly['carabao_name'] . '</td>';
        echo '<td>' . $rowWeekly['weekly_count'] . '</td>';
        echo '<td>' . $rowWeekly['weekly_actual'] . '</td>';
        echo '<td><button onclick="printReceipt(' . $memberID . ')">Print Receipt</button></td>'; // Added action column with a button
        echo '</tr>';

        $count++;
    }

    echo '</tbody>';
    echo '</table>';
    echo '</section>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</section>';
    ?>
    <script>
        function printReceipt(memberID) {
            // Fetch and print the transaction details using AJAX
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
            xhttp.open('GET', 'print_receipt.php?member_id=' + memberID, true);
            xhttp.send();
        }
    </script>

</article>